<?php

namespace Bundle\MongoAdminBundle\Render;

class Document {
    public function preparePreview(array $document) {
        $prepared = $this->prepareFields($document);

        return print_r($prepared, true);
    }

    private function prepareFields(array $document) {
        foreach ($document as $i => $value) {
            if ($value instanceof \MongoId) {
                $document[$i] = (string)$value;
            }
        }

        return $document;
    }
}