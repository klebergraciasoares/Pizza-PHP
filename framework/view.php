<?php
if(!empty($view) && file_exists(realpath(dirname(__FILE__) . '/../views/'.$view.'.php'))) {
	include(dirname(__FILE__) . '/../views/'.$view.'.php');
} else {
	include(dirname(__FILE__) . '/../views/404.php');
}
?>