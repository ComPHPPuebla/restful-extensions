<?php
$autoloader = require 'vendor/autoload.php';
$autoloader->add('', __DIR__); //UserTable

use \Doctrine\DBAL\DriverManager;
use \ComPHPPuebla\Rest\ResourceCollection;
use \ComPHPPuebla\Rest\Resource;
use \ComPHPPuebla\Rest\ResourceOptions;
use \ComPHPPuebla\Paginator\PagerfantaPaginator;
use \ComPHPPuebla\Paginator\PagerfantaPaginatorFactory;
use \ComPHPPuebla\Validator\ValitronValidator;

$connection = DriverManager::getConnection([
    'path' => 'test.sq3',
    'driver' => 'pdo_sqlite',
    'password' => 't3st!',
    'user' => 'test',
]);
$paginator = new PagerfantaPaginator($pageSize = 1);

$usersCollection = new ResourceCollection(
    new UserTable('users', $connection), new PagerfantaPaginatorFactory($paginator)
);

$users = $usersCollection->retrieveAll(['page' => 3]);

foreach ($users->getCurrentPageResults() as $user) {
    echo $user['username'], "\n";
}

$userResource = new Resource(new UserTable('users', $connection));

$user = $userResource->retrieveOne($id = 1);

echo $user['username'], "\n";

$validator = new ValitronValidator([
    'required' => [[[
        'username',
        'password',
    ]]],
    'length' => [
        ['username', 1, 15],
        ['password', 1, 20],
    ],
], 'examples');

$user = new Resource(new UserTable('users', $connection), $validator);
$userInfo = ['username' => 'johnsmith', 'password' => '12345'];

if ($user->isValid($userInfo)) {
    $newUser = $user->create($userInfo);
}

$user = new Resource(new UserTable('users', $connection), $validator);
$userId = array_shift($newUser);
$newUser['password'] = 'ilovecoding';

if ($user->isValid($newUser)) {
    $updatedUser = $user->update($newUser, $userId);
}

echo 'New password ', $updatedUser['password'], "\n";

$user = new Resource(new UserTable('users', $connection));

$user->delete($id = $updatedUser['user_id']);

$usersOptions = new ResourceOptions(
    $options = ['GET', 'POST', 'PUT', 'DELETE', 'OPTIONS', 'HEAD'],
    $collectionOptions = ['GET', 'POST', 'OPTIONS']
);

echo 'Resource OPTIONS ', implode(', ', $usersOptions->getOptions()), "\n";
echo 'Resource collection OPTIONS ', implode(', ', $usersOptions->getCollectionOptions()), "\n";
