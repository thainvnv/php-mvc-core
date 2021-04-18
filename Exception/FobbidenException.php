<?php 
	namespace app\core\Exception;
	
	class FobbidenException extends \Exception
	{
	    protected $code = 403;
	    protected $message = 'You don\'t have permission to get this page.'; 
	}
	

?>