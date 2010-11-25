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