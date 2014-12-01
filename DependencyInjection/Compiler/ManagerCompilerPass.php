<?php
/**
 * Created by PhpStorm.
 * User: malte
 * Date: 01.12.14
 * Time: 15:30
 */

namespace Swoopster\ObjectManagerBundle\DependencyInjection\Compiler;


use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class ManagerCompilerPass implements CompilerPassInterface{

	/**
	 * You can modify the container here before it is dumped to PHP code.
	 *
	 * @param ContainerBuilder $container
	 *
	 * @api
	 */
	public function process(ContainerBuilder $container)
	{
		if (!$container->hasDefinition('swoopster.object_manager.manager_factory')) {
			return;
		}

		$definition = $container->getDefinition(
			'swoopster.object_manager.manager_factory'
		);

		$taggedServices = $container->findTaggedServiceIds(
			'swoopster.object_manager'
		);

		foreach ($taggedServices as $id => $tags) {
			$definition->addMethodCall(
				'addManager',
				array(new Reference($id))
			);
		}
	}
}