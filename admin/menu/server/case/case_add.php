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
else if($_POST['game'] == "lazy")
    $show = error(_empty_game);
else if(empty($_POST['name']))
    $show = error(_empty_servername);
else
{
    db("INSERT INTO ".dba::get('server')."
               SET `ip`         = '".string::encode($_POST['ip'])."',
                   `port`       = '".convert::ToInt($_POST['port'])."',
                   `qport`      = '".string::encode($_POST['qport'])."',
                   `name`       = '".string::encode($_POST['name'])."',
                   `pwd`        = '".string::encode($_POST['pwd'])."',
                   `custom_icon`= '".string::encode($_POST['custom_game_icon'])."',
                   `game`       = '".string::encode($_POST['status'])."'");

    $show = info(_server_admin_added, "?index=admin&amp;admin=server");
}