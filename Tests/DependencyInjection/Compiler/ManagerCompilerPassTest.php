<?php
/**
 * Created by PhpStorm.
 * User: malte
 * Date: 01.12.14
 * Time: 16:07
 */

namespace Swoopster\ObjectManagerBundle\Tests\DependencyInjection\Compiler;


use Fixtures\Bundles\AnnotationsBundle\Entity\Test;
use Matthias\SymfonyDependencyInjectionTest\PhpUnit\AbstractCompilerPassTestCase;
use Swoopster\ObjectManagerBundle\DependencyInjection\Compiler\ManagerCompilerPass;
use Swoopster\ObjectManagerBundle\Doctrine\DoctrineManager;
use Swoopster\ObjectManagerBundle\Model\ManagerFactory;
use Swoopster\ObjectManagerBundle\Tests\TestModel;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;

class ManagerCompilerPassTest extends AbstractCompilerPassTestCase{

	public function testManagerCompilerPass()
	{
		$this->container->setDefinition('swoopster.object_manager.manager_factory', new Definition(get_class(new ManagerFactory())));
		$testManager = new Definition();
		$testManager->setClass(get_class( new DoctrineManager(get_class(new TestModel()))));
		$testManager->setArguments(array(get_class(new TestModel())));
		$testManager->setTags(array('swoopster.object_manager' => ''));
		$this->container->setDefinition('swoopster.test_manager', $testManager);

		$this->compile();

		$this->assertEquals($this->container->get('swoopster.test_manager'), $this->container->get('swoopster.object_manager.manager_factory')->getManager(get_class(new TestModel())));
	}

	/**
	 * Register the compiler pass under test, just like you would do inside a bundle's load()
	 * method:
	 *
	 *   $container->addCompilerPass(new MyCompilerPass());
	 */
	protected function registerCompilerPass(ContainerBuilder $container)
	{
		$container->addCompilerPass(new ManagerCompilerPass());
	}
}