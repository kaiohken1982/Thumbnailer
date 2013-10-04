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
		$this->options = $options;
	}
	
	/**
	 * Get the thumbnailer given with the options
	 * @throws \Exception
	 * @return Thumbnailer\Thumbnailer\Thumbnailer
	 */
    protected function getThumbnailer()
    {
    	if(!$this->options['thumbnailer'] instanceof Thumbnailer) {
    		throw new \Exception('The thumbnailer service given is not instance of Thumbnailer\Thumbnailer\Thumbnailer');
    	}
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
    protected function getThumbs() 
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
