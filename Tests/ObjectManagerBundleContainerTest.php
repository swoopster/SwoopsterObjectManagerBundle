<?php
/*
* This file is part of the SwoopsterObjectManagerBundle package.
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/
namespace Swoopster\ObjectManagerBundle\Tests;

use Liip\FunctionalTestBundle\Test\WebTestCase;

class ObjectManagerBundleContainerTest extends WebTestCase
{
	/**
	 * @dataProvider dataServiceConstruction
	 */
	public function testServiceConstruction($id, $class)
	{
		$service = $this->getContainer()->get($id);
		$this->assertInstanceOf($class, $service);
	}

	static public function dataServiceConstruction()
	{
		return array(
			array('swoopster.object_manager.test_doctrine_manager', 'Swoopster\ObjectManagerBundle\Doctrine\DoctrineManager')
		);
	}
} 