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

    #### Public ####
    public static function register_function($callback,$function)
    { self::$caller_function[$callback][] = $function; }

    public static function register_method($callback,$method,$function)
    { self::$caller_method[$callback][] = array('method' => $method, 'function' => $function); }

    ### GameServer Events ###
    public static function server_image_map($image_map='',$game='',$map='',$game_icon='')
    {
        if(count(self::$caller_function['server_image_map']) >= 1)
        {
            foreach (self::$caller_function['server_image_map'] as $call)
            {
                if(function_exists($call))
                    call_user_func($call,$image_map,$game,$map,$game_icon);
            }
        }

        if(count(self::$caller_method['server_image_map']) >= 1)
        {
            foreach (self::$caller_method['server_image_map'] as $call_array)
            {
                if(method_exists($call_array['method'], $call_array['function']))
                    call_user_func($call_array['method'].'::'.$call_array['function'],$image_map,$game,$map,$game_icon);
            }
        }
    }
}