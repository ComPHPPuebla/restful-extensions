<?php
$autoloader = require 'vendor/autoload.php';
$autoloader->add('', __DIR__); //UserTable

use \Slim\Views\TwigExtension;
use \ComPHPPuebla\Hypermedia\Formatter\HAL\ResourceFormatter;
use \ComPHPPuebla\Hypermedia\Formatter\HAL\CollectionFormatter;
use \Doctrine\DBAL\DriverManager;
use \ComPHPPuebla\Paginator\PagerfantaPaginator;
use \ComPHPPuebla\Paginator\PagerfantaPaginatorFactory;
use \Slim\Slim;
use \Slim\Environment;

Environment::mock([
    'REQUEST_METHOD' => 'GET',
    'REMOTE_ADDR' => '127.0.0.1',
    'REQUEST_URI' => '/users',
    'SERVER_NAME' => 'localhost',
    'SERVER_PORT' => '80',
]);
$app = new Slim();
$app->get('/users', function() {})->name('users');
$app->get('/users/:id', function() {})->name('user');

$connection = DriverManager::getConnection(['path' => 'test.sq3', 'driver' => 'pdo_sqlite']);
$factory = new PagerfantaPaginatorFactory(new PagerfantaPaginator($pageSize = 2));
$userTable = new UserTable('users', $connection);
$queryStringParams = ['page' => 1, 'page_size' => 2];

$paginator = $factory->createPaginator($queryStringParams, $userTable);

$urlHelper = new TwigExtension();
$formatter = new ResourceFormatter($urlHelper, 'user', 'user_id');

$collectionFormatter = new CollectionFormatter($urlHelper, 'users', $formatter);

$halCollection = $collectionFormatter->format($paginator, $queryStringParams);

echo 'HAL collection', "\n";
var_dump($halCollection);

$resource = ['user_id' => 3, 'username' => 'montealegreluis', 'password' => 'letmein'];

$halResource = $formatter->format($resource, []);

echo 'HAL resource', "\n";
var_dump($halResource);
