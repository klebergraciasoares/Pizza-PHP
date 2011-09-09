<?php
	include(dirname(__FILE__) . '/../routes.php');
	
	// if a route is set for the controller
	if(!empty($controller) && isset($routes[$controller])) {
		// get the first set of parameters
		$temp = $routes[$controller];
		
		// set other variables
		$controller = (!empty($temp['controller'])) ? $temp['controller'] : $controller;
		$view = (!empty($temp['view'])) ? $temp['view'] : $view;
		$theme = (!empty($temp['theme'])) ? $temp['theme'] : $theme;
		
		// get the number of parameters given in the URL
		$paramcount = intval($app->getParameterCount());
		
		// if there are variables set for this number of parameters
		if(isset($temp[$paramcount])) {
			
			$temp = $temp[$paramcount];
			
			// name all the parameters as variables
			if(!empty($temp['params'])) {
				$params = $temp['params'];
				
				for($i = 0; $i<$paramcount && isset($params[$i]); $i++) {
					$$params[$i] = $param[$i];
				}			
			}
			
			// set other variables for this number of parameters
			$controller = (!empty($temp['controller'])) ? $temp['controller'] : $controller;
			$view = (!empty($temp['view'])) ? $temp['view'] : $view;
			$theme = (!empty($temp['theme'])) ? $temp['theme'] : $theme;
		}
		
		unset($temp);
		unset($paramcount);
	}
?>