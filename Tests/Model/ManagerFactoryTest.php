<?php
/*
* This file is part of the SwoopsterObjectManagerBundle package.
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/
namespace Swoopster\ObjectManagerBundle\Tests\Model;


use Liip\FunctionalTestBundle\Test\WebTestCase;
use Swoopster\ObjectManagerBundle\Model\ManagerFactory;
use Swoopster\ObjectManagerBundle\Tests\TestModel;

class ManagerFactoryTest extends WebTestCase
{
	private  $manager;

	public function __construct()
	{
		$this->manager = $this->getMockForAbstractClass('Swoopster\ObjectManagerBundle\Model\AbstractManager', array(get_class($this->getExampleModel())));
	}

	public function testAddManager()
	{
		$factory = $this->getFactory();
		$this->assertTrue(\PHPUnit_Framework_Assert::readAttribute($factory, 'manager') === array(get_class($this->getExampleModel())=> $this->manager));
	}

	public function testGetManager()
	{
		$factory = $this->getFactory();

		//test get existing manager
		$this->assertEquals($this->manager, $factory->getManager(get_class($this->getExampleModel())));

		//test get non-existing manager
		$this->setExpectedException('\InvalidArgumentException');
		$factory->getManager('dadsg');
	}

	private function getExampleModel()
	{
		return new TestModel();
	}

	private function getFactory()
	{
		$factory = new ManagerFactory();

		$factory->addManager($this->manager);

		return $factory;
	}

} 