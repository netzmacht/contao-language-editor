<?php if (!defined('TL_ROOT')) die('You can not access this file directly!');

/**
 * Language editor
 * Copyright (C) 2010,2011 Tristan Lins
 *
 * @copyright  InfinitySoft 2012, netzmacht creative 2015
 * @author     Tristan Lins <tristan.lins@infinitysoft.de>
 * @author     David Molineus <david.molineus@netzmacht.de>
 * @package    Language Editor
 * @license    LGPL
 * @filesource
 */


/**
 * Back end modules
 */
$GLOBALS['BE_MOD']['system']['language-editor'] = array
(
	'tables'     => array('tl_translation'),
	'icon'       => 'system/modules/language-editor/html/icon.png',
	'search'     => array('LanguageVariableSearch', 'searchLanguageVariable'),
	'build'      => array('LanguageVariableSearch', 'buildLanguageVariableKeys'),
	'stylesheet' => 'system/modules/language-editor/html/backend.css'
);


/**
 * Sprachvariablen
 */
$GLOBALS['TL_TRANSLATION']['tl_translation']['tl_translation|langgroup']   = array('type' => '');
$GLOBALS['TL_TRANSLATION']['tl_translation']['tl_translation|langvar']     = array('type' => 'inputField');
$GLOBALS['TL_TRANSLATION']['tl_translation']['tl_translation|language']    = array('type' => 'inputField');
$GLOBALS['TL_TRANSLATION']['tl_translation']['tl_translation|backend']     = array('type' => 'inputField');
$GLOBALS['TL_TRANSLATION']['tl_translation']['tl_translation|frontend']    = array('type' => 'inputField');
$GLOBALS['TL_TRANSLATION']['tl_translation']['tl_translation|default']     = array('type' => 'inputField');
$GLOBALS['TL_TRANSLATION']['tl_translation']['tl_translation|translation'] = array('type' => 'inputField');


/**
 * Form fields
 */
$GLOBALS['BE_FFL']['langplain'] = 'Netzmacht\Contao\LanguageEditor\LangPlainWidget';
