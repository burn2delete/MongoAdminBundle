<?php

namespace Bundle\MongoAdminBundle\Controller;

class MongoAdminControllerTest extends \PHPUnit_Framework_TestCase {

    protected $controller;
    protected $request;
    protected $engine;
    protected $mongoManager;

    public function setUp() {
        $this->request = $this->getMockBuilder('Symfony\Component\HttpFoundation\Request')
            ->disableOriginalConstructor()
            ->getMock();

        $this->engine = $this->getMockBuilder('Symfony\Component\Templating\Engine')
            ->disableOriginalConstructor()
            ->getMock();

        $this->mongoManager = $this->getMock('Bundle\MongoAdminBundle\MongoManager');

        $this->controller = new MongoAdminController($this->request, $this->engine, $this->mongoManager);
    }

    public function testIndex() {
        $collections = array('test' => 'test');
        $template = 'test string';

        $this->mongoManager->expects($this->once())
            ->method('getCollectionsArray')
            ->will($this->returnValue($collections));

        $this->engine->expects($this->once())
            ->method('render')
            ->with('MongoAdminBundle:view:index.twig', array('collections' => $collections))
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
            ->method('getServerData')
            ->with($server)
            ->will($this->returnValue($serverData));

        $this->engine->expects($this->once())
            ->method('render')
            ->with('MongoAdminBundle:view:server.twig', array('server' => $serverData))
            ->will($this->returnValue($template));

        $response = $this->controller->viewServer($server);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals($template, $response->getContent());
    }
}