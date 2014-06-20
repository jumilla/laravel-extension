<?php namespace Jumilla\LaravelExtension\Generators;
 
use DB;
use Str;

/**
* Migration generate from MySQL 
* Original:
*	for Laravel3
*   http://laravelsnippets.com/snippets/convert-an-existing-mysql-database-to-migrations
*
* Usage:
*   // When run from a controller.
*   $migrate = MigrationGenerator_Mysql::ignore(['some_table'])
*       ->convert('datebase')
*       ->write();
*
* @author @michaeljcalkins
* @author Fumio Furukawa <fumio.furukawa@gmail.com>
*/
class MigrationGenerator_Mysql
{   
	private static $ignore = ['migrations'];
		
	private static $database = "";
	
	private static $migrations = false;
	
	private static $schema = [];
	
	private static $selects = ['column_name as Field', 'column_type as Type', 'is_nullable as Null', 'column_key as Key', 'column_default as Default', 'extra as Extra', 'data_type as Data_Type'];
	
	private static $instance;
	
	private static $up = "";
	
	private static $down = "";
	
	private static function getTables($database)
	{
		return DB::select(
			'SELECT table_name FROM information_schema.tables WHERE table_schema="' . $database . '"'
		); 
	}
 
	private static function getTableDescribes($table)
	{
		return DB::table('information_schema.columns')
			->where('table_schema', '=', self::$database)
			->where('table_name', '=', $table)
			->get(self::$selects);
	}
	
	private static function compileSchema()
	{
		$upSchema = "";
		$downSchema = "";
		$newSchema = "";
	 
		foreach (self::$schema as $name => $values) {
			if (in_array($name, self::$ignore)) {
				continue;   
			}
			
			$upSchema .= "
		//
		// NOTE -- {$name}
		// --------------------------------------------------
 
		{$values['up']}";   
			
			$downSchema .= "
		{$values['down']}";
		}
 
	
		$schema = "<?php
 
//
// NOTE Migration Created: " . date("Y-m-d H:i:s") . "
// --------------------------------------------------        
 
class Create_" . Str::title(self::$database) . "_Database {
	
	//
	// NOTE - Make changes to the database.
	// --------------------------------------------------
 
	public function up()
	{
		" . $upSchema . "
		" . self::$up . "
	}
 
	//
	// NOTE - Revert the changes to the database.
	// --------------------------------------------------
 
	public function down()
	{
		" . $downSchema . "
		" . self::$down . "
	}
}";
 
		return $schema;
	}
	
	public static function up($up) 
	{
		self::$up = $up;
		
		return self::$instance;
	}
	
	public static function down($down) 
	{
		self::$down = $down;
		
		return self::$instance;
	}
	
	public static function ignore($tables)
	{
		self::$ignore = array_merge($tables, self::$ignore);
		
		return self::$instance;
	}
	
	public function migrations()
	{
		self::$migrations = true;
		
		return self::$instance;
	}
	
	public function write()
	{
		$schema = self::compileSchema();

		$filename = date('Y_m_d_His') . "_create_" . self::$database . "_database.php";
 
		file_put_contents(app_path() . "/database/migrations/{$filename}", $schema);
	}

	public function get()
	{
		return self::compileSchema();
	}
	
	public static function convert($database)
	{
		self::$instance = new self();    
		
		self::$database = $database;
		$table_headers = array('Field', 'Type', 'Null', 'Key', 'Default', 'Extra');
		$tables = self::getTables($database);
		echo count($tables), "\n";

		foreach ($tables as $key => $value) {
			echo sprintf('Table %s: ', $value->table_name);

			if (in_array($value->table_name, self::$ignore)) {
				echo 'Ignored.', "\n";
				continue;   
			}
 
			$down = "Schema::drop('{$value->table_name}');";
			$up = "Schema::create('{$value->table_name}', function($" . "table) {\n";
			$tableDescribes = self::getTableDescribes($value->table_name);
	
			foreach($tableDescribes as $values) {
				$method = "";
				$para = strpos($values->type, '(');
				$type = $para > -1 ? substr($values->type, 0, $para) : $values->type;
				
				$numbers = "";
				$nullable = $values->null == "NO" ? "" : "->nullable()";
				$default = empty($values->default) ? "" : "->default(\"{$values->default}\")";
				$unsigned = strpos($values->type, "unsigned") === false ? '' : '->unsigned()';
	
				switch($type) {
					case 'int' :
						$method = 'integer';
						break;
	
					case 'char' :
					case 'varchar' :
						$para = strpos($values->type, '(');
						$numbers = ", " . substr($values->type, $para + 1, -1);
						$method = 'string';
						break;
		
					case 'float' :
						$method = 'float';
						break;
					
					case 'decimal' :
						$para = strpos($values->type, '(');
						$numbers = ", " . substr($values->type, $para + 1, -1);
						$method = 'decimal';
						break;
					
					case 'tinyint' :
						$method = 'boolean';
						break;
					
					case 'timestamp' :
					case 'datetime' :
						$method = 'date';
						break;
					
					case 'mediumtext' :
						$method = 'mediumtext';
						break;
					
					case 'text' :
						$method = 'text';
						break;
				}
				
				if ($values->key == 'PRI') {
					$method = 'increments';   
				}
				
			$up .= "            $" . "table->{$method}('{$values->field}'{$numbers}){$nullable}{$default}{$unsigned};\n";
	
			}
	
			$up .= "        });\n\n";
			
			self::$schema[$value->table_name] = array(
				'up' => $up,
				'down' => $down
			);

			echo sprintf('OK.'), "\n";
		}

		echo 'Done.', "\n";
		
		return self::$instance;
	}

}
