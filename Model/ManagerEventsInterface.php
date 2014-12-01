<?php
/*
* This file is part of the SwoopsterObjectManagerBundle package.
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/
namespace Swoopster\ObjectManagerBundle\Model;

/**
 * declares event support for an entity manager
 *
 * Interface ManagerEventsInterface
 * @package Swoopster\ObjectManagerBundle\Model
 */
interface ManagerEventsInterface
{

	public function prePersist($entity);

	public function postPersist($entity);

	public function preUpdate($entity);

	public function postUpdate($entity);

	public function preRemove($entity);

	public function postRemove($entity);

	public function postLoad($entity);

} 