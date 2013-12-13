<?php
namespace ComPHPPuebla\Twig;

use \Hal\Resource;
use \Hal\Link;
use \Twig_Extension as TwigExtension;
use \Twig_SimpleFunction as SimpleFunction;

class HalRendererExtension extends TwigExtension
{
    /**
     * @return string
     */
    public function getName()
    {
        return 'hal';
    }

    /**
     * @return SimpleFunction[]
     */
    public function getFunctions()
    {
        return [
            new SimpleFunction('renderJson', array($this, 'renderJson')),
            new SimpleFunction('renderXml', array($this, 'renderXml')),
        ];
    }

    /**
     * @param  array    $resourceInfo
     * @return Resource
     */
    public function process(array $resourceInfo)
    {
        $resource = new Resource($resourceInfo['links']['self'], $resourceInfo['data']);
        unset($resourceInfo['links']['self']);

        foreach ($resourceInfo['links'] as $key => $href) {
            $resource->setLink(new Link($href, $key));
        }

        if (isset($resourceInfo['embedded'])) {

            foreach ($resourceInfo['embedded'] as $rel) {

                foreach ($rel as $rel => $data) {
                    $resource->setEmbedded(
                        $rel, new Resource($data['links']['self'], $data['data'])
                    );
                }
            }
        }

        return $resource;
    }

    /**
     * @param  array  $resource
     * @return string
     */
    public function renderJson(array $resource)
    {
        return json_encode(
            $this->process($resource)->toArray(), JSON_NUMERIC_CHECK | JSON_PRETTY_PRINT
        );
    }

    /**
     * @param  array  $resource
     * @return string
     */
    public function renderXml(array $resource)
    {
        $xml = $this->process($resource)->getXML();
        $dom = dom_import_simplexml($xml)->ownerDocument;
        $dom->formatOutput = true;

        return $dom->saveXML();
    }
}
