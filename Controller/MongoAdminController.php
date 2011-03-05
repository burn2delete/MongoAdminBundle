<?php

namespace Bundle\Steves\MongoAdminBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Bundle\Steves\MongoAdminBundle\MongoManager;
use Bundle\Steves\MongoAdminBundle\Render\DocumentFactory;

class MongoAdminController {

    protected $request;
    protected $templating;
    protected $mongoManager;
    protected $rendererFactory;

    public function __construct(Request $request, EngineInterface $templating, MongoManager $mongoManager, DocumentFactory $rendererFactory) {
        $this->request = $request;
        $this->templating = $templating;
        $this->mongoManager = $mongoManager;
        $this->rendererFactory = $rendererFactory;
    }

    public function index() {
        $collections = $this->mongoManager->getCollectionsArray();

        $content = $this->templating->render('MongoAdminBundle:view:index.html.twig', array('collections' => $collections));
        return new Response($content, 200);
    }

    public function viewServer($server) {
        $databases = $this->mongoManager->getDatabases($server);

        $content = $this->templating->render(
            'MongoAdminBundle:view:server.html.twig',
            array(
                'server' => $server,
                'databases' => $databases
            )
        );

        return new Response($content, 200);
    }

    public function viewDb($server, $db) {
        $mongoDb = $this->mongoManager->getDatabase($server, $db);
        $collections = $mongoDb->listCollections();

        $content = $this->templating->render(
            'MongoAdminBundle:view:db.html.twig',
            array(
                'server' => $server,
                'db' => $db,
                'collections' => $collections
            )
        );

        return new Response($content, 200);
    }

    public function viewCollection($server, $db, $collection) {
        $mongoCollection = $this->mongoManager->getCollection($server, $db, $collection);

        $content = $this->templating->render(
            'MongoAdminBundle:view:collection.html.twig',
            array(
                'server' => $server,
                'db' => $db,
                'collection' => $collection,
                'cursor' => $mongoCollection->find()
            )
        );

        return new Response($content, 200);
    }

    public function viewDocument($server, $db, $collection, $id) {
        $document = $this->mongoManager->getDocumentById($server, $db, $collection, $id);
        $renderer = $this->rendererFactory->getRenderer($server);

        $content = $this->templating->render(
            'MongoAdminBundle:view:document.html.twig',
            array(
                'server' => $server,
                'db' => $db,
                'collection' => $collection,
                'id' => $id,
                'document' => $document,
                'documentPreview' => $renderer->preparePreview($document)
            )
        );

        return new Response($content, 200);
    }
}