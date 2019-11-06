<?php

/*
*   Author: Wiebe Ranzijn
*   Description: Json Filter & Search, with Ajax
*/

// ---------------------------------------------------------------------------------

require "../private/includes/router.php";

$router = new router;
$routes = $router->get_routes();
$GLOBALS['file_uri'] = implode('/', array_slice(explode('/', $_SERVER['SCRIPT_NAME']), 0, -1)) . '/';
$GLOBALS['nav_uri'] = "/";

$controller = "MainController";
require "../private/controllers/" . $controller . ".php";
$controller = new $controller;

if (isset($_GET['search'], $_GET['filter'], $_GET['sort'])) {
    $controller->requestSearch($_GET);
}
if (!count($_GET)) $controller->requestHome();

// ---------------------------------------------------------------------------------