<?php

namespace Thumbnailer\Filter\File;

use Zend\Filter\AbstractFilter;

use Thumbnailer\Thumbnailer\Thumbnailer;

class ImageThumb 
	extends AbstractFilter
{
	/**
	 * Filter options
	 * @var array
	 */
	protected $options = array(
		'thumbnailer' => null,
		'thumbs' => array(150),
	);
	
	/**
	 * Set filter options
	 * @param array $options
	 */
	public function __construct($options)
	{
		$this->setOptions($options);
	}
	
	/**
	 * Set the thumbnailer given with the options
	 * @throws \Exception
	 * @return Cropper\Filter\File\ImageCrop
	 */
    public function setThumbnailer(Thumbnailer $thumbnailer)
    {
        $this->options['thumbnailer'] = $thumbnailer;
        
        return $this;
    }
    
    /**
     * Get thumbnailer service
     * @return Thumbnailer\Thumbnailer\Thumbnailer;
     */
    public function getThumbnailer() 
    {
    	return $this->options['thumbnailer'];
    }
    
    /**
     * Get thumbs to create.
     * This returns an array of integers that represent 
     * the size of the image(s) to be creted.
     * Default value is to create only one thumb with 150px width.
     * 
     * @return array
     */
    public function getThumbs() 
    {
    	return $this->options['thumbs'];
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
