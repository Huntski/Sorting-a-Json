
<div class="navigation">
    <div class="shop-counter" onclick="prepareShoppingCart()">
        <img class="shop-counter__img" src="<?= $uri ?>img/cart.png" alt="">
        <h2 class="shop-counter__count">
            <?php
            $counter = 0;
            foreach ($items as $i) if (isset($i[1])) (is_numeric($i[1])) ? $counter += $i[1] : $counter++;
            echo $counter;

            ?>
        </h2>
    </div>

    <input class="search__input input--search" type="search" placeholder="Search..">

    <h2 class="h2">Filter</h2>
    <div class="filter-box">
        <select class="search__filter select--meh">
            <option value="titel">titel</option>
            <option value="auteur">auteur</option>
            <option value="uitgave">datum uitgave</option>
            <option value="paginas">aantal bladzijden</option>
            <option value="taal">taal</option>
            <option value="ean">ean</option>
        </select>

        <select class="search__sort select--meh">
            <option value="1">oplopend</option>
            <option value="0">aflopend</option>
        </select>
    </div>
</div>