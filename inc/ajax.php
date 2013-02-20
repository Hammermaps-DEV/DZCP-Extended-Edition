<?php
/**
 * <DZCP-Extended Edition>
 * @package: DZCP-Extended Edition
 * @author: DZCP Developer Team || Hammermaps.de Developer Team
 * @link: http://www.dzcp.de || http://www.hammermaps.de
 */

## OUTPUT BUFFER START ##
include("../inc/buffer.php");

//Settings
$ajaxJob = true;
$ajaxThumbgen = ((isset($_GET['loader']) ? $_GET['loader'] : false) == 'thumbgen' ? true : false);

## INCLUDES ##
include(basePath."/inc/debugger.php");
include(basePath."/inc/config.php");
include(basePath."/inc/bbcode.php");
include(basePath."/inc/thumbgen.php");

## FUNCTIONS ##
if(($add_menu_functions = get_files(basePath.'/inc/menu-functions/',false,true,array('php'))) && !$ajaxThumbgen)
{
    if(count($add_menu_functions) >= 1)
    {
        $menu_index = array();
        foreach($add_menu_functions as $func)
        {
            if(file_exists(basePath.'/inc/menu-functions/'.$func))
            {
                $func_name = str_replace('.php', '', $func);
                $menu_index[md5_file(basePath.'/inc/menu-functions/'.$func)] = $func_name;
                require_once(basePath.'/inc/menu-functions/'.$func);
            }
        }
    }
}

## SETTINGS ##
$dir = "sites";

## SECTIONS ##
switch(isset($_GET['loader']) ? $_GET['loader'] : 'old_func'):
    case 'menu';
        switch (isset($_GET['mod']) ? $_GET['mod'] : ''):
            case 'server';
                die('<table class="hperc" cellspacing="0">'.server(convert::ToInt($_GET['serverID'])).'</table>');
            break;
            case 'teamspeak';
                die('<table class="hperc" cellspacing="0">'.teamspeak().'</table>');
            break;
            case 'xfire';
                die(xfire($_GET['username']));
            break;
            case 'menu';
                if(array_key_exists($_GET['hash'], $menu_index))
                    if(function_exists($menu_index[$_GET['hash']]))
                        die(call_user_func($menu_index[$_GET['hash']]));
            break;
        endswitch;
    break;
    case 'thumbgen';
        thumbgen($_GET['file'], isset($_GET['width']) ? $_GET['width'] : '', isset($_GET['height']) ? $_GET['height'] : '');
    break;

    case 'old_func';
        switch (isset($_GET['i']) ? $_GET['i'] : ''):
            case 'kalender';
                die(kalender($_GET['month'],$_GET['year']));
            break;
            case 'teams';
                die(team($_GET['tID']));
            break;
            case 'shoutbox';
                die('<table class="hperc" cellspacing="1">'.shout(1).'</table>');
            break;
        endswitch;
    break;
endswitch;
?>