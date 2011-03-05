<?php

namespace Bundle\Steves\MongoAdminBundle\Render;

use Symfony\Component\Routing\RouterInterface;

class Document {
    protected $router;
    protected $server;

    public function __construct(RouterInterface $router, $server) {
        $this->router = $router;
        $this->server = $server;
    }

    public function preparePreview(array $document) {
        $prepared = $this->prepareFields($document);

        return print_r($prepared, true);
    }

    protected function prepareFields(array $document) {
        foreach ($document as $i => $value) {
            if (is_array($value)) {
                $value = $this->prepareFields($value);
                $value = $this->prepareReference($value);

                $document[$i] = $value;
            } elseif ($value instanceof \MongoId) {
                $document[$i] = (string)$value;
            }
        }

        return $document;
    }

    protected function prepareReference(array $value) {
        if (isset($value['$ref']) && isset($value['$id']) && isset($value['$db'])) {
            $url = $this->router->generate('mongo_document', array(
                'server' => $this->server,
                'db' => $value['$db'],
                'collection' => $value['$ref'],
                'id' => $value['$id'],
            ));

            $value['$id'] = '<a href="' . $url . '">' . $value['$id'] . '</a>';
        }

        return $value;
    }
}