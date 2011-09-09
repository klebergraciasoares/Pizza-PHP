<?php

include(dirname(__FILE__) . '/settings.php');

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

$app = new Pizza\Application($root, $param);
unset($param);
unset($root);

// setup routes
include(dirname(__FILE__) . '/configureroutes.php');

include(dirname(__FILE__) . '/../libraryheader.php');

// include the correct controller
if(!empty($controller) && file_exists(dirname(__FILE__) . '/../controllers/'.$controller.'_con.php')) {
	include(dirname(__FILE__) . '/../controllers/'.$controller.'_con.php');
} else {
	include(dirname(__FILE__) . '/../controllers/404_con.php');
}

?>