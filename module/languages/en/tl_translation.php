<?php

/**
 * Language editor
 * Copyright (C) 2010,2011 Tristan Lins, 2015 David Molineus
 *
 * @copyright  InfinitySoft 2012, netzmacht creative 2015
 * @author     Tristan Lins <tristan.lins@infinitysoft.de>
 * @author     David Molineus <david.molineus@netzmacht.de>
 * @package    Language Editor
 * @license    LGPL
 * @filesource
 */


/**
 * Fields
 */
$GLOBALS['TL_LANG']['tl_translation']['langgroup'] = array('Group', 'Please choose a group to limit the search.');
$GLOBALS['TL_LANG']['tl_translation']['langvar']   = array('Language var', 'Please choose the language var to edit.');
$GLOBALS['TL_LANG']['tl_translation']['language']  = array('Language', 'Please choose a language for the translation.');
$GLOBALS['TL_LANG']['tl_translation']['backend']   = array('Apply in the backend', 'Applies the translation in the backend.');
$GLOBALS['TL_LANG']['tl_translation']['frontend']  = array('Apply in the frontend', 'Applies the translation in the frontend.');
$GLOBALS['TL_LANG']['tl_translation']['default']   = array('Default', 'This is the default value of the language var.');
$GLOBALS['TL_LANG']['tl_translation']['content']   = array('Content', 'Please insert the new content of the language var.');


/**
 * Legend
 */
$GLOBALS['TL_LANG']['tl_translation']['translation_legend'] = 'Language var';


/**
 * Buttons
 */
$GLOBALS['TL_LANG']['tl_translation']['search'] = 'Search language var';
$GLOBALS['TL_LANG']['tl_translation']['build']  = 'Build index';
$GLOBALS['TL_LANG']['tl_translation']['new']    = array('Create entry', 'Create a new language var');
$GLOBALS['TL_LANG']['tl_translation']['show']   = array('Details', 'Details of the language var ID %s');
$GLOBALS['TL_LANG']['tl_translation']['copy']   = array('Copy language var', 'Copy language var ID %s');
$GLOBALS['TL_LANG']['tl_translation']['delete'] = array('Delete language var', 'Delete langauge var ID %s');
$GLOBALS['TL_LANG']['tl_translation']['edit']   = array('Edit language var', 'Edit language var ID %s');


/**
 * Errors
 */
$GLOBALS['TL_LANG']['tl_translation']['require_backend_frontend'] = 'Please select at least one option.';

/**
 * Search
 */
$GLOBALS['TL_LANG']['tl_translation']['keyword']  = array('Keyword', 'Please insert the keyword to search. It can contains html.');
$GLOBALS['TL_LANG']['tl_translation']['empty']    = 'No results in this group.';
$GLOBALS['TL_LANG']['tl_translation']['back']     = 'Back';
$GLOBALS['TL_LANG']['tl_translation']['continue'] = 'Continue';
$GLOBALS['TL_LANG']['tl_translation']['dosearch'] = 'Search';

/**
 * Generate language variable keys
 */
$GLOBALS['TL_LANG']['tl_translation']['statistic']    = 'Statistics';
$GLOBALS['TL_LANG']['tl_translation']['groupCount']   = 'Known groups';
$GLOBALS['TL_LANG']['tl_translation']['langvarCount'] = 'Known language vars';
$GLOBALS['TL_LANG']['tl_translation']['clean']        = array('Rebuild all language vars', 'All language vars will be rebuild');
$GLOBALS['TL_LANG']['tl_translation']['update']       = 'Language var index is rebuild.&hellip;';
$GLOBALS['TL_LANG']['tl_translation']['regenerate']   = 'Find language vars';
$GLOBALS['TL_LANG']['tl_translation']['regenerated']  = 'All language vars found.';
$GLOBALS['TL_LANG']['tl_translation']['nothingtodo']  = 'Nothing to do.';
