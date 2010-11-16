<?php

namespace Bundle\MongoAdminBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Templating\Engine;
use Doctrine\ODM\MongoDB\DocumentManager;

class MongoAdminController {

	protected $request;
	protected $templating;
	protected $documentManager;

	public function __construct(Request $request, Engine $templating, DocumentManager $documentManager) {
		$this->request = $request;
		$this->templating = $templating;
		$this->documentManager = $documentManager;
	}

	public function index() {
		$metadataFactory = $this->documentManager->getMetadataFactory();
		$metadata = $metadataFactory->getAllMetadata();

		$collections = array();
		foreach ($metadata as $collection) {
			if (!in_array($collection->getCollection(), $collections)) {
				$collections[] = $collection->getCollection();
			}
		}

		$content = $this->templating->render('MongoAdminBundle:Index:index.twig', array('collections' => $collections));
		return new Response($content, 200);
	}
}