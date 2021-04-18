<?php 

	namespace app\core\db;

	use app\core\Application;
	

	class Database
	{
		public $pdo;

		public function __construct(array $config)
		{
			$dsn = $config['dsn'] ?? '';
			$username = $config['username'] ?? '';
			$password = $config['password'] ?? '';

		    $this->pdo = new \PDO($dsn, $username, $password);
		    $this->pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
		}

		public function applyMigrations()
		{
	
		    $this->createMigrationsTable();
		    $appliedMigrations = $this->getAppliedMigrations();

		    $newMigrations = [];
		    $files = scandir(Application::$ROOT_DIR . '/migrations');
		    $toAppliedMigrations = array_diff($files, $appliedMigrations);

		    foreach ($toAppliedMigrations as $migration) {
		    	if ($migration == '.' || $migration == '..') {
		    		continue;
		    	}
		    		
		    	$className = 'app\\migrations\\'. pathinfo($migration, PATHINFO_FILENAME);
		    
		    	$instance = new $className();
		    	echo $this->log("Applying migration $migration");
		    	$instance->up();
		    	echo $this->log("Applied migration $migration");
		    	$newMigrations[] = $migration;

		    }
		    if (!empty($newMigrations)) {
		    	$this->saveMigrations($newMigrations);
		    } else {
		    		
		    	echo $this->log('All migrations are applied.');
		    }
		}


		public function createMigrationsTable()
		{
		    $this->pdo->exec("CREATE TABLE IF NOT EXISTS migrations (
		    	id INT AUTO_INCREMENT PRIMARY KEY,
		    	migration VARCHAR(255),
		    	created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
			)ENGINE=INNODB;");
		}

		public function getAppliedMigrations()
		{
		    $statement = $this->pdo->prepare("SELECT migration FROM migrations");
		    $statement->execute(); 

		    return $statement->fetchAll(\PDO::FETCH_COLUMN);
		}

		public function saveMigrations(array $migrations)
		{
			$str = array_map(function($m){
					return "('$m')";
						}, $migrations);

			$value = implode($str, ',');
		    $statement = $this->pdo->prepare("INSERT INTO migrations (migration) VALUES $value");
		    $statement->execute();
		}

		protected function log(string $message)
		{
			return '['. date('Y-m-d H:i:s') . '] ' . $message . PHP_EOL;
		}
	}
?>