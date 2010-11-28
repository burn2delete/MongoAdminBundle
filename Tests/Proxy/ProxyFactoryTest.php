<?php

namespace Bundle\MongoAdminBundle\Proxy;

class ProxyFactoryTest extends \PHPUnit_Framework_TestCase {

    protected $factory;

    public function setUp() {
        $this->factory = new ProxyFactory();
    }

    public function testGetDatabase() {
        $databaseName = 'test_db';

        $mongoDb = $this->getMockBuilder('MongoDB')
            ->disableOriginalConstructor()
            ->getMock();

        $mongo = $this->getMockBuilder('Mongo')
            ->disableOriginalConstructor()
            ->getMock();

        $mongo->expects($this->once())
            ->method('selectDB')
            ->with($databaseName)
            ->will($this->returnValue($mongoDb));

        $database = $this->factory->getDatabase($mongo, $databaseName);

        $this->assertEquals($databaseName, $database->getName());
    }
}