<?php

namespace Bundle\Steves\MongoAdminBundle\Controller;

class MongoAdminControllerTest extends \PHPUnit_Framework_TestCase {

    protected $controller;
    protected $request;
    protected $engine;
    protected $rendererFactory;
    protected $mongoManager;

    public function setUp() {
        $this->request = $this->getMockBuilder('Symfony\Component\HttpFoundation\Request')
            ->disableOriginalConstructor()
            ->getMock();

        $this->engine = $this->getMockBuilder('Symfony\Bundle\TwigBundle\TwigEngine')
            ->disableOriginalConstructor()
            ->getMock();

        $this->mongoManager = $this->getMockBuilder('Bundle\Steves\MongoAdminBundle\MongoManager')
            ->disableOriginalConstructor()
            ->getMock();

        $this->rendererFactory = $this->getMockBuilder('Bundle\Steves\MongoAdminBundle\Render\DocumentFactory')
            ->disableOriginalConstructor()
            ->getMock();

        $this->controller = new MongoAdminController($this->request, $this->engine, $this->mongoManager, $this->rendererFactory);
    }

    public function testIndex() {
        $collections = array('test' => 'test');
        $template = 'test string';

        $this->mongoManager->expects($this->once())
            ->method('getCollectionsArray')
            ->will($this->returnValue($collections));

        $this->engine->expects($this->once())
            ->method('render')
            ->with('MongoAdminBundle:view:index.html.twig', array('collections' => $collections))
            ->will($this->returnValue($template));

        $response = $this->controller->index();

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals($template, $response->getContent());
    }

    public function testViewServer() {
        $serverData = array('databases' => array('name' => 'test', 'collections' => 0));
        $template = 'test string';
        $server = 'server_one';

        $this->mongoManager->expects($this->once())
            ->method('getDatabases')
            ->with($server)
            ->will($this->returnValue($serverData));

        $this->engine->expects($this->once())
            ->method('render')
            ->with('MongoAdminBundle:view:server.html.twig', array('server' => $server, 'databases' => $serverData))
            ->will($this->returnValue($template));

        $response = $this->controller->viewServer($server);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals($template, $response->getContent());
    }

    public function testViewDb() {
        $collections = array('test' => 'test');
        $template = 'test string';
        $server = 'server_one';
        $db = 'test_db';

        $mongoDb = $this->getMockBuilder('MongoDB')
            ->disableOriginalConstructor()
            ->getMock();

        $this->mongoManager->expects($this->once())
            ->method('getDatabase')
            ->with($server, $db)
            ->will($this->returnValue($mongoDb));

        $mongoDb->expects($this->once())
            ->method('listCollections')
            ->will($this->returnValue($collections));

        $this->engine->expects($this->once())
            ->method('render')
            ->with('MongoAdminBundle:view:db.html.twig', array('server' => $server, 'db' => $db, 'collections' => $collections))
            ->will($this->returnValue($template));

        $response = $this->controller->viewDb($server, $db);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals($template, $response->getContent());
    }

    public function testViewCollection() {
        $template = 'test string';
        $server = 'server_one';
        $db = 'test_db';
        $collection = 'test_collection';

        $mongoCollection = $this->getMockBuilder('MongoCollection')
            ->disableOriginalConstructor()
            ->getMock();

        $mongoCursor = $this->getMockBuilder('MongoCursor')
            ->disableOriginalConstructor()
            ->getMock();

        $this->mongoManager->expects($this->once())
            ->method('getCollection')
            ->with($server, $db, $collection)
            ->will($this->returnValue($mongoCollection));

        $mongoCollection->expects($this->once())
            ->method('find')
            ->will($this->returnValue($mongoCursor));

        $this->engine->expects($this->once())
            ->method('render')
            ->with('MongoAdminBundle:view:collection.html.twig', array(
                'server' => $server,
                'db' => $db,
                'collection' => $collection,
                'cursor' => $mongoCursor
            ))
            ->will($this->returnValue($template));

        $response = $this->controller->viewCollection($server, $db, $collection);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals($template, $response->getContent());
    }

    public function testViewDocument() {
        $template = 'test string';
        $server = 'server_one';
        $db = 'test_db';
        $collection = 'test_collection';
        $id = 'abc123';
        $document = array('_id' => $id);
        $documentPreview = print_r($document, true);

        $this->mongoManager->expects($this->once())
            ->method('getDocumentById')
            ->with($server, $db, $collection, $id)
            ->will($this->returnValue($document));

        $renderer = $this->getMockBuilder('Bundle\Steves\MongoAdminBundle\Render\Document')
            ->disableOriginalConstructor()
            ->getMock();

        $this->rendererFactory->expects($this->once())
            ->method('getRenderer')
            ->with($server)
            ->will($this->returnValue($renderer));

        $renderer->expects($this->once())
            ->method('preparePreview')
            ->with($document)
            ->will($this->returnValue($documentPreview));

        $this->engine->expects($this->once())
            ->method('render')
            ->with(
                'MongoAdminBundle:view:document.html.twig',
                array(
                    'server' => $server,
                    'db' => $db,
                    'collection' => $collection,
                    'id' => $id,
                    'document' => $document,
                    'documentPreview' => $documentPreview,
                )
            )
            ->will($this->returnValue($template));

        $response = $this->controller->viewDocument($server, $db, $collection, $id);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals($template, $response->getContent());
    }
}