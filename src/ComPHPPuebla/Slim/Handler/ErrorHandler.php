<?php
namespace ComPHPPuebla\Slim\Handler;

use \Exception;

class ErrorHandler extends Handler
{
    public function __invoke(Exception $e)
    {
        $this->app->log->error($e);

        $this->app->status(500);
        $this->app->render("error/exception.{$this->app->viewExtension}.twig", [
            'exception' => (string)$e,
            'mode' => $this->app->getMode(),
        ]);
    }
}
