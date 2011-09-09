<?php

// put cron code inside the cron_con.php controller

$_GET['page'] = 'cron';

/*
// emulate the use of get parameters - param1, param2 etc
$_GET['param1'] = 'parameter 1 value';
*/

include(dirname(__FILE__) . '/framework/head.php');
include(dirname(__FILE__) . '/framework/foot.php');

?>