<?php

namespace Bundle\MongoAdminBundle\Proxy;

class ProxyFactory {

    public function getDatabase(\Mongo $mongo, $name) {
        return new Database($mongo, $name);
    }
}