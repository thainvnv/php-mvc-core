<?php 
	namespace thainv\phpmvc\Exception;
	
	class FobbidenException extends \Exception
	{
	    protected $code = 403;
	    protected $message = 'You don\'t have permission to get this page.'; 
	}
	

?>