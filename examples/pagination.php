<?php
$autoloader = require 'vendor/autoload.php';
$autoloader->add('', __DIR__); //UserTable

use \Doctrine\DBAL\DriverManager;
use \ComPHPPuebla\Paginator\PagerfantaPaginator;
use \ComPHPPuebla\Paginator\PagerfantaPaginatorFactory;

$connection = DriverManager::getConnection([
    'path' => 'test.sq3',
    'driver' => 'pdo_sqlite',
    'password' => 't3st!',
    'user' => 'test',
]);

$factory = new PagerfantaPaginatorFactory(new PagerfantaPaginator($pageSize = 1));
$userTable = new UserTable('users', $connection);

$paginator = $factory->createPaginator(['page' => 2], $userTable);

echo 'Users in current page:', "\n";
foreach ($paginator->getCurrentPageResults() as $user) {
    echo $user['username'], "\n";
}

if ($paginator->haveToPaginate()) {
    echo 'Showing page ', $paginator->getCurrentPage(), ' of ', $paginator->getNbPages(), "\n";

    if ($paginator->hasPreviousPage()) {
        echo 'Previous ', $paginator->getPreviousPage(), "\n";
    }

    if ($paginator->hasNextPage()) {
        echo 'Next ', $paginator->getNextPage(), "\n";
    }
}
