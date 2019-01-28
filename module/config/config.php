<?php

/**
 * Contao Language editor
 *
 * @package    Language Editor
 * @author     Tristan Lins <tristan.lins@infinitysoft.de>
 * @author     David Molineus <david.molineus@netzmacht.de>
 * @copyright  2012 InfinitySoft 2012
 * @copyright  2015-2019 netzmacht David Molineus
 * @license    LGPL-3.0-or-later https://github.com/netzmacht/contao-language-editor/blob/master/LICENSE
 * @filesource
 */

/**
 * Back end modules
 */
$GLOBALS['BE_MOD']['system']['language-editor'] = array
(
    'tables'     => array('tl_translation'),
    'icon'       => 'system/modules/language-editor/html/icon.png',
    'search'     => array('Netzmacht\Contao\LanguageEditor\LanguageVariableSearch', 'searchLanguageVariable'),
    'build'      => array('Netzmacht\Contao\LanguageEditor\LanguageVariableSearch', 'buildLanguageVariableKeys'),
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

/**
 * Hooks
 */
$GLOBALS['TL_HOOKS']['loadLanguageFile'][] = array('Netzmacht\Contao\LanguageEditor\Hooks', 'loadLanguageFile');
