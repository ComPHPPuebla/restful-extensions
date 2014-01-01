<?php
/**
 * RESTful extensions CLI
 *
 * PHP version 5.4
 *
 * This source file is subject to the license that is bundled with this package in the
 * file LICENSE.
 *
 * @author     LMV <montealegreluis@gmail.com>
 */
require 'vendor/autoload.php';

use \Symfony\Component\Console\Application;
use \Symfony\Component\Console\Helper\HelperSet;
use \Symfony\Component\Console\Helper\DialogHelper;
use \Doctrine\DBAL\Tools\Console\Helper\ConnectionHelper;
use \Doctrine\DBAL\DriverManager;
use \ComPHPPuebla\Doctrine\Command\CreateDatabaseCommand;
use \ComPHPPuebla\Doctrine\Command\DropDatabaseCommand;

/**
 * DBAL Fixtures CLI
 *
 * @author     LMV <montealegreluis@gmail.com>
 */
$cli = new Application('RESTful extensions CLI', '0.1.0');
$cli->setCatchExceptions(true);

$connection = DriverManager::getConnection([
    'path' => 'test.sq3',
    'driver' => 'pdo_sqlite',
    'password' => 't3st!',
    'user' => 'test',
]);

$helperSet = new HelperSet();
$helperSet->set(new DialogHelper(), 'dialog');
$helperSet->set(new ConnectionHelper($connection), 'db');

$cli->setHelperSet($helperSet);

$cli->addCommands([
    new CreateDatabaseCommand(),
    new DropDatabaseCommand(),
]);

$cli->run();
