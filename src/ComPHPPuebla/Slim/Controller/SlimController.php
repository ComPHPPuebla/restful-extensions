<?php
namespace ComPHPPuebla\Slim\Controller;

use \Slim\Http\Response;
use \Slim\Http\Request;

abstract class SlimController
{
    /**
     * @var Request
     */
    protected $request;

    /**
     * @var Response
     */
    protected $response;

    /**
     * @param Request $request
     * @param Response $response
     */
    public function __construct(Request $request, Response $response)
    {
        $this->request = $request;
        $this->response = $response;
    }
}
