<?php
/**
 * <DZCP-Extended Edition>
 * @package: DZCP-Extended Edition
 * @author: DZCP Developer Team || Hammermaps.de Developer Team
 * @link: http://www.dzcp.de || http://www.hammermaps.de
 */

function search()
{
    return show("menu/search", array("search" => (empty($_GET['search']) ? _search_word : $_GET['search'])));
}