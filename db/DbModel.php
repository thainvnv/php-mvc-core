<?php 
	namespace thainv\phpmvc\db;

	use thainv\phpmvc\Application;
	use thainv\phpmvc\Model;

	abstract class DbModel extends Model
	{
		
		abstract public function attributes();
		
		public function findOne($where)
		{
			$tableName = $this->tableName();
			$attributes = array_keys($where);
			$conditions = array_map(function($attr){
				return "$attr = :$attr";
			}, $attributes);
			$conditions = implode("AND", $conditions);

		    $sql = "SELECT * FROM $tableName WHERE $conditions";
		    	
		    $statement = self::prepare($sql);
		    foreach ($where as $key => $value) {
		    	$statement->bindValue(":$key", $value);
		    }
		    $statement->execute();
		    return $statement->fetchObject(static::class);
		}

		public function save()
		{
		    $tableName = $this->tableName();
		    $attributes = $this->attributes();

		    $values = array_map(function($attr){
		    	return ':' . $attr ;
		    }, $attributes);
		    $values = implode($values, ',');
		    $sql = "INSERT INTO $tableName (". implode($attributes, ',').") VALUES ($values)";
		    $statement = self::prepare($sql);
		    foreach ($attributes as $attribute) {
		    	$statement->bindValue(":$attribute", $this->{$attribute});
		    }
		    $statement->execute();
		    return true;
		}

		protected static function prepare($sql)
		{
			return Application::$app->db->pdo->prepare($sql);
		}
	}
?>