<?php
/*
* This file is part of the SwoopsterObjectManagerBundle package.
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/
namespace Swoopster\ObjectManagerBundle\Tests\DependencyInjection;


use Matthias\SymfonyConfigTest\PhpUnit\AbstractConfigurationTestCase;
use Swoopster\ObjectManagerBundle\DependencyInjection\Configuration;
use Swoopster\ObjectManagerBundle\DependencyInjection\SwoopsterObjectManagerExtension;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class ConfigurationTest extends AbstractConfigurationTestCase
{
	public function testDefaultConfiguration()
	{
		$this->assertProcessedConfigurationEquals(
			array(),
			array(
				'bundle_dir' => 'src',
				'model_dir' => 'Model',
				'bundles' => array(),
				'mapping_format' => 'xml'
			)
		);
	}

	public function testModiefiedConfiguration()
	{
		$this->assertProcessedConfigurationEquals(
			array(
				array('bundle_dir' => 'dir'),
				array('model_dir' => 'Entity'),
				array('bundles' => array(
					array('namespace' => 'Test\TestBundle')
				))
			),
			array(
				'bundle_dir' => 'dir',
				'model_dir' => 'Entity',
				'mapping_format' => 'xml',
				'bundles' => array(
					array('namespace' => 'Test\TestBundle')
				))
			);
	}


	/**
	 * @return ContainerBuilder
	 */
	protected  function getContainer()
	{
		$container = new ContainerBuilder();
		$container->setParameter('kernel.environment', 'test');
		return $container;
	}

	protected function getConfiguration()
	{
		return new Configuration();
	}
} 