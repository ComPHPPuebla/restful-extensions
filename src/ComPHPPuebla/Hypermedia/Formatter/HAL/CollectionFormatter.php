<?php
namespace ComPHPPuebla\Hypermedia\Formatter\HAL;

use \Slim\Views\TwigExtension;
use \ComPHPPuebla\Paginator\Paginator;

class CollectionFormatter extends Formatter
{
    /**
     * @var Paginator
     */
    protected $paginator;

    /**
     * @var ResourceFormatter
     */
    protected $formatter;

    /**
     * @param Paginator $paginator
     */
    public function __construct(
        TwigExtension $urlHelper, $routeName, Paginator $paginator, ResourceFormatter $formatter
    )
    {
        parent::__construct($urlHelper, $routeName);
        $this->paginator = $paginator;
        $this->formatter = $formatter;
    }

    /**
     * @param int   $count
     * @param array $collection
     * @param array $params
     * @param string $routeName
     */
    public function format(array $resources, array $params)
    {
        $this->setResources($resources);

        $halCollection = ['links' => []];

        $halCollection['links'] = $this->createPaginationLinks($this->routeName, $params);
        $halCollection['links']['self'] = $this->buildUrl($this->routeName, $params);

        $embedded = [];
        foreach ($this->paginator->getCurrentPageResults() as $resource) {
            $embedded[][$this->routeName] = $this->formatter->format($resource, $params);
        }

        $halCollection['embedded'] = $embedded;
        $halCollection['data'] = [];

        return $halCollection;
    }

    /**
     * @param array $items
     */
    protected function setResources(array $items)
    {
        $count = $items['count'];
        unset($items['count']);
        $this->paginator->setResults($items, $count);
    }

    /**
     * @param string   $routeName
     * @param array $params
     */
    protected function createPaginationLinks($routeName, array $params)
    {
        if (!isset($params['page'])) {

            return [];
        }

        $this->paginator->setCurrentPage($params['page']);

        $links = [];
        if ($this->paginator->haveToPaginate()) {

            $params['page'] = 1;
            $links['first'] = $this->buildUrl($routeName, $params);

            if ($this->paginator->hasNextPage()) {
                $params['page'] = $this->paginator->getNextPage();
                $links['next'] = $this->buildUrl($routeName, $params);
            }

            if ($this->paginator->hasPreviousPage()) {
                $params['page'] = $this->paginator->getPreviousPage();
                $links['prev'] = $this->buildUrl($routeName, $params);
            }

            $params['page'] = $this->paginator->getNbPages();
            $links['last'] = $this->buildUrl($routeName, $params);
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

        return $baseUrl . '?' . http_build_query($params);
    }
}
