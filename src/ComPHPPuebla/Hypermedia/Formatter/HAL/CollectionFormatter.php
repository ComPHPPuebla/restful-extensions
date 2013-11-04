<?php
namespace ComPHPPuebla\Hypermedia\Formatter\HAL;

use \ComPHPPuebla\Paginator\Paginator;
use \Slim\Views\TwigExtension;
use \ArrayObject;
use \IteratorAggregate;

class CollectionFormatter extends Formatter
{
    /**
     * @var ResourceFormatter
     */
    protected $formatter;

    /**
     * @param TwigExtension $urlHelper
     * @param string $routeName
     * @param ResourceFormatter $formatter
     */
    public function __construct(TwigExtension $urlHelper, $routeName, ResourceFormatter $formatter)
    {
        parent::__construct($urlHelper, $routeName);
        $this->formatter = $formatter;
    }

    /**
     * @param int   $count
     * @param array $collection
     * @param array $params
     * @param string $routeName
     */
    public function format(IteratorAggregate $paginator, array $params)
    {
        $isPaginator = $paginator instanceof Paginator;

        $halCollection = ['links' => []];
        if ($isPaginator) {
            $halCollection['links'] = $this->createPaginationLinks(
                $paginator, $this->routeName, $params
            );
        }
        $halCollection['links']['self'] = $this->buildUrl($this->routeName, $params);

        $embedded = [];
        $items = $isPaginator ? $paginator->getCurrentPageResults() : $paginator->getArrayCopy();
        foreach ($items as $resource) {
            $embedded[][$this->routeName] = $this->formatter->format(
                new ArrayObject($resource), $params
            );
        }

        $halCollection['embedded'] = $embedded;
        $halCollection['data'] = [];

        return $halCollection;
    }

    /**
     * @param string   $routeName
     * @param array $params
     */
    protected function createPaginationLinks(Paginator $paginator, $routeName, array $params)
    {
        $paginator->setCurrentPage($params['page']);

        $links = [];
        if ($paginator->haveToPaginate()) {

            if (1 !== $paginator->getCurrentPage()) {
                $params['page'] = 1;
                $links['first'] = $this->buildUrl($routeName, $params);
            }
            if ($paginator->hasNextPage()) {
                $params['page'] = $paginator->getNextPage();
                $links['next'] = $this->buildUrl($routeName, $params);
            }

            if ($paginator->hasPreviousPage()) {
                $params['page'] = $paginator->getPreviousPage();
                $links['prev'] = $this->buildUrl($routeName, $params);
            }
            if ($paginator->getNbPages() !== $paginator->getCurrentPage()) {
                $params['page'] = $paginator->getNbPages();
                $links['last'] = $this->buildUrl($routeName, $params);
            }
        }

        return $links;
    }

    /**
     * @param string $routeName
     * @param array $params
     * @return string
     */
    protected function buildUrl($routeName, array $params)
    {
        $baseUrl = $this->urlHelper->site($this->urlHelper->urlFor($routeName));

        return trim($baseUrl . '?' . http_build_query($params), '?');
    }
}
