<?php

namespace Bundle\MongoAdminBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBag;

class YamlMongoAdminExtensionTest extends \PHPUnit_Framework_TestCase {
    public function testDependencyInjectionConfigurationDefaults() {
        $container = $this->getContainer();
        $loader = new MongoAdminExtension();

        $loader->configLoad(array(), $container);

        $this->assertEquals('Mongo', $container->getParameter('mongo_admin.mongo.class'));
        $this->assertEquals('Bundle\MongoAdminBundle\MongoManager', $container->getParameter('mongo_admin.mongo_manager.class'));
        $this->assertEquals('Bundle\MongoAdminBundle\Helper\MongoManagerHelper', $container->getParameter('mongo_admin.helper.mongo_manager.class'));
        $this->assertEquals('Bundle\MongoAdminBundle\Controller\MongoAdminController', $container->getParameter('mongo_admin_controller_class'));

        $definition = $container->getDefinition('mongo_admin.mongo_manager');
        $this->assertEquals('%mongo_admin.mongo_manager.class%', $definition->getClass());

        $definition = $container->getDefinition('mongo_admin.helper.mongo_manager');
        $this->assertEquals('%mongo_admin.helper.mongo_manager.class%', $definition->getClass());
        $this->assertArrayHasKey('templating.helper', $definition->getTags());
    }

    public function testSingleServer() {
        $container = $this->getContainer();
        $loader = new MongoAdminExtension();
        $container->registerExtension($loader);

        $this->loadFromFile($container, 'single');

        $container->freeze();

        $definition = $container->getDefinition('mongo_admin.mongo_instance.server_one');
        $this->assertEquals('%mongo_admin.mongo.class%', $definition->getClass());
        $this->assertEquals(array('mongodb://localhost:27017', array('connect' => true)), $definition->getArguments());
    }

    public function testMultipleServers() {
        $container = $this->getContainer();
        $loader = new MongoAdminExtension();
        $container->registerExtension($loader);

        $this->loadFromFile($container, 'multiple');

        $container->freeze();

        $definition = $container->getDefinition('mongo_admin.mongo_instance.server_one');
        $this->assertEquals('%mongo_admin.mongo.class%', $definition->getClass());
        $this->assertEquals(array('mongodb://localhost:27017', array('connect' => true)), $definition->getArguments());

        $definition = $container->getDefinition('mongo_admin.mongo_instance.server_two');
        $this->assertEquals('%mongo_admin.mongo.class%', $definition->getClass());
        $this->assertEquals(array('mongodb://localhost:27017', array('connect' => true)), $definition->getArguments());
    }

    protected function loadFromFile(ContainerBuilder $container, $file) {
        $loadYaml = new YamlFileLoader($container, __DIR__ . '/Fixtures/config/yml');
        $loadYaml->load($file . '.yml');
    }

    protected function getContainer($bundle = 'YamlBundle') {
        require_once __DIR__ . '/Fixtures/Bundles/' . $bundle . '/' . $bundle . '.php';

        return new ContainerBuilder(new ParameterBag(array(
            'kernel.bundle_dirs' => array('MongoAdminBundle\\Tests\\DependencyInjection\\Fixtures\\Bundles' => __DIR__ . '/Fixtures/Bundles'),
            'kernel.bundles'     => array('MongoAdminBundle\\Tests\\DependencyInjection\\Fixtures\\Bundles\\' . $bundle . '\\' . $bundle),
            'kernel.cache_dir'   => sys_get_temp_dir(),
        )));
    }
}