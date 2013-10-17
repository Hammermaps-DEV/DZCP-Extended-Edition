<?php
/**
 * <DZCP-Extended Edition>
 * @package: DZCP-Extended Edition
 * @author: DZCP Developer Team || Hammermaps.de Developer Team
 * @link: http://www.dzcp.de || http://www.hammermaps.de
 */

if(_adminMenu != 'true') exit();

$custom_icon = '<option value="">'._custom_game_icon_none.'</option>';
$files = get_files(basePath.'/inc/images/gameicons/custom/',false,true,$picformat);
if(count($files) >= 1)
{
    foreach($files as $file)
    {
        $custom_icon .= show(_select_field, array("value" => $file, "what" => strtoupper(preg_replace("#\.(.*?)$#","",$file)), "sel" => ''));
    }
}

$show = show($dir."/server_add", array("games" => listgames(),"custom_icon" => $custom_icon));