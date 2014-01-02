<?php
require 'vendor/autoload.php';

use \Slim\Views\Twig;
use \ComPHPPuebla\Twig\HalRendererExtension;

$resource = [
    'links' => [
        'self' => '/users/1',
    ],
    'data' => [
        'username' => 'montealegreluis',
        'password' => 'changeme',
    ],
];

$view = new Twig();
$view->twigTemplateDirs = ['examples'];
$view->parserExtensions = [
    new HalRendererExtension(),
];
$view->setData(['resource' => $resource]);

echo $view->display('show.json.twig'), "\n";

echo $view->display('show.xml.twig'), "\n";

$resources = [
    'links' => [
        'self' => '/users?page=1',
        'next' => '/users?page=2',
    ],
    'data' => [],
    'embedded' => [
        [
            'users' => [
                'links' => [
                    'self' => '/users/1',
                ],
                'data' => [
                    'username' => 'montealegreluis',
                    'password' => 'changeme',
                ],
            ],
        ],
        [
            'users' => [
                'links' => [
                    'self' => '/users/2',
                ],
                'data' => [
                    'username' => 'michmendar',
                    'password' => 'letmein',
                ],
            ],
        ],
    ],
];

$view->setData(['resource' => $resources]);

echo $view->display('show.json.twig'), "\n";

echo $view->display('show.xml.twig'), "\n";
