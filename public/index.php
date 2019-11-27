<?php

/*
*   Author: Wiebe Ranzijn
*   Description: Json Filter & Search, with Ajax
*/

$GLOBALS['file_uri'] = implode('/', array_slice(explode('/', $_SERVER['SCRIPT_NAME']), 0, -1)) . '/';
$GLOBALS['nav_uri'] = "/";

// ---------------------------------------------------------------------------------

require "../private/includes/router.php";

$router = new router;
$methods = $router->get_methods();
require "../private/controllers/" . $methods["controller"] . ".php";
$controller = new $methods["controller"];
$function = $methods['function'];
$params = $methods['params'];
($params)
    ? $controller->$function($params)
    : $controller->$function();

// ---------------------------------------------------------------------------------