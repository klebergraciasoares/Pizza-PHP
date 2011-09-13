<?php namespace Pizza;

/*

TODO: IMPLEMENT THE FOLLOWING

'ascii',
'alphanumeric',
'numeric'
'email',
'url',
'ukpostcode',
'datetime' => 'date string here',
'decimal' => 2,
'presence',
'minlength' => 5,
'maxlength' => 20,
'sameas' => 'other field index',
'regex' => 'regex string here',
'custom' => function($string) { return true; }

*/

class Validate {

	public function autoValidate($type, $value, $validationparameter) {
		return true;	
	}
}

?>