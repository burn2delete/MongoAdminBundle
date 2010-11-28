<?php

namespace Bundle\MongoAdminBundle;

class MongoManagerTest extends \PHPUnit_Framework_TestCase {

    protected $mongoManager;
    protected $proxyFactory;

    public function setUp() {
        $this->proxyFactory = $this->getMock('Bundle\MongoAdminBundle\Proxy\ProxyFactory');

        $this->mongoManager = new MongoManager($this->proxyFactory);
    }

    public function testSetGetMongo() {
        $mongo = $this->getMockBuilder('Mongo')
            ->disableOriginalConstructor()
            ->getMock();

        $this->mongoManager->addMongo('test_mongo', $mongo);
        $this->assertSame($mongo, $this->mongoManager->getMongo('test_mongo'));
    }

    public function testGetMongoReturnsNullOnNotFound() {
        $this->assertNull($this->mongoManager->getMongo('not_set'));
    }

    public function testGetDatabases() {
        $mongo = $this->getMockBuilder('Mongo')
            ->disableOriginalConstructor()
            ->getMock();

        $db = $this->getMockBuilder('Bundle\MongoAdminBundle\Proxy\Database')
            ->disableOriginalConstructor()
            ->getMock();

        $expectedNoCollections = array('databases' => array(array('name' => 'test')));
        $expected = array('test' => $db);

        $mongo->expects($this->once())
            ->method('listDBs')
            ->will($this->returnValue($expectedNoCollections));

        $this->proxyFactory->expects($this->once())
            ->method('getDatabase')
            ->with($mongo, $expectedNoCollections['databases'][0])
            ->will($this->returnValue($db));

        $this->mongoManager->addMongo('test', $mongo);
        $actual = $this->mongoManager->getDatabases('test');

        $this->assertEquals($expected, $actual);
    }

    public function testGetDatabasesReturnsNullOnNoServer() {
        $this->assertNull($this->mongoManager->getDatabases('test'));
    }

    public function testGetServerDb() {
        $server = 'server_one';
        $dbName = 'db_one';

        $mongo = $this->getMockBuilder('Mongo')
            ->disableOriginalConstructor()
            ->getMock();

        $db = $this->getMockBuilder('MongoDB')
            ->disableOriginalConstructor()
            ->getMock();

        $mongo->expects($this->once())
            ->method('selectDb')
            ->with($dbName)
            ->will($this->returnValue($db));

        $this->mongoManager->addMongo($server, $mongo);
        $this->assertSame($db, $this->mongoManager->getServerDb($server, $dbName));
    }

    public function testGetServerDbReturnsNullOnNoServer() {
        $this->assertNull($this->mongoManager->getServerDb('test', 'test'));
    }

    public function testGetCollection() {
        $server = 'server_one';
        $dbName = 'db_one';
        $collectionName = 'testCollection';

        $mongo = $this->getMockBuilder('Mongo')
            ->disableOriginalConstructor()
            ->getMock();

        $db = $this->getMockBuilder('MongoDB')
            ->disableOriginalConstructor()
            ->getMock();

        $collection = $this->getMockBuilder('MongoCollection')
            ->disableOriginalConstructor()
            ->getMock();

        $mongo->expects($this->once())
            ->method('selectDb')
            ->with($dbName)
            ->will($this->returnValue($db));

        $db->expects($this->once())
            ->method('selectCollection')
            ->with($collectionName)
            ->will($this->returnValue($collection));

        $this->mongoManager->addMongo($server, $mongo);
        $this->assertSame($collection, $this->mongoManager->getCollection($server, $dbName, $collectionName));
    }

    public function testGetCollectionReturnsNullOnNoServer() {
        $this->assertNull($this->mongoManager->getCollection('test', 'test', 'test'));
    }

    public function testGetDocumentById() {
        $server = 'server_one';
        $dbName = 'db_one';
        $collectionName = 'testCollection';
        $id = '4cf033c2a91a834a7d000000';
        $document = array('_id' => $id);

        $mongo = $this->getMockBuilder('Mongo')
            ->disableOriginalConstructor()
            ->getMock();

        $db = $this->getMockBuilder('MongoDB')
            ->disableOriginalConstructor()
            ->getMock();

        $collection = $this->getMockBuilder('MongoCollection')
            ->disableOriginalConstructor()
            ->getMock();

        $mongo->expects($this->once())
            ->method('selectDb')
            ->with($dbName)
            ->will($this->returnValue($db));

        $db->expects($this->once())
            ->method('selectCollection')
            ->with($collectionName)
            ->will($this->returnValue($collection));

        $collection->expects($this->once())
            ->method('findOne')
            ->with(array('_id' => new \MongoId($id)))
            ->will($this->returnValue($document));

        $this->mongoManager->addMongo($server, $mongo);
        $this->assertSame($document, $this->mongoManager->getDocumentById($server, $dbName, $collectionName, $id));
    }

    public function testGetDocumentByIdReturnsNullOnNoServer() {
        $this->assertNull($this->mongoManager->getDocumentById('test', 'test', 'test', 'test'));
    }

    public function testGetCollectionsArray() {
        $expected = array('test_mongo' => array(
            'test_db' => array('collection_one', 'collection_two')
        ));

        $mongo = $this->getMockBuilder('Mongo')
            ->disableOriginalConstructor()
            ->getMock();

        $database = $this->getMockBuilder('Bundle\MongoAdminBundle\Proxy\Database')
            ->disableOriginalConstructor()
            ->getMock();

        $collectionOne = $this->getMockBuilder('MongoCollection')
            ->disableOriginalConstructor()
            ->getMock();

        $collectionTwo = $this->getMockBuilder('MongoCollection')
            ->disableOriginalConstructor()
            ->getMock();

        $mongo->expects($this->once())
            ->method('listDBs')
            ->will($this->returnValue(array('databases' => array(array('name' => 'test_db')))));

        $this->proxyFactory->expects($this->once())
            ->method('getDatabase')
            ->with($mongo, array('name' => 'test_db'))
            ->will($this->returnValue($database));

        $database->expects($this->once())
            ->method('listCollections')
            ->will($this->returnValue(array($collectionOne, $collectionTwo)));

        $collectionOne->expects($this->once())
            ->method('getName')
            ->will($this->returnValue('collection_one'));

        $collectionTwo->expects($this->once())
            ->method('getName')
            ->will($this->returnValue('collection_two'));

        $this->mongoManager->addMongo('test_mongo', $mongo);
        $result = $this->mongoManager->getCollectionsArray();

        $this->assertEquals($expected, $result);
    }
}