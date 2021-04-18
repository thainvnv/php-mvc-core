<?php 

	namespace app\core\form;

	use app\core\Model;


	class TextareaField extends BaseField
	{
	  
	  	public function __construct(Model $model, string $attribute)
		{
		  	parent::__construct($model, $attribute);

		}

		public function renderInput()
		{
		    return sprintf('<textarea class="form-control%s" cols="5" rows="7" id="%s" name="%s">%s</textarea>',
		    	$this->model->hasError($this->attribute) ? ' is-invalid' : '',
		    	$this->attribute,
		    	$this->attribute,
		    	$this->model->{$this->attribute}

			);
		}
	}
	
?>