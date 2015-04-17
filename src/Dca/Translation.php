<?php

/**
 * Language editor
 * Copyright (C) 2010,2011 Tristan Lins, 2015 David Molineus
 *
 * @copyright  InfinitySoft 2012, netzmacht creative 2015
 * @author	   Tristan Lins <tristan.lins@infinitysoft.de>
 * @author	   David Molineus <david.molineus@netzmacht.de>
 * @package	   Language Editor
 * @license	   LGPL
 * @filesource
 */

namespace Netzmacht\Contao\LanguageEditor\Dca;

use Config;
use DataContainer;
use File;
use Netzmacht\Contao\LanguageEditor\LanguageEditor;
use Session;

class Translation extends \Backend
{
    /**
     * @var Config
     */
    protected $Config;

    /**
     * @var Session
     */
    protected $Session;

    /**
     * @var LanguageEditor
     */
    protected $LanguageEditor;

    /**
     * Import the back end user object
     */
    public function __construct()
    {
        parent::__construct();
        $this->import('BackendUser', 'User');
        $this->LanguageEditor = LanguageEditor::getInstance();

        // get translation keys found by the TranslationSearch::buildTranslationKeys method
        $objDir = new \RegexIterator(new \DirectoryIterator(TL_ROOT . '/system/languages/'), '#^langkeys\..*\.php$#');
        /** @var SplFileInfo $objFile */
        foreach ($objDir as $objFile) {
            require_once($objFile->getPathname());
        }

        uksort($GLOBALS['TL_TRANSLATION'], 'strcasecmp');
    }

    public function getLabel($arrRow, $label)
    {
        if ($arrRow['backend'] && $arrRow['frontend']) {
            $label = 'BE+FE ' . $label;
        } else if ($arrRow['backend']) {
            $label = 'BE ' . $label;
        } else if ($arrRow['frontend']) {
            $label = 'FE ' . $label;
        } else {
            $label = $this->generateImage('system/themes/' . $this->getTheme() . '/images/invisible.gif', '');
        }

        list($strGroup, $strPath) = explode('::', $arrRow['langvar'], 2);
        $strPath = (!preg_match('#^' . preg_quote($strGroup) . '|#', $strPath) ? $strGroup . '.' : '') . str_replace('|', '.', $strPath);

        if (empty($GLOBALS['TL_TRANSLATION'][$strGroup][$strPath]['label'])) {
            $label .= ' <strong>' . $strPath . '</strong>';
        } else {
            $label .= ' <strong>' . $GLOBALS['TL_TRANSLATION'][$strGroup][$strPath]['label'] . '</strong>';
        }

        $varContent = deserialize($arrRow['content']);
        if (!$varContent) {
            $varContent = $arrRow['content'];
        }

        if (is_array($varContent)) {
            $label .= '<pre class="translation_content">' . '&ndash; ' . implode('<br>&ndash; ', array_map(array($this->LanguageEditor, 'plainEncode'), $varContent)) . '</pre>';
        } else {
            $label .= '<pre class="translation_content">' . $this->LanguageEditor->plainEncode($varContent) . '</pre>';
        }

        return $label;
    }

    public function loadTranslation(\DataContainer $dc)
    {
        $session     = Session::getInstance();
        $sessionData = $session->get('tl_translation');

        if (isset($sessionData['lazy_update']) && $sessionData['lazy_update']) {
            $this->updateTranslations();

            $sessionData['lazy_update'] = false;
            $session->set('tl_translation', $sessionData);
            $this->reload();
        }

        $objTranslation = $this->Database
            ->prepare("SELECT * FROM tl_translation WHERE id=?")
            ->execute($dc->id);

        if ($objTranslation->next()) {
            list($strGroup, $strPath) = explode('::', $objTranslation->langvar, 2);

            $this->loadLanguageFile(isset(LanguageEditor::$defaultGroups[$strGroup])
                ? LanguageEditor::$defaultGroups[$strGroup]
                : $strGroup, $objTranslation->language, true);

            if (isset($GLOBALS['TL_TRANSLATION'][$strGroup][$strPath])) {
                $arrConfig = $GLOBALS['TL_TRANSLATION'][$strGroup][$strPath];

                switch ($arrConfig['type']) {
                    case 'legend':
                        // do nothing, use default config
                        break;

                    case 'inputField':
                        $GLOBALS['TL_DCA']['tl_translation']['fields']['content']['eval']['multiple'] = true;
                        $GLOBALS['TL_DCA']['tl_translation']['fields']['content']['eval']['size'] = 2;
                        break;

                    case 'text':
                        $GLOBALS['TL_DCA']['tl_translation']['fields']['content']['inputType'] = 'textarea';
                        break;
                }
            }
        }
    }

