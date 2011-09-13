<?php

// You must fill these with random data ONCE and never change again unless they become compromised
// But note that changing these will invalidate all users' passwords and sessions
$GLOBALS['random1'] = '';
$GLOBALS['random2'] = '';

// Domain that cookies should be set on
$GLOBALS['cookiedomain'] = '';

// the root of the site's URL. If not at root of domain need to specify root here.
// e.g. mysite.com/pizzaphp/ would need the string '/pizzaphp'
$root = '';

$loadmodules = array(
	'MySQLDatabase',
	'User',
	'Form',
);

// Would you like debugging to be turned on?
$debugging = true;

// **********************************************************************
// * DON'T TOUCH THE FOLLOWING UNLESS YOU KNOW WHY YOU'RE CHANGING THEM *
// **********************************************************************

// MUST correspond to the number of parameters set in your htaccess file
$totalparams = 5;

// maximum amount of time a session can be left idle in seconds. Default 1800 (30 mins).
$GLOBALS['maxsessionperiod'] = 1800;

// maximum number of concurrent user sessions for each user
$GLOBALS['maxusersessions'] = 20;

// set true for extra security, but it will break the back button in the browser
$new_id_each_page = false;

?>