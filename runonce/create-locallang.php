<?php

namespace Netzmacht\Contao\LanguageEditor;

class RunOnceController
{
    public function run()
    {
        if (!file_exists(TL_ROOT . '/system/languages/locallang.php')) {
            $file = new \File('system/languages/locallang.php');
            $file->write('<?php' . "\n");
            $file->close();
        }
    }
}

$controller = new RunOnceController();
$controller->run();
