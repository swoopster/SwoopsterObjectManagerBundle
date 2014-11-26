<?php
/*
* This file is part of the SwoopsterObjectManagerBundle package.
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/


if (!is_file($autoloadFile = __DIR__.'/../vendor/autoload.php')) {
	throw new \LogicException('Could not find autoload.php in vendor/. Bundle needs to be part of an Symfony Project --dev"?');
}
require $autoloadFile;