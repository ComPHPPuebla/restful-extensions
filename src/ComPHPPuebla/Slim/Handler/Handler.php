<?php
namespace ComPHPPuebla\Slim\Handler;

use \Slim\Slim;

class Handler
{
    /**
     * @var \Slim\Slim
     */
    protected $app;

    /**
     * @param \Slim\Slim $app
     */
    public function __construct(Slim $app)
    {
        $this->app = $app;
    }
}
