Image Thumbnailer Module
========================

[![Build Status](https://travis-ci.org/kaiohken1982/Thumbnailer.png)](https://travis-ci.org/kaiohken1982/Thumbnailer)
[![Coverage Status](https://coveralls.io/repos/kaiohken1982/Thumbnailer/badge.png)](https://coveralls.io/r/kaiohken1982/Thumbnailer)
[![Dependency Status](https://www.versioneye.com/user/projects/52b17633ec1375723700004e/badge.png)](https://www.versioneye.com/user/projects/52b17633ec1375723700004e)
[![Latest Stable Version](https://poser.pugx.org/razor/thumbnailer/v/stable.png)](https://packagist.org/packages/razor/thumbnailer)
[![Total Downloads](https://poser.pugx.org/razor/thumbnailer/downloads.png)](https://packagist.org/packages/razor/thumbnailer)
[![Latest Unstable Version](https://poser.pugx.org/razor/thumbnailer/v/unstable.png)](https://packagist.org/packages/razor/thumbnailer)

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

### Run unit test
 
Please note you must be in the module root.

```
curl -s http://getcomposer.org/installer | php
php composer.phar install
cd tests
../vendor/bin/phpunit 
```

If you have xdebug enabled and you want to see code coverage 
run the command below, it'll create html files in 
Watermarker\test\data\coverage

```
../vendor/bin/phpunit --coverage-html data/coverage
```
```