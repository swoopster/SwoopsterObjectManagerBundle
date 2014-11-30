<?php
/*
* This file is part of the SwoopsterObjectManagerBundle package.
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/
namespace Swoopster\ObjectManagerBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('swoopster_object_manager');
		$rootNode
			->children()
				->scalarNode('bundle_dir')
					->defaultValue('src')
				->end()
				->scalarNode('model_dir')
					->defaultValue('Model')
				->end()
				->enumNode('mapping_format')
					->values(array('xml', 'yml'))
					->defaultValue('xml')
				->end()
				->arrayNode('bundles')
					->prototype('array')
						->children()
							->scalarNode('namespace')->end()
						->end()
					->end()
				->end()
			->end();

        // Here you should define the parameters that are allowed to
        // configure your bundle. See the documentation linked above for
        // more information on that topic.

        return $treeBuilder;
    }
}
