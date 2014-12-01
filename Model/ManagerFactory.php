<?php
/**
 * Created by PhpStorm.
 * User: malte
 * Date: 01.12.14
 * Time: 15:29
 */

namespace Swoopster\ObjectManagerBundle\Model;

/**
 * Class ManagerFactory
 *
 * factory to get manager by class name
 *
 * @package Swoopster\ObjectManagerBundle\Model
 */
class ManagerFactory
{
	/**
	 * @var ManagerInterface
	 */
	private $manager = array();

	/**
	 * @param ManagerInterface $manager
	 */
	public function addManager(ManagerInterface $manager)
	{
		$this->manager[$manager->getClass()] = $manager;
	}

	/**
	 * @param string $className
	 */
	public function getManager($className)
	{
		if(array_key_exists($className, $this->manager))
		{
			return $this->manager[$className];
		}
		throw new \InvalidArgumentException('manager doesn\'t exist');
	}
} 