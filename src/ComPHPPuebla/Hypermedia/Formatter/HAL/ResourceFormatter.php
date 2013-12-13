<?php
namespace ComPHPPuebla\Hypermedia\Formatter\HAL;

use \Slim\Views\TwigExtension;

class ResourceFormatter extends Formatter
{
    /**
     * @var string
     */
    protected $resourceKeyId;

    /**
     * @param TwigExtension $urlHelper
     */
    public function __construct(TwigExtension $urlHelper, $routeName, $resourceKeyId)
    {
        parent::__construct($urlHelper, $routeName);
        $this->resourceKeyId = $resourceKeyId;
    }

    /**
     * @param  array $resource
     * @param  array $params
     * @return array
     */
    public function format($resource, array $params)
    {
        $halResource = ['links' => []];

        $halResource['links']['self'] = $this->urlHelper->site(
            $this->urlHelper->urlFor($this->routeName, ['id' => $resource[$this->resourceKeyId]])
        );

        $halResource['data'] = $resource;

        return $halResource;
    }
}
