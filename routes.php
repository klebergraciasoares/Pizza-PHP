<?php

$routes = array(
	'example' => array(
		/*'controller' => 'examplecontroller',*/
		'view' => 'newexample',
		
		/* numbers specify parameter count */
		0 => array(
			/* overrides any other variables set */
			'view' => 'example',
		),
		1 => array(
			/* set the names of the parameters */
			'params' => array('id'),
		),
		2 => array(
			'params' => array('id', 'page'),
		),
	),
	
	'home' => array(
		'theme' => 'home',
	),
	
	'example2' => array(
		// setting the theme to Pizza\NOTHEME will run the controller but
		// will not show a view
		'theme' => Pizza\NOTHEME,
	),
);
?>