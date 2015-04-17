<?php

/**
 * @package    dev
 * @author     David Molineus <david.molineus@netzmacht.de>
 * @copyright  2015 netzmacht creative David Molineus
 * @license    LGPL 3.0
 * @filesource
 *
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
        if (\Input::get('do') !== 'language-editor') {
            include TL_ROOT . '/system/languages/locallang.php';
        }
    }
}
