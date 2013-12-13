<?php
namespace ComPHPPuebla\Hypermedia\Formatter\HAL;

use \ComPHPPuebla\Paginator\Paginator;
use \Slim\Views\TwigExtension;

class CollectionFormatter extends Formatter
{
    /**
     * @var ResourceFormatter
     */
    protected $formatter;

    /**
     * @param TwigExtension     $urlHelper
     * @param string            $routeName
     * @param ResourceFormatter $formatter
     */
    public function __construct(TwigExtension $urlHelper, $routeName, ResourceFormatter $formatter)
    {
        parent::__construct($urlHelper, $routeName);
        $this->formatter = $formatter;
    }

    /**
     * @see \ComPHPPuebla\Hypermedia\Formatter\HAL\Formatter::format()
     */
    public function format($paginator, array $params)
    {
        $embedded = [];
        $resources = $paginator->getCurrentPageResults();
        foreach ($resources as $resource) {
            $embedded[][$this->routeName] = $this->formatter->format($resource, $params);
        }

        $halCollection['embedded'] = $embedded;
        $halCollection['data'] = [];

        $halCollection['links'] = [];
        $halCollection['links'] = $this->createPaginationLinks(
            $paginator, $this->routeName, $params
        );
        $halCollection['links']['self'] = $this->buildUrl($this->routeName, $params);

        return $halCollection;
    }

    /**
     * @param string $routeName
     * @param array  $params
     */
    protected function createPaginationLinks(Paginator $paginator, $routeName, array $params)
    {
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
     * @param  string $routeName
     * @param  array  $params
     * @return string
     */
    protected function buildUrl($routeName, array $params)
    {
        $baseUrl = $this->urlHelper->site($this->urlHelper->urlFor($routeName));

        return trim($baseUrl . '?' . http_build_query($params), '?');
    }
}
