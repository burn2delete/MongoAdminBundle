<?php

namespace Bundle\Steves\MongoAdminBundle\Proxy;

class Database {

    protected $db;
    protected $count;
    protected $sizeOnDisk;
    protected $name;
    protected $mongo;

    public function __construct(Mongo $mongo, \MongoDb $db, array $database) {
        $this->mongo = $mongo;
        $this->name = $database['name'];

        $this->db = $db;
        $this->sizeOnDisk = $database['sizeOnDisk'];
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

    public function selectCollection($collection) {
        return $this->db->selectCollection($collection);
    }
}