<?php
namespace ComPHPPuebla\Hypermedia\Formatter\HAL;

use \Slim\Views\TwigExtension;
use \ComPHPPuebla\Hypermedia\Formatter\Formatter;

abstract class HALFormatter implements Formatter
{
    /**
     * @var TwigExtension
     */
    protected $urlHelper;

    /**
     * @var string
     */
    protected $routeName;
}
