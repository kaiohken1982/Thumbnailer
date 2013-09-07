<?php
namespace Thumbnailer\Thumbnailer;

use Thumbnailer\Thumbnailer\Exception\GDLibraryMissingException;
use Thumbnailer\Thumbnailer\Exception\NoSourceException;

/**
 * Resize an image
 *
 * @Version 1.0
 * @author Sergio Rinaudo
 */
class Thumbnailer 
	implements ThumbnailerInterface
{
	/**
	 * Quality of the resized image
	 * @var int
	 */
	private $quality = 100;
	
	/**
	 * The opened (source) image
	 * @var string
	 */
	private $sourceImagePath;
	
	/**
	 * The informations extracted from the source image
	 * @var null|array
	 */
	private $imageInfo;
	
	/**
	 * Image extension
	 * @var string
	 */
	private $extension;
	
	/**
	 * Source Image resource
	 * @var resource
	 */
	private $sourceImageResource;
	
	/**
	 * Destination Image resource
	 * @var resource
	 */
	private $destImageResource;
	
	/**
	 * Destination image calculate width
	 * @var int
	 */
	private $destImageWidth;
	
	/**
	 * Destination image calculated height
	 * @var int
	 */
	private $destImageHeight;
	
	/**
     * Class construct.
     * 
     * @throws GDLibraryMissingException
     */
    public function __construct() 
    {
        if(!function_exists("gd_info")) {
            throw new GDLibraryMissingException();
        }
    }
    
    /**
     * (non-PHPdoc)
     * @see \Thumbnailer\Thumbnailer\ThumbnailerInterface::parseConfig()
     */
    public function parseConfig($config) 
    {
    	if(isset($config['quality'])) {
			$this->setQuality($config['quality']);
    	}
    }
    
    /**
     * Set the quality value
     * 
     * @return \Thumbnailer\Thumbnailer\Thumbnailer
     */
    public function setQuality($quality) 
    {
    	$this->quality = $quality;
    }
    
    /**
     * Get the quality value
     * 
     * @return int
     */
    public function getQuality()
    {
    	return $this->quality;
    }
    
    /**
     * Set extension value
     * 
     * @return \Thumbnailer\Thumbnailer\Thumbnailer
     */
    public function setExtension($extension) 
    {
    	$this->extension = strtolower($extension);
    }
    
    /**
     * Get extension value
     * 
     * @return string
     */
    public function getExtension()
    {
    	return $this->extension;
    }
    
    /**
     * (non-PHPdoc)
     * @see \Thumbnailer\Thumbnailer\ThumbnailerInterface::open()
     */
    public function open($sourceImagePath) 
    {
        if(!file_exists($sourceImagePath) || !is_readable($sourceImagePath)) {
            throw new \RuntimeException("File '" . $sourceImagePath . "' does *not* exists OR is *not* readable.");
        }
    	$this->sourceImagePath = $sourceImagePath;
    	$this->parseImageInfo()
    		->initSourceImageResource();
    	
    	return $this;
    }
    
    /**
     * (non-PHPdoc)
     * @see \Thumbnailer\Thumbnailer\ThumbnailerInterface::parseImageInfo()
     */
    public function parseImageInfo() 
    {
    	if (null === $this->sourceImagePath) {
    		throw new NoSourceException();
    	}
    	
    	$pathinfo = pathinfo($this->sourceImagePath);
    	if (!is_array($pathinfo)) {
    		throw new \RuntimeException("It is not possible to correctly get pathinfo for the image file '" . $this->sourceImagePath . "' ");
    	}
        
        $imagesize = getimagesize($this->sourceImagePath);
        if (!is_array($imagesize)) {
            throw new \RuntimeException("It is not possible to correctly get imagesize for the image file '" . $this->sourceImagePath . "' ");
        }
    	
    	$info = $pathinfo + $imagesize;
        
        if (isset($info[0])) {
            $info['width'] = $info[0];
        } else {
            throw new \RuntimeException("Unable to get width for the image file '" . $this->sourceImagePath . "' ");
        }
        
        if (isset($info[1])) {
            $info['height'] = $info[1];
        } else {
            throw new \RuntimeException("Unable to get height for the image file '" . $this->sourceImagePath . "' ");
        }
        
        if (!isset($info['mime'])) {
            throw new \RuntimeException("Unable to get mimetype for the image file '" . $this->sourceImagePath . "' ");
        }

        unset($info[0], $info[1], $info[2], $info[3]);
        
        switch(strtolower($info['mime'])) {
		case 'image/png':
			$info['extension'] = 'png';
            break;
            
		case 'image/jpeg':
			$info['extension'] = 'jpg';
            break;
            
		case 'image/gif':
			$info['extension'] = 'gif';
            break;
            
		default:
			throw new \RuntimeException("Unable to get extension for the image file '" . $this->sourceImagePath . "' ");
            break;
        }
    	
        // We set the default width/height values for the destination image if resize is not called
        $this->destImageWidth = $info['width'];
        $this->destImageHeight = $info['height'];
        
        $this->setExtension($info['extension']);
        $this->imageInfo = $info;
        
    	return $this;
    }
    
    /**
     * Get the source image info
     * 
     * @return array
     */
    public function getImageInfo() 
    {
    	return $this->imageInfo;
    }
    
    /**
     * Get the source resource image
     * 
     * @throws \RuntimeException
     * @return resource
     */
    public function getSourceImageResource() 
    {
		return $this->sourceImageResource;
    }
    
    /**
     * Init the source image resource
     * 
     * @throws \RuntimeException
     * @return \Thumbnailer\Thumbnailer\Thumbnailer
     */
    protected function initSourceImageResource() 
    { 
    	$ext = $this->getExtension();
    	
    	switch($ext) {
		case 'gif':
			$this->sourceImageResource = ImageCreateFromGif($this->sourceImagePath);
			break;
    	
		case 'jpg':
			$this->sourceImageResource = ImageCreateFromJpeg($this->sourceImagePath);
			break;
    	
		case 'png':
			$this->sourceImageResource = ImageCreateFromPng($this->sourceImagePath);
			break;
    	
		default:
			throw new \RuntimeException("An error occurred trying to create the image, extension '" . $ext. "' is not supported");
			break;
    	}
    	
    	return $this;
    }
    
    /**
     * Init the destination resource image.
     * This is an empty image and use the width and height values 
     * of the source image if resize is not called by the user
     * 
     * @throws \RuntimeException
     * @return resource
     */
    protected function initDestImageResource() 
    {
    	// gif does not supports truecolor for resize
    	if (function_exists("ImageCreateTrueColor") && $this->getExtension() != 'gif') {
    		$this->destImageResource = ImageCreateTrueColor($this->destImageWidth, $this->destImageHeight);
    	} else {
    		$this->destImageResource = ImageCreate($this->destImageWidth, $this->destImageHeight);
    	}
    	
    	return $this;
    } 
    
    /**
     * (non-PHPdoc)
     * @see \Thumbnailer\Thumbnailer\ThumbnailerInterface::resize()
     */
    public function resize($width = 0, $height = 0) 
    {
        if ($width != 0 || $height != 0) {
            $width = $this->getNewWidth($width, $height);
            $height = $this->getNewHeight($width, $height);
        } else {
            $width = $this->imageInfo['width'];
            $height = $this->imageInfo['height'];
        }

        $this->destImageWidth = $width;
        $this->destImageHeight = $height;
        
        return $this;
    }
    
    /**
     * (non-PHPdoc)
     * @see \Thumbnailer\Thumbnailer\ThumbnailerInterface::render()
     */
    public function render($destImagePath = null) 
    {
    	$this->initDestImageResource();
    	$this->applyTransparency();
    	
    	ImageCopyResampled(
	    	$this->destImageResource,
	    	$this->sourceImageResource,
	    	0, 0, 0, 0,
	    	$this->destImageWidth,
	    	$this->destImageHeight,
	    	$this->imageInfo['width'],
	    	$this->imageInfo['height']
    	);
    	
        // Let's try to delete the image if already exists
        if(null !== $destImagePath) {
            if (is_file($destImagePath) && file_exists($destImagePath)) {
                if (!unlink($destImagePath)) {
                    throw new \RuntimeException("File '" . $destImagePath . "' already exists and it is *not* writeable");
                }
            }
        } else {
            header('Content-type: ' . $this->imageInfo['mime']);
        }
        
		switch($this->getExtension()) {
		case 'gif':
			return ImageGif($this->destImageResource, $destImagePath);
			break;
            
		case 'jpg':
			return ImageJpeg($this->destImageResource, $destImagePath, $this->getQuality());
            break;
            
		case 'png':
			return ImagePng($this->destImageResource, $destImagePath);
            break;
        }
        
        return false;
    }
    
    /**
     * (non-PHPdoc)
     * @see \Thumbnailer\Thumbnailer\ThumbnailerInterface::save()
     */
    public function save($destImagePath) 
    {
    	return $this->render($destImagePath);
    }
    
    /**
     * Returns the new $width value
     *
     * @param int $width
     * @param int $height
     * @return int
     */
    protected function getNewWidth($width, $height) 
    {
        if ($width > 0) {
            return $width;
        }
        
        // original height : new height = original width : X
        return $height * $this->imageInfo['width'] / $this->imageInfo['height'];
    }
    
    /**
     * Returns the new $height value
     *
     * @param int $width
     * @param int $height
     * @return int
     */
    protected function getNewHeight($width, $height) 
    {
        if ($height > 0) {
            return $height;
        }
        
        // original width : new width = original height : X
        return $width * $this->imageInfo['height'] / $this->imageInfo['width'];
    }
    
    /**
     * This is needed to keep trasparency, for more information visit
     * http://www.php.net/manual/en/function.imagecolortransparent.php
     *
     * @return \Thumbnailer\Thumbnailer\Thumbnailer
     */
    protected function applyTransparency() 
    {
        $transparencyIndex = imagecolortransparent($this->sourceImageResource);
        $transparencyColor = array('red' => 255, 'green' => 255, 'blue' => 255);
        
        if ($transparencyIndex >= 0) {
            $transparencyColor = imagecolorsforindex($this->sourceImageResource, $transparencyIndex);   
        }
        
        $transparencyIndex = imagecolorallocate($this->destImageResource, 
        		$transparencyColor['red'], $transparencyColor['green'], $transparencyColor['blue']);
        imagefill($this->destImageResource, 0, 0, $transparencyIndex);
        imagecolortransparent($this->destImageResource, $transparencyIndex);
        
        return $this;
    } 
}