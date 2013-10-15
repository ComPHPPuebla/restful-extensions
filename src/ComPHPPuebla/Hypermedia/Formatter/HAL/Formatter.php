<?php
namespace ComPHPPuebla\Hypermedia\Formatter\HAL;

use \Slim\Views\TwigExtension;

abstract class Formatter
{
    /**
     * @var TwigExtension
     */
    protected $urlHelper;

    /**
     * @var string
     */
    protected $routeName;

    /**
     * @param TwigExtension $urlHelper
     */
    public function __construct(TwigExtension $urlHelper, $routeName)
    {
        $this->urlHelper = $urlHelper;
        $this->routeName = $routeName;
    }

    /**
     * @param array $values
     */
    abstract public function format(array $resources, array $params);
}
