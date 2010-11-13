<?php

namespace Bundle\MongoAdminBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;

class MongoAdminExtension extends Extension {

	protected $resources = array('mongo_admin' => 'mongo_admin.xml');

	public function configLoad($config, ContainerBuilder $container) {
		$loader = new XmlFileLoader($container, __DIR__ . '/../Resources/config');
		$loader->load($this->resources['mongo_admin']);

		return $container;
	}

	public function getNamespace() {
		return 'http://www.symfony-project.org/schema/dic/symfony';
	}

	public function getXsdValidationBasePath() {
		return __DIR__ . '/../Resources/config';
	}

	public function getAlias() {
		return 'mongo_admin';
	}
}