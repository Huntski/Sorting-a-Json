<?php

class MainController {

    // ----------------------------------------------------------------- Variables

    private $json;
    private $template_folder;
    private $uri;

    // ----------------------------------------------------------------- Constructor

    function __construct () {

        $this->json = array(
            'file__location' => '../private/includes/',
            'file__name' => 'book.json'
        );

        $this->template_folder = '../private/templates/';
        $this->uri = $_SERVER['REQUEST_URI'] . 'public/';

    }

    // -----------------------------------------------------------------


    // ========================================================== Private functions  ========================================================== //

    // ----------------------------------------------------------------- Get json contents

    private function get_json_contents () {

        $json_location = $this->json['file__location'] . $this->json['file__name'];
        return json_decode(file_get_contents($json_location));

    }

    // -----------------------------------------------------------------


    // ========================================================== Public functions  ========================================================== //

    // ----------------------------------------------------------------- Show page

    public function request_main () {

        $uri = $this->uri;
        $folder = $this->template_folder;

        require $folder . "header.php";
        require $folder . "main.php";
        require $folder . "footer.php";

    }

    // ----------------------------------------------------------------- Search

    public function request_search ($search_querys) {

        $contents = $this->get_json_contents();
        $results = array();

        global $filter;

        $search       = filter_var($search_querys['search'], FILTER_SANITIZE_STRING);
        $filter = filter_var($search_querys['filter'], FILTER_SANITIZE_STRING);
        $sort         = filter_var($search_querys['sort'], FILTER_VALIDATE_INT);

        if (!$search) $search = "negative";
        if (in_array($filter, ['titel', 'auteur', 'date', 'paginas', 'taal', 'prijs']) === false) $filter = "titel";
        // $['filter'] = $filter;

        if ($search == "negative") {
            echo "no search";
            $results = $contents;
        } else {
            foreach ($contents as $key => $value) {
                $filter__value = strtolower($value->$filter);
                $search__value = strtolower($search);
                if (strpos($filter__value, $search__value) !== false) {
                    array_push($results, $contents[$key]);
                }
            }
        }

        function cmp ($a, $b)  {
            $filter = $GLOBALS['filter'];
            if (filter_var($a, FILTER_VALIDATE_INT)) {
                return strcmp($a->$filter, $b->$filter);
            } else {
                return strcasecmp($a->$filter, $b->$filter);
            }
        }

        usort($results, "cmp");

        echo json_encode($results);

    }

    // -----------------------------------------------------------------

}