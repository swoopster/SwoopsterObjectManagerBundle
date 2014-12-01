<?php
/*
* This file is part of the SwoopsterObjectManagerBundle package.
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/
namespace Swoopster\ObjectManagerBundle\EventListener;

use Doctrine\ORM\Event\LifecycleEventArgs;
use Swoopster\ObjectManagerBundle\Model\EventDrivenModelInterface;
use Swoopster\ObjectManagerBundle\Model\ManagerEventsInterface;
use Swoopster\ObjectManagerBundle\Model\ManagerFactory;
use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;

class DoctrineEntityListener
{
	/**
	 * @var ManagerFactory
	 */
	private $managerFactory;

	/**
	 * @param ManagerFactory $managerFactory
	 */
	public function __construct(ManagerFactory $managerFactory)
	{
		$this->managerFactory = $managerFactory;
	}

	/**
	 * @param LifecycleEventArgs $args
	 */
	public function prePersist(LifecycleEventArgs $args)
	{
		$entity = $args->getObject();

		if($entity instanceof EventDrivenModelInterface){
			$this->getManager($entity)->prePersist($entity);
		}
	}

	/**
	 * @param LifecycleEventArgs $args
	 */
	public function postPersist(LifecycleEventArgs $args)
	{
		$entity = $args->getObject();

		if($entity instanceof EventDrivenModelInterface){
			$this->getManager($entity)->postPersist($entity);
		}
	}

	/**
	 * @param LifecycleEventArgs $args
	 */
	public function preUpdate(LifecycleEventArgs $args)
	{
		$entity = $args->getObject();
		if($entity instanceof EventDrivenModelInterface){
			$this->getManager($entity)->preUpdate($entity);
		}
	}

	/**
	 * @param LifecycleEventArgs $args
	 */
	public function postUpdate(LifecycleEventArgs $args)
	{
		$entity = $args->getObject();
		if($entity instanceof EventDrivenModelInterface){
			$this->getManager($entity)->postUpdate($entity);
		}
	}

	/**
	 * @param LifecycleEventArgs $args
	 */
	public function preRemove(LifecycleEventArgs $args)
	{
		$entity = $args->getObject();
		if($entity instanceof EventDrivenModelInterface){
			$this->getManager($entity)->preRemove($entity);
		}
	}

	/**
	 * @param LifecycleEventArgs $args
	 */
	public function postRemove(LifecycleEventArgs $args)
	{
		$entity = $args->getObject();
		if($entity instanceof EventDrivenModelInterface){
			$this->getManager($entity)->postRemove($entity);
		}
	}

	/**
	 * @param LifecycleEventArgs $args
	 */
	public function postLoad(LifecycleEventArgs $args)
	{
		$entity = $args->getObject();
		if($entity instanceof EventDrivenModelInterface){
			$this->getManager($entity)->postLoad($entity);
		}
	}

	/**
	 * @param $entity
	 * @return ManagerEventsInterface
	 */
	private function getManager($entity)
	{
		$manager = $this->managerFactory->getManager(get_class($entity));
		if(!$manager instanceof ManagerEventsInterface){
			throw new InvalidConfigurationException('Class doesn\'t implements ManagerEventsInterface');
		}
		return $manager;
	}

} 