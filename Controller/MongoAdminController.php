<?php

namespace Bundle\MongoAdminBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class MongoAdminController {

	protected $request;

	public function __construct(Request $request) {
		$this->request = $request;
	}

	public function index() {
		$content = 'test';

		return new Response($content, 200);
	}
}