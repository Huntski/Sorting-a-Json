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
        $this->uri = $GLOBALS['file_uri'] = implode('/', array_slice(explode('/', $_SERVER['SCRIPT_NAME']), 0, -1)) . '/';

    }

    // ========================================================== Private functions  ========================================================== //

    public function validateFile ($fileName, $dir) { // Check if file exist in template folder
        $valid = false;
        $allowedFileRequests = array(); // Create array with valid files
        foreach(scandir($dir) as $file) array_push($allowedFileRequests, explode(".php", $file)[0]);
        if (in_array($fileName, $allowedFileRequests)) $valid = true;
        return $valid;
    }

    // -----------------------------------------------------------------

    private function get_json_contents () { // Get json contents

        $json_location = $this->json['file__location'] . $this->json['file__name'];
        return json_decode(file_get_contents($json_location));

    }

    // -----------------------------------------------------------------

    private function searchJson ($search_querys) { // Search

        $contents = $this->get_json_contents();
        $results = array();

        global $filter;

        $search = filter_var($search_querys['search'], FILTER_SANITIZE_STRING);
        $filter = filter_var($search_querys['filter'], FILTER_SANITIZE_STRING);
        $sort   = filter_var($search_querys['sort'], FILTER_VALIDATE_INT);

        if (in_array($filter, ['titel', 'auteur', 'uitgave', 'paginas', 'taal', 'prijs', 'ean']) === false) {
            echo "Filter incorrect";
            die();
        }

        if (!$search) {
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

        if ($sort === 1) $results = array_reverse($results);

        return $results;

    }

    // -----------------------------------------------------------------

    private function getContents ($item) {

        $contents = $this->get_json_contents();

        foreach ($contents as $key => $value) {
            if (isset($value->titel))
                if ($value->titel == $item[0]) return $value;
        }

        return false;

    }

    // ========================================================== Public functions  ========================================================== //

    public function requestHome ($routes) {

        $request = filter_var($routes[0]);

        $items = array();

        if (isset($_COOKIE['items'])) {
            $items = json_decode($_COOKIE['items']);
        }

        $uri = $this->uri;
        $folder = $this->template_folder;

        require $folder . "header.php";
        require $folder . "home.php";
        ?>
        <script type="text/javascript">
        window.addEventListener('DOMContentLoaded', (event) => {
            <?php
            if (strtolower($routes[0]) == 'shoppingcart') {
                ?>prepareShoppingCart();<?php
            } else {
                ?>prepareSearchRequest();<?php
            }
            ?>
        });
        </script>
        <?php
        require $folder . "footer.php";

    }

    // -----------------------------------------------------------------

    public function requestSearch ($querys_array) { // Show search result in html

        $result = $this->searchJson($querys_array);

        if ($querys_array['search'] == 'uwu') {
            ?>

            <h2 class="item none">OwO</h2>

            <?php
        } else if (count($result) == 0) {

        }

        require $this->template_folder . "items.php";

    }

    // -----------------------------------------------------------------

    public function requestShoppingcart () {
        require $this->template_folder . "shoppingcart.php";
    }

}