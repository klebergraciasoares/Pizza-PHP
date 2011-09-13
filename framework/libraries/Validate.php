<?php namespace Pizza;

/*

TODO: IMPLEMENT THE FOLLOWING

'ukpostcode',
'datetime' => 'date string here',

IMPLEMENTED:

'ascii',
'alphanumeric',
'numeric',
'email',
'decimal' => 2,
'url',
'presence',
'minlength' => 5,
'maxlength' => 20,
'sameas' => 'other field index',
'regex' => 'regex string here',
'custom' => function($string) { return true; }

*/

class Validate {

	public function regex($string, $pattern) {
		if (preg_match($pattern, $string)>0) {
			return true;
		}
		
		return false;
	}
	
	public function isEmailAddress($string) {
		return $this->regex($string, '^[a-z0-9!#$%&\'*+/=?^_`{|}~-]+(?:\.[a-z0-9!#$%&\'*+/=?^_`{|}~-]+)*@(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\.)+[a-z0-9](?:[a-z0-9-]*[a-z0-9])?$');
	}
	
	public function isURL($string, $withprotocol = false) {
		if($withprotocol==true) {
			return $this->regex($string, '^((https?|ftp|file):((//)|(\\\\))+[\w\d:#@%/;$()~_?\+-=\\\.&]*$');
		}
		
		return $this->regex($string, '^[\w\d:#@%/;$()~_?\+-=\\\.&]*$');
	}
	
	public function equal($string1, $string2) {
		return $string1==$string2;
	}
	
	public function hasMinLength($string, $length) {
		return (strlen($string)>=$length);
	}
	
	public function hasMaxLength($string, $length) {
		return (strlen($string)<=$length);
	}
	
	public function isPresent($string) {
		return (strlen($string)>0);
	}

	public function isASCII($string) {
		return $this->regex($string, '^[\x00-\x7F]*$');
	}
	
	public function isAlphaNumeric($string) {
		return $this->regex($string, '^[a-zA-Z0-9]*$');
	}
	
	public function isNumeric($string) {
		return $this->regex($string, '^([1-9]?)|([1-9][0-9]*)$');
	}
	
	public function isDecimal($string, $decimalplaces) {
		return $this->regex($string, "^([1-9]?)|([1-9][0-9]*(.[0-9]{1,$decimalplaces}))\$");
	}

	public function autoValidate($type, $value, $validationparameter) {
	
		switch ($type) {
			case 'ascii':
				return $this->isASCII($value);
			case 'alphanumeric':
				return $this->isAlphaNumeric($value);
			case 'numeric':
				return $this->isNumeric($value);
			case 'decimal':
				return $this->decimal($value, $validationparameter);
			case 'email':
				return $this->isEmail($value);
			case 'url':
				return $this->isURL($value, $validationparameter);
			case 'presence':
				return $this->isPresent($value);
			case 'minlength':
				return $this->hasMinLength($value);
			case 'maxlength':
				return $this->hasMaxLength($value);
			case 'sameas':
				return $this->equal($value, $validationparameter);
			case 'regex':
				return $this->regex($value, $validationparameter);
			case 'custom':
				return $validationparameter($value);
			default:
				return false;
		}
	}
}

?>