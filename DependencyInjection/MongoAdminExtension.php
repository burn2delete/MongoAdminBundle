<?php

namespace Bundle\MongoAdminBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\DependencyInjection\Definition;

class MongoAdminExtension extends Extension {

    protected $resources = array('mongo_admin' => 'mongo_admin.xml');

    public function configLoad($config, ContainerBuilder $container) {
        $loader = new XmlFileLoader($container, __DIR__ . '/../Resources/config');
        $loader->load($this->resources['mongo_admin']);

        if (isset($config['servers']) && is_array($config['servers'])) {
            foreach ($config['servers'] as $name => $connection) {
                $this->loadMongoInstance($name, $connection, $container);
            }
        }

        return $container;
    }

    protected function loadMongoInstance($name, $connection, ContainerBuilder $container) {
        if (substr($connection, 0, 8) !== 'mongodb:') {
            $connection = 'mongodb:' . $connection;
        }

        $mongo = new Definition('%mongo_admin.mongo.class%', array(
            substr($connection, 0, 8) !== 'mongodb:' ? 'mongodb:' . $connection : $connection,
            array('connect' => true)
        ));

        $container->setDefinition(sprintf('mongo_admin.mongo_instance.%s', $name), $mongo);

        $mongoManager = $container->getDefinition('mongo_admin.mongo_manager');
        $mongoManager->addMethodCall('addMongo', array(
            $name,
            new Reference(sprintf('mongo_admin.mongo_instance.%s', $name))
        ));
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