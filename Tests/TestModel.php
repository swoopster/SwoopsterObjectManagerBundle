<?php
/*
* This file is part of the SwoopsterObjectManagerBundle package.
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/
namespace Swoopster\ObjectManagerBundle\Tests;
use Swoopster\ObjectManagerBundle\Model\EventDrivenModelInterface;

/**
 * Class TestModel
 *
 * @\Doctrine\ORM\Mapping\Entity()
 */
class TestModel implements EventDrivenModelInterface{
	/**
	 * @var
	 * @\Doctrine\ORM\Mapping\Id()
	 * @\Doctrine\ORM\Mapping\GeneratedValue()
	 */
	public $id;
} 