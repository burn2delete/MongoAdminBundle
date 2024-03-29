<?php

namespace Bundle\Steves\MongoAdminBundle\Proxy;

class Mongo {

    protected $proxyFactory;
    protected $mongo;
    protected $databases = array();

    public function __construct($connection, $options, ProxyFactory $proxyFactory) {
        $this->mongo = $proxyFactory->getMongo($connection, $options);
        $this->proxyFactory = $proxyFactory;
    }

    public function selectDb($db) {
        if (isset($this->databases[$db])) {
            return $this->databases[$db];
        }

        $dbList = $this->listDbs();
        foreach ($dbList['databases'] as $i => $database) {
            if ($database['name'] == $db) {
                return $this->proxyFactory->getDatabase($this, $this->mongo->selectDb($db), $database);
            }
        }

        return $this->proxyFactory->getDatabase($this, $this->mongo->selectDb($db), array('name' => $db, 'sizeOnDisk' => 0));;
    }

    public function listDbs() {
        return $this->mongo->listDbs();
    }

    public function dropDb($db) {
        $this->mongo->dropDb($db);
    }
}