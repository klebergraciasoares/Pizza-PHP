<?php

// Do not edit this file. Put your code inside of a theme.

include(dirname(__FILE__) . '/framework/head.php');
if($theme!=-1) {
	if(!file_exists(realpath(dirname(__FILE__) . '/theme/'.$theme.'.php'))) {
		$theme = 'default';
	}
	include(dirname(__FILE__) . '/theme/'.$theme.'.php');
}
include(dirname(__FILE__) . '/framework/foot.php');
?>
