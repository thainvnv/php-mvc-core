<?php 
	
	namespace thainv\phpmvc;

	use app\models\User;
	use thainv\phpmvc\db\Database;
	

	class Application
	{
		public static $ROOT_DIR;
		public static $app;
		public $router;
		public $request;
		public $response;
		public $session;
		public $controller;
		public $view;
		public $db;
		public $user = null;

		public function __construct($rootPath, array $config){
			self::$ROOT_DIR = $rootPath;
			self::$app = $this;
			$this->request = new Request();
			$this->response = new Response();
			$this->session = new Session();
			$this->router = new Router($this->request, $this->response);
			$this->view = new View();
			$this->db = new Database($config['db']);

			
			$primaryValue = $this->session->get('user');

			if ($primaryValue) {
				$this->user =  new $config['userClass'];
				$primaryKey  = $this->user->primaryKey();
				$this->user = $this->user->findOne([$primaryKey => $primaryValue]);
			}
			
			
		}

		public function run()
		{
			try {
				echo $this->router->resolve();
			} catch (\Exception $e) {

				echo $this->router->renderView('_'. $e->getCode());
			}
		   
		}

		public function login(User $user)
		{
			$this->user = $user;
		    $primaryKey = $this->user->primaryKey();
		    $primaryValue = $this->user->{$primaryKey};
		    $this->session->set('user', $primaryValue);
		    return true;
		}

		public function logout()
		{
		    $this->user = null;
		    $this->session->destroy('user');
		}

		public static function isGuest()
		{
		    return !self::$app->user;
		}

	}
?>