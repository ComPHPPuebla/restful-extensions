<?php
namespace ComPHPPuebla\Hypermedia\Formatter\HAL;

use \Slim\Views\TwigExtension;
use \ComPHPPuebla\Paginator\Paginator;
use \IteratorAggregate;

class CollectionFormatter extends Formatter
{
    /**
     * @var ResourceFormatter
     */
    protected $formatter;

    /**
     * @param Paginator $paginator
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
        $halCollection = ['links' => []];

        $halCollection['links'] = $this->createPaginationLinks(
            $paginator, $this->routeName, $params
        );
        $halCollection['links']['self'] = $this->buildUrl($this->routeName, $params);

        $embedded = [];
        foreach ($paginator->getCurrentPageResults() as $resource) {
            $embedded[][$this->routeName] = $this->formatter->format($resource, $params);
        }

        $halCollection['embedded'] = $embedded;
        $halCollection['data'] = [];

        return $halCollection;
    }


    /**
     * @param string   $routeName
     * @param array $params
     */
    protected function createPaginationLinks(IteratorAggregate $paginator, $routeName, array $params)
    {
        if (!isset($params['page'])) {

            return [];
        }

        $paginator->setCurrentPage($params['page']);

        $links = [];
        if ($paginator->haveToPaginate()) {

            $params['page'] = 1;
            $links['first'] = $this->buildUrl($routeName, $params);

            if ($paginator->hasNextPage()) {
                $params['page'] = $this->paginator->getNextPage();
                $links['next'] = $this->buildUrl($routeName, $params);
            }

            if ($paginator->hasPreviousPage()) {
                $params['page'] = $this->paginator->getPreviousPage();
                $links['prev'] = $this->buildUrl($routeName, $params);
            }

            $params['page'] = $paginator->getNbPages();
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