    public function saveLangGroup($varValue, \DataContainer $dc)
    {
        $langGroup = preg_replace('#^([^:]+)::.*$#', '$1', $varValue);

        $database = \Database::getInstance()
            ->prepare('UPDATE tl_translation %s WHERE id=?')
            ->set(array('langgroup' => $langGroup))
            ->execute($dc->id);

        $dc->activeRecord->langgroup = $langGroup;

        return $varValue;
    }

    public function getLanguageVariablesOptions(DataContainer $dc)
    {
        $arrOptions = array();
        foreach ($GLOBALS['TL_TRANSLATION'] as $strGroup => $arrKeys) {
            $arrOptions[$strGroup] = array();
            foreach ($arrKeys as $strKey=>$arrKey) {
                if (!empty($arrKey['type'])) {
                    $strPath = (!preg_match('#^' . preg_quote($strGroup) . '|#', $strKey) ? $strGroup . '.' : '') . str_replace('|', '.', $strKey);
                    $arrOptions[$strGroup][$strGroup . '::' . $strKey] = '[' . $strPath . ']'
                        . (isset($arrKey['label']) ? ' ' . $arrKey['label'] : '');
                }
            }
        }
        return $arrOptions;
    }

    public function loadDefault($varValue, DataContainer $dc)
    {
        return strlen($dc->activeRecord->langvar)
            ? $this->LanguageEditor->getLangValue($GLOBALS['TL_LANG'], explode('|', preg_replace('#^[^:]+::#', '', $dc->activeRecord->langvar)))
            : '';
    }

    public function loadContent($varValue, DataContainer $dc)
    {
        if (empty($varValue) && strlen($dc->activeRecord->langvar)) {
            return $this->LanguageEditor->getLangValue($GLOBALS['TL_LANG'], explode('|', preg_replace('#^[^:]+::#', '', $dc->activeRecord->langvar)), true);
        } else {
            return $varValue;
        }
    }

    public function markUpdate()
    {
        $session     = Session::getInstance();
        $sessionData = $session->get('tl_translation');

        if (!is_array($sessionData)) {
            $sessionData = array('lazy_update' => array());
        }

        $sessionData['lazy_update'] = true;
        $session->set('tl_translation', $sessionData);
    }

    public function updateTranslations()
    {
        $this->log('Update translations', 'tl_translation::updateTranslations', 'TL_INFO');

        $translations = $this->loadTranslationsFromDatabase();

        $objFile = new File('system/languages/locallang.php');
        $objFile->write("<?php if (!defined('TL_ROOT')) die('You can not access this file directly!');

/**
* DO NOT MODIFY THIS FILE, IT IS GENERATED BY THE LANGUAGE EDITOR!
*/
");

        foreach ($translations as $strLanguage=>$arrLangTranslations) {
            $objFile->append(sprintf("if (\$GLOBALS['TL_LANGUAGE'] == %s) {", var_export($strLanguage, true)));

            foreach ($arrLangTranslations['both'] as $variable=>$strValue) {
                $objFile->append(sprintf("\t%s = %s;", $variable, $strValue));
            }

            if (count($arrLangTranslations['be'])) {
                $objFile->append("\tif (TL_MODE == 'BE') {");
                foreach ($arrLangTranslations['be'] as $variable=>$strValue) {
                    $objFile->append(sprintf("\t\t%s = %s;", $variable, $strValue));
                }
                $objFile->append("\t}");
            }

            if (count($arrLangTranslations['fe'])) {
                $objFile->append("\tif (TL_MODE=='FE') {");
                foreach ($arrLangTranslations['fe'] as $variable=>$strValue) {
                    $objFile->append(sprintf("\t\t%s = %s;", $variable, $strValue));
                }
                $objFile->append("\t}");
            }

            $objFile->append("}");
        }
    }

    /**
     * @return array
     */
    private function loadTranslationsFromDatabase()
    {
        $result       = \Database::getInstance()->query('SELECT * FROM tl_translation WHERE backend=1 OR frontend=1');
        $translations = array();

        while ($result->next()) {
            $path     = explode('|', preg_replace('#^[^:]+::#', '', $result->langvar));
            $variable = "\$GLOBALS['TL_LANG']";
            foreach ($path as $key) {
                $variable .= '[' . var_export($key, true) . ']';
            }

            $value = deserialize($result->content);
            if (!$value) {
                $value = $result->content;
            }

            if (!isset($translations[$result->language])) {
                $translations[$result->language] = array('both' => array(), 'be' => array(), 'fe' => array());
            }

            if ($result->backend && $result->frontend) {
                $translations[$result->language]['both'][$variable] = var_export($value, true);
            } else {
                if ($result->backend) {
                    $translations[$result->language]['be'][$variable] = var_export($value, true);
                } else {
                    if ($result->frontend) {
                        $translations[$result->language]['fe'][$variable] = var_export($value, true);
                    }
                }
            }
        }

        return $translations;
    }
}
