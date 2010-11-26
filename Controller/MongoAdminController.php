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
        $serverData = $this->mongoManager->getServerData($server);

        $content = $this->templating->render(
            'MongoAdminBundle:view:server.twig', 
            array(
                'server' => $server, 
                'serverData' => $serverData
            )
        );

        return new Response($content, 200);
    }

    public function viewDb($server, $db) {
        $mongoDb = $this->mongoManager->getServerDb($server, $db);
        $collections = $mongoDb->listCollections();

        $content = $this->templating->render(
            'MongoAdminBundle:view:db.twig', 
            array(
                'server' => $server,
                'db' => $db, 
                'collections' => $collections
            )
        );

        return new Response($content, 200);
    }
}