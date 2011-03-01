<?php

namespace Bundle\Steves\MongoAdminBundle\Extension;

use Bundle\Steves\MongoAdminBundle\Helper\MongoManagerHelper;

class MongoManagerExtension extends \Twig_Extension {
    private $mongoHelper;

    public function __construct(MongoManagerHelper $mongoHelper) {
        $this->mongoHelper = $mongoHelper;
    }

    public function getGlobals() {
        return array(
            'mongo_manager' => $this->mongoHelper
        );
    }

    /**
     * Returns the name of the extension.
     *
     * @return string The extension name
     */
    public function getName() {
        return 'mongo_manager';
    }
}
