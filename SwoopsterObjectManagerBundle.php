<?php

namespace Swoopster\ObjectManagerBundle;

use Doctrine\Bundle\DoctrineBundle\DependencyInjection\Compiler\DoctrineOrmMappingsPass;
use Swoopster\ObjectManagerBundle\DependencyInjection\Compiler\ManagerCompilerPass;
use Swoopster\ObjectManagerBundle\DependencyInjection\Compiler\RegisterMappingsCompilerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class SwoopsterObjectManagerBundle extends Bundle
{

	public function  build(ContainerBuilder $container)
	{
		parent::build($container);

		$container->addCompilerPass(new RegisterMappingsCompilerPass());
		$container->addCompilerPass(new ManagerCompilerPass());
	}


}
