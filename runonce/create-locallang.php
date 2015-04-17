<?php

namespace Netzmacht\Contao\LanguageEditor;

class RunonceController
{
    public function run()
    {
        if (!file_exists(TL_ROOT . 'system/languages/locallang.php')) {
            $file = new \File('system/languages/locallang.php');
            $file->append('<?php' . "\n");
            $file->close();
        }
    }
}

$controller = new RunonceController();
$controller->run();
