<?php
namespace ComPHPPuebla\Slim\Middleware;

use \Slim\Middleware;
use \Negotiation\Negotiator;

class ContentNegotiationMiddleware extends Middleware
{
    /**
     * @var array
     */
    protected $validContentTypes;

    /**
     * Initialize valid content types
     */
    public function __construct()
    {
        $this->validContentTypes = ['application/json', 'application/xml'];
    }

    /**
     * Ensure the provided content type is valid and set the proper response content type
     *
     * @see \Slim\Middleware::call()
     */
    public function call()
    {
        if (!$this->responseShouldHaveBody()) {

            $this->next->call();

            return;
        }

        $negotiator = new Negotiator();
        $format = $negotiator->getBest($this->app->request()->headers('Accept'));
        $type = $format->getValue();

        if (in_array($type, $this->validContentTypes)) {

            $this->app->contentType($type);
            $this->next->call();

            return;
        }

        $this->app->status(406); //Not acceptable
    }

    /**
     * @return boolean
     */
    public function responseShouldHaveBody()
    {
        $request = $this->app->request();

        return $request->isGet() || $request->isPost() || $request->isPut();
    }
}
