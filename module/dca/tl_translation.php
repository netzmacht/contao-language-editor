<?php use Netzmacht\Contao\LanguageEditor\LanguageEditor;

if (!defined('TL_ROOT')) die('You can not access this file directly!');

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
			array('Netzmacht\Contao\LanguageEditor\Dca\Translation', 'loadTranslation')
		),
		'onsubmit_callback' => array
		(
			array('Netzmacht\Contao\LanguageEditor\Dca\Translation', 'markUpdate')
		),
		'ondelete_callback' => array
		(
			array('Netzmacht\Contao\LanguageEditor\Dca\Translation', 'markUpdate')
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
			'label_callback'          => array('Netzmacht\Contao\LanguageEditor\Dca\Translation', 'getLabel')
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
			'save_callback'           => array(array('Netzmacht\Contao\LanguageEditor\Dca\Translation', 'saveLangGroup'))
		),
		'langvar' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_translation']['langvar'],
			'default'                 => $this->Input->get('langvar'),
			'search'                  => true,
			'inputType'               => 'select',
			'options_callback'        => array('Netzmacht\Contao\LanguageEditor\Dca\Translation', 'getLanguageVariablesOptions'),
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
				array('Netzmacht\Contao\LanguageEditor\Dca\Translation', 'loadDefault')
			)
		),
		'content' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_translation']['content'],
			'search'                  => true,
			'inputType'               => 'text',
			'eval'                    => array('tl_class'=>'clr long', 'includeBlankOption'=>true, 'allowHtml'=>true, 'preserveTags'=>true),
			'load_callback'           => array(
				array('Netzmacht\Contao\LanguageEditor\Dca\Translation', 'loadContent')
			)
		)
	)
);
