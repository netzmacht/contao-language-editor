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

namespace Netzmacht\Contao\LanguageEditor;

/**
 * Class Hooks
 *
 * @package Netzmacht\Contao\LanguageEditor
 */
class Hooks
{
    /**
     * Load the language file of the langauge editor.
     */
    public function loadLanguageFile()
    {
        if (\Input::get('do') !== 'language-editor' && file_exists(TL_ROOT . '/system/languages/locallang.php')) {
            include TL_ROOT . '/system/languages/locallang.php';
        }
    }
}
