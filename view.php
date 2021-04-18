<?php 
	namespace app\core;
	
	class View
	{
	    public $title = '';

	    public function renderView($view, $params = []) {
	    		
	    	$viewContent = $this->renderOnlyView($view, $params);
	    
	    	$layoutContent = $this->layoutContent();

	    	return str_replace('{{content}}', $viewContent, $layoutContent);
	    }

	    protected function layoutContent() {
	    	$layout = 'main';
	    	if (Application::$app->controller) {
	    		$layout = Application::$app->controller->layout;
	    	}
	    	
	    	ob_start();
	    	include_once Application::$ROOT_DIR . "/views/layouts/$layout.php";
	    	return ob_get_clean();
	    }

	    protected function renderOnlyView($view, $params = []) {
	    	extract($params);
	    	ob_start();
	    	include_once Application::$ROOT_DIR . "/views/$view.php";
	    	return ob_get_clean();
	    }
	}
	
?>