<?php

declare(strict_types=1);

namespace Controller;

class Captcha extends \Difra\Controller
{
    /**
     * View captcha
     */
    public function indexAction()
    {
        $captcha = \Difra\Captcha::getInstance();
        $captcha->setSize(105, 36);
        //$Capcha->setKeyLength( 4 );
        header('Content-type: image/png');
        header("Expires: Sat, 26 Jul 1997 05:00:00 GMT"); // Some time in the past
        header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
        header("Cache-Control: no-store, no-cache, must-revalidate");
        header("Cache-Control: post-check=0, pre-check=0", false);
        header("Pragma: no-cache");
        echo $captcha->viewCaptcha();
        \Difra\View::$rendered = true;
    }
}
