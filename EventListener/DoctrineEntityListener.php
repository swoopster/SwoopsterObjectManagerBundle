<?php
/*
* This file is part of the SwoopsterObjectManagerBundle package.
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/
namespace Swoopster\ObjectManagerBundle\EventListener;

use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
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
			$this->callEvent('prePersist', $entity);
		}
	}

	/**
	 * @param LifecycleEventArgs $args
	 */
	public function postPersist(LifecycleEventArgs $args)
	{
		$entity = $args->getObject();

		if($entity instanceof EventDrivenModelInterface){
			$this->callEvent('postPersist', $entity);
		}
	}

	/**
	 * @param LifecycleEventArgs $args
	 */
	public function preUpdate(PreUpdateEventArgs $args)
	{
		$entity = $args->getObject();
		if($entity instanceof EventDrivenModelInterface){
			$this->callEvent('preUpdate', $entity, array('change_set'=> $args->getEntityChangeSet()));
		}
	}

	/**
	 * @param LifecycleEventArgs $args
	 */
	public function postUpdate(LifecycleEventArgs $args)
	{
		$entity = $args->getObject();
		if($entity instanceof EventDrivenModelInterface){
			$this->callEvent('postUpdate', $entity);
		}
	}

	/**
	 * @param LifecycleEventArgs $args
	 */
	public function preRemove(LifecycleEventArgs $args)
	{
		$entity = $args->getObject();
		if($entity instanceof EventDrivenModelInterface){
			$this->callEvent('preRemove', $entity);
		}
	}

	/**
	 * @param LifecycleEventArgs $args
	 */
	public function postRemove(LifecycleEventArgs $args)
	{
		$entity = $args->getObject();
		if($entity instanceof EventDrivenModelInterface){
			$this->callEvent('postRemove', $entity);
		}
	}

	/**
	 * @param LifecycleEventArgs $args
	 */
	public function postLoad(LifecycleEventArgs $args)
	{
		$entity = $args->getObject();
		if($entity instanceof EventDrivenModelInterface){
			$this->callEvent('postLoad', $entity);
		}
	}

	/**
	 * @param $event
	 * @param $entity
	 * @param array $params event depending parameter
	 */
	private function  callEvent($event, $entity, $params = array())
	{
		$walk = function($item, $key, $params){
			$item->{$params['event']}($params['entity'], $params['params']);
		};

		$result = array_walk($this->getManager($entity), $walk , array('event' => $event, 'entity' => $entity, 'params' => $params));
	}

	/**
	 * @param $entity
	 * @return ManagerEventsInterface[]
	 */
	private function getManager($entity)
	{
		$manager = array();
		$class = get_class($entity);

		do{
			$foundManager = $this->getManagerByString($class);
			if(isset($foundManager)){
				array_push($manager,  $foundManager);
			}

		}while($class = get_parent_class($class));

		if(empty($manager))
		{
			throw new InvalidConfigurationException('Class doesn\'t implements ManagerEventsInterface');
		}
		return $manager;
	}

	/**
	 * @param $className
	 * @return null|ManagerEventsInterface
	 */
	private function getManagerByString($className)
	{
		$manager = $this->managerFactory->getManager($className);
		if(!$manager instanceof ManagerEventsInterface){
			return null;
		}
		return $manager;
	}

} 