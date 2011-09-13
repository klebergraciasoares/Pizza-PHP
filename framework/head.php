<?php

include(dirname(__FILE__) . '/constants.php');
include(dirname(__FILE__) . '/settings.php');

if($debugging==true) {
	error_reporting(E_ALL);
	ini_set('display_errors', '1');
} else {
	error_reporting(0);
	ini_set('display_errors', '0');
}

date_default_timezone_set('UTC');
ini_set('default_charset', 'UTF-8');

// start session
session_start();
if ($new_id_each_page==true) {
	session_regenerate_id(true);
}
unset($new_id_each_page);

// set page data
$controller = !empty($_GET['page']) ? $_GET['page'] : 'home';
$view = $controller;
$theme = 'default';

for($i = 0; $i<$totalparams; $i++) {
	if(isset($_GET['param'.$i])) {
		$param[$i] = $_GET['param'.$i];
	} else {
		$param[$i] = '';
	}
}
unset($totalparams);

include_once(dirname(__FILE__) . '/libraries/Application.php');

$app = new Pizza\Application($root, $param, $debugging);
unset($param);
unset($root);
unset($debugging);

// setup routes
include(dirname(__FILE__) . '/configureroutes.php');

// load modules requested by user
foreach ($loadmodules as $module) {
	if(file_exists(realpath(dirname(__FILE__) . "/libraries/$module.php"))) {
		include(dirname(__FILE__) . "/libraries/$module.php");
	} else {
		$app->errecho("Module $module could not be loaded as it does not exist.\n");
	}
}

unset($module);
unset($loadmodules);

include(dirname(__FILE__) . '/../appheader.php');

// include the correct controller
if(!empty($controller) && file_exists(realpath(dirname(__FILE__) . '/../controllers/'.$controller.'_con.php'))) {
	include(dirname(__FILE__) . '/../controllers/'.$controller.'_con.php');
} else {
	include(dirname(__FILE__) . '/../controllers/404_con.php');
}

?>