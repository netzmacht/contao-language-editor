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

namespace Netzmacht\Contao\LanguageEditor;

/**
 * Class LangPlainWidget
 *
 * @copyright  InfinitySoft 2012
 * @author	   Tristan Lins <tristan.lins@infinitysoft.de>
 * @package	   Language Editor
 */
class LangPlainWidget extends \Widget
{
    /**
     * Template
     * @var string
     */
    protected $strTemplate = 'be_widget';

    /**
     * Validate values.
     *
     * @param mixed $varInput The widget input.
     *
     * @return bool
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
