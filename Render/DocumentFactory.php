<?php

namespace Bundle\Steves\MongoAdminBundle\Render;

use Symfony\Component\Routing\RouterInterface;

class DocumentFactory {
    private $router;

    public function __construct(RouterInterface $router) {
        $this->router = $router;
    }

    public function getRenderer($server) {
        return new Document($this->router, $server);
    }
}
