<?php

class MainController {

    // ----------------------------------------------------------------- Variables

    private $json;
    private $template_folder;
    private $uri;

    // ----------------------------------------------------------------- Constructor

    function __construct () {

        $this->json = array(
            'location' => '../private/includes/',
            'name' => '.json'
        );

        $this->template_folder = '../private/templates/';
        $this->uri = $_SERVER['REQUEST_URI'] . 'public/';

    }

    // ----------------------------------------------------------------- Main controller function

    public function request_main () {

        $uri = $this->uri;
        $folder = $this->template_folder;

        require $folder . "header.php";
        require $folder . "main.php";
        require $folder . "footer.php";

    }

    // -----------------------------------------------------------------

    public function request_search ($search_querys) {

        $allowed_querys = array('search', 'filter', 'sort');
        $filtered_querys = array();
        foreach ($search_querys as $key => $query) {
            if (in_array($key, $allowed_querys)) {
                array_push($filtered_querys, [$key => filter_var($query, FILTER_SANITIZE_STRING)]);
            }
        }

    }
    // -----------------------------------------------------------------

}