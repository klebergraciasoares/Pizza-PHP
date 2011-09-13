<?php namespace Pizza;

/*

TODO: IMPLEMENT THE FOLLOWING

'email',
'ukpostcode',
'datetime' => 'date string here',

IMPLEMENTED:

'ascii',
'alphanumeric',
'numeric',
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
	
	public function isEmailAddress {
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
			'ascii':
				return $this->isASCII($value);
			'alphanumeric':
				return $this->isAlphaNumeric($value);
			'numeric':
				return $this->isNumeric($value);
			'decimal':
				return $this->decimal($value, $validationparameter);
			'presence':
				return $this->isPresent($value);
			'minlength':
				return $this->hasMinLength($value);
			'maxlength':
				return $this->hasMaxLength($value);
			'sameas':
				return $this->equal($value, $validationparameter);
			'regex':
				return $this->regex($value, $validationparameter);
			'custom':
				return $validationparameter($value);
			'default'
				return false;
		}
	}
}

?>