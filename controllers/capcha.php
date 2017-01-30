<?php

/**
 * Class CapchaController
 * Displays capchas.
 */
class CapchaController extends Difra\Controller
{
    /**
     * View capcha
     */
    public function indexAction()
    {
        $capcha = \Difra\Capcha::getInstance();
        $capcha->setSize(105, 36);
        //$Capcha->setKeyLength( 4 );
        header('Content-type: image/png');
        header("Expires: Sat, 26 Jul 1997 05:00:00 GMT"); // Some time in the past
        header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
        header("Cache-Control: no-store, no-cache, must-revalidate");
        header("Cache-Control: post-check=0, pre-check=0", false);
        header("Pragma: no-cache");
        echo $capcha->viewCapcha();
        \Difra\View::$rendered = true;
    }
}
