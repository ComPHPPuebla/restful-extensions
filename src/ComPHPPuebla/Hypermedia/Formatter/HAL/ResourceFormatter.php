<?php
namespace ComPHPPuebla\Hypermedia\Formatter\HAL;

use \Slim\Views\TwigExtension;
use \IteratorAggregate;

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
     * @param array $resources
     * @param array $params
     * @return array
     */
    public function format(IteratorAggregate $resource, array $params)
    {
        $halResource = ['links' => []];

        $halResource['links']['self'] = $this->urlHelper->site(
            $this->urlHelper->urlFor($this->routeName, ['id' => $resource[$this->resourceKeyId]])
        );

        $halResource['data'] = $resource->getIterator()->getArrayCopy();

        return $halResource;
    }
}
