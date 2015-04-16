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
        $arrSession = Session::getInstance()->get('tl_translation');
        if (count($arrSession['lazy_update'])) {
            foreach ($arrSession['lazy_update'] as $strGroup) {
                $this->updateTranslations($strGroup);
            }
            $this->updateLocallang();
            $arrSession['lazy_update'] = array();
            Session::getInstance()->set('tl_translation', $arrSession);
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

    public function markUpdate(DataContainer $dc)
    {
        $arrSession = Session::getInstance()->get('tl_translation');

        if (!is_array($arrSession)) {
            $arrSession = array('lazy_update' => array());
        }

        if (!in_array($dc->activeRecord->langgroup, $arrSession['lazy_update'])) {
            $arrSession['lazy_update'][] = $dc->activeRecord->langgroup;
        }

        Session::getInstance()->set('tl_translation', $arrSession);
    }

    public function updateTranslations($strGroup)
    {
        $this->log('Update translations for ' . $strGroup, 'tl_translation::updateTranslations', TL_INFO);

        $strFile = 'system/languages/locallang.' . $strGroup . '.php';

        $strSql = "SELECT * FROM tl_translation WHERE langgroup=? AND (backend=? OR frontend=?)";
        $arrArgs = array($strGroup, 1, 1);

        $objTranslation = $this->Database
            ->prepare($strSql)
            ->execute($arrArgs);
        if ($objTranslation->numRows) {
            $arrTranslation = array();
            while ($objTranslation->next()) {
                $arrPath = explode('|', preg_replace('#^[^:]+::#', '', $objTranslation->langvar));
                $strVariable = "\$GLOBALS['TL_LANG']";
                foreach ($arrPath as $strPath) {
                    $strVariable .= '[' . var_export($strPath, true) . ']';
                }

                $varValue = deserialize($objTranslation->content);
                if (!$varValue) {
                    $varValue = $objTranslation->content;
                }

                if (!isset($arrTranslation[$objTranslation->language])) {
                    $arrTranslation[$objTranslation->language] = array('both'=>array(), 'be'=>array(), 'fe'=>array());
                }
                if ($objTranslation->backend && $objTranslation->frontend) {
                    $arrTranslation[$objTranslation->language]['both'][$strVariable] = var_export($varValue, true);
                } else if ($objTranslation->backend) {
                    $arrTranslation[$objTranslation->language]['be'][$strVariable] = var_export($varValue, true);
                } else if ($objTranslation->frontend) {
                    $arrTranslation[$objTranslation->language]['fe'][$strVariable] = var_export($varValue, true);
                }
            }

            $objFile = new File($strFile);
            $objFile->write("<?php if (!defined('TL_ROOT')) die('You can not access this file directly!');

/**
 * DO NOT MODIFY THIS FILE, IT IS GENERATED BY THE LANGUAGE EDITOR!
 */
");

            foreach ($arrTranslation as $strLanguage=>$arrLangTranslations) {
                $objFile->append(sprintf("if (\$GLOBALS['TL_LANGUAGE'] == %s) {", var_export($strLanguage, true)));

                foreach ($arrLangTranslations['both'] as $strVariable=>$strValue) {
                    $objFile->append(sprintf("\t%s = %s;", $strVariable, $strValue));
                }

                if (count($arrLangTranslations['be'])) {
                    $objFile->append("\tif (TL_MODE=='BE') {");
                    foreach ($arrLangTranslations['be'] as $strVariable=>$strValue) {
                        $objFile->append(sprintf("\t\t%s = %s;", $strVariable, $strValue));
                    }
                    $objFile->append("\t}");
                }

                if (count($arrLangTranslations['fe'])) {
                    $objFile->append("\tif (TL_MODE=='FE') {");
                    foreach ($arrLangTranslations['fe'] as $strVariable=>$strValue) {
                        $objFile->append(sprintf("\t\t%s = %s;", $strVariable, $strValue));
                    }
                    $objFile->append("\t}");
                }

                $objFile->append("}");
            }
        } else if (is_file(TL_ROOT . '/' . $strFile)) {
            $objFile = new File($strFile);
            $objFile->delete();
        }
    }

    public function updateLocallang()
    {
        $this->log('Update locallang and add translations', 'tl_translation::updateTranslations', TL_INFO);

        $objFile = new File('system/config/langconfig.php');

        $strContent = "<?php\n";
        $arrLines = $objFile->getContentAsArray();

        $blnAppend = true;
        foreach ($arrLines as $strLine)
        {
            $strTrim = trim($strLine);

            if ($strTrim == '<?php' || $strTrim == '?>') {
                continue;
            }

            $strTrim = preg_replace('#^<\?php\s*#', '', $strTrim);
            $strTrim = preg_replace('#\s*\?>$#', '', $strTrim);

            if ($strTrim == '### TRANSLATION EDITOR START ###') {
                $blnAppend = false;
                continue;
            }

            if ($strTrim == '### TRANSLATION EDITOR STOP ###') {
                $blnAppend = true;
                continue;
            }

            if ($blnAppend) {
                $strContent .= $strTrim . "\n";
            }
        }

        $objTranslationGroup = $this->Database
            ->prepare("SELECT DISTINCT langgroup FROM tl_translation WHERE backend=? OR frontend=? ORDER BY langgroup")
            ->execute(1, 1);
        if ($objTranslationGroup->numRows) {
            $strContent .= "### TRANSLATION EDITOR START ###\n";
            $strContent .= "/**
 * DO NOT MODIFY THIS PART, IT IS GENERATED BY THE LANGUAGE EDITOR!
 */
if (Input::getInstance()->get('do') != 'language-editor') {\n";

            while ($objTranslationGroup->next()) {
                $strFile = '/system/languages/locallang.' . $objTranslationGroup->langgroup . '.php';
                if (file_exists(TL_ROOT . $strFile)) {
                    $strContent .= sprintf("\tinclude(TL_ROOT . %s);\n", var_export($strFile, true));
                }
            }

            $strContent .= "}
### TRANSLATION EDITOR STOP ###\n";
        }

        $strContent .= '?>';

        $objFile->write($strContent);
    }
}
