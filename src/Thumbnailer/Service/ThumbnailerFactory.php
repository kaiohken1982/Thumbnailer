<?php 
namespace Thumbnailer\Service;

use Thumbnailer\Thumbnailer\Thumbnailer;
use Zend\ServiceManager\ServiceLocatorInterface,
	Zend\ServiceManager\FactoryInterface;

class ThumbnailerFactory
	implements FactoryInterface
{
    /**
     *
     * @param \Zend\ServiceManager\ServiceLocatorInterface $serviceLocator
     * @return \Thumbnailer\Thumbnailer\Thumbnailer
     */
    public function createService(ServiceLocatorInterface $sl)
    {
		$config = $sl->get('Configuration');
        $thumbnailerConfig = isset($config['thumbnailer']) ? $config['thumbnailer'] : array();
        $thumbnailer = new Thumbnailer();
        $thumbnailer->parseConfig($thumbnailerConfig);
		
		return $thumbnailer;
    }
}