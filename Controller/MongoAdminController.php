<?php

namespace Bundle\MongoAdminBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Templating\Engine;
use Bundle\MongoAdminBundle\MongoManager;

class MongoAdminController {

	protected $request;
	protected $templating;
	protected $mongoManager;

	public function __construct(Request $request, Engine $templating, MongoManager $mongoManager) {
		$this->request = $request;
		$this->templating = $templating;
		$this->mongoManager = $mongoManager;
	}

	public function index() {
		$collections = $this->mongoManager->getCollectionsArray();

		$content = $this->templating->render('MongoAdminBundle:Index:index.twig', array('collections' => $collections));
		return new Response($content, 200);
	}
}