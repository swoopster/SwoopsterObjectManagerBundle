<?php

namespace Swoopster\ObjectManagerBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class SwoopsterObjectManagerExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('manager.xml');
		if($container->getParameter('kernel.environment') === 'test'){
			$loader2 = new Loader\XmlFileLoader($container, new FileLocator(__DIR__.'/../Tests/Fixtures/App/app/config'));
			$loader2->load('manager.xml');
		};

		$container->setParameter('swoopster_object_manager.model_dir', $config['model_dir']);
		$container->setParameter('swoopster_object_manager.bundle_dir', $config['bundle_dir']);
		$container->setParameter('swoopster_object_manager.mapping_format', $config['mapping_format']);
		$container->setParameter('swoopster_object_manager.bundles', $config['bundles']);
    }
}
