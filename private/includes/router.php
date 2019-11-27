<?php

/*
    The router is just for getting information from the url.
    The index file then uses that information to get the correct files needed for the correct page.
*/

class router {

    // ---------------------------------------------------------------------------------

    private $routes;
    private $controller_folder = "../private/controllers/";

    // ---------------------------------------------------------------------------------

    function __construct () {
        $uri = str_replace('/Sorting-a-Json/', "", $_SERVER['REQUEST_URI']); // Get Routes from server uri
        // $uri = substr($_SERVER['REQUEST_URI'], strlen($basepath));
        if (strstr($uri, '?')) $uri = substr($uri, 0, strpos($uri, '?')); // Remove the '?' from the url
        $uri = '/' . trim($uri, '/'); // Make url smaller
        $uri_routes = array(); // Create array with all uri routes
        $uri_routes = explode('/', $uri); // Get all the paths from the url
        $routes = array(); // create array for only routes

        foreach($uri_routes as $route)
            if(trim($route))
                array_push($routes, $route);

        if (!count($routes)) array_push($routes, 'home');
        $this->routes = $routes;
        return $routes; // Return routes
    }

    // ---------------------------------------------------------------------------------

    public function get_methods () {
        $controller = "MainController";
        $folder = $this->controller_folder;
        $params = $this->routes;

        if (isset($_GET['search'], $_GET['filter'], $_GET['sort'])) {
            $function = "requestSearch";
            $params = $_GET;
        } else {
            switch ($this->routes[0]) {
                case "home":
                    $function = "requestHome";
                case "shoppingcart":
                    $function = "requestHome";
                    break;
                case "template":
                    $function = "requestTemplate";
                    if (isset($this->routes[1]))
                        $params = $this->routes[1];
                    break;
                default:
                    $function = "requestHome";
            }
        }

        return array("controller" => $controller, "function" => $function, "params" => $params);
    }

    // ---------------------------------------------------------------------------------

}