<?php

namespace Bundle\MongoAdminBundle\Helper;

use Symfony\Component\Templating\Helper\Helper;
use Bundle\MongoAdminBundle\MongoManager;

class MongoManagerHelper extends Helper {

    protected $mongoManager;

    public function __construct(MongoManager $mongoManager) {
        $this->mongoManager = $mongoManager;
    }

    public function getCollections() {
        return $this->mongoManager->getCollectionsArray();
    }

    public function getName() {
        return 'mongo_manager';
    }
}