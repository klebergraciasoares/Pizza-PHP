<?php namespace Pizza;

class Application
{
	private $param;
	private $root;
	private $paramcount;
	
	function __construct($root, $param) {
		$this->root = $root;
		$this->param = $param;
		
		// count the number of parameters
		$this->paramcount = 0;
		while($this->paramcount<count($param) && !empty($param[$this->paramcount])) {
			$this->paramcount++;
		}
	}

	public static function redirect($page, $includeparameters = true) {
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
		$url = $this->root . '/';
		$url .= $page;
		
		if($includeparameters==true) {
			for($i = 0; $i<count($this->param) && !empty($this->param[$i]); $i++) {
				$url .= '/' . $this->param[$i];
			}
		}
		
		return $url;
	}
	
	public function getParameterCount() {
		return $this->paramcount;
	}
	
	public function getRoot() {
		return $this->root;
	}
}

?>