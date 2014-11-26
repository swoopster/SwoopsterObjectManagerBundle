<?php

/*
* This file is part of the SwoopsterObjectManagerBundle package.
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Config\Loader\LoaderInterface;

class AppKernel extends Kernel
{
	public function registerBundles()
	{
		$bundles = array(
			new Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
			new Symfony\Bundle\SecurityBundle\SecurityBundle(),
// register the other bundles your tests depend on
// and don't forget your own bunde!
			new \Swoopster\ObjectManagerBundle\SwoopsterObjectManagerBundle(),
		);

		if (in_array($this->getEnvironment(), array('test'))) {
			$bundles[] = new Liip\FunctionalTestBundle\LiipFunctionalTestBundle();
		}

		return $bundles;
	}
	public function registerContainerConfiguration(LoaderInterface $loader)
	{
		$loader->load(__DIR__.'/config/config_'.$this->getEnvironment().'.yml');
	}
	/**
	 * @return string
	 */
	public function getCacheDir()
	{
		return sys_get_temp_dir().'/AcmeHelloBundle/cache';
	}
	/**
	 * @return string
	 */
	public function getLogDir()
	{
		return sys_get_temp_dir().'/AcmeHelloBundle/logs';
	}
}

