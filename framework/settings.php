<?php

// the root of the site's URL. If not at root of domain need to specify root here.
// e.g. mysite.com/pizzaphp/ would need the string '/pizzaphp'
$root = '';

$loadmodules = array(
	'MySQLDatabase',
); 

// Would you like debugging to be turned on?
$debugging = true;

// DON'T TOUCH THE FOLLOWING UNLESS YOU KNOW WHY YOU'RE CHANGING THEM

// MUST correspond to the number of parameters set in your htaccess file
$totalparams = 5;

?>