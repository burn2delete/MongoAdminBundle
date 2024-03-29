<?php

namespace Bundle\Steves\MongoAdminBundle;

use Symfony\Component\DependencyInjection\Container;
use Bundle\Steves\MongoAdminBundle\Proxy\Mongo;
use Bundle\Steves\MongoAdminBundle\Proxy\ProxyFactory;

class MongoManager {
    
    protected $mongos = array();
    protected $proxyFactory;
    protected $skipDbs = array('admin', 'local');

    public function __construct(ProxyFactory $proxyFactory) {
        $this->proxyFactory = $proxyFactory;
    }

    public function addMongo($name, Mongo $mongo) {
        $this->mongos[$name] = $mongo;
    }

    public function getMongo($name) {
        if (isset($this->mongos[$name])) {
            return $this->mongos[$name];
        } 

        return null;
    }

    public function getDatabases($server) {
        if (($mongo = $this->getMongo($server)) === null) {
            return null;
        }

        $databases = array();
        $dbList = $mongo->listDBs();

        foreach ($dbList['databases'] as $i => $database) {
            if (in_array($database['name'], $this->skipDbs)) {
                continue;
            }

            $databases[$database['name']] = $mongo->selectDb($database['name']);
        }

        return $databases;
    }

    public function getDatabase($server, $db) {
        if (($mongo = $this->getMongo($server)) === null) {
            return null;
        }

        return $mongo->selectDb($db);
    }

    public function getCollection($server, $db, $collection) {
        if (($mongoDb = $this->getDatabase($server, $db)) === null) {
            return null;
        }

        return $mongoDb->selectCollection($collection);
    }

    public function getDocumentById($server, $db, $collection, $id) {
        if (($collection = $this->getCollection($server, $db, $collection)) === null) {
            return null;
        }

        return $collection->findOne(array('_id' => new \MongoId($id)));
    }

    public function getCollectionsArray() {
        $collections = array();

        foreach ($this->mongos as $name => $mongo) {
            $collections[$name] = array();
            $databases = $mongo->listDBs();

            foreach ($databases['databases'] as $database) {
                if (in_array($database['name'], $this->skipDbs)) {
                    continue;
                }

                $db = $mongo->selectDb($database['name']);

                $collections[$name][$database['name']] = array();

                foreach ($db->listCollections() as $collection) {
                    $collections[$name][$database['name']][] = $collection->getName();
                }
            }
        }

        return $collections;
    }
}