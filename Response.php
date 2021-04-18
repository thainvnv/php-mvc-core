<?php 

	namespace thainv\phpmvc;
	
	class Response
	{
		public function setStatusCode(int $code)
		{
		    http_response_code($code);
		}

		public function redirect(string $url)
		{
		    return header("Location: $url");
		}
	}

?>