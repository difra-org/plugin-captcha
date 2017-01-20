<?php

namespace Difra\Plugins\Capcha;

/**
 * Class Plugin
 * @package Difra\Plugins\Capcha
 */
class Plugin extends \Difra\Plugin
{
    protected $provides = 'captcha';
//    protected $require = '';
    protected $version = 7;
    protected $description = 'Captcha';

    public function init()
    {
    }
}

Plugin::enable();
