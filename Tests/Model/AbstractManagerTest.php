<?php
/*
* This file is part of the SwoopsterObjectManagerBundle package.
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

namespace Swoopster\ObjectManagerBundle\Tests\Model;


use Liip\FunctionalTestBundle\Test\WebTestCase;
use Swoopster\ObjectManagerBundle\Model\AbstractManager;

class AbstractManagerTest extends WebTestCase
{
    public function testClass()
    {
        $this->assertEquals($this->getExampleClass(), $this->getObject()->getClass(), 'Klassennamen stimmen nicht ueberein');

		$this->assertNotEquals($this->getFalseExampleClass(), $this->getObject(), 'Klassen müssen übereinstimmen');
    }

    public function testCreate()
    {
        $this->assertInstanceOf($this->getExampleClass(), $this->getObject()->create(), 'Objekt wird nicht korrek erstellt');
    }

	public function testGetSecurityContext(){
		$mock = $this->getObject();
		$mock->setContainer($this->getContainer()->get('service_container'));


		$this->assertEquals($this->getContainer()->get('security.context'), $mock->getSecurityContext(), 'have to return instance of SecurityContext');
	}


    /**
     * @return AbstractManager
     */
    protected function getObject()
    {
        return $this->getMockForAbstractClass(
            $this->getClass(),
            array($this->getExampleClass())
        );
    }

    protected function getClass()
    {
        return 'Swoopster\ObjectManagerBundle\Model\AbstractManager';
    }

    protected function getExampleClass()
    {
       return 'Swoopster\ObjectManagerBundle\Tests\TestModel';
    }

	protected function getFalseExampleClass()
	{
		return 'Swoopster\ObjectManagerBundle\Tests\Model\FalseTestModel';
	}
}


class FalseTestModel {

}