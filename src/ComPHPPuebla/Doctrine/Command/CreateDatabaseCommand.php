<?php
/*
 * Create a database
 *
 * PHP version 5.3
 *
 * This source file is subject to the license that is bundled with this package in the
 * file LICENSE.
 *
 * @author     LMV <luis.montealegre@mandragora-web-systems.com>
 */
namespace ComPHPPuebla\Doctrine\Command;

use \Symfony\Component\Console\Command\Command;
use \Symfony\Component\Console\Input\InputInterface;
use \Symfony\Component\Console\Output\OutputInterface;
use \Doctrine\DBAL\DriverManager;
use \Exception;

/**
 * Create a database
 *
 * @author     LMV <luis.montealegre@mandragora-web-systems.com>
 */
class CreateDatabaseCommand extends Command
{
    /**
     * {@inheritDoc}
     */
    protected function configure()
    {
        $this->setName('dbal:database:create')
             ->setDescription('Creates the configured databases')
             ->setHelp(<<<EOT
The <info>dbal:database:create</info> command creates the connection database.
EOT
        );
    }

    /**
     * {@inheritDoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $connection = $this->getHelper('db')->getConnection();
        $params = $connection->getParams();
        $name = isset($params['path']) ? $params['path'] : $params['dbname'];
        unset($params['dbname']);

        $tmpConnection = DriverManager::getConnection($params);
        if (!isset($params['path'])) {
            $name = $tmpConnection->getDatabasePlatform()->quoteSingleIdentifier($name);
        }

        $error = false;
        try {
            $tmpConnection->getSchemaManager()->createDatabase($name);
            $output->writeln(sprintf('<info>Created database named <comment>%s</comment></info>', $name));
        } catch (Exception $e) {
            $output->writeln(sprintf('<error>Could not create database named <comment>%s</comment></error>', $name));
            $output->writeln(sprintf('<error>%s</error>', $e->getMessage()));
            $error = true;
        }

        $tmpConnection->close();
    }
}
