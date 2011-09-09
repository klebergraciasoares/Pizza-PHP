<?php

include(dirname(__FILE__) . '/constants.php');
include(dirname(__FILE__) . '/settings.php');

if($debugging==true) {
	error_reporting(E_ALL ^ E_NOTICE);
	ini_set('display_errors', '1');
}

date_default_timezone_set('UTC');
ini_set('default_charset', 'UTF-8');

// set page data
$controller = !empty($_GET['page']) ? $_GET['page'] : 'home';
$view = $controller;
$theme = 'default';

for($i = 0; $i<$totalparams; $i++) {
	$param[$i] = $_GET['param'.$i];
}
unset($totalparams);

include_once(dirname(__FILE__) . '/libraries/Application.php');

$app = new Pizza\Application($root, $param, $errors);
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