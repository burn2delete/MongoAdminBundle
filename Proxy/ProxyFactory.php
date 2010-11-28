<?php

namespace Bundle\MongoAdminBundle\Proxy;

class ProxyFactory {

    public function getDatabase(\Mongo $mongo, array $database) {
        return new Database($mongo, $database);
    }
}