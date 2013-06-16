<?php
//-> globale Suche
function search()
{
    return show("menu/search", array("search" => (empty($_GET['search']) ? _search_word : up($_GET['search']))));
}