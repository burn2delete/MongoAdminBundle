<?php

namespace Bundle\MongoAdminBundle\Document;

class TestDocument {

	protected $id;
	protected $testField;

	public function setId($id) {
		$this->id = $id;
	}

	public function getId() {
		return $this->id;
	}

	public function setTestField($testField) {
		$this->testField = $testField;
	}

	public function getTestField() {
		return $this->testField;
	}
}