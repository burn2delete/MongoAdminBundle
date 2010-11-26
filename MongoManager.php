<?php

namespace Bundle\MongoAdminBundle;

use Symfony\Component\DependencyInjection\Container;

class MongoManager {
    
    protected $mongos = array();

    public function addMongo($name, \Mongo $mongo) {
        $this->mongos[$name] = $mongo;
    }

    public function getMongo($name) {
        if (isset($this->mongos[$name])) {
            return $this->mongos[$name];
        } 

        return null;
    }

    public function getServerData($server) {
        if (($mongo = $this->getMongo($server)) === null) {
            return null;
        }

        $databases = $mongo->listDBs();

        foreach ($databases['databases'] as $i => $database) {
            $databases['databases'][$i]['collections'] = count($mongo->selectDb($database['name'])->listCollections());
        }

        return $databases;
    }

    public function getServerDb($server, $db) {
        if (($mongo = $this->getMongo($server)) === null) {
            return null;
        }

        return $mongo->selectDb($db);
    }

    public function getCollection($server, $db, $collection) {
        if (($mongoDb = $this->getServerDb($server, $db)) === null) {
            return null;
        }

        return $mongoDb->selectCollection($collection);
    }

    public function getCollectionsArray() {
        $collections = array();

        foreach ($this->mongos as $name => $mongo) {
            $collections[$name] = array();
            $databases = $mongo->listDBs();

            foreach ($databases['databases'] as $database) {
                $db = $mongo->selectDB($database['name']);

                $collections[$name][$database['name']] = array();

                foreach ($db->listCollections() as $collection) {
                    $collections[$name][$database['name']][] = $collection->getName();
                }
            }
        }

        return $collections;
    }
}