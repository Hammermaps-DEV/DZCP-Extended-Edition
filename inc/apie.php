<?php
/**
 * <DZCP-Extended Edition>
 * @package: DZCP-Extended Edition API Event Core
 * @author: DZCP Developer Team || Hammermaps.de Developer Team
 * @link: http://www.dzcp.de || http://www.hammermaps.de
 */

class API_EVENTS
{
    private static $caller_function = array();
    private static $caller_method = array();
    public static $version = '0.1';
    public static $time = '27.12.2013';

    ## DZCP ##
    /**
     * dzcp_on_login
     * dzcp_on_logout
     */

    ## GS ##
    /**
     * server_image_map
     */

    #### Public ####
    public static function register_function($callback,$function)
    { self::$caller_function[$callback][] = $function; }

    public static function register_method($callback,$method,$function)
    { self::$caller_method[$callback][] = array('method' => $method, 'function' => $function); }

    ### GameServer Events ###
    public static function server_image_map($image_map='',$game='',$map='',$game_icon='') {
        if(!modapi_events_enabled || !modapi_enabled) return;
        if(count(self::$caller_function['server_image_map']) >= 1) {
            foreach (self::$caller_function['server_image_map'] as $call)
            { if(function_exists($call)) call_user_func($call,$image_map,$game,$map,$game_icon); }
        }

        if(count(self::$caller_method['server_image_map']) >= 1) {
            foreach (self::$caller_method['server_image_map'] as $call_array)
            { if(method_exists($call_array['method'], $call_array['function'])) call_user_func($call_array['method'].'::'.$call_array['function'],$image_map,$game,$map,$game_icon); }
        }
    }

    ### DZCP Events ###
    public static function onLogin($userid='',$username='',$user_language='',$permanent_login='') {
        if(!modapi_events_enabled || !modapi_enabled) return;
        if(count(self::$caller_function['dzcp_on_login']) >= 1) {
            foreach (self::$caller_function['dzcp_on_login'] as $call)
            { if(function_exists($call)) call_user_func($call,$userid,$username,$user_language,$permanent_login); }
        }

        if(count(self::$caller_method['dzcp_on_login']) >= 1) {
            foreach (self::$caller_method['dzcp_on_login'] as $call_array)
            { if(method_exists($call_array['method'], $call_array['function'])) call_user_func($call_array['method'].'::'.$call_array['function'],$userid,$username,$user_language,$permanent_login); }
        }
    }

    public static function onLogout($userid='') {
        if(!modapi_events_enabled || !modapi_enabled) return;
        if(count(self::$caller_function['dzcp_on_logout']) >= 1) {
            foreach (self::$caller_function['dzcp_on_logout'] as $call)
            { if(function_exists($call)) call_user_func($call,$userid); }
        }

        if(count(self::$caller_method['dzcp_on_logout']) >= 1) {
            foreach (self::$caller_method['dzcp_on_logout'] as $call_array)
            { if(method_exists($call_array['method'], $call_array['function'])) call_user_func($call_array['method'].'::'.$call_array['function'],$userid); }
        }
    }
}