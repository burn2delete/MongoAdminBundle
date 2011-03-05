<?php

namespace Bundle\Steves\MongoAdminBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBag;

class YamlMongoAdminExtensionTest extends \PHPUnit_Framework_TestCase {
    public function testDependencyInjectionConfigurationDefaults() {
        $container = $this->getContainer();
        $loader = new MongoAdminExtension();

        $loader->load(array(), $container);

        $this->assertEquals('Bundle\Steves\MongoAdminBundle\Proxy\Mongo', $container->getParameter('mongo_admin.mongo.class'));
        $this->assertEquals('Bundle\Steves\MongoAdminBundle\MongoManager', $container->getParameter('mongo_admin.mongo_manager.class'));
        $this->assertEquals('Bundle\Steves\MongoAdminBundle\Proxy\ProxyFactory', $container->getParameter('mongo_admin.proxy_factory.class'));
        $this->assertEquals('Bundle\Steves\MongoAdminBundle\Controller\MongoAdminController', $container->getParameter('mongo_admin_controller_class'));
        $this->assertEquals('Bundle\Steves\MongoAdminBundle\Render\DocumentFactory', $container->getParameter('mongo_admin.renderer.document.factory.class'));

        $definition = $container->getDefinition('mongo_admin.mongo_manager');
        $this->assertEquals('%mongo_admin.mongo_manager.class%', $definition->getClass());

        $definition = $container->getDefinition('templating.helper.mongo_manager');
        $this->assertEquals('Bundle\Steves\MongoAdminBundle\Helper\MongoManagerHelper', $definition->getClass());
        $this->assertArrayHasKey('twig.helper', $definition->getTags());

        $definition = $container->getDefinition('mongo_admin.proxy_factory');
        $this->assertEquals('%mongo_admin.proxy_factory.class%', $definition->getClass());

        $definition = $container->getDefinition('mongo_admin.renderer.document.factory');
        $this->assertEquals('%mongo_admin.renderer.document.factory.class%', $definition->getClass());
    }

    public function testSingleServer() {
        $container = $this->getContainer();
        $loader = new MongoAdminExtension();
        $container->registerExtension($loader);

        $this->loadFromFile($container, 'single');

        $container->getCompilerPassConfig()->setOptimizationPasses(array());
        $container->getCompilerPassConfig()->setRemovingPasses(array());
        $container->compile();

        $definition = $container->getDefinition('mongo_admin.mongo_instance.server_one');
        $this->assertEquals('%mongo_admin.mongo.class%', $definition->getClass());
    }

    public function testMultipleServers() {
        $container = $this->getContainer();
        $loader = new MongoAdminExtension();
        $container->registerExtension($loader);

        $this->loadFromFile($container, 'multiple');

        $container->getCompilerPassConfig()->setOptimizationPasses(array());
        $container->getCompilerPassConfig()->setRemovingPasses(array());
        $container->compile();

        $definition = $container->getDefinition('mongo_admin.mongo_instance.server_one');
        $this->assertEquals('%mongo_admin.mongo.class%', $definition->getClass());

        $definition = $container->getDefinition('mongo_admin.mongo_instance.server_two');
        $this->assertEquals('%mongo_admin.mongo.class%', $definition->getClass());
    }

    protected function loadFromFile(ContainerBuilder $container, $file) {
        $loadYaml = new YamlFileLoader($container,  new FileLocator(__DIR__ . '/Fixtures/config/yml'));
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