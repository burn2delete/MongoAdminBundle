<?php

namespace Bundle\Steves\MongoAdminBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class ImportDataCommand extends Command {
    public function configure() {
        $this->setName('mongo-admin:import-data')
            ->setDescription('Imports data from json files')
            ->addArgument('server', InputArgument::REQUIRED, 'Server to import data to')
            ->addArgument('database', InputArgument::REQUIRED, 'Database to import data to')
            ->addArgument('file', InputArgument::REQUIRED, 'Path to json file')
            ->addOption('drop', 'd', InputOption::VALUE_NONE, 'Drop the database before importing');
    }

    public function execute(InputInterface $input, OutputInterface $output) {
        $server = $input->getArgument('server');
        $database = $input->getArgument('database');
        $file = $input->getArgument('file');
        $drop = $input->getOption('drop');

        if (!file_exists($file)) {
            throw new \InvalidArgumentException(sprintf('Unable to locate file: %s', $file));
        }

        if (($mongo = $this->getMongoInstance($server)) === null) {
            throw new \InvalidArgumentException(sprintf('Invalid server provided: %s', $server));
        }

        $encoded = file_get_contents($file);

        if (($data = json_decode($encoded, true)) === null) {
            throw new \InvalidArgumentException('Error decoding data');
        }

        if ($drop) {
            $mongo->dropDb($database);
        }

        $db = $this->getMongoDatabase($server, $database);
        foreach ($data as $collectionName => $documents) {
            $collection = $db->selectCollection($collectionName);

            foreach ($documents as $document) {
                $collection->insert($document);
            }
        }
    }

    protected function getMongoInstance($server) {
        $manager = $this->container->get('mongo_admin.mongo_manager');
        return $manager->getMongo($server);
    }

    protected function getMongoDatabase($server, $db) {
        $manager = $this->container->get('mongo_admin.mongo_manager');
        return $manager->getDatabase($server, $db);
    }
}
