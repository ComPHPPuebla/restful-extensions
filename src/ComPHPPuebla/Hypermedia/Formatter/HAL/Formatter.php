<?php
namespace ComPHPPuebla\Hypermedia\Formatter\HAL;

use \Slim\Views\TwigExtension;
use \IteratorAggregate;

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
     * @param array $resources
     * @param array | Paginator $params
     */
    abstract public function format($resources, array $params);
}
