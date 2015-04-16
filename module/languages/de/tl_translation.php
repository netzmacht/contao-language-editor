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
 * @author	   Tristan Lins <tristan.lins@infinitysoft.de>
 * @package	   Language Editor
 * @license	   LGPL
 * @filesource
 */


/**
 * Fields
 */
$GLOBALS['TL_LANG']['tl_translation']['langgroup'] = array('Gruppe', 'Wählen Sie hier eine Gruppe, wenn Sie die Suche einschränken wollen.');
$GLOBALS['TL_LANG']['tl_translation']['langvar']   = array('Sprachvariable', 'Wählen Sie hier die Sprachvariable die Sie editieren wollen.');
$GLOBALS['TL_LANG']['tl_translation']['language']  = array('Sprache', 'Wählen Sie hier die Sprache für die Übersetzung');
$GLOBALS['TL_LANG']['tl_translation']['backend']   = array('Im Backend anwenden', 'Wendet die Übersetzung im Backend an.');
$GLOBALS['TL_LANG']['tl_translation']['frontend']  = array('Im Frontend anwenden', 'Wendet die Übersetzung im Frontend an.');
$GLOBALS['TL_LANG']['tl_translation']['default']   = array('Standardwert', 'Hier sehen Sie welchen Standardwert die Sprachvariable hat.');
$GLOBALS['TL_LANG']['tl_translation']['content']   = array('Inhalt', 'Geben Sie hier Ihren neuen Wert für die Sprachvariable ein.');


/**
 * Legend
 */
$GLOBALS['TL_LANG']['tl_translation']['translation_legend'] = 'Sprachvariable';


/**
 * Buttons
 */
$GLOBALS['TL_LANG']['tl_translation']['search'] = 'Sprachvariable suchen';
$GLOBALS['TL_LANG']['tl_translation']['build']  = 'Index aufbauen';
$GLOBALS['TL_LANG']['tl_translation']['new']    = array('Neue Sprachvariable', 'Eine neue Sprachvariable erstellen');
$GLOBALS['TL_LANG']['tl_translation']['show']   = array('Sprachvariablendetails', 'Details der Sprachvariable ID %s anzeigen');
$GLOBALS['TL_LANG']['tl_translation']['copy']   = array('Sprachvariable duplizieren', 'Sprachvariable ID %s duplizieren');
$GLOBALS['TL_LANG']['tl_translation']['delete'] = array('Sprachvariable löschen', 'Sprachvariable ID %s löschen');
$GLOBALS['TL_LANG']['tl_translation']['edit']   = array('Sprachvariable bearbeiten', 'Sprachvariable ID %s bearbeiten');


/**
 * Errors
 */
$GLOBALS['TL_LANG']['tl_translation']['require_backend_frontend'] = 'Sie müssen mindestens eine Option auswählen.';


/**
 * Search
 */
$GLOBALS['TL_LANG']['tl_translation']['keyword']  = array('Zeichenkette', 'Geben Sie hier die zu suchende Zeichenkette ein. Diese darf auch HTML beinhalten.');
$GLOBALS['TL_LANG']['tl_translation']['empty']    = 'In diese Gruppe wurde nichts gefunden';
$GLOBALS['TL_LANG']['tl_translation']['back']     = 'Zurück';
$GLOBALS['TL_LANG']['tl_translation']['continue'] = 'Weitersuchen';
$GLOBALS['TL_LANG']['tl_translation']['dosearch'] = 'Suchen';

/**
 * Generate language variable keys
 */
$GLOBALS['TL_LANG']['tl_translation']['statistic']    = 'Statistik';
$GLOBALS['TL_LANG']['tl_translation']['groupCount']   = 'Bekannte Gruppen';
$GLOBALS['TL_LANG']['tl_translation']['langvarCount'] = 'Bekannte Sprachvariablen';
$GLOBALS['TL_LANG']['tl_translation']['clean']        = array('Alle Sprachvariablen neu aufbauen', 'Auch bekannte Sprachvariablengruppen werden neu indiziert.');
$GLOBALS['TL_LANG']['tl_translation']['update']       = 'Index wird aufgebaut&hellip;';
$GLOBALS['TL_LANG']['tl_translation']['regenerate']   = 'Sprachvariablen erfassen';
$GLOBALS['TL_LANG']['tl_translation']['regenerated']  = 'Alle Sprachvariablen wurden erfasst.';
$GLOBALS['TL_LANG']['tl_translation']['nothinktodo']  = 'Es gibt nichts zu tun.';
