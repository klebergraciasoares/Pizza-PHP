<?php namespace Pizza;

class Application
{
	private $param;
	private $root;
	private $debugging;
	private $paramcount;
	
	function __construct($root, $param, $debugging) {
		$this->root = $root;
		$this->param = $param;
		$this->debugging = $debugging;
		// count the number of parameters
		$this->paramcount = 0;
		while($this->paramcount<count($param) && !empty($param[$this->paramcount])) {
			$this->paramcount++;
		}
	}

	public function redirect($page, $includeparameters = true) {
		$url = $this->root . '/';
		$url .= $page;
		
		if($includeparameters==true) {
			for($i = 0; $i<count($this->param) && !empty($this->param[$i]); $i++) {
				$url .= '/' . $this->param[$i];
			}
		}
		
		header("Location: $url");
		exit();
	}
	
	public function link($page, $includeparameters = true) {
		$url = $this->root . '/' . $page;
		
		if($includeparameters==true) {
			for($i = 0; $i<count($this->param) && !empty($this->param[$i]); $i++) {
				$url .= '/' . $this->param[$i];
			}
		}
		
		return $url;
	}
	
	public function errecho($string, $log = false, $type = 0, &$db = NULL, $alwayslog = false) {
		if($this->debugging==true) {
			echo $string;
		}
		
		if($log==true && $db!=NULL && ($this->debugging==true || $alwayslog==true)) {
			return $db->log($string, $type);
		}
		
		return true;
	}
	
	public function getParameterCount() {
		return $this->paramcount;
	}
	
	public function getRoot() {
		return $this->root;
	}
	
	public function __get($root) {
		return $this->root;
	}
	
	public function isDebuggingOn() {
		return $this->debugging;
	}
}

?>