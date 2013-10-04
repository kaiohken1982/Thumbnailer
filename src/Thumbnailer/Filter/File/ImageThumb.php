<?php

namespace Thumbnailer\Filter\File;

use Zend\Filter\AbstractFilter;

use Thumbnailer\Thumbnailer\Thumbnailer;

class ImageThumb 
	extends AbstractFilter
{
	protected $thumbnailer;
	
	protected $options = array();
	
	public function __construct($options)
	{
		if(isset($options['thumbnailer'])) {
			$this->setThumbnailer($options['thumbnailer']);
			unset($options['thumbnailer']);
		}
		
		$this->options = $options;
	}
	
    protected function setThumbnailer(Thumbnailer $thumbnailer)
    {
        $this->thumbnailer = $thumbnailer;
        
        return $this;
    }
	
    protected function getThumbnailer()
    {
        return $this->thumbnailer;
    }
    
    /**
     * Get thumbs to create.
     * This returns an array of integers that represent 
     * the size of the image(s) to be creted.
     * Default value is to create only one thumb with 150px width.
     * 
     * @return array
     */
    protected function getThumbs() 
    {
    	if(isset($this->options['thumbs'])) {
    		return $this->options['thumbs'];
    	}
    	
    	return array(150);
    }
	
    /**
     * @param  string $value
     * @return string|mixed
     */
    public function filter($value)
    {
    	$isFile = false;
    	if(is_array($value) && isset($value['tmp_name'])) {
    		$filtered = $value['tmp_name'];
    		$isFile = true;
    	} else {
    		$filtered = $value;
    	}
    	
    	$basename = basename($filtered);
    	$dirname = dirname($filtered);
    	
		$thumbnailer = $this->getThumbnailer();
		$thumbnailer->open($filtered);
		
		foreach($this->getThumbs() as $thumb) {
			$thumbnailer->resize($thumb);
			$thumbnailer->save($dirname . '/' . $thumb . '_' . $basename);
		}
        
        return $value;
    }
}
