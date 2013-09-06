<?php 
namespace Thumbnailer\Thumbnailer;

interface ThumbnailerInterface 
{
	/**
	 * Configure object params
	 * 
	 * @param array $config
     * @return \Thumbnailer\Thumbnailer\Thumbnailer
	 */
	function parseConfig($config);
	
	/**
	 * Open an image to be elaborated
	 * 
	 * @param string $imagePath
	 * @throws \RuntimeException
	 * @return \Thumbnailer\Thumbnailer\ThumbnailerInterface
	 */
	function open($sourceImagePath);
	
	/**
	 * Parse the informations from the source image
	 * 
	 * @throws \Thumbnailer\Thumbnailer\Exception\NoSourceException
	 * @return \Thumbnailer\Thumbnailer\ThumbnailerInterface
	 */
	function parseImageInfo();
	
	/**
	 * Resize the opened image with the passed params
	 * 
	 * @param number $width
	 * @param number $height
	 * @return \Thumbnailer\Thumbnailer\ThumbnailerInterface
	 */
	function resize($width = 0, $height = 0);
	
	/**
	 * Render the resized image if no param passed.
	 * Otherwise will act as save
	 * 
	 * @param string $destImagePath
	 * @return bool
	 */
	function render($destImagePath = null);
	
	/**
	 * Save the image to the given path
	 * 
	 * @param string $destImagePath
	 * @return bool
	 */
	function save($destImagePath);
}