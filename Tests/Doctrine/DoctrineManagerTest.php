<?php
/*
* This file is part of the SwoopsterObjectManagerBundle package.
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/
namespace Swoopster\ObjectManagerBundle\Tests\Doctrine;

use Doctrine\Common\Persistence\ObjectManager;
use Liip\FunctionalTestBundle\Test\WebTestCase;
use PHPUnit_Framework_MockObject_MockObject;
use Swoopster\ObjectManagerBundle\Doctrine\DoctrineManager;

class DoctrineManagerTest extends WebTestCase
{

	const TEST_OBJECT_CLASS = 'Swoopster\ObjectManagerBundle\Tests\Doctrine\TestObject';
	/**
	 * @var DoctrineManager
	 */
	private $doctrineManager;

	/**
	 * @var PHPUnit_Framework_MockObject_MockObject
	 */
	private $om;

	/**
	 * @var PHPUnit_Framework_MockObject_MockObject
	 */
	private $repository;

	public function setUp(){
		$registry = $this->getMock('Doctrine\Bundle\DoctrineBundle\Registry', array(), array($this->getContainer(), array(), array(), 'default', 'default'));
		$this->om =  $this->om = $this->getMock('Doctrine\Common\Persistence\ObjectManager');
		$this->repository = $this->getMock('Doctrine\Common\Persistence\ObjectRepository');
		$registry->expects($this->any())
			->method('getManagerForClass')
			->with($this->equalTo(self::TEST_OBJECT_CLASS))
			->will($this->returnValue($this->om));
		$this->om->expects($this->any())
			->method('getRepository')
			->with($this->equalTo(static::TEST_OBJECT_CLASS))
			->will($this->returnValue($this->repository));
		$this->doctrineManager = new DoctrineManager(self::TEST_OBJECT_CLASS);
		$this->doctrineManager->setRegistry($registry);
		$this->doctrineManager->setContainer($this->getContainer()->get('service_container'));
	}

	public function testGetEntityManager(){
			$this->assertEquals($this->om, $this->doctrineManager->getEntityManager(), 'classes doesn\'t match');
	}

	public function testFindAll()
	{
		$result = array($this->getObject(), $this->getObject());
		$this->repository->expects($this->once())
			->method('findAll')
			->will($this->returnValue($result));

		$this->assertEquals($result, $this->doctrineManager->findAll());
	}

	public function testFindBy()
	{
		$result = $this->getObject();
		$this->repository->expects($this->any())
			->method('findBy')
			->with(array(), array(), 0, 1)
			->will($this->returnValue($result))
		;

		$this->assertEquals($result, $this->doctrineManager->findBy(array(), array(), 0, 1));
	}

	public function testFindOneBy()
	{
		$result = $this->getObject();
		$this->repository->expects($this->any())
			->method('findOneBy')
			->with(array(), array())
			->will($this->returnValue($result))
		;

		$this->assertEquals($result, $this->doctrineManager->findOneBy(array(), array()));
	}

	public function testFind()
	{
		$result = $this->getObject();
		$this->repository->expects($this->any())
			->method('find')
			->with(1)
			->will($this->returnValue($result))
		;
		$this->assertEquals($result, $this->doctrineManager->find(1), 'should match');
	}

	public function testSave()
	{
		$object = $this->getObject();
		global $resultPersist, $resultFlush;
		$resultPersist = false;
		$resultFlush = false;
		$this->om->expects($this->any())
			->method('persist')
			->with($object)
			->will($this->returnCallback(
				function(){
					global $resultPersist;
					$resultPersist = true;
				})
			);
		$this->om->expects($this->once())
			->method('flush')
			->willReturnCallback(function(){
				global $resultFlush;
				$resultFlush = true;
			});
		$this->doctrineManager->save($object, false);
		$this->assertTrue($resultPersist);
		$this->assertFalse($resultFlush);

		$this->doctrineManager->save($object);
		$this->assertTrue($resultPersist);
		$this->assertTrue($resultFlush);
	}

	public function testSaveFalseObject()
	{
		try{
			$this->doctrineManager->save(new FalseTestObject());
			$this->fail("Expected exception not thrown");
		}catch (\Exception $e){
			$this->assertInstanceOf('\InvalidArgumentException', $e);
		}
	}

	public function testDelete()
	{
		$object = $this->getObject();
		global $resultDelete, $resultFlush;
		$resultDelete = false;
		$resultFlush = false;
		$this->om->expects($this->any())
			->method('remove')
			->with($object)
			->will($this->returnCallback(
					function(){
						global $resultDelete;
						$resultDelete = true;
					})
			);
		$this->om->expects($this->once())
			->method('flush')
			->willReturnCallback(function(){
				global $resultFlush;
				$resultFlush = true;
			});
		$this->doctrineManager->delete($object, false);
		$this->assertTrue($resultDelete);
		$this->assertFalse($resultFlush);

		$this->doctrineManager->delete($object);
		$this->assertTrue($resultDelete);
		$this->assertTrue($resultFlush);
	}




	protected function getObject(){
		$class =  self::TEST_OBJECT_CLASS;
		return new $class();
	}

}

class TestObject{

}

class FalseTestObject{

}