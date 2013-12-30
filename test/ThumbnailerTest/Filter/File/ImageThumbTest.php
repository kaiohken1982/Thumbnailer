<?php 
namespace ThumbnailerTest\Filter\File; 

use Thumbnailer\Thumbnailer\Thumbnailer,
    Thumbnailer\Filter\File\ImageThumb;
use ThumbnailerTest\Bootstrap;
use PHPUnit_Framework_TestCase;

class ImageThumbTest 
	extends PHPUnit_Framework_TestCase
{
	protected $obj;
	
	protected $thumbs = array(300, 400, 500);
	
	protected function setUp()
	{
	    $options = array('thumbnailer' => new Thumbnailer(), 'thumbs' => $this->thumbs);
		$this->obj = new ImageThumb($options);
	}
	
	/**
	 * @covers \Thumbnailer\Filter\File\ImageThumb::__construct()
	 * @covers \Thumbnailer\Filter\File\ImageThumb::setThumbnailer()
	 * @covers \Thumbnailer\Filter\File\ImageThumb::getThumbnailer()
	 */
	public function testServiceGetterSetter() 
	{
	    $this->assertTrue($this->obj->getThumbnailer() instanceof Thumbnailer);
	}

	/**
	 * @covers \Thumbnailer\Filter\File\ImageThumb::setThumbnailer()
	 * @expectedException \Exception
	 */
	public function testSetThumbnailerException() 
	{
	    $this->obj->setThumbnailer('this shold raise an exception');
	    $this->assertNull($this->obj->getThumbnailer());
	}

	/**
	 * @covers \Thumbnailer\Filter\File\ImageThumb::filter()
	 */
	public function testSetFilter() 
	{
	    $path = dirname(dirname(dirname(__DIR__))) . 
	        DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'berserk.png';
	    $this->obj->filter($path);
	    
	    $data = array('tmp_name' => $path);
	    $this->obj->filter($data);
	}

	/**
	 * @covers \Thumbnailer\Filter\File\ImageThumb::getThumbs()
	 */
	public function testGetterSetterThumbs() 
	{
	    $thumbs = $this->obj->getThumbs();
	    $this->assertTrue($thumbs === $this->thumbs);
	}
}