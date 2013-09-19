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
    if(empty($username) || !xfire_enable) return '-';
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

//-> Show Steam Status
function steam($steam_url='')
{
    if(empty($steam_url) || !steam_enable) return '-';
    if(Cache::check('steam_'.$steam_url))
    {
        $steam_data = SteamAPI::getUserInfos($steam_url);
        Cache::set('steam_'.$steam_url, $steam_data, steam_refresh);
    }
    else
        $steam_data = Cache::get('steam_'.$steam_url);

    if(!$steam_data || empty($steam_data)) return '-';
    if(steam_avatar_cache)
    {
        if(Cache::check_binary('steam_pic_'.$steam_url))
        {
            if($img_stream = fileExists($steam_data['user']['avatarIcon_url']))
            {
                $steam_data['user']['avatarIcon_url'] = 'data:image/png;base64,'.base64_encode($img_stream);
                Cache::set_binary('steam_pic_'.$steam_url, $img_stream, '', steam_avatar_refresh);
            }
        }
        else
            $steam_data['user']['avatarIcon_url'] = 'data:image/png;base64,'.base64_encode(Cache::get_binary('steam_pic_'.$steam_url));
    }

    switch($steam_data['user']['onlineState'])
    {
        case 'in-game': $status_set = '2'; $text_1 = _steam_in_game; $text_2 = $steam_data['user']['gameextrainfo']; break;
        case 'online': $status_set = '1'; $text_1 = _steam_online; $text_2 = ''; break;
        default: $status_set = '0'; $text_1 = $steam_data['user']['runnedSteamAPI'] ? show(_steam_offline,array('time' => get_elapsed_time($steam_data['user']['lastlogoff'],time(),1))) : _steam_offline_simple; $text_2 = ''; break;
    }

    return show(_steamicon,array('profile_url' => $steam_data['user']['profile_url'],'username' => $steam_data['user']['nickname'],'avatar_url' => $steam_data['user']['avatarIcon_url'],
                                 'text1' => $text_1,'text2' => $text_2,'status' => $status_set));
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
            case 'steam';
                die(steam(string::decode($_GET['steamid'])));
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