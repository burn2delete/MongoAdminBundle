<?php

namespace Bundle\MongoAdminBundle\Render;

class Document {
    public function preparePreview($document) {
        return print_r($document, true);
    }
}