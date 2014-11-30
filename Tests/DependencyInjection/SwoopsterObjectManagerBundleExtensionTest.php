<?php
/*
* This file is part of the SwoopsterObjectManagerBundle package.
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/
namespace Swoopster\ObjectManagerBundle\Tests\DependencyInjection;


use Matthias\SymfonyDependencyInjectionTest\PhpUnit\AbstractExtensionTestCase;
use Swoopster\ObjectManagerBundle\DependencyInjection\SwoopsterObjectManagerExtension;
use Symfony\Component\DependencyInjection\Extension\ExtensionInterface;

class SwoopsterObjectManagerBundleExtensionTest extends AbstractExtensionTestCase
{
	public function setUp()
	{
		parent::setUp();
		$this->container->setParameter('kernel.environment', 'test');
	}

	public function testLoad()
	{
		$this->load();
		//Testing manager definitions
		$this->assertContainerBuilderHasServiceDefinitionWithMethodCall('swoopster.object_manager.abstract_manager', 'setContainer', array('service_container'));

		$this->assertContainerBuilderHasServiceDefinitionWithParent('swoopster.object_manager.doctrine_manager', 'swoopster.object_manager.abstract_manager');
		$this->assertContainerBuilderHasServiceDefinitionWithMethodCall('swoopster.object_manager.doctrine_manager', 'setRegistry', array('doctrine'));

		//Testing Parameterdefinitions
		$this->assertContainerBuilderHasParameter('swoopster_object_manager.model_dir');
		$this->assertContainerBuilderHasParameter('swoopster_object_manager.bundle_dir');
		$this->assertContainerBuilderHasParameter('swoopster_object_manager.bundles');
		$this->assertContainerBuilderHasParameter('swoopster_object_manager.mapping_format');
	}

	/**
	 * Return an array of container extensions you need to be registered for each test (usually just the container
	 * extension you are testing.
	 *
	 * @return ExtensionInterface[]
	 */
	protected function getContainerExtensions()
	{
		return array(
			new SwoopsterObjectManagerExtension()
		);
	}
}