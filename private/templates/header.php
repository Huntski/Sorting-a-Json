<!DOCTYPE html>
<html lang="nl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="description" content="A Json search & filter with Ajax">
    <meta name="author" content="Wiebe Ranzijn">
    <title>Json Sorteren</title>
    <link rel="stylesheet" href="<?= $uri ?>css/style.css">
</head>

<body>
    <header class="header">

        <div class="shop-counter" onclick="requestShoppingCart()">
            <img class="shop-counter__img" src="<?= $uri ?>img/cart.png" alt="">
            <h2 class="shop-counter__count"><?php

            $counter = 0;
            foreach ($items as $i) $counter += $i[1];
            echo $counter;

            ?></h2>
        </div>

        <h2 class="h2">Search</h2>
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


    </header>