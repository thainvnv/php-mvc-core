<?php 

	namespace thainv\phpmvc\middlewares;
	
	use thainv\phpmvc\Exception\FobbidenException;
	use thainv\phpmvc\Application;

	class AuthMiddleware extends BaseMiddleware
 	{
 		public $actions;

 		public function __construct(array $actions = [])
 		{
 		    $this->actions = $actions;
 		}

	    public function execute()
	    {
	        if (Application::isGuest()) {
	        	if (!empty($this->actions) && in_array(Application::$app->controller->action, $this->actions)) {
	        		Application::$app->response->setStatusCode(403);
	        		throw new FobbidenException();
	        		
	        	}
	        }
	    }
	}
	
?>