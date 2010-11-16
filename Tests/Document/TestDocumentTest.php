<?php

namespace Bundle\MongoAdminBundle\Document;

class TestDocumentTest extends \PHPUnit_Framework_TestCase {

	protected $document;

	protected function setUp() {
		$this->document = new TestDocument();
	}

	public function testGetId() {
		$this->assertNull($this->document->getId());
	}

	public function testSetId() {
		$id = 'abc123';
		$this->document->setId($id);
		$this->assertEquals($id, $this->document->getId());
	}

	public function testGetTestField() {
		$this->assertNull($this->document->getTestField());
	}

	public function testSetTestField() {
		$field = 'some_value';
		$this->document->setTestField($field);
		$this->assertEquals($field, $this->document->getTestField());
	}
}