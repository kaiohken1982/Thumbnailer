<?php 
namespace Thumbnailer\Thumbnailer\Exception;

class GDLibraryMissingException 
	extends \Exception
{
	protected $message = 'This class requires GD Libraries to be installed. Visit http://us2.php.net/manual/en/ref.image.php for more information.';
}