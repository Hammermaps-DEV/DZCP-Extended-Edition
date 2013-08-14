<?php
/**
 * <DZCP-Extended Edition>
 * @package: DZCP-Extended Edition
 * @author: DZCP Developer Team || Hammermaps.de Developer Team
 * @link: http://www.dzcp.de || http://www.hammermaps.de
 */

if(_adminMenu != 'true') exit();
$qry = db("SELECT id FROM ".dba::get('ts')." WHERE `show_navi` = 1");
if(_rows($qry))
{
    while($get = _fetch($qry))
    { db("UPDATE ".dba::get('ts')." SET `show_navi` = '0' WHERE `id` = ".$get['id'].";"); }
}

db("UPDATE ".dba::get('ts')." SET `show_navi` = '1' WHERE `id` = ".convert::ToInt($_GET['id']).";");
$show = header("Location: ?admin=teamspeak");