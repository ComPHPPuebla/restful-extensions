<?php
namespace ComPHPPuebla\Slim\Middleware;

use \ComPHPPuebla\Model\Model;
use \Slim\Middleware;
use \InvalidArgumentException;

class CheckOptionsMiddleware extends Middleware
{
    /**
     * @var Model
     */
    protected $model;

    /**
     * @param Model $model
     */
    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    /**
     * Check if the current HTTP method is allowed for the resource being accesed
     *
     * @see \Slim\Middleware::call()
     */
    public function call()
    {
        $params = $this->app->router()->getCurrentRoute()->getParams();

        $options = $this->model->getOptionsList();
        if (isset($params['id'])) {
            $options = $this->model->getOptions();
        }

        if (in_array($this->app->request()->getMethod(), $options)) {
            $this->next->call();

            return;
        }

        $this->app->response()->setStatus(405); // Method Not Allowed
    }
}
