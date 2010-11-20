<?php

namespace Bundle\MongoAdminBundle;

use Symfony\Component\DependencyInjection\Container;

class MongoManager {

	const SERVICE_TAG = 'doctrine.odm.mongodb.document_manager';
	
	protected $container;
	protected $documentManagers = array();

	public function __construct(container $container) {
		$this->container = $container;

		foreach ($this->container->findTaggedServiceIds(static::SERVICE_TAG) as $serviceId => $tag) {
			$this->documentManagers[$serviceId] = $this->container->get($serviceId);
		}
	}

	public function getCollectionsArray() {
		$collections = array();

		foreach ($this->documentManagers as $serviceId => $documentManager) {
			$collections[$serviceId] = array();

			$mongo = $documentManager->getMongo();
			$databases = $mongo->listDBs();

			foreach ($databases['databases'] as $database) {
				$db = $mongo->selectDB($database['name']);

				$collections[$serviceId][$database['name']] = array();

				foreach ($db->listCollections() as $collection) {
					$collections[$serviceId][$database['name']][] = $collection->getName();
				}
			}
		}

		return $collections;
	}
}