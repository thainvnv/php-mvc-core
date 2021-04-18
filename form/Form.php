<?php 
	
	namespace thainv\phpmvc\form;
	use thainv\phpmvc\Model;
	
	class Form
	{

		public static function begin(string $action, string $method)
		{
		    echo sprintf('<form action="%s" method="%s" >', $action, $method);
		    return new Form();
		}

		public static function end()
		{
		    echo "</form>";
		}

		public function field(Model $model, string $attribute)
		{
		    return new InputField($model, $attribute);
		}

		public function textareaField(Model $model, string $attribute)
		{
		    return new TextareaField($model, $attribute);
		}
	}

?>