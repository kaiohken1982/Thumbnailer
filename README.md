[![Build Status](https://travis-ci.org/kaiohken1982/Thumbnailer.png)](https://travis-ci.org/kaiohken1982/Thumbnailer) - [![Dependency Status](https://www.versioneye.com/user/projects/52b17633ec1375723700004e/badge.png)](https://www.versioneye.com/user/projects/52b17633ec1375723700004e)

Image Thumbnailer Module
========================

An image thumbnailer service module for Zend Framework 2


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
 
### Run unit test
 
Please note you must be in the module root.

```
curl -s http://getcomposer.org/installer | php
php composer.phar install
cd tests
../vendor/bin/phpunit -c phpunit.xml.dist
```