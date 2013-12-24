<?php
/**
 * <DZCP-Extended Edition>
 * @package: DZCP-Extended Edition
 * @author: DZCP Developer Team || Hammermaps.de Developer Team
 * @link: http://www.dzcp.de || http://www.hammermaps.de
 */

if(_adminMenu != 'true') exit();

if(empty($_POST['kat']))
    $show = error(_config_empty_katname);
else
{
    $katimg = "";
    if($_POST['img'] != "lazy")
        $katimg = "`katimg` = '".string::encode($_POST['img'])."',";

    db("UPDATE ".dba::get('newskat')." SET ".$katimg." `kategorie` = '".string::encode($_POST['kat'])."' WHERE id = '".convert::ToInt($_GET['id'])."'");
    $show = info(_config_newskats_edited, "?index=admin&amp;admin=news");
}