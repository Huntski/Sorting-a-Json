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

    // ----------------------------------------------------------------- Search

    private function searchJson ($search_querys) {

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


    // ========================================================== Public functions  ========================================================== //

    // ----------------------------------------------------------------- Show home

    public function requestHome () {

        $uri = $this->uri;
        $folder = $this->template_folder;

        require $folder . "header.php";
        require $folder . "main.php";
        require $folder . "footer.php";

    }

    // ----------------------------------------------------------------- Show search html

    public function requestSearch ($querys_array) {

        $result = $this->searchJson($querys_array);

        if (count($result) == 0) :
            ?>

            <h2 class="none">No results found</h2>

            <?php
        endif;

        foreach ($result as $item) :
            ?>

            <div class="item">
                <div class="item__info">
                    <h1 class="item__title"><?=$item->titel?></h1>
                    <h3 class="item__date"><?=$item->uitgave?></h3>
                </div>
            </div>

            <div class="item__img">
                <img src="<?=$item->cover?>" alt="">
            </div>

        <?php

        endforeach;

    }

    // -----------------------------------------------------------------

}