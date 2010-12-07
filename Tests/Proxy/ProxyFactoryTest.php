<?php

namespace Bundle\MongoAdminBundle\Proxy;

class ProxyFactoryTest extends \PHPUnit_Framework_TestCase {

    protected $factory;

    public function setUp() {
        $this->factory = new ProxyFactory();
    }

    public function testGetDatabase() {
        $data = array(
            'name' => 'test_db',
            'sizeOnDisk' => 123456789
        );

        $mongoDb = $this->getMockBuilder('MongoDB')
            ->disableOriginalConstructor()
            ->getMock();

        $mongo = $this->getMockBuilder('Bundle\MongoAdminBundle\Proxy\Mongo')
            ->disableOriginalConstructor()
            ->getMock();

        $database = $this->factory->getDatabase($mongo, $mongoDb, $data);

        $this->assertEquals($data['name'], $database->getName());
        $this->assertEquals($data['sizeOnDisk'], $database->getSizeOnDisk());
    }
}