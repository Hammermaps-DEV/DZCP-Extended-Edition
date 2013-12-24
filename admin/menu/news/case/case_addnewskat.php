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
    db("INSERT INTO ".dba::get('newskat')." SET `katimg` = '".string::encode($_POST['img'])."', `kategorie` = '".string::encode($_POST['kat'])."'");
    $show = info(_config_newskats_added, "?index=admin&amp;admin=news");
}