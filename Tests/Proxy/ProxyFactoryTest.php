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

        $mongo = $this->getMockBuilder('Mongo')
            ->disableOriginalConstructor()
            ->getMock();

        $mongo->expects($this->once())
            ->method('selectDB')
            ->with($data['name'])
            ->will($this->returnValue($mongoDb));

        $database = $this->factory->getDatabase($mongo, $data);

        $this->assertEquals($data['name'], $database->getName());
        $this->assertEquals($data['sizeOnDisk'], $database->getSizeOnDisk());
    }
}