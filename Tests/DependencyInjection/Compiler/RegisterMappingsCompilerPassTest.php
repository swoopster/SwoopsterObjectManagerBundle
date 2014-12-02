<?php
/*
* This file is part of the SwoopsterObjectManagerBundle package.
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/
namespace Swoopster\ObjectManagerBundle\Tests\DependencyInjection\Compiler;

use Matthias\SymfonyDependencyInjectionTest\PhpUnit\AbstractCompilerPassTestCase;
use Swoopster\ObjectManagerBundle\DependencyInjection\Compiler\RegisterMappingsCompilerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;

class RegisterMappingsCompilerPassTest extends AbstractCompilerPassTestCase
{
	public function testXmlMappings()
	{
		$modelDir = 'Model';
		$bundleDir = 'src';
		$namespace = 'Swoopster\TestBundle';
		$root = __DIR__.'/../../Fixtures/App/app';
		$this->setDefinition('doctrine.orm.default_metadata_driver', new Definition());
		$this->container->setParameter('doctrine.default_entity_manager', 'default');

		$this->container->setParameter('swoopster_object_manager.model_dir', $modelDir);
		$this->container->setParameter('swoopster_object_manager.mapping_format', 'xml');
		$this->container->setParameter('swoopster_object_manager.bundles', array(
			array('namespace' => $namespace)
		));

		$this->compile();

		$compilerPass = $this->container->getCompilerPassConfig()->getPasses()[1];

		$this->assertEquals($modelDir, \PHPUnit_Framework_Assert::readAttribute($compilerPass, 'modelDir'));
		$this->assertEquals(array(
			realpath($root.'/../'.$bundleDir.'/Swoopster/TestBundle/Resources/config/doctrine/model') => $namespace.'\\'.$modelDir
		), \PHPUnit_Framework_Assert::readAttribute($compilerPass, 'namespaces'));

	}
	/**
	 * Register the compiler pass under test, just like you would do inside a bundle's load()
	 * method:
	 *
	 *   $container->addCompilerPass(new MyCompilerPass());
	 */
	protected function registerCompilerPass(ContainerBuilder $container)
	{
		$container->addCompilerPass(new RegisterMappingsCompilerPass());
	}

}