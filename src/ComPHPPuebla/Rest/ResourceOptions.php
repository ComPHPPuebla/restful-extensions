<?php
namespace ComPHPPuebla\Rest;

class ResourceOptions
{
    /**
     * @var array
     */
    protected $collectionOptions;

    /**
     * @var array
     */
    protected $options;

    /**
     * @param array $options
     * @param array $collectionOptions
     */
    public function __construct(
        array $options = ['GET', 'POST', 'PUT', 'DELETE', 'OPTIONS', 'HEAD'],
        array $collectionOptions = ['GET', 'POST', 'OPTIONS']
    )
    {
        $this->options = $options;
        $this->collectionOptions = $collectionOptions;
    }

    /**
     * @return array
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * @return array
     */
    public function getCollectionOptions()
    {
        return $this->collectionOptions;
    }
}
