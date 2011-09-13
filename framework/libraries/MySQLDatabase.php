<?php namespace Pizza;

// TODO: MAKE BASE DATABASE CLASS

class MySQLDatabase
{
	private $con;
	private $server;
	private $username;
	private $password;
	private $database;
	
	private $connected;

	function __construct($server, $username, $password, $database) {
		$this->server = $server;
		$this->username = $username;
		$this->password = $password;
		$this->database = $database;
		
		$this->connected = false;
	}
	
	private function connect() {
		if($this->connected==false) {
			$this->con = mysql_connect($this->server, $this->username, $this->password);
			
			if ($this->con==false) {
				// replace with throw exception
				die('Could not connect: ' . mysql_error());
			}
			
			mysql_select_db($this->database, $con);
			$this->connected = true;
		}
	}
	
	private function disconnect() {
		if($this->connected==true) {
			mysql_close($this->con);
			$this->connected = false;
		}
	}
	
	public function query($sql) {
		$this->connect();
		return mysql_query($sql);
	}
	
	public function selectRange($columns, $table, $start, $end, $condition = '', $result_type = MYSQL_ASSOC) {
		$columns = $this->escapeString($columns);
		$sql = "SELECT $columns FROM $table";
		
		if(!empty($condition)) {
			$sql .= " WHERE $condition";
		}
		
		$sql .= ' LIMIT '.$start.', '.($end-start+1).';';
		$result = mysql_query($sql);
		$row = mysql_fetch_array($result, $result_type);
		mysql_free_result($result);
		
		return $row;
	}
	
	public function selectFirst($columns, $table, $condition = '', $result_type = MYSQL_ASSOC) {
		return $this->selectRange($columns, $table, 0, 0, $condition, $result_type);
	}
	
	public function selectPage($columns, $table, $page, $rowsperpage, $condition = '', $result_type = MYSQL_ASSOC) {
		$start = (intval($page) - 1) * intval($rowsperpage);
		return $this->selectRange($columns, $table, $start, $rowsperpage, $condition, $result_type);
	}
	
	public function count($table, $condition = '') {
		$sql = "SELECT COUNT(*) AS total FROM $table";
		
		if(!empty($condition)) {
			$sql .= " WHERE $condition";
		}
		
		$sql .= ' LIMIT 1;';
		$result = mysql_query($sql);
		$row = mysql_fetch_assoc($result);
		mysql_free_result($result);
		
		return $row['total'];
	}
	
	public function escapeString($string) {
		if(empty($string)) {
			return $string;
		}
	
		if(is_array($string)) {
			return array_map($this->escapeString, $string);
		}
	
		$this->connect();
		return mysql_real_escape_string($string, $this->con);
	}
	
	public function log($string, $type) {
		$type = intval($type);
		$string = $this->escapeString($string);
		
		return $this->query("INSERT INTO PizzaLog (value, type) VALUES ($string, $type);");
	}
	
	function __deconstruct() {
		$this->disconnect();
	}
}

?>