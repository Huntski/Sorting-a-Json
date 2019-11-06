<?php

/*
    The router is just for getting information from the url.
    The index file then uses that information to get the correct files needed for the correct page.
*/

class router {

    // ---------------------------------------------------------------------------------------------------

    function get_routes() {
        $uri = str_replace('/jsonSorteren_uwu/', "", $_SERVER['REQUEST_URI']); // Get Routes from server uri
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

        return $routes; // Return routes
    }

    // ---------------------------------------------------------------------------------------------------

}