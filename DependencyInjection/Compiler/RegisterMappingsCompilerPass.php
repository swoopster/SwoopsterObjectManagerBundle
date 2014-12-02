<?php
/*
* This file is part of the SwoopsterObjectManagerBundle package.
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/
namespace Swoopster\ObjectManagerBundle\DependencyInjection\Compiler;

use Doctrine\Bundle\DoctrineBundle\DependencyInjection\Compiler\DoctrineOrmMappingsPass;
use ReflectionClass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;

/**
 * Class RegisterMappingsCompilerPass
 *
 * register mappings by doctrineormmappingspass
 *
 * @package Swoopster\ObjectManagerBundle\DependencyInjection\Compiler
 */
class RegisterMappingsCompilerPass extends  DoctrineOrmMappingsPass
{
	/**
	 * @var string
	 */
	private $modelDir;

	public function __construct(){
		$namespaces = array();
		//Dummy configuration
		$arguments = array($namespaces, '.orm.xml');
		$locator = new Definition('Doctrine\Common\Persistence\Mapping\Driver\SymfonyFileLocator', $arguments);
		$driver = new Definition('Doctrine\ORM\Mapping\Driver\XmlDriver', array($locator));
		parent::__construct($driver, $namespaces, array());
	}

	/**
	 * You can modify the container here before it is dumped to PHP code.
	 *
	 * @param ContainerBuilder $container
	 *
	 * @api
	 */
	public function process(ContainerBuilder $container)
	{
		if(count($container->getParameterBag()->all()) === 0) return;
		//get parameter
		$this->modelDir = $container->getParameter('swoopster_object_manager.model_dir');

		$this->configureCompilerPass($container);

		parent::process($container);
	}

	/**
	 * Choose which format to configure
	 *
	 * @param ContainerBuilder $container
	 * @throws \Exception
	 */
	private function configureCompilerPass(ContainerBuilder $container)

	{
		$format = $container->getParameter('swoopster_object_manager.mapping_format');
		switch($format){
			case 'xml':
				$this->configureXmlMappingPass($container);
				break;
			default:
				throw new \Exception($format.' - format not implemeted yet');
		}
	}

	/**
	 * configure xml
	 *
	 * @param ContainerBuilder $container
	 */
	private function configureXmlMappingPass(ContainerBuilder $container)
	{
		$mappings =  array();
		foreach ($container->getParameter('swoopster_object_manager.bundles') as $bundle) {
			$bundleDir = $this->getBundlePath($bundle['namespace']);
			$modelDir = realpath($bundleDir. '/Resources/config/doctrine/model');
			$mappings = array_merge($mappings, array(
				$modelDir => $bundle['namespace'].'\\'.$this->modelDir,
			));

		}


		if(!empty($mappings)){
			$this->namespaces = $mappings;
		}

		$arguments = array($this->namespaces, '.orm.xml');
		$locator = new Definition('Doctrine\Common\Persistence\Mapping\Driver\SymfonyFileLocator', $arguments);
		$this->driver = new Definition('Doctrine\ORM\Mapping\Driver\XmlDriver', array($locator));
	}

	/**
	 * Get Path to Bundle root
	 *
	 * @param string $namespace
	 * @return string
	 */
	private function getBundlePath($namespace)
	{
		$split = explode('\\', $namespace);
		$rc = new ReflectionClass($namespace . '\\' . implode('', $split));
		return dirname($rc->getFileName());

	}
}