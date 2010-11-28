<?php

namespace Bundle\MongoAdminBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Templating\Engine;
use Bundle\MongoAdminBundle\MongoManager;
use Bundle\MongoAdminBundle\Render\Document;

class MongoAdminController {

    protected $request;
    protected $templating;
    protected $mongoManager;
    protected $documentRenderer;

    public function __construct(Request $request, Engine $templating, MongoManager $mongoManager, Document $documentRenderer) {
        $this->request = $request;
        $this->templating = $templating;
        $this->mongoManager = $mongoManager;
        $this->documentRenderer = $documentRenderer;
    }

    public function index() {
        $collections = $this->mongoManager->getCollectionsArray();

        $content = $this->templating->render('MongoAdminBundle:view:index.twig', array('collections' => $collections));
        return new Response($content, 200);
    }

    public function viewServer($server) {
        $databases = $this->mongoManager->getDatabases($server);

        $content = $this->templating->render(
            'MongoAdminBundle:view:server.twig',
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
            'MongoAdminBundle:view:db.twig',
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
            'MongoAdminBundle:view:collection.twig',
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

        $content = $this->templating->render(
            'MongoAdminBundle:view:document.twig',
            array(
                'server' => $server,
                'db' => $db,
                'collection' => $collection,
                'id' => $id,
                'document' => $document,
                'documentPreview' => $this->documentRenderer->preparePreview($document)
            )
        );

        return new Response($content, 200);
    }
}