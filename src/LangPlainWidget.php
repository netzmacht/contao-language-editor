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
 * Class LangPlainWidget is used to display the language var content as plain text
 */
class LangPlainWidget extends \Widget
{
    /**
     * The template name.
     *
     * @var string
     */
    protected $strTemplate = 'be_widget';

    /**
     * Validate values.
     *
     * @param mixed $varInput The widget input value.
     *
     * @return bool
     *
     * @SuppressWarnings(PHPMD.UnusedFormalParameters)
     */
    protected function validator($varInput)
    {
        return true;
    }

    /**
     * Generate the widget and return it as string.
     *
     * @return string
     */
    public function generate()
    {
        return sprintf('<pre class="plaintrans">%s</pre>', $this->varValue);
    }
}
