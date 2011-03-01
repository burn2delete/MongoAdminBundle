<?php

namespace Bundle\Steves\MongoAdminBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

class MongoAdminExtension extends Extension {
    /**
     * Loads a specific configuration.
     *
     * @param array   $config        An array of configuration values
     * @param ContainerBuilder $configuration A ContainerBuilder instance
     *
     * @throws \InvalidArgumentException When provided tag is not defined in this extension
     */
    public function load(array $config, ContainerBuilder $container) {
        $loader = new XmlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('mongo_admin.xml');

        if (isset($config[0]['servers']) && is_array($config[0]['servers'])) {
            foreach ($config[0]['servers'] as $name => $connection) {
                $this->loadMongoInstance($name, $connection, $container);
            }
        }

        return $container;
    }

    private function loadMongoInstance($name, $connection, ContainerBuilder $container) {
        if (substr($connection, 0, 8) !== 'mongodb:') {
            $connection = 'mongodb://' . $connection;
        }

        $mongo = new Definition('%mongo_admin.mongo.class%', array(
            substr($connection, 0, 8) !== 'mongodb:' ? 'mongodb:' . $connection : $connection,
            array('connect' => true),
            new Reference('mongo_admin.proxy_factory')
        ));

        $container->setDefinition(sprintf('mongo_admin.mongo_instance.%s', $name), $mongo);

        $mongoManager = $container->getDefinition('mongo_admin.mongo_manager');
        $mongoManager->addMethodCall('addMongo', array(
            $name,
            new Reference(sprintf('mongo_admin.mongo_instance.%s', $name))
        ));
    }

    public function getXsdValidationBasePath() {
        return __DIR__ . '/../Resources/config';
    }

    public function getNamespace() {
        return 'http://www.symfony-project.org/schema/dic/symfony';
    }

    public function getAlias() {
        return 'mongo_admin';
    }
}