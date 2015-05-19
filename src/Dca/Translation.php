<?php

/**
 * Language editor
 * Copyright (C) 2010,2011 Tristan Lins, 2015 David Molineus
 *
 * @copyright  InfinitySoft 2012, netzmacht creative 2015
 * @author     David Molineus <david.molineus@netzmacht.de>
 * @package    Language Editor
 * @license    LGPL
 * @filesource
 */

namespace Netzmacht\Contao\LanguageEditor\Dca;

use DataContainer;
use File;
use Netzmacht\Contao\LanguageEditor\LanguageEditor;
use Session;

/**
 * Backend gui translation handler.
 *
 * @package Netzmacht\Contao\LanguageEditor\Dca
 */
class Translation extends \Backend
{
    /**
     * The language editor reference.
     *
     * @var LanguageEditor
     */
    protected $LanguageEditor;

    /**
     * Construct.
     */
    public function __construct()
    {
        parent::__construct();
        $this->import('BackendUser', 'User');

        $this->LanguageEditor = LanguageEditor::getInstance();
        $this->loadTranslationKeys();
    }

    /**
     * Generate the label.
     *
     * @param array  $row   The current row.
     * @param string $label The default label.
     *
     * @return string
     */
    public function getLabel($row, $label)
    {
        if ($row['backend'] && $row['frontend']) {
            $label = 'BE+FE ' . $label;
        } else if ($row['backend']) {
            $label = 'BE ' . $label;
        } else if ($row['frontend']) {
            $label = 'FE ' . $label;
        } else {
            $label = \Image::getHtml('system/themes/' . $this->getTheme() . '/images/invisible.gif', '');
        }

        list($group, $path) = explode('::', $row['langvar'], 2);
        $path = (!preg_match('#^' . preg_quote($group) . '|#', $path) ? $group . '.' : '') . str_replace('|', '.', $path);

        if (empty($GLOBALS['TL_TRANSLATION'][$group][$path]['label'])) {
            $label .= ' <strong>' . $path . '</strong>';
        } else {
            $label .= ' <strong>' . $GLOBALS['TL_TRANSLATION'][$group][$path]['label'] . '</strong>';
        }

        $varContent = deserialize($row['content']);
        if (!$varContent) {
            $varContent = $row['content'];
        }

        if (is_array($varContent)) {
            $label .= '<pre class="translation_content">' . '&ndash; ';
            $label .= implode('<br>&ndash; ', array_map(array($this->LanguageEditor, 'plainEncode'), $varContent)) . '</pre>';
        } else {
            $label .= '<pre class="translation_content">' . $this->LanguageEditor->plainEncode($varContent) . '</pre>';
        }

        return $label;
    }

    /**
     * Load the translation.
     *
     * @param DataContainer $dc The data container driver.
     *
     * @return void
     */
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

        $translation = \Database::getInstance()
            ->prepare("SELECT * FROM tl_translation WHERE id=?")
            ->execute($dc->id);

        if ($translation->next()) {
            $this->prepareDca($translation);
        }
    }

    /**
     * Save the lang group during saving the lang var.
     *
     * @param mixed         $value The lang var value.
     * @param DataContainer $dc    The data container driver.
     *
     * @return mixed
     */
    public function saveLangGroup($value, \DataContainer $dc)
    {
        $langGroup = preg_replace('#^([^:]+)::.*$#', '$1', $value);

        \Database::getInstance()
            ->prepare('UPDATE tl_translation %s WHERE id=?')
            ->set(array('langgroup' => $langGroup))
            ->execute($dc->id);

        $dc->activeRecord->langgroup = $langGroup;

        return $value;
    }

    /**
     * Get all language variable options.
     *
     * @param DataContainer $dc The data container driver.
     *
     * @return array
     */
    public function getLanguageVariablesOptions(DataContainer $dc)
    {
        $options = array();
        foreach ($GLOBALS['TL_TRANSLATION'] as $group => $keys) {
            $options[$group] = array();
            foreach ($keys as $key => $config) {
                if (!empty($config['type'])) {
                    $strPath = (!preg_match('#^' . preg_quote($group) . '|#', $key) ? $group . '.' : '') . str_replace('|', '.', $key);
                    $options[$group][$group . '::' . $key] = '[' . $strPath . ']'
                        . (isset($config['label']) ? ' ' . $config['label'] : '');
                }
            }
        }
        return $options;
    }

    /**
     * Load the default lang value.
     *
     * @param mixed         $value The lang var value.
     * @param DataContainer $dc    The data container driver.
     *
     * @return array|string
     */
    public function loadDefault($value, DataContainer $dc)
    {
        return strlen($dc->activeRecord->langvar)
            ? $this->LanguageEditor->getLangValue($GLOBALS['TL_LANG'], explode('|', preg_replace('#^[^:]+::#', '', $dc->activeRecord->langvar)))
            : '';
    }

    /**
     * Loa the content.
     *
     * @param mixed         $value The lang var value.
     * @param DataContainer $dc    The data container driver.
     *
     * @return array|string
     */
    public function loadContent($value, DataContainer $dc)
    {
        if (empty($value) && strlen($dc->activeRecord->langvar)) {
            return $this->LanguageEditor->getLangValue($GLOBALS['TL_LANG'], explode('|', preg_replace('#^[^:]+::#', '', $dc->activeRecord->langvar)), true);
        } else {
            return $value;
        }
    }

    /**
     * Mark cached language file as deprecated to update it.
     *
     * @return void
     */
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

    /**
     * Update the translations.
     *
     * @return void
     */
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
     * Load all translations from the database and prepare it being saved in the file.
     *
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

    /**
     * Load the translations keys.
     *
     * @return void
     */
    private function loadTranslationKeys()
    {
        // get translation keys found by the TranslationSearch::buildTranslationKeys method
        $files = new \RegexIterator(new \DirectoryIterator(TL_ROOT . '/system/languages/'), '#^langkeys\..*\.php$#');

        foreach ($files as $file) {
            require_once($file->getPathname());
        }

        uksort($GLOBALS['TL_TRANSLATION'], 'strcasecmp');
    }

    /**
     * Prepare the dca for the given translation record.
     *
     * @param \Database\Result $translation The translation.
     *
     * @return void
     */
    private function prepareDca($translation)
    {
        list($group, $path) = explode('::', $translation->langvar, 2);

        $this->loadLanguageFile(
            isset(LanguageEditor::$defaultGroups[$group])
                ? LanguageEditor::$defaultGroups[$group]
                : $group,
            $translation->language,
            true
        );

        if (isset($GLOBALS['TL_TRANSLATION'][$group][$path])) {
            $arrConfig = $GLOBALS['TL_TRANSLATION'][$group][$path];

            switch ($arrConfig['type']) {
                case 'legend':
                    // do nothing, use default config
                    break;

                case 'inputField':
                    $GLOBALS['TL_DCA']['tl_translation']['fields']['content']['eval']['multiple'] = true;
                    $GLOBALS['TL_DCA']['tl_translation']['fields']['content']['eval']['size']     = 2;
                    break;

                case 'text':
                    $GLOBALS['TL_DCA']['tl_translation']['fields']['content']['inputType'] = 'textarea';
                    break;
            }
        }
    }
}
