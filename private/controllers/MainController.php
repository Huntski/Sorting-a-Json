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
                    <ul class="item__list">
                        <li class="item__list__item"><?=$item->auteur?></li>
                        <li class="item__list__item"><?=$item->uitgave?></li>
                        <li class="item__list__item"><?=$item->ean?></li>
                        <li class="item__list__item"><?=$item->paginas?></li>
                        <li class="item__list__item"><?=$item->taal?></li>
                    </ul>
                </div>

                <div class="item__img">
                    <img src="<?=$item->cover?>" alt="">
                </div>
            </div>

        <?php

        endforeach;

    }

    // -----------------------------------------------------------------

}