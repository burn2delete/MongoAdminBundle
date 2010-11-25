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

        $content = $this->templating->render('MongoAdminBundle:view:index.twig', array('collections' => $collections));
        return new Response($content, 200);
    }

    public function viewServer($server) {
        $mongo = $this->mongoManager->getMongo($server);
        $databases = $mongo->listDBs();

        $content = $this->templating->render('MongoAdminBundle:view:server.twig', array('databases' => $databases['databases']));
        return new Response($content, 200);
    }
}