Thumbnailer
============

A thumbnailer service module for Zend Framework 2


### Install with Composer
 ```
{
  "repositories": [
        {
            "type": "vcs",
            "url": "https://github.com/kaiohken1982/Thumbnailer.git"
        }
    ],
    "require": {
        ......,
        "razor/thumbnailer" : "dev-master"
    }
}
 ```

### How to use

In a controller

 ```
		$thumbnailer = $this->getServiceLocator()->get('Thumbnailer');
		$thumbnailer->open('\path\to\image.png');
		$thumbnailer->resize(400);
		$thumbnailer->save('\path\to\image_resized.png');
 ```