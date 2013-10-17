<?php
/**
 * <DZCP-Extended Edition>
 * @package: DZCP-Extended Edition
 * @author: DZCP Developer Team || Hammermaps.de Developer Team
 * @link: http://www.dzcp.de || http://www.hammermaps.de
 */

if(_adminMenu != 'true') exit();

$get = db("SELECT * FROM ".dba::get('server')." WHERE id = '".convert::ToInt($_GET['id'])."'",false,true);
$custom_icon = '<option value="">'._custom_game_icon_none.'</option>';
$files = get_files(basePath.'/inc/images/gameicons/custom/',false,true,$picformat);
if(count($files) >= 1)
{
    foreach($files as $file)
    {
        $sel = ($file == $get['custom_icon'] ? 'selected="selected"' : '');
        $custom_icon .= show(_select_field, array("value" => $file, "what" => strtoupper(preg_replace("#\.(.*?)$#","",$file)), "sel" => $sel));
    }
}

$show = show($dir."/server_edit", array("sip" => string::decode($get['ip']),
                                        "sname" => string::decode($get['name']),
                                        "id" => $_GET['id'],
                                        "sport" => $get['port'],
                                        "qport" => $get['qport'],
                                        "games" => listgames($get['game']),
                                        "spwd" => $get['pwd'],
                                        "custom_icon" => $custom_icon));