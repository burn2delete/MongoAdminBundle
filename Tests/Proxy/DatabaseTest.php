<?php

namespace Bundle\MongoAdminBundle\Proxy;

class DatabaseTest extends \PHPUnit_Framework_TestCase {

    protected $database;
    protected $databaseName = 'test_database';
    protected $sizeOnDisk = 123456789;
    protected $mockMongoDb;

    public function setUp() {
        $mongo = $this->getMockBuilder('Mongo')
            ->disableOriginalConstructor()
            ->getMock();

        $this->mockMongoDb = $this->getMockBuilder('MongoDB')
            ->disableOriginalConstructor()
            ->getMock();

        $mongo->expects($this->once())
            ->method('selectDB')
            ->with($this->databaseName)
            ->will($this->returnValue($this->mockMongoDb));

        $this->database = new Database(
            $mongo,
            array(
                'name' => $this->databaseName, 
                'sizeOnDisk' => $this->sizeOnDisk
            )
        );
    }

    public function testGetSizeOnDisk() {
        $this->assertEquals($this->sizeOnDisk, $this->database->getSizeOnDisk());
    }

    public function testGetName() {
        $this->assertEquals($this->databaseName, $this->database->getName());
    }

    public function testCount() {
        $this->mockMongoDb->expects($this->once())
            ->method('listCollections')
            ->will($this->returnValue(array()));

        $this->database->count();
    }
}