<?php
/*
 * Drop a database
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
use \Symfony\Component\Console\Input\InputOption;
use \Symfony\Component\Console\Input\InputInterface;
use \Symfony\Component\Console\Output\OutputInterface;
use \InvalidArgumentException;
use \Exception;

/**
 * Drop a database
 *
 * @author     LMV <luis.montealegre@mandragora-web-systems.com>
 */
class DropDatabaseCommand extends Command
{
    /**
     * {@inheritDoc}
     */
    protected function configure()
    {
        $this->setName('dbal:database:drop')
             ->setDescription('Drops the configured database')
             ->addOption('force', null, InputOption::VALUE_NONE, 'Set this parameter to execute this action')
             ->setHelp(<<<EOT
The <info>dbal:database:drop</info> command drops the connection database.

The --force parameter has to be used to actually drop the database.
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
        $name = $this->getDatabaseName($params);

        if ($input->getOption('force')) {

            if (!isset($params['path'])) {
                $name = $connection->getDatabasePlatform()->quoteSingleIdentifier($name);
            }

            try {
                $connection->getSchemaManager()->dropDatabase($name);
                $output->writeln(sprintf('<info>Dropped database named <comment>%s</comment></info>', $name));
            } catch (Exception $e) {
                $output->writeln(sprintf('<error>Could not drop database named <comment>%s</comment></error>', $name));
                $output->writeln(sprintf('<error>%s</error>', $e->getMessage()));
            }

        } else {

            $output->writeln(sprintf('<info>This operation would drop the database named <comment>%s</comment>.</info>', $name));
            $output->writeln('Please run the operation with --force to execute');
            $output->writeln('<error>All data will be lost!</error>');
        }
    }

    /**
     * @var array $params
     * @throws InvalidArgumentException
     * @return string
     */
    protected function getDatabaseName(array $params)
    {
        $name = false;
        if (isset($params['path'])) {
            $name = $params['path'];
        } elseif (isset($params['dbname'])) {
            $name = $params['dbname'];
        }

        if (!$name) {
            throw new InvalidArgumentException("Connection does not contain a 'path' or 'dbname' parameter and cannot be dropped.");
        }

        return $name;
    }
}
