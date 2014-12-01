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

	public function prePersist($entity, $params = array());

	public function postPersist($entity, $params = array());

	public function preUpdate($entity, $params = array());

	public function postUpdate($entity, $params = array());

	public function preRemove($entity, $params = array());

	public function postRemove($entity, $params = array());

	public function postLoad($entity, $params = array());

} 