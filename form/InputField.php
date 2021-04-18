<?php 

	namespace thainv\phpmvc\form;

	use thainv\phpmvc\Model;

	class InputField extends BaseField
	{
		public const TYPE_TEXT = 'text';
		public const TYPE_EMAIL = 'email';
		public const TYPE_PASSWORD = 'password';
		public const TYPE_NUMBER= 'number';
		
		public $type;


		public function __construct(Model $model, string $attribute)
		{
		  	parent::__construct($model, $attribute);
			$this->type = self::TYPE_TEXT;
		}

		public function renderInput()
		{
		    return sprintf('<input type="%s" id="%s" name="%s" value="%s" class="form-control%s" >',
		    	$this->type,
		    	$this->attribute,
		    	$this->attribute,
		    	$this->model->{$this->attribute},
		    	$this->model->hasError($this->attribute) ? ' is-invalid' : ''

			);
		}
		
		public function setEmailField()
		{
			$this->type = self::TYPE_EMAIL;
			return $this;
		}

		public function setPasswordField()
		{
			$this->type = self::TYPE_PASSWORD;
			return $this;
		}

		public function setNumberField()
		{
			$this->type = self::TYPE_NUMBER;
			return $this;
		}


	}

?>