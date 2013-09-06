<?php 
namespace Thumbnailer\Thumbnailer\Exception;

class NoSourceException 
	extends \Exception
{
	protected $message = 'No source image defined. Call Thumbnailer::open first.';
}