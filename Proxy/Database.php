<?php

namespace Bundle\MongoAdminBundle\Proxy;

class Database {

    protected $db;
    protected $count;
    protected $sizeOnDisk;
    protected $name;
    protected $mongo;

    public function __construct(\Mongo $mongo, $name, $sizeOnDisk = 0) {
        $this->mongo = $mongo;
        $this->name = $name;

        $this->db = $mongo->selectDB($name);
        $this->sizeOnDisk = $sizeOnDisk;
    }

    public function getName() {
        return $this->name;
    }

    public function getSizeOnDisk() {
        return $this->sizeOnDisk;
    }

    public function count() {
        if ($this->count === null) {
            $this->count = count($this->listCollections());
        }

        return $this->count;
    }

    public function listCollections() {
        return $this->db->listCollections();
    }
}