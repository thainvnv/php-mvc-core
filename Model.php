<?php 
	namespace thainv\phpmvc;

	abstract class Model
	{
		public const RULE_REQUIRED = 'required';
		public const RULE_EMAIL = 'email';
		public const RULE_MIN = 'min';
		public const RULE_MAX = 'max';
		public const RULE_MATCH = 'match';
		public const RULE_UNIQUE = 'unique';
		public $errors = [];

		abstract public function tableName();
		abstract public function primaryKey();
		abstract public function getLabels(string $attribute);

		public function loadData($params)
		{
		    foreach ($params as $key => $value) {
		    	if (property_exists($this, $key)) {
		    		$this->{$key} = $value;
		    	}
		    }
		    
		}

		public function validate(array $rules)
		{
		    foreach ($rules as $attribute => $rules) {
		    	$value = $this->{$attribute};
		    	foreach ($rules as $rule) {
		    		$ruleName = $rule;

		    		if (!is_string($ruleName)) {
		    			$ruleName = $rule[0];
		    		}
		    		if ($ruleName === self::RULE_REQUIRED && empty($value)) {
		    			$this->addErrorsForRule($attribute, self::RULE_REQUIRED);
		    		}
		    		if ($ruleName === self::RULE_EMAIL && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
		    			$this->addErrorsForRule($attribute, self::RULE_EMAIL);
		    		}
		    		if ($ruleName === self::RULE_MIN && strlen($value) < $rule['min']) {
		    			$this->addErrorsForRule($attribute, self::RULE_MIN, $rule);
		    		}
		    		if ($ruleName === self::RULE_MAX && strlen($value) > $rule['max']) {
		    			$this->addErrorsForRule($attribute, self::RULE_MAX, $rule);
		    		}
		    		if ($ruleName === self::RULE_MATCH && $value !== $this->{$rule['match']}) {
		    			$rule['match'] = $this->getLabels($rule['match']);
		    			$this->addErrorsForRule($attribute, self::RULE_MATCH, $rule);
		    		}
		    		if ($ruleName === self::RULE_UNIQUE ) {

		    			$className = "\\app\\models\\" .$rule['class'];
		    			$attribute = $rule['attribute'] ?? $attribute;
		    			$tableName = $className::tableName();

		    			$sql = "SELECT * FROM $tableName WHERE $attribute = :attr";
		    			$statement = Application::$app->db->pdo->prepare($sql);
		    			$statement->bindValue(':attr', $value);
		    			$statement->execute();

		    			if ($statement->fetchObject()) {
		    				$this->addErrors($attribute, self::RULE_UNIQUE, ['field' => $this->getLabels($attribute)]);
		    			}
		    			
		    		}


		    	}
		    }
		    return empty($this->errors);
		}


		private function addErrorsForRule(string $fieldName, string $rule, array $params = [])
		{
			$message = $this->errorMessage()[$rule] ?? '';
			foreach ($params as $key => $value) {
				$message = str_replace("{{$key}}", $value, $message);
			}
		    $this->errors[$fieldName][] = $message;
		}

		public function addErrors(string $fieldName, string $message)
		{
		    $this->errors[$fieldName][] = $message;
		}


		public function errorMessage()
		{
		    return [
		    	self::RULE_REQUIRED => 'This field is required',
		    	self::RULE_EMAIL => 'This field must be valid email address',
		    	self::RULE_MIN => 'Min length of this field is {min}',
		    	self::RULE_MAX => 'Max length of this field is {max}',
		    	self::RULE_MATCH => 'This field must be the same as {match}',
		    	self::RULE_UNIQUE => 'Record with this {field} is already exists.'
		    ];
		}

		public function hasError(string $attribute)
		{
		    return $this->errors[$attribute] ?? false;
		}

		public function getFirstError(string $attribute)
		{
		    return $this->errors[$attribute][0] ?? '';
		}
	}

?>