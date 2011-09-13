<?php namespace Pizza;

class User {
	
	private $fields;
	private $usernamefield;
	private $loggedin;
	
	function __construct($fields, $usernamefield) {
		$this->fields = $fields;
		$this->usernamefield = $usernamefield;
		$this->loggedin = false;
	}
	
	private function encryptPassword($password, $salt) {
		// DO NOT CHANGE THIS LINE AS IT WILL INVALIDATE ALL USERS' PASSWORDS
		return hash('sha256', strrev($GLOBALS["random1"] . $password . $salt . 'PIZZAPHP'));
	}
	
	private function deleteOldSessions(&$db) {
		$maxtime = $GLOBALS['maxsessionperiod'];
		
		// TODO: DELETE ALL OLDER THAN MAX SESSION PERIOD
		$sql = '';
		$db->query($sql);
	}
	
	public static function logout(&$db) {
		$this->deleteOldSession();
		
		$vars = $_SESSION;
		$_SESSION = array();
		
		// destroy session cookies as suggested in PHP docs
		if (ini_get("session.use_cookies")) {
		    $params = session_get_cookie_params();
		    setcookie(session_name(), '', time() - 42000,
		        $params["path"], $params["domain"],
		        $params["secure"], $params["httponly"]
		    );
		}
		
		session_destroy();
		
		setcookie('PizzaUserSession', '', time()-3600);
		
		session_start();
		session_regenerate_id(true);
		
		foreach ($vars as $key => $value) {
			if($key=='PizzaUserID' || $key=='PizzaUserTime' || $key=='PizzaUserBrowser') {
				continue;
			}
			
			$_SESSION[$key] = $value;
		}
	}
	
	// TODO: ADD MAXIMUM NUMBER OF TRIES
	// TODO: LOG LOGIN ATTEMPTS
	// TODO: COOKIE DOMAIN
	// TODO: SESSION EXPIRE IN SETTINGS
	// TODO: SETTING TO HAVE MAXIMUM NUMBER OF LOGIN SESSIONS
	public function login($username, $password, &$db) {
		User::logout($db);
		
		$username = $db->escapeString($username);
	
		$row = $db->selectFirst('PizzaUserID, password, salt', 'PizzaUser', $this->usernamefield."='$username'");
		
		if(mysql_num_rows($row)<1) {
			return false;
		}
		
		if($this->encryptPassword($password, $row['salt'])==$row['password']) {
			$_SESSION['PizzaUserID'] = $row['PizzaUserID'];
			$_SESSION['PizzaUserTime'] = strtotime('now');
			$_SESSION['PizzaUserBrowser'] = md5($_SERVER['HTTP_USER_AGENT'] . $GLOBALS['random2']);
			
			$userid = $row['PizzaUserID'];
		
			// try to make a new session id 10 times
			for ($i = 0, $result = false; $result==false && $i<10; $i++) {
			
				$session = md5(date('isuH'));
				
				$sql = "INSERT INTO PizzaUserSessions (PizzaUserID, sessionpass) VALUES ('$userid', '$session');";
				$result = $db->query($sql);
			}
			
			if($result==false) {
				User::logout($db);
				return false;
			}
			
			// set equal to expire time
			setcookie('PizzaUserSession', $session, time()+$GLOBALS['maxsessionperiod']);
		
			return true;
		}
		
		return false;
	}
	
	public function checkUserSession(&$db) {
		return false;
	}
	
	public function isLoggedIn() {
		return true;
	}
}

?>