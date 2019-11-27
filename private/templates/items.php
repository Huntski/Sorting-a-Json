<?php

if (count($result)) {

    foreach ($result as $item) :
        ?>

        <div class="item">
            <div class="item__cover">
                <img class="item__cover__img" src="<?=$item->cover?>" alt="">
            </div>

            <div class="item__info">
                <h1 class="item__title"><?=$item->titel?></h1>
                <ul class="item__list">
                    <li class="item__list__item"><?=$item->auteur?></li>
                    <li class="item__list__item">Uitgave: <?=$item->uitgave?></li>
                    <li class="item__list__item">Ean: <?=$item->ean?></li>
                    <li class="item__list__item">Paginas: <?=$item->paginas?></li>
                    <li class="item__list__item">Taal: <?=$item->taal?></li>
                </ul>
            </div>

            <button class="button--plus item__button" onclick="addCookie('<?=$item->titel?>')">
                <div></div>
                <div></div>
            </button>
        </div>

    <?php

    endforeach;
} else {
    ?>
    <h2 class="item none">No results found</h2>
    <?php
}