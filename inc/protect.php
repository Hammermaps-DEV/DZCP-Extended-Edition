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
        self::$client_ip = visitorIp(); self::load();
        self::$index[self::$client_ip][] = array('username' => $username, 'time' => time());
        self::save();
    }

    public static final function reset_login_search()
    {
        if(!use_dzcp_protect) return;
        self::$client_ip = visitorIp(); self::load();
        if(array_key_exists(self::$client_ip, self::$index))
            unset(self::$index[self::$client_ip]);

        self::save();
    }

    //Erkennt versuche, um Logins herauszufinden
    public static final function detect_login_search_run()
    {
        if(!use_dzcp_protect) return;
        self::$client_ip = visitorIp(); self::load();
        if(!is_array(self::$index) || !count(self::$index)) return;
        if(array_key_exists(self::$client_ip, self::$index))
        {
            if(count(self::$index[self::$client_ip]) >= 1)
            {
                $temp_save = time(); $temp_user = array();
                $temp_time_block = false; $temp_user_block = false;
                foreach(self::$index[self::$client_ip] as $login)
                {
                    //Zeit difference ist zu klein, ein Bot der Logins ausprobiert?
                    if(!$temp_time_block && use_protect_block_timer && abs($temp_save - $login['time']) <= max_protect_time_diff) $temp_time_block = true; $temp_save = $login['time'];

                    //Zu viele unterschiedliche Usernamen ausprobiert?
                    if(!array_var_exists($login['username'], $temp_user)) $temp_user[] = $login['username'];
                    if(use_protect_block_user && count($temp_user) >= max_protect_users) $temp_user_block = true;
                }

                //Keine Local IPS bannen
                if(!validateIpV4Range(self::$client_ip, '[192].[168].[0-255].[0-255]') && !validateIpV4Range(self::$client_ip, '[127].[0].[0-255].[0-255]') && !validateIpV4Range(self::$client_ip, '[10].[0-255].[0-255].[0-255]') && !validateIpV4Range(self::$client_ip, '[172].[16-31].[0-255].[0-255]'))
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

    private static final function load()
    {
        if(Cache::use_cache())
            self::$index = (!Cache::check('cms_protect') ? Cache::get('cms_protect') : self::$index);
        else
        {
            if(!class_exists('cache_mysql'))
                require_once(basePath.'/inc/additional-kernel/cache/class_cache_mysql.php');

            self::$index = (!cache_mysql::mysqlc_check('cms_protect') ? cache_mysql::mysqlc_get('cms_protect') : self::$index);
        }
    }

    private static final function save()
    {
        if(Cache::use_cache())
             Cache::set('cms_protect',self::$index,max_protect_cache_time);
        else
        {
            if(!class_exists('cache_mysql'))
                require_once(basePath.'/inc/additional-kernel/cache/class_cache_mysql.php');

            cache_mysql::mysqlc_set('cms_protect',self::$index,max_protect_cache_time);
        }
    }
}