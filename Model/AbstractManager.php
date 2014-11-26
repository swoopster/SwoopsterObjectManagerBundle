<?php
/*
* This file is part of the SwoopsterObjectManagerBundle package.
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

namespace Swoopster\ObjectManagerBundle\Model;


use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\Security\Core\SecurityContext;

abstract class AbstractManager implements ManagerInterface
{
    /**
     * @var string
     */
    protected $class;

    /**
     * @var Container
     */
    protected $container;

    /**
     * @param Container $container
     *
     */
    public function setContainer(Container $container)
    {
        $this->container = $container;
    }

    /**
     * @return SecurityContext
     */
    public function getSecurityContext()
    {
        return $this->container->get('security.context');
    }

    /**
     * @param string $class
     */
    public function __construct($class)
    {
        $this->class = $class;
    }

    /**
     * @return string
     */
    public function getClass()
    {
        return $this->class;
    }

    /**
     * @return $this->class
     */
    public function create()
    {
        return new $this->class();
    }

    /**
     * @param $object
     *
     * @throws \InvalidArgumentException
     */
    protected function checkObject($object)
    {
        if (!$object instanceof $this->class) {
            throw new \InvalidArgumentException(sprintf(
                'Object must be instance of %s, %s given',
                $this->class, is_object($object)? get_class($object) : gettype($object)
            ));
        }
    }
} 