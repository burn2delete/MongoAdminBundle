<?php

namespace Bundle\MongoAdminBundle\Controller;

class MongoAdminControllerTest extends \PHPUnit_Framework_TestCase {

	protected $controller;

	protected function setUp() {
		$request = $this->getMockBuilder('Symfony\Component\HttpFoundation\Request')
			->disableOriginalConstructor()
			->getMock();

		$engine = $this->getMockBuilder('Symfony\Component\Templating\Engine')
			->disableOriginalConstructor()
			->getMock();

		$this->controller = new MongoAdminController($request, $engine);
	}
}