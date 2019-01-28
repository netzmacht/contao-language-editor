<?php

/**
 * Contao Language editor
 *
 * @package    Language Editor
 * @author     David Molineus <david.molineus@netzmacht.de>
 * @author     Fritz Michael Gschwantner <fmg@inspiredminds.at>
 * @copyright  2012 InfinitySoft 2012
 * @copyright  2015-2019 netzmacht David Molineus
 * @license    LGPL-3.0-or-later https://github.com/netzmacht/contao-language-editor/blob/master/LICENSE
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
        ),
        'sql' => array
        (
            'keys' => array
            (
                'id' => 'primary',
                'langgroup' => 'index',
                'langvar' => 'index',
            ),
        ),
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
            'translation' => array('langvar', 'language', 'backend', 'frontend', 'default', 'content')
        )
    ),


    // Fields
    'fields' => array
    (
        'id' => array
        (
            'sql' => 'int(10) unsigned NOT NULL auto_increment',
        ),
        'tstamp' => array
        (
            'sql' => "int(10) unsigned NOT NULL default '0'",
        ),
        'langgroup' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_translation']['langgroup'],
            'filter'                  => true,
            'inputType'               => 'text',
            'sql'                     => "varchar(255) NOT NULL default ''",
        ),
        'langvar' => array
        (
            'label'            => &$GLOBALS['TL_LANG']['tl_translation']['langvar'],
            'default'          => $this->Input->get('langvar'),
            'search'           => true,
            'inputType'        => 'select',
            'save_callback'    => array(array('Netzmacht\Contao\LanguageEditor\Dca\Translation', 'saveLangGroup')),
            'options_callback' => array(
                'Netzmacht\Contao\LanguageEditor\Dca\Translation',
                'getLanguageVariablesOptions'
            ),
            'eval'             => array(
                'mandatory'          => true,
                'chosen'             => true,
                'includeBlankOption' => true,
                'submitOnChange'     => true,
                'tl_class'           => 'w50',
                'alwaysSave'         => true,
            ),
            'sql'              => "varchar(255) NULL",
        ),
        'language' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_translation']['language'],
            'default'                 => $this->Input->get('language') ? $this->Input->get('language') : $GLOBALS['TL_LANGUAGE'],
            'filter'                  => true,
            'inputType'               => 'select',
            'options'                 => $this->getLanguages(),
            'eval'                    => array('mandatory'=>true, 'chosen'=>true, 'submitOnChange'=>true, 'tl_class'=>'w50'),
            'sql'                     => "char(2) NOT NULL default ''",
        ),
        'backend' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_translation']['backend'],
            'default'                 => true,
            'filter'                  => true,
            'inputType'               => 'checkbox',
            'eval'                    => array('tl_class'=>'w50'),
            'sql'                     => "char(1) NOT NULL default ''",
        ),
        'frontend' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_translation']['frontend'],
            'default'                 => true,
            'filter'                  => true,
            'inputType'               => 'checkbox',
            'eval'                    => array('tl_class'=>'w50'),
            'sql'                     => "char(1) NOT NULL default ''",
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
            ),
            'sql'                     => "blob NULL",
        )
    )
);
