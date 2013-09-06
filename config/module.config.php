<?php
namespace Thumbnailer;

return array(
	'service_manager' => array(
		'factories' => array(
			'Thumbnailer\Thumbnailer\Thumbnailer' => new Service\ThumbnailerFactory()
		),
		'aliases' => array(
			'Thumbnailer' => 'Thumbnailer\Thumbnailer\Thumbnailer'
		)
	)
);