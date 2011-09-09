<?php namespace Pizza;

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
	
	public function selectFirst($columns, $table, $condition = '', $result_type = MYSQL_ASSOC) {
		$sql = "SELECT $columns FROM $table";
		
		if(!empty($condition)) {
			$sql .= " WHERE $condition";
		}
		
		$sql .= ' LIMIT 1;';
		$result = mysql_query($sql);
		$row = mysql_fetch_array($result, $result_type);
		mysql_free_result($result);
		
		return $row;
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
		return mysql_real_escape_string($string, $this->con);
	}
	
	function __deconstruct() {
		$this->disconnect();
	}
}

?>