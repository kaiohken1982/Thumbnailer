<?php 
namespace ThumbnailerTest\Service; 

use Thumbnailer\Thumbnailer\Thumbnailer,
    Thumbnailer\Service\ThumbnailerFactory;
use ThumbnailerTest\Bootstrap;
use PHPUnit_Framework_TestCase;

class ThumbnailerFactoryTest 
	extends PHPUnit_Framework_TestCase
{
	protected $obj;
	
	protected function setUp()
	{
		$this->obj = new ThumbnailerFactory();
	}
	
	/**
	 * @covers \Thumbnailer\Service\ThumbnailerFactory::createService()
	 */
	public function testGetSourceImageResource() 
	{
		$service = $this->obj->createService(Bootstrap::getServiceManager());
		$this->assertTrue($service instanceof Thumbnailer);
	}
}