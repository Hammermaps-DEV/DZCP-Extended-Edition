﻿<?php
/**
 * <DZCP-Extended Edition>
 * @package: DZCP-Extended Edition
 * @author: DZCP Developer Team || Hammermaps.de Developer Team
 * @link: http://www.dzcp.de || http://www.hammermaps.de
 */

####################################
## Wird in einer Index ausgeführt ##
####################################
if (!defined('IS_DZCP'))
    exit();

if (_version < '1.0') //Mindest Version pruefen
    $index = _version_for_page_outofdate;
else
{

    $qry = db("SELECT link FROM ".$db['sponsoren']."
             WHERE id = '".intval($_GET['id'])."'");
    $get = _fetch($qry);

    $upd = db("UPDATE ".$db['sponsoren']."
             SET `hits` = hits+1
             WHERE id = '".intval($_GET['id'])."'");

    header("Location: ".$get['link']);
}
?>