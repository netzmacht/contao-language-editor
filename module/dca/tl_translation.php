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
 * Table tl_translation
 */
$GLOBALS['TL_DCA']['tl_translation'] = array
(

	// Config
	'config' => array
	(
		'dataContainer'               => 'Table',
		'enableVersioning'            => true,
		'onload_callback' => array
		(
			array('tl_translation', 'loadTranslation')
		),
		'onsubmit_callback' => array
		(
			array('tl_translation', 'markUpdate')
		),
		'ondelete_callback' => array
		(
			array('tl_translation', 'markUpdate')
		)
	),

	// List
	'list' => array
	(
		'sorting' => array
		(
			'mode'                    => 1,
			'fields'                  => array('langgroup', 'language', 'langvar'),
			'flag'                    => 11,
			'panelLayout'             => 'filter;search,limit',
		),
		'label' => array
		(
			'fields'                  => array('language'),
			'format'                  => '[%s]',
			'label_callback'          => array('tl_translation', 'getLabel')
		),
		'global_operations' => array
		(
			'search' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_translation']['search'],
				'href'                => 'key=search',
				'class'               => 'header_language_editor_search',
				'attributes'          => 'onclick="Backend.getScrollOffset();" accesskey="s"'
			),
			'build' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_translation']['build'],
				'href'                => 'key=build',
				'class'               => 'header_language_editor_build',
				'attributes'          => 'onclick="Backend.getScrollOffset();" accesskey="b"'
			),
			'all' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['MSC']['all'],
				'href'                => 'act=select',
				'class'               => 'header_edit_all',
				'attributes'          => 'onclick="Backend.getScrollOffset();" accesskey="e"'
			)
		),
		'operations' => array
		(
			'edit' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_translation']['edit'],
				'href'                => 'act=edit',
				'icon'                => 'edit.gif'
			),
			'copy' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_translation']['copy'],
				'href'                => 'act=copy',
				'icon'                => 'copy.gif'
			),
			'delete' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_translation']['delete'],
				'href'                => 'act=delete',
				'icon'                => 'delete.gif',
				'attributes'          => 'onclick="if (!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['deleteConfirm'] . '\')) return false; Backend.getScrollOffset();"'
			),
			'show' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_translation']['show'],
				'href'                => 'act=show',
				'icon'                => 'show.gif'
			)
		)
	),

	// Palettes
	'metapalettes' => array
	(
		'default'                     => array(
			'translation' => array('langgroup', 'langvar', 'language', 'backend', 'frontend', 'default', 'content')
		)
	),


	// Fields
	'fields' => array
	(
		'langgroup' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_translation']['langgroup'],
			'filter'                  => true,
			'inputType'               => 'hiddenField',
			'eval'                    => array('alwaysSave'=>true, 'doNotShow'=>true),
			'save_callback'           => array(array('tl_translation', 'saveLangGroup'))
		),
		'langvar' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_translation']['langvar'],
			'default'                 => $this->Input->get('langvar'),
			'search'                  => true,
			'inputType'               => 'select',
			'options_callback'        => array('tl_translation', 'getLanguageVariablesOptions'),
			'eval'                    => array('mandatory'=>true, 'chosen'=>true, 'includeBlankOption'=>true, 'submitOnChange'=>true, 'tl_class'=>'w50')
		),
		'language' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_translation']['language'],
			'default'                 => $this->Input->get('language') ? $this->Input->get('language') : $GLOBALS['TL_LANGUAGE'],
			'filter'                  => true,
			'inputType'               => 'select',
			'options'                 => $this->getLanguages(),
			'eval'                    => array('mandatory'=>true, 'chosen'=>true, 'submitOnChange'=>true, 'tl_class'=>'w50')
		),
		'backend' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_translation']['backend'],
			'default'                 => true,
			'filter'                  => true,
			'inputType'               => 'checkbox',
			'eval'                    => array('tl_class'=>'w50')
		),
		'frontend' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_translation']['frontend'],
			'default'                 => true,
			'filter'                  => true,
			'inputType'               => 'checkbox',
			'eval'                    => array('tl_class'=>'w50')
		),
		'default' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_translation']['default'],
			'inputType'               => 'langplain',
			'eval'                    => array('tl_class'=>'clr long', 'doNotCopy'=>true, 'doNotShow'=>true),
			'load_callback'           => array(
				array('tl_translation', 'loadDefault')
			)
		),
		'content' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_translation']['content'],
			'search'                  => true,
			'inputType'               => 'text',
			'eval'                    => array('tl_class'=>'clr long', 'includeBlankOption'=>true, 'allowHtml'=>true, 'preserveTags'=>true),
			'load_callback'           => array(
				array('tl_translation', 'loadContent')
			)
		)
	)
);


/**
 * Class tl_translation
 *
 * @copyright  InfinitySoft 2012
 * @author     Tristan Lins <tristan.lins@infinitysoft.de>
 * @package    Language Editor
 */
class tl_translation extends Backend
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
		$this->import('LanguageEditor');

		// get translation keys found by the TranslationSearch::buildTranslationKeys method
		$objDir = new RegexIterator(new DirectoryIterator(TL_ROOT . '/system/languages/'), '#^langkeys\..*\.php$#');
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

	public function loadTranslation(DataContainer $dc)
	{
		$arrSession = $this->Session->get('tl_translation');
		if (count($arrSession['lazy_update'])) {
			foreach ($arrSession['lazy_update'] as $strGroup) {
				$this->updateTranslations($strGroup);
			}
			$this->updateLocallang();
			$arrSession['lazy_update'] = array();
			$this->Session->set('tl_translation', $arrSession);
			$this->reload();
		}

		$objTranslation = $this->Database
			->prepare("SELECT * FROM tl_translation WHERE id=?")
			->execute($dc->id);

		if ($objTranslation->next()) {
			list($strGroup, $strPath) = explode('::', $objTranslation->langvar, 2);

			$this->loadLanguageFile(isset(LanguageEditor::$arrDefaultGroups[$strGroup])
				? LanguageEditor::$arrDefaultGroups[$strGroup]
				: $strGroup, $objTranslation->language, true);

			if (isset($GLOBALS['TL_TRANSLATION'][$strGroup][$strPath])) {
				$arrConfig = $GLOBALS['TL_TRANSLATION'][$strGroup][$strPath];

				switch ($arrConfig['type']) {
					case 'legend':
						// do nothink, use default config
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

	public function saveLangGroup($varValue, DataContainer $dc)
	{
		return preg_replace('#^([^:]+)::.*$#', '$1', $dc->activeRecord->langvar);
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
		$arrSession = $this->Session->get('tl_translation');

		if (!is_array($arrSession)) {
			$arrSession = array('lazy_update' => array());
		}

		if (!in_array($dc->activeRecord->langgroup, $arrSession['lazy_update'])) {
			$arrSession['lazy_update'][] = $dc->activeRecord->langgroup;
		}

		$this->Session->set('tl_translation', $arrSession);
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
