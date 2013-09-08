<?php 
namespace ThumbnailerTest\Thumbnailer; 

use Thumbnailer\Thumbnailer\Thumbnailer;
use PHPUnit_Framework_TestCase;

class ThumbnailerTest 
	extends PHPUnit_Framework_TestCase
{
	protected $obj;
	
	protected $testImagePath;
	
	protected $testImageName = 'berserk.png';
	
	protected $testThumbName = 'berserkThumb.png';
	
	protected function setUp()
	{
		$this->testImagePath = dirname(__DIR__) . '\\..\\data\\';
		$this->obj = new Thumbnailer();
		$this->obj->open($this->testImagePath . $this->testImageName);
	}
	
	/**
	 * @covers \Thumbnailer\Thumbnailer\Thumbnailer::__construct()
	 * @covers \Thumbnailer\Thumbnailer\Thumbnailer::getSourceImageResource()
	 * @covers \Thumbnailer\Thumbnailer\Thumbnailer::initSourceImageResource()
	 */
	public function testGetSourceImageResource() 
	{
		$resource = $this->obj->getSourceImageResource();
		$this->assertEquals('gd', get_resource_type($resource));
	}
	
	/**
	 * @covers \Thumbnailer\Thumbnailer\Thumbnailer::open()
	 * @covers \Thumbnailer\Thumbnailer\Thumbnailer::parseImageInfo()
	 * @covers \Thumbnailer\Thumbnailer\Thumbnailer::getImageInfo()
	 * @covers \Thumbnailer\Thumbnailer\Thumbnailer::initSourceImageResource()
	 */
	public function testImageInfo() 
	{
		$info = $this->obj->getImageInfo();

		$this->assertTrue(isset($info['dirname']));
		$this->assertTrue(isset($info['basename']));
		$this->assertTrue(isset($info['extension']));
		$this->assertTrue(isset($info['filename']));
		$this->assertTrue(isset($info['bits']));
		$this->assertTrue(isset($info['mime']));
		$this->assertTrue(isset($info['width']));
		$this->assertTrue(isset($info['height']));

		$this->assertEquals(816, $info['height']);
		$this->assertEquals(1916, $info['width']);
		$this->assertEquals("image/png", $info['mime']);
		$this->assertEquals(8, $info['bits']);
		$this->assertEquals("berserk", $info['filename']);
		$this->assertEquals("png", $info['extension']);
		$this->assertEquals("berserk.png", $info['basename']);
	}
	
	/**
	 * @covers \Thumbnailer\Thumbnailer\Thumbnailer::setExtension()
	 * @covers \Thumbnailer\Thumbnailer\Thumbnailer::getExtension()
	 */
	public function testSetGetExtension() 
	{
		$this->assertEquals("png", $this->obj->getExtension());
	}
	
	/**
	 * @covers \Thumbnailer\Thumbnailer\Thumbnailer::save()
	 * @covers \Thumbnailer\Thumbnailer\Thumbnailer::render()
	 * @covers \Thumbnailer\Thumbnailer\Thumbnailer::initDestImageResource()
	 * @covers \Thumbnailer\Thumbnailer\Thumbnailer::applyTransparency()
	 */
	public function testSaveThumbnail() 
	{
		$this->obj->save($this->testImagePath . $this->testThumbName);
		
		// Delete any previous created image
		if (file_exists($this->testImagePath . $this->testThumbName)) {
			unlink($this->testImagePath . $this->testThumbName);
		}
	}
	
	/**
	 * @covers \Thumbnailer\Thumbnailer\Thumbnailer::parseConfig()
	 * @covers \Thumbnailer\Thumbnailer\Thumbnailer::setQuality()
	 * @covers \Thumbnailer\Thumbnailer\Thumbnailer::getQuality()
	 */
	public function testparseConfig() 
	{
		$this->obj->parseConfig(array('quality' => 80));
		$this->assertEquals(80, $this->obj->getQuality());
	}
	
	/**
	 * @covers \Thumbnailer\Thumbnailer\Thumbnailer::open()
	 * @expectedException \RuntimeException
	 */
	public function testOpenInvalidSourceImagePath() 
	{
		$this->obj = new Thumbnailer();
		$this->obj->open('blablabla');
	}
	
	/**
	 * @covers \Thumbnailer\Thumbnailer\Thumbnailer::parseImageInfo()
	 * @expectedException \Thumbnailer\Thumbnailer\Exception\NoSourceException
	 */
	public function testNoSourceImagePath() 
	{
		$this->obj = new Thumbnailer();
		$this->obj->parseImageInfo();
	}
	
	/**
	 * @covers \Thumbnailer\Thumbnailer\Thumbnailer::resize()
	 * @covers \Thumbnailer\Thumbnailer\Thumbnailer::getNewWidth()
	 * @covers \Thumbnailer\Thumbnailer\Thumbnailer::getNewHeight()
	 */
	public function testResizeNoHeight() 
	{
		$this->obj->resize(300);
		$this->obj->save($this->testImagePath . $this->testThumbName);
		$this->obj->open($this->testImagePath . $this->testThumbName);
		$info = $this->obj->getImageInfo();

		$this->assertEquals(127, $info['height']);
		$this->assertEquals(300, $info['width']);
		$this->assertEquals("image/png", $info['mime']);
		$this->assertEquals(8, $info['bits']);
		$this->assertEquals("berserkThumb", $info['filename']);
		$this->assertEquals("png", $info['extension']);
		$this->assertEquals("berserkThumb.png", $info['basename']);
		
		// Delete any previous created image
		if (file_exists($this->testImagePath . $this->testThumbName)) {
			unlink($this->testImagePath . $this->testThumbName);
		}
	}
	
	/**
	 * @covers \Thumbnailer\Thumbnailer\Thumbnailer::resize()
	 * @covers \Thumbnailer\Thumbnailer\Thumbnailer::getNewWidth()
	 * @covers \Thumbnailer\Thumbnailer\Thumbnailer::getNewHeight()
	 */
	public function testResizeNoWidth() 
	{
		$this->obj->resize(0, 300);
		$this->obj->save($this->testImagePath . $this->testThumbName);
		$this->obj->open($this->testImagePath . $this->testThumbName);
		$info = $this->obj->getImageInfo();

		$this->assertEquals(300, $info['height']);
		$this->assertEquals(704, $info['width']);
		$this->assertEquals("image/png", $info['mime']);
		$this->assertEquals(8, $info['bits']);
		$this->assertEquals("berserkThumb", $info['filename']);
		$this->assertEquals("png", $info['extension']);
		$this->assertEquals("berserkThumb.png", $info['basename']);
		
		// Delete any previous created image
		if (file_exists($this->testImagePath . $this->testThumbName)) {
			unlink($this->testImagePath . $this->testThumbName);
		}
	}
}