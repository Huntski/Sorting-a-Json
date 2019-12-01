<div class='shopping-cart'>

    <button class="button--return" onclick="prepareSearchRequest()">Return</button>

    <?php

    $cookies = json_decode($_COOKIE['items']);

    if (!count($cookies)) {
        ?>

        <div class="shopping-cart">
            <h2 class="">Shopping cart is empty</h2>
        </div>

    <?php
    }

    $i = 0;
    foreach ($cookies as $cookie) {
        $contents = $this->getContents($cookie);
        if (isset($contents->titel)) :
            ?>

            <div class="cart-item cart-item__<?=$i?>">
                <h2 class="cart-item__title"><span class="cart-item__amount"><?= $cookie[1] ?></span><?= $contents->titel ?></h2>
                <button class="cart-item__delete" onclick="removeItem('<?= $contents->titel ?>', 'cart-item__<?=$i?>')">Remove</button>
            </div>

            <?php
        endif;
        $i++;
    }

    ?>
</div>