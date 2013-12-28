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
    if(Cache::check('steam_'.$steam_url) || !steam_infos_cache)
    {
        $steam_data = SteamAPI::getUserInfos($steam_url);

        if(steam_infos_cache)
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

    return show((isset($_GET['list']) ? _steamicon_nouser : _steamicon),array('profile_url' => $steam_data['user']['profile_url'],'username' => $steam_data['user']['nickname'],'avatar_url' => $steam_data['user']['avatarIcon_url'],
                                 'text1' => $text_1,'text2' => $text_2,'status' => $status_set));
}

//-> Show Xfire Status
function skype($username='')
{
    if(empty($username) || !skype_enable) return '-';

    if(skype_preloader)
    {
        if(Cache::check_binary('skype_'.$username))
        {
            if(!$img_skype = fileExists(Skype::get_status($username,true,true,'smallicon')))
                return show((isset($_GET['list']) ? _skypeicon_nouser : _skypeicon),array('username' => $username, 'img' => Skype::get_status($username,true,true,'smallicon')));

            Cache::set_binary('skype_'.$username, $img_skype, '', skype_refresh);
            return show((isset($_GET['list']) ? _skypeicon_nouser : _skypeicon),array('username' => $username, 'img' => 'data:image/png;base64,'.base64_encode($img_skype)));
        }
        else
            return show((isset($_GET['list']) ? _skypeicon_nouser : _skypeicon),array('username' => $username, 'img' => 'data:image/png;base64,'.base64_encode(Cache::get_binary('skype_'.$username))));
    }

    return show((isset($_GET['list']) ? _skypeicon_nouser : _skypeicon),array('username' => $username, 'img' => Skype::get_status($username,true,true,'smallicon')));
}

## SETTINGS ##
$dir = "sites";

## SECTIONS ##
//Hack for Audio Securimage
$mod = isset($_GET['loader']) ? $_GET['loader'] : 'old_func';
$mod_exp = @explode('@', $mod);
if(count($mod_exp) >= 2 && $mod_exp[0] == 'securimage_audio')
{
    $audio_namespace = $mod_exp[1];
    $mod = $mod_exp[0];
}

if($mod != 'securimage' && $mod != 'securimage_audio' && $mod != 'thumbgen')
    header("Content-Type: text/xml; charset=".(!defined('_charset') ? 'iso-8859-1' : _charset));

switch($mod):
    case 'menu':
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
            case 'skype';
                die(skype(string::decode($_GET['username'])));
            break;
            /* TODO */
            case 'xbox';
                die('-');
            break;
            case 'psn';
                die('-');
            break;
            case 'origin';
                die('-');
            break;
            case 'bnet';
                die('-');
            break;
            /* TODO */
            case 'menu';
                if(array_key_exists($_GET['hash'], $menu_index))
                    if(function_exists($menu_index[$_GET['hash']]))
                        die(call_user_func($menu_index[$_GET['hash']]));
            break;
            case 'shoutbox':
                die('<table class="hperc" cellspacing="1">'.shout(true).'</table>');
            break;
        endswitch;
    break;

    case 'thumbgen':
    if(!headers_sent())
        thumbgen($_GET['file'], isset($_GET['width']) ? $_GET['width'] : '', isset($_GET['height']) ? $_GET['height'] : '');
    break;

    case 'securimage':
        if(!headers_sent())
        {
            $securimage->background_directory = basePath.'/inc/images/securimage/background/';
            $securimage->code_length  = rand(4, 6);
            $securimage->image_height = isset($_GET['height']) ? convert::ToInt($_GET['height']) : 40;
            $securimage->image_width  = isset($_GET['width']) ? convert::ToInt($_GET['width']) : 200;
            $securimage->perturbation = .75;
            $securimage->text_color   = new Securimage_Color("#CA0000");
            $securimage->num_lines    = isset($_GET['lines']) ? convert::ToInt($_GET['lines']) : 2;
            $securimage->namespace    = isset($_GET['namespace']) ? $_GET['namespace'] : 'default';
            if(isset($_GET['length'])) $securimage->code_length = convert::ToInt($_GET['length']);
            die($securimage->show());
        }
    break;

    case 'securimage_audio':
        if(!headers_sent())
        {
            if(file_exists(basePath.'/inc/additional-kernel/securimage/audio/'.language::get_language_tag().'/0.wav'))
                $securimage->audio_path = basePath.'/inc/additional-kernel/securimage/audio/'.language::get_language_tag().'/';

            $securimage->namespace = isset($audio_namespace) ? $audio_namespace : 'default';
            die($securimage->outputAudioFile());
        }
    break;

    case 'addon_installer':
        header("Content-Type: text/html; charset=".(!defined('_charset') ? 'iso-8859-1' : _charset));
        if($_GET['step'] == 'sql')
            die(addons_installer::run_sql_installer(base64_decode($_GET['addon'])));
        else if($_GET['step'] == 'file')
            die(addons_installer::run_file_installer(base64_decode($_GET['addon'])));
    break;

    case 'old_func':
        switch (isset($_GET['i']) ? $_GET['i'] : ''):
            case 'kalender';
                die(kalender($_GET['month'],$_GET['year']));
            break;
            case 'teams';
                die(team($_GET['tID']));
            break;
        endswitch;
    break;
endswitch;

// Cookie speichern
cookie::save();

// Datenbankverbindung beenden
database::close();