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
class LanguageEditor extends Backend
{
	public static $arrDefaultGroups = array(
		'CNT'    => 'countries', // countries
		'ERR'    => 'default',   // Error messages
		'PTY'    => 'default',   // Page types
		'FOP'    => 'default',   // File operation permissions
		'CHMOD'  => 'default',   // CHMOD levels
		'DAYS'   => 'default',   // Day names
		'MONTHS' => 'default',   // Month names
		'MSC'    => 'default',   // Miscellaneous
		'UNITS'  => 'default',   // Units
		'XPL'    => 'explain',   // Explanations
		'LNG'    => 'languages', // Languages
		'MOD'    => 'modules',   // Back end modules
		'SEC'    => 'default',   // Security questions
		'CTE'    => 'default',   // Content elements
		'FMD'    => 'default'    // Front end modules
	);

	/**
	 * Singleton instance
	 */
	protected static $objInstance = null;

	/**
	 * Get singleton instance
	 */
	protected static function getInstance()
	{
		if (self::$objInstance === null) {
			self::$objInstance = new LanguageEditor();
		}

		return self::$objInstance;
	}

	/**
	 * singleton constructor
	 */
	protected function __construct() {}

	public function getLangValue(&$arrParent, $arrPath, $blnRaw = false)
	{
		$strNext = array_shift($arrPath);

		// language path not found
		if (!isset($arrParent[$strNext])) {
			return 'not found!';
		}

		// walk deeper
		else if (count($arrPath)) {
			return $this->getLangValue($arrParent[$strNext], $arrPath, $blnRaw);
		}

		// return raw value
		else if ($blnRaw) {
			return $arrParent[$strNext];
		}

		// value is array (like label)
		else if (is_array($arrParent[$strNext])) {
			return '&ndash; ' . implode('<br>&ndash; ', $this->plainEncode($arrParent[$strNext]));
		}

		// value is somethink else
		else {
			return $this->plainEncode($arrParent[$strNext]);
		}
	}

	public function plainEncode($varValue)
	{
		if (is_array($varValue)) {
			foreach ($varValue as $k=>$v) {
				$varValue[$k] = $this->plainEncode($v);
			}
			return $varValue;
		} else {
			return htmlentities($varValue, ENT_QUOTES | ENT_HTML401, 'UTF-8');
		}
	}
}
