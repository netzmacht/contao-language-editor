<?php

/**
 * Contao Language editor
 *
 * @package    Language Editor
 * @author     David Molineus <david.molineus@netzmacht.de>
 * @copyright  2012 InfinitySoft 2012
 * @copyright  2015-2019 netzmacht David Molineus
 * @license    LGPL-3.0-or-later https://github.com/netzmacht/contao-language-editor/blob/master/LICENSE
 * @filesource
 */

namespace Netzmacht\Contao\LanguageEditor;

/**
 * Class Hooks.
 *
 * @package Netzmacht\Contao\LanguageEditor
 */
class Hooks
{
    /**
     * Load the language file of the language editor.
     *
     * @return void
     */
    public function loadLanguageFile()
    {
        if (\Input::get('do') !== 'language-editor' && file_exists(TL_ROOT . '/system/languages/locallang.php')) {
            include TL_ROOT . '/system/languages/locallang.php';
        }
    }
}
