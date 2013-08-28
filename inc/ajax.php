<?php
/**
 * <DZCP-Extended Edition>
 * @package: DZCP-Extended Edition
 * @author: DZCP Developer Team || Hammermaps.de Developer Team
 * @link: http://www.dzcp.de || http://www.hammermaps.de
 */

#########################
## OUTPUT BUFFER START ##
#########################
include("../inc/buffer.php");

if(isset($_GET['loader']) && $_GET['loader'] == 'thumbgen')
{
    ini_set('display_errors', 0);
    error_reporting(0);
}

//Settings
$ajaxJob = true;
$ajaxThumbgen = ((isset($_GET['loader']) ? $_GET['loader'] : false) == 'thumbgen' ? true : false);

## INCLUDES ##
include(basePath."/inc/debugger.php");
include(basePath."/inc/config.php");
include(basePath."/inc/common.php");
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
                if(@file_exists(basePath.'/inc/menu-functions/'.$func.'.xml')) //XML Extension
                    xml::openXMLfile('menu_'.$func,'inc/menu-functions/'.$func.'.xml');

                $func_name = str_replace('.php', '', $func);
                $menu_index[md5_file(basePath.'/inc/menu-functions/'.$func)] = $func_name;
                require_once(basePath.'/inc/menu-functions/'.$func);
            }
        }
    }
}

//-> Show Xfire Status
function xfire($username='')
{
    if(empty($username))
        return '-';

    switch(xfire_skin)
    {
        case 'shadow': $skin = 'sh'; break;
        case 'kampf': $skin = 'co'; break;
        case 'scifi': $skin = 'sf'; break;
        case 'fantasy': $skin = 'os'; break;
        case 'wow': $skin = 'wow'; break;
        default: $skin = 'bg'; break;
    }

    if(xfire_preloader)
    {
        if(Cache::check_binary('xfire_'.$username))
        {
            if(!$img_stream = fileExists('http://de.miniprofile.xfire.com/bg/'.$skin.'/type/0/'.$username.'.png'))
                return show(_xfireicon,array('username' => $username, 'img' => 'http://de.miniprofile.xfire.com/bg/'.$skin.'/type/0/'.$username.'.png'));

            Cache::set_binary('xfire_'.$username, $img_stream, '', xfire_refresh);
            return show(_xfireicon,array('username' => $username, 'img' => 'data:image/png;base64,'.base64_encode($img_stream)));
        }
        else
            return show(_xfireicon,array('username' => $username, 'img' => 'data:image/png;base64,'.base64_encode(Cache::get_binary('xfire_'.$username))));
    }

    return show(_xfireicon,array('username' => $username, 'img' => 'http://de.miniprofile.xfire.com/bg/'.$skin.'/type/0/'.$username.'.png'));
}

## SETTINGS ##
$dir = "sites";

## SECTIONS ##
header("Content-Type: text/xml; charset=".(!defined('_charset') ? 'iso-8859-1' : _charset));
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
                die(xfire(string::decode($_GET['username'])));
            break;
            case 'menu';
                if(array_key_exists($_GET['hash'], $menu_index))
                    if(function_exists($menu_index[$_GET['hash']]))
                        die(call_user_func($menu_index[$_GET['hash']]));
            break;
        endswitch;
    break;

    case 'thumbgen';
    if(!headers_sent())
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

// Cookie speichern
cookie::save();

// Datenbankverbindung beenden
database::close();