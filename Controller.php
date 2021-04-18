<?php 
	namespace app\core;

	use app\core\Application;
	use app\core\middlewares\BaseMiddleware;


	class Controller
	{
		public const RULE_REQUIRED = 'required';
		public const RULE_EMAIL = 'email';
		public const RULE_MIN = 'min';
		public const RULE_MAX = 'max';
		public const RULE_MATCH = 'match';
		public const RULE_UNIQUE = 'unique';
		
		public $layout = 'main';
		public $action = '';

		protected $middlewares = [];
		
		public function setLayout($layout)
		{
		    $this->layout = $layout;
		}
		public function render($view, $params = [])
		{
		    return Application::$app->router->renderView($view, $params);
		}

		public function registerMiddleware(BaseMiddleware $middleware)
		{
		    $this->middlewares[] = $middleware;
		}

		public function getMiddleware()
		{
		    return $this->middlewares;
		}
	}
?>