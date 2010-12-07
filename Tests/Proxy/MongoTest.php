<?php

namespace Bundle\MongoAdminBundle\Proxy;

class MongoTest extends \PHPUnit_Framework_TestCase {

    protected $mongo;
    protected $mockMongo;
    protected $proxyFactory;

    protected function setUp() {
        $connection = 'test connection';
        $options = array('connect' => true);

        $this->mockMongo = $this->getMockBuilder('Mongo')
            ->disableOriginalConstructor()
            ->getMock();

        $this->proxyFactory = $this->getMock('Bundle\MongoAdminBundle\Proxy\ProxyFactory');
        $this->proxyFactory->expects($this->once())
            ->method('getMongo')
            ->with($connection, $options)
            ->will($this->returnValue($this->mockMongo));

        $this->mongo = new Mongo($connection, $options, $this->proxyFactory);
    }

    public function testListDbs() {
        $expected = array('databases' => array(array('name' => 'test')));

        $this->mockMongo->expects($this->once())
            ->method('listDbs')
            ->will($this->returnValue($expected));

        $actual = $this->mongo->listDbs();
        $this->assertEquals($expected, $actual);
    }

    public function testDropDb() {
        $db = 'test_db';

        $this->mockMongo->expects($this->once())
            ->method('dropDb')
            ->with($db);

        $this->mongo->dropDb($db);
    }
}