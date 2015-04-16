<?php if (!defined('TL_ROOT')) die('You can not access this file directly!');

/**
 * Language editor
 * Copyright (C) 2010,2011 Tristan Lins
 *
 * Extension for:
 * Contao Open Source CMS
 * Copyright (C) 2005-2010 Leo Feyer
 *
 * Formerly known as TYPOlight Open Source CMS.
 *
 * This program is free software: you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation, either
 * version 3 of the License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU
 * Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public
 * License along with this program. If not, please visit the Free
 * Software Foundation website at <http://www.gnu.org/licenses/>.
 *
 * PHP version 5
 * @copyright  InfinitySoft 2012
 * @author     Tristan Lins <tristan.lins@infinitysoft.de>
 * @package    Language Editor
 * @license    LGPL
 * @filesource
 */


/**
 * Class LanguageVariableSearch
 *
 * @copyright  InfinitySoft 2012
 * @author     Tristan Lins <tristan.lins@infinitysoft.de>
 * @package    Language Editor
 */
class LanguageVariableSearch extends Backend
{
	/**
	 * @var LanguageEditor
	 */
	protected $LanguageEditor;

	/**
	 * @var array
	 */
	protected $arrLanguageVariableKeys = null;

	public function __construct()
	{
		parent::__construct();
		$this->import('LanguageEditor');
	}

	/**
	 * @return string
	 */
	public function searchLanguageVariable()
	{
		$objTemplate = new BackendTemplate('be_translation_search');

		if ($this->Input->post('FORM_SUBMIT') == 'tl_translation_search') {
			if ($this->Input->get('back')) {
				$this->redirect('contao/main.php?do=language-editor&key=search');
			}

			$_SESSION['tl_translation_search_keyword']     = $this->Input->post('keyword');
			$_SESSION['tl_translation_search_language']    = $this->Input->post('language');
			$_SESSION['tl_translation_search_translation'] = $this->Input->post('translation');

			$strKeyword      = html_entity_decode($this->Input->post('keyword'), ENT_QUOTES | ENT_HTML401, 'UTF-8');
			$strLanguage     = $this->Input->post('language');
			$arrTranslations = $this->Input->post('translation')
				? array($this->Input->post('translation'))
				: ($this->Input->post('translations')
					? explode(',', $this->Input->post('translations'))
					: array_keys($GLOBALS['TL_TRANSLATION']));
			$arrResult       = array();
			$intResults      = 0;
			$blnFullMatch    = strpos($strKeyword, '*') !== false;
			$strKeywordRgxp  = '#' . ($blnFullMatch ? '^' : '') . implode('.*', array_map('preg_quote', explode('*', $strKeyword))) . ($blnFullMatch ? '$' : '') . '#i';

			$start = time();
			$end = ini_get('max_execution_time');
			if ($end > 0) {
				$end = $start + 0.75 * $end;
			} else {
				$end = $start + 30;
			}
			while (time() < $end && count($arrTranslations) && !$intResults) {
				$strTranslation  = array_shift($arrTranslations);

				$arrResult[$strTranslation] = array();

				$this->loadLanguageFile(isset(LanguageEditor::$arrDefaultGroups[$strTranslation]) ? LanguageEditor::$arrDefaultGroups[$strTranslation] : $strTranslation, $strLanguage, true);
				if (isset($GLOBALS['TL_LANG'][$strTranslation]) && isset($GLOBALS['TL_TRANSLATION'][$strTranslation])) {
					foreach ($GLOBALS['TL_TRANSLATION'][$strTranslation] as $strPath=>$arrConfig) {
						$varValue = $this->LanguageEditor->getLangValue($GLOBALS['TL_LANG'], explode('|', $strPath), true);

						if (!is_array($varValue)) {
							$varValue = array($varValue);
						}

						foreach ($varValue as $v) {
							if (preg_match($strKeywordRgxp, $v)
								|| preg_match($strKeywordRgxp, strip_tags($v))) {
								$arrResult[$strTranslation][$strPath] = count($varValue) > 1 ? $varValue : $varValue[0];
								$intResults ++;
								break;
							}
						}
					}
				}
			}

			$objTemplate->translations = $arrTranslations;
			$objTemplate->result       = $arrResult;
		}

		else {
			if (!isset($_SESSION['tl_translation_search_language'])) {
				$_SESSION['tl_translation_search_language'] = $GLOBALS['TL_LANGUAGE'];
			}

			$objTemplate->translations = array_keys($GLOBALS['TL_TRANSLATION']);
		}

		return $objTemplate->parse();
	}

