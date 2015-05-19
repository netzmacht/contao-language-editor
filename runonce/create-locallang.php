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

class RunOnceController
{
    public function run()
    {
        if (!file_exists(TL_ROOT . '/system/languages/locallang.php')) {
            // The composer client uses a custom error handler so the auto creation of the folder does not work
            // because Contao does not check if the folder exists.
            if (!is_dir(TL_ROOT . '/system/languages')) {
                $files = \Files::getInstance();
                $files->mkdir('system/languages');
            }

            $file = new \File('system/languages/locallang.php');
            $file->write('<?php' . "\n");
            $file->close();
        }
    }
}

$controller = new RunOnceController();
$controller->run();
