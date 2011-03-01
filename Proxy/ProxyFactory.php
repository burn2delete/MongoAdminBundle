<?php

namespace Bundle\Steves\MongoAdminBundle\Proxy;

class ProxyFactory {

    public function getMongo($connection, $options) {
        return new \Mongo($connection, $options);
    }

    public function getDatabase(Mongo $mongo, \MongoDb $mongodb, array $database) {
        return new Database($mongo, $mongodb, $database);
    }
}