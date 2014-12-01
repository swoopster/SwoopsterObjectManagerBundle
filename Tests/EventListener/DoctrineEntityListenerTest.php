<?php
/*
* This file is part of the SwoopsterObjectManagerBundle package.
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/
namespace Swoopster\ObjectManagerBundle\Tests\EventListener;

use Liip\FunctionalTestBundle\Test\WebTestCase;
use PHPUnit_Framework_MockObject_MockObject;
use Swoopster\ObjectManagerBundle\EventListener\DoctrineEntityListener;
use Swoopster\ObjectManagerBundle\Model\EventDrivenModelInterface;
use Swoopster\ObjectManagerBundle\Model\ManagerFactory;
use Swoopster\ObjectManagerBundle\Tests\TestModel;

class DoctrineEntityListenerTest extends WebTestCase
{
	/**
	 * @var PHPUnit_Framework_MockObject_MockObject
	 */
	private $manager;

	private $managerFactory;

	/**
	 * @var DoctrineEntityListener
	 */
	private $listener;

	private $args;

	public function setUp(){


		$this->manager = $this->getMock('Swoopster\ObjectManagerBundle\Model\ManagerEventsInterface');
		$this->manager->expects($this->any())
			->method('getClass')
			->willReturn(get_class(new TestModel()));


		$this->mockManagerFactory($this->manager);
		$this->mockListener($this->managerFactory);

		$this->args = $this->getMock('Doctrine\ORM\Event\LifecycleEventArgs',array('getObject'), array(new TestModel(), $this->getMock('Doctrine\Common\Persistence\ObjectManager')));
		$this->args->expects($this->any())
			->method('getObject')
			->willReturn(new TestModel());
	}

	public function tearDown() {
		$this->listener = null;
		$this->manager = null;
		$this->args = null;
		$this->managerFactory = null;
	}

	public function testPrePersist()
	{
		$this->manager->expects($this->exactly(1))
			->method('prePersist')
			->with(new TestModel())
			->willReturnCallback($func = function(){var_dump('test');})
		;

		$this->listener->prePersist($this->args);
	}

	public function testPostPersist()
	{
		$this->manager->expects($this->exactly(1))
			->method('postPersist')
			->with(new TestModel())
		;

		$this->listener->postPersist($this->args);
	}

	public function testPreUpdate()
	{
		$this->manager->expects($this->exactly(1))
			->method('preUpdate')
			->with(new TestModel())
		;

		$this->listener->preUpdate($this->args);
	}

	public function testPostUpdate()
	{
		$this->manager->expects($this->exactly(1))
			->method('postUpdate')
			->with(new TestModel())
		;

		$this->listener->postUpdate($this->args);
	}

	public function testPreRemove()
	{
		$this->manager->expects($this->exactly(1))
			->method('preRemove')
			->with(new TestModel())
		;

		$this->listener->preRemove($this->args);
	}

	public function testPostRemove()
	{
		$this->manager->expects($this->exactly(1))
			->method('postRemove')
			->with(new TestModel())
		;

		$this->listener->postRemove($this->args);
	}

	public function testPostLoad()
	{
		$this->manager->expects($this->exactly(1))
			->method('postLoad')
			->with(new TestModel())
		;

		$this->listener->postLoad($this->args);
	}

	public function testChainedManager(){
		$firstManager = $this->getMock('Swoopster\ObjectManagerBundle\Model\ManagerEventsInterface');
		$firstManager->expects($this->any())
			->method('getClass')
			->willReturn(get_class(new TestModel()));

		$secondManager = $this->getMock('Swoopster\ObjectManagerBundle\Model\ManagerEventsInterface');
		$secondManager->expects($this->any())
			->method('getClass')
			->willReturn(get_class(new ChildTestModel()));

		$this->managerFactory = $this->getMock(get_class(new ManagerFactory()));
		$this->managerFactory->expects($this->at(0))
			->method('getManager')
			->with(get_class(new ChildTestModel()))
			->willReturn($firstManager);
		;
		$this->managerFactory->expects($this->at(1))
			->method('getManager')
			->with(get_class(new TestModel()))
			->willReturn($secondManager);

		$this->mockListener($this->managerFactory);

		$this->args = $this->getMock('Doctrine\ORM\Event\LifecycleEventArgs',array('getObject'), array(new ChildTestModel(), $this->getMock('Doctrine\Common\Persistence\ObjectManager')));
		$this->args->expects($this->any())
			->method('getObject')
			->willReturn(new ChildTestModel());

		$firstManager->expects($this->exactly(1))
			->method('postRemove')
			->with(new ChildTestModel())
		;

		$secondManager->expects($this->exactly(1))
			->method('postRemove')
			->with(new ChildTestModel())
			;

		$this->listener->postRemove($this->args);
	}

	public function testExcpetionNoManagerEventInterface()
	{
		$this->manager = $this->getMock('Swoopster\ObjectManagerBundle\Model\ManagerInterface');
		$this->manager->expects($this->any())
			->method('getClass')
			->willReturn(get_class(new TestModel()));

		$this->mockManagerFactory($this->manager);
		$this->mockListener($this->managerFactory);

		$this->setExpectedException('Symfony\Component\Config\Definition\Exception\InvalidConfigurationException');
		$this->listener->postRemove($this->args);
	}

	private function mockManagerFactory($manager)
	{
		$this->managerFactory = $this->getMock(get_class(new ManagerFactory()));
		$this->managerFactory->expects($this->any())
			->method('getManager')
			->with(get_class(new TestModel()))
			->willReturn($manager);
		;

	}

	private function mockListener($factory)
	{
		$this->listener = new DoctrineEntityListener($factory);
	}
}

class ChildTestModel extends TestModel implements EventDrivenModelInterface{

}