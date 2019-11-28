<div class='shopping-cart'>

    <button class="button--active" onclick="prepareSearchRequest()">Books</button>

    <?php

    $cookies = json_decode($_COOKIE['items']);

    if (!count($cookies)) {
        ?>

        <div class="shopping-cart">
            <h2>Shopping cart is empty</h2>
        </div>

    <?php
    }

    $i = 0;
    foreach ($cookies as $cookie) {
        $contents = $this->getContents($cookie);
        if (isset($contents->titel)) :
            ?>

            <div class="cart-item item">
                <h2 class="cart-item__title cart-item__<?=$i?>"><?= $contents->titel ?> <span class="cart-item__amount"><?= $cookie[1] ?></span></h2>
                <button onclick="removeItem('<?= $contents->titel ?>', 'cart-item__<?=$i?>')">x</button>
            </div>

            <?php
        endif;
        $i++;
    }

    ?>
</div>