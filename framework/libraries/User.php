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
		
		$sql = "DELETE FROM PizzaUserSessions WHERE (timestamp+'$maxtime')>NOW();";
		$db->query($sql);
	}
	
	public static function logout(&$db) {
		$this->deleteOldSessions($db);
		
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
		
		setcookie('PizzaUserSession', '', time()-3600, '/', $GLOBALS['cookiedomain']);
		
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
	public function login($username, $password, &$db) {
		User::logout($db);
		
		$username = $db->escapeString($username);
	
		$row = $db->selectFirst('PizzaUserID, password, salt', 'PizzaUser', $this->usernamefield."='$username'");
		
		if (mysql_num_rows($row)<1) {
			return false;
		}
		
		if ($this->encryptPassword($password, $row['salt'])==$row['password']) {
			$_SESSION['PizzaUserID'] = $row['PizzaUserID'];
			$_SESSION['PizzaUserTime'] = strtotime('now');
			$_SESSION['PizzaUserBrowser'] = md5($_SERVER['HTTP_USER_AGENT'] . $GLOBALS['random2']);
			
			$userid = $row['PizzaUserID'];
			
			// if logged in the maximum number of allowed times
			if ($db->count('PizzaUserSessions', "PizzaUserID='$userid'")>=$GLOBALS['maxusersessions']) {
				return false;
			}
		
			// try to make a new session id 10 times
			for ($i = 0, $result = false; $result==false && $i<10; $i++) {
			
				$session = md5(date('isuH'));
				
				$sql = "INSERT INTO PizzaUserSessions (PizzaUserID, sessionpass) VALUES ('$userid', '$session');";
				$result = $db->query($sql);
			}
			
			if ($result==false) {
				User::logout($db);
				return false;
			}
			
			// set equal to expire time
			setcookie('PizzaUserSession', $session, time()+$GLOBALS['maxsessionperiod'], '/', $GLOBALS['cookiedomain']);
		
			return true;
		}
		
		return false;
	}
	
	public function checkSession(&$db) {
	
		$this->deleteOldSessions($db);
	
		// if session variables set
		if (isset($_SESSION['PizzaUserID']) && isset($_SESSION['PizzaUserTime']) && isset($_SESSION['PizzaUserBrowser'])) {
			//if the user browser is the same
			if ($_SESSION['PizzaUserBrowser']==md5($_SERVER['HTTP_USER_AGENT'] . $GLOBALS['random2'])) {
				// if the session hasn't timed out
				if ((intval($_SESSION['PizzaUserTime']) + intval($GLOBALS['maxsessionperiod']))>strtotime('now')) {
					// if the use has the correct session cookie
					if (isset($_COOKIE['PizzaUserSession'])) {
						$session = $_COOKIE['PizzaUserSession'];
						
						$row = $db->selectFirst('PizzaUserID, password, salt', 'PizzaUser', $this->usernamefield."='$username'");
						$userid = $row['PizzaUserID'];
						
						// if session cookie matches one in the database
						if ($db->count('PizzaUserSessions', "userid='$userid' && sessionpass='$session'")>0) {
							// they are logged in
							$this->loggedin = true;
							return true;
						}
					}
				}
			}
		}
		
		User::logout($db);
		return false;
	}
	
	public function checkUserSession(&$db) {
		return false;
	}
	
	public function isLoggedIn() {
		return $this->loggedin;
	}
}

?>