<?php

namespace Bundle\MongoAdminBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Templating\Engine;

class MongoAdminController {

	protected $request;
	protected $templating;

	public function __construct(Request $request, Engine $templating) {
		$this->request = $request;
		$this->templating = $templating;
	}

	public function index() {
		$content = $this->templating->render('MongoAdminBundle:Index:index.twig');

		return new Response($content, 200);
	}
}