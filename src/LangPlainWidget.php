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
 * Class LangPlainWidget is used to display the language var content as plain text.
 *
 * @copyright  InfinitySoft 2012
 * @author	   Tristan Lins <tristan.lins@infinitysoft.de>
 * @package	   Language Editor
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
