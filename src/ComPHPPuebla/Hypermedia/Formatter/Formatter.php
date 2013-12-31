<?php
namespace ComPHPPuebla\Hypermedia\Formatter;

interface Formatter
{
    /**
     * @param array | Paginator $resources
     * @param array             $params
     */
    public function format($resources, array $params);
}
