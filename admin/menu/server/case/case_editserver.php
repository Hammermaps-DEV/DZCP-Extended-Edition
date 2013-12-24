<?php
/**
 * <DZCP-Extended Edition>
 * @package: DZCP-Extended Edition
 * @author: DZCP Developer Team || Hammermaps.de Developer Team
 * @link: http://www.dzcp.de || http://www.hammermaps.de
 */

if(_adminMenu != 'true') exit();

if(empty($_POST['ip']) || empty($_POST['port']))
    $show = error(_empty_ip);
else if(empty($_POST['name']))
    $show = error(_empty_servername);
else
{
    if($_POST['game'] == "lazy") $game = "";
    else $game = "`game` = '".string::encode($_POST['status'])."',";

    $get = db("SELECT ip,port,game FROM ".dba::get('server')." WHERE id = '".convert::ToInt($_GET['id'])."'",false,true);
    $cache_hash = md5($get['ip'].':'.$get['port'].'_'.$get['game']);
    Cache::delete('server_'.$cache_hash);

    db("UPDATE ".dba::get('server')."
               SET `ip`         = '".string::encode($_POST['ip'])."',
                   `port`       = '".convert::ToInt($_POST['port'])."',
                   `qport`      = '".string::encode($_POST['qport'])."',
                   `name`       = '".string::encode($_POST['name'])."',
                   `custom_icon`= '".string::encode($_POST['custom_game_icon'])."',
                   ".$game."
                   `pwd`        = '".string::encode($_POST['pwd'])."'
               WHERE id = '".convert::ToInt($_GET['id'])."'");

    $show = info(_server_admin_edited, "?index=admin&amp;admin=server");
}