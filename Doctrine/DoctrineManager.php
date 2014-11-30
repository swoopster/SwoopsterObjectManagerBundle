<?php
/*
* This file is part of the SwoopsterObjectManagerBundle package.
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/
namespace Swoopster\ObjectManagerBundle\Doctrine;

use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManager;
use Swoopster\ObjectManagerBundle\Model\AbstractManager;

/**
 * Class DoctrineManager
 *
 * provides necessary manager functions to use with doctrine
 *
 * @package Swoopster\ObjectManagerBundle\Doctrine
 */
class DoctrineManager extends AbstractManager
{
	/**
	 * @var ManagerRegistry
	 */
	protected $registry;

	/**
	 * set doctrine registry
	 *
	 * @param ManagerRegistry $registry
	 */
	public function setRegistry(ManagerRegistry $registry)
	{
		$this->registry = $registry;
	}

	/**
	 * {@inheritdoc}
	 */
	public function findAll()
	{
		return $this->getRepository()->findAll();
	}
	/**
	 * {@inheritdoc}
	 */
	public function findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
	{
		return $this->getRepository()->findBy($criteria, $orderBy, $limit, $offset);
	}
	/**
	 * {@inheritdoc}
	 */
	public function findOneBy(array $criteria, array $orderBy = null)
	{
		return $this->getRepository()->findOneBy($criteria, $orderBy);
	}
	/**
	 * {@inheritdoc}
	 */
	public function find($id)
	{
		return $this->getRepository()->find($id);
	}

	/**
	 * {@inheritdoc}
	 */
	public function save($entity, $andFlush = true)
	{
		$this->checkObject($entity);
		$this->getObjectManager()->persist($entity);
		if ($andFlush) {
			$this->getObjectManager()->flush();
		}
	}
	/**
	 * {@inheritdoc}
	 */
	public function delete($entity, $andFlush = true)
	{
		$this->checkObject($entity);
		$this->getObjectManager()->remove($entity);
		if ($andFlush) {
			$this->getObjectManager()->flush();
		}
	}

	/**
	 * @return EntityManager
	 */
	public function getEntityManager()
	{
		return $this->getObjectManager();
	}

	/**
	 * @throws \RuntimeException
	 * @return ObjectManager
	 */
	protected  function getObjectManager()
	{
		$manager = $this->registry->getManagerForClass($this->getClass());
		if (!$manager) {
			throw new \RuntimeException(sprintf("Unable to find the mapping information for the class %s."
				." Please check the 'auto_mapping' option (http://symfony.com/doc/current/reference/configuration/doctrine.html#configuration-overview)"
				." or add the bundle to the 'mappings' section in the doctrine configuration.", $this->getClass()));
		}
		return $manager;
	}

	protected function getRepository()
	{
		return $this->getObjectManager()->getRepository($this->getClass());
	}

} 