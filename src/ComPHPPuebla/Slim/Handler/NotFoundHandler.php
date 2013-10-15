<?php
namespace ComPHPPuebla\Slim\Handler;

class NotFoundHandler extends Handler
{
    public function __invoke()
    {
        $url = $this->getServerUrl($this->app->request()->getPathInfo());
        $this->app->log->info("Page not found {$url}");

        $this->app->status(404);
        $this->app->contentType('text/plain');
        $this->app->render("error/notfound.text.twig", ['url' => $url]);
    }

    /**
     * @param  string $url
     * @return string
     */
    protected function getServerUrl($url)
    {
        $env = $this->app->environment();

        return "{$env['slim.url_scheme']}://{$env['SERVER_NAME']}$url";
    }
}
