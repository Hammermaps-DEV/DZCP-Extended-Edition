<?php
/**
 * <DZCP-Extended Edition>
 * @package: DZCP-Extended Edition
 * @author: DZCP Developer Team || Hammermaps.de Developer Team
 * @link: http://www.dzcp.de || http://www.hammermaps.de
 */

class cms_protect
{
    private static $client_ip = '';
    private static $index = array();

    //Erkennt versuche, um Logins herauszufinden
    public static final function detect_login_search($username='')
    {
        if(!use_dzcp_protect) return;
        self::$client_ip = visitorIp();
        if(show_cms_protect_debug)
            DebugConsole::insert_info('cms_protect::detect_login_search()', 'Loaded index has '.count(self::$index).' IPs');

        if(array_key_exists(self::$client_ip, self::$index) && show_cms_protect_debug)
            DebugConsole::insert_info('cms_protect::detect_login_search()', 'Index IP: "'.self::$client_ip.'" has '.count(self::$index[self::$client_ip]).' old attempts!');

        if(show_cms_protect_debug)
            DebugConsole::insert_info('cms_protect::detect_login_search()', 'Detect login search from "'.self::$client_ip.'" add to Index');

        self::$index[self::$client_ip][] = array('username' => $username, 'time' => time());

        if(show_cms_protect_debug)
            DebugConsole::insert_info('cms_protect::detect_login_search()', 'Index IP: "'.self::$client_ip.'" has '.count(self::$index[self::$client_ip]).' new attempts!');
    }

    public static final function reset_login_search()
    {
        if(!use_dzcp_protect) return;
        self::$client_ip = visitorIp();
        if(array_key_exists(self::$client_ip, self::$index))
        {
            if(show_cms_protect_debug)
                DebugConsole::insert_info('cms_protect::reset_login_search()', 'Reset login search from "'.self::$client_ip.'"');

            unset(self::$index[self::$client_ip]);
        }
    }

    //Erkennt versuche, um Logins herauszufinden
    public static final function detect_login_search_run()
    {
        if(!use_dzcp_protect) return;
        self::$client_ip = visitorIp();
        if(!is_array(self::$index) || !count(self::$index)) return;
        if(array_key_exists(self::$client_ip, self::$index))
        {
            if(count(self::$index[self::$client_ip]) >= 1)
            {
                $temp_save = time(); $temp_user = array(); $temp_username = '';
                $temp_time_block = false; $temp_user_block = false;
                foreach(self::$index[self::$client_ip] as $login)
                {
                    //Zeit difference ist zu klein, ein Bot der Logins ausprobiert?
                    if($login['username'] != $temp_username && !$temp_time_block && use_protect_block_timer && abs($temp_save - $login['time']) <= max_protect_time_diff)
                        $temp_time_block = true;

                    $temp_save = $login['time'];
                    $temp_username = $login['username'];

                    //Zu viele unterschiedliche Usernamen ausprobiert?
                    if(!key_exists($login['username'], $temp_user))
                        $temp_user[$login['username']] = true;

                    if(use_protect_block_user && count($temp_user) >= max_protect_users)
                        $temp_user_block = true;
                }

                //Keine Local IPS bannen
                if(($temp_time_block || $temp_user_block) && !validateIpV4Range(self::$client_ip, '[192].[168].[0-255].[0-255]') && !validateIpV4Range(self::$client_ip, '[127].[0].[0-255].[0-255]') && !validateIpV4Range(self::$client_ip, '[10].[0-255].[0-255].[0-255]') && !validateIpV4Range(self::$client_ip, '[172].[16-31].[0-255].[0-255]'))
                {
                    $systemban = array();
                    $systemban['confidence'] = '0'; $systemban['frequency'] = '0';
                    $systemban['banned_msg'] = 'Autoblock by System';
                    if($temp_user_block) $systemban['banned_msg'] = 'Autoblock by System: Search too many usernames';
                    if($temp_time_block) $systemban['banned_msg'] = 'Autoblock by System: Login was too quickly';
                    db("INSERT INTO `".dba::get('ipban')."` SET `ip` = '".self::$client_ip."', `time` = '".time()."', `typ` = '2', `data` = '".bin2hex(array_to_string($systemban))."';"); //Banned
                    self::reset_login_search();
                    die('Deine IP ist gesperrt!<p>Your IP is banned!<p><b>'.$temp_time_block ? 'Your Login was too quickly' : 'You use too many usernames!'.'</b>');
                }
            }
        }
    }

    public static final function load()
    {
        if(Cache::use_cache())
            self::$index = !Cache::check('cms_protect') ? string_to_array(Cache::get('cms_protect')) : self::$index;
        else
        {
            if(!class_exists('cache_mysql'))
                require_once(basePath.'/inc/additional-kernel/cache/class_cache_mysql.php');

            self::$index = (!cache_mysql::mysqlc_check('cms_protect') ? string_to_array(cache_mysql::mysqlc_get('cms_protect')) : self::$index);
        }

        if(show_cms_protect_debug)
            DebugConsole::insert_info('cms_protect::load()', 'Index loaded "'.count(self::$index).'" indexes');
    }

    public static final function save()
    {
        if(show_cms_protect_debug)
            DebugConsole::insert_info('cms_protect::save()', 'Index saved "'.count(self::$index).'" ip for Save');

        if(Cache::use_cache())
        {
            if(Cache::set('cms_protect',array_to_string(self::$index),max_protect_cache_time))
            {
                if(show_cms_protect_debug)
                    DebugConsole::insert_successful('cms_protect::save()', 'Index saved!');
            }
            else
            {
                if(show_cms_protect_debug)
                    DebugConsole::insert_error('cms_protect::save()', 'Index save failed!');
            }
        }
        else
        {
            if(!class_exists('cache_mysql'))
                require_once(basePath.'/inc/additional-kernel/cache/class_cache_mysql.php');

            if(cache_mysql::mysqlc_set('cms_protect',array_to_string(self::$index),max_protect_cache_time))
            {
                if(show_cms_protect_debug)
                    DebugConsole::insert_successful('cms_protect::save()', 'Index saved!');
            }
            else
            {
                if(show_cms_protect_debug)
                    DebugConsole::insert_error('cms_protect::save()', 'Index save failed!');
            }
        }
    }
}