	/**
	 * @return string
	 */
	public function buildLanguageVariableKeys()
	{
		$objTemplate = new BackendTemplate('be_translation_search_build_keys');

		if ($this->Input->post('FORM_SUBMIT') == 'tl_translation_search_build_keys') {
			// clean old files
			if ($this->Input->post('clean')) {
				$objDir = new RegexIterator(new DirectoryIterator(TL_ROOT . '/system/languages/'), '#^langkeys\..*\.php$#');
				/** @var SplFileInfo $objFile */
				foreach ($objDir as $objFile) {
					$objFile = new File('system/languages/' . $objFile->getFilename());
					$objFile->delete();
				}
			}

			$arrTranslations = $this->getTranslations();

			if (count($arrTranslations)) {
				$objTemplate->translations = $arrTranslations;
			} else {
				$_SESSION['TL_INFO'][] = $GLOBALS['TL_LANG']['tl_translation']['nothinktodo'];
				$this->reload();
			}
		}

		else if ($this->Input->get('translation')) {
			// get the next translation group
			$strTranslation = $this->Input->get('translation');

			$objFile = new File('system/languages/langkeys.' . $strTranslation . '.php');
			$objFile->write("<?php if (!defined('TL_ROOT')) die('You can not access this file directly!');

/**
 * DO NOT MODIFY THIS FILE, IT IS GENERATED BY THE LANGUAGE EDITOR!
 */
");

			// load the language
			$this->loadLanguageFile(isset(LanguageEditor::$arrDefaultGroups[$strTranslation]) ? LanguageEditor::$arrDefaultGroups[$strTranslation] : $strTranslation);

			if (is_array($GLOBALS['TL_LANG'][$strTranslation])) {
				$this->arrLanguageVariableKeys = array();

				$this->buildLangageVariableKeysFrom($strTranslation,
					$strTranslation,
					$GLOBALS['TL_LANG'][$strTranslation]);

				ksort($this->arrLanguageVariableKeys);

				foreach ($this->arrLanguageVariableKeys as $strTranslation=>$v) {
					foreach ($v as $strPath=>$arrConfig) {
						$strKey = "\$GLOBALS['TL_TRANSLATION']['$strTranslation']['$strPath']";
						$strValue = var_export($arrConfig, true);
						$objFile->append($strKey . ' = ' . $strValue . ";\n");
					}
				}
			}

			$objFile->close();

			header('Content-Type: image/png');
			$handle = fopen(TL_ROOT . '/system/modules/language-editor/html/complete.png', 'rb');
			fpassthru($handle);
			fclose($handle);
			exit;
		}

		else {
			// get translation keys found by the TranslationSearch::buildTranslationKeys method
			$objDir = new RegexIterator(new DirectoryIterator(TL_ROOT . '/system/languages/'), '#^langkeys\..*\.php$#');
			/** @var SplFileInfo $objFile */
			foreach ($objDir as $objFile) {
				require_once($objFile->getPathname());
			}
		}

		return $objTemplate->parse();
	}

	/**
	 * @param $strTranslation
	 * @param $strPath
	 * @param $arrLang
	 * @return void
	 */
	protected function buildLangageVariableKeysFrom($strTranslation, $strPath, $arrLang)
	{
		if (!isset($GLOBALS['TL_TRANSLATION'][$strTranslation][$strPath])) {
			if (!is_array($arrLang)) {
				$this->arrLanguageVariableKeys[$strTranslation][$strPath] = array(
					'type' => 'text'
				);
			}

			else if (array_is_assoc($arrLang) || count($arrLang)>2) {
				foreach ($arrLang as $k=>$v) {
					$this->buildLangageVariableKeysFrom($strTranslation, $strPath . '|' . $k, $v);
				}
			}

			else {
				$this->arrLanguageVariableKeys[$strTranslation][$strPath] = array(
					'type' => 'inputField'
				);
			}
		}
	}

	protected function getTranslations()
	{
		$arrTranslations = array();

		// walk over modules and find translations
		$arrModules = $this->Config->getActiveModules();
		foreach ($arrModules as $strModule) {
			$strPath = TL_ROOT . '/system/modules/' . $strModule . '/languages';
			if (is_dir($strPath)) {
				$objDir = new RecursiveIteratorIterator(
						new RecursiveDirectoryIterator($strPath));
				foreach ($objDir as $objFile) {
					if (preg_match('#/languages/\w\w/([^/]+)\.php#', $objFile->getPathname(), $match)
						&& $match[1] != 'countries'
						&& $match[1] != 'default'
						&& $match[1] != 'explain'
						&& $match[1] != 'languages'
						&& $match[1] != 'modules'
						&& !in_array($match[1], $arrTranslations)) {
						$arrTranslations[] = $match[1];
					}
				}
			}
		}
		sort($arrTranslations);

		// add defaults
		$arrTranslations = array_merge(
			$arrTranslations,
			array_keys(LanguageEditor::$arrDefaultGroups)
		);

		$arrTemp = array();
		foreach ($arrTranslations as $strTranslation) {
			if (!is_file(TL_ROOT . '/system/languages/langkeys.' . $strTranslation . '.php')) {
				$arrTemp[] = $strTranslation;
			}
		}

		return $arrTemp;
	}
}
