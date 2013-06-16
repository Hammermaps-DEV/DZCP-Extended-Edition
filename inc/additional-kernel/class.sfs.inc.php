<?php
/**
 * <DZCP-Extended Edition>
 * @package: DZCP-Extended Edition
 * @author: DZCP Developer Team || Hammermaps.de Developer Team
 * @link: http://www.dzcp.de || http://www.hammermaps.de
 */

class sfs
{
    private static $endpoint = 'http://www.stopforumspam.com/';
    private static $url = '';
    private static $json = '';
    private static $confidence = 70;
    private static $frequency = 50;
    private static $autoblock = true;
    private static $blockuser = false;

    public static function check()
    {
        global $userid;
        $userIP = visitorIp();

        ## http://de.wikipedia.org/wiki/Private_IP-Adresse ##
        if(!validateIpV4Range($userIP, '[192].[168].[0-255].[0-255]') && !validateIpV4Range($userIP, '[127].[0].[0-255].[0-255]') && !validateIpV4Range($userIP, '[10].[0-255].[0-255].[0-255]') && !validateIpV4Range($userIP, '[172].[16-31].[0-255].[0-255]'))
        {
            $sql = db("SELECT * FROM `".dba::get('ipban')."` WHERE `ip` = '".$userIP."' LIMIT 1");
            if(_rows($sql) >= 1)
            {
                $get = _fetch($sql);
                if((time()-$get['time']) > (2*86400) && $get['enable']) //48H
                {
                    self::get(array('ip' => $userIP)); //Array ( [success] => 1 [ip] => Array ( [lastseen] => 2013-04-26 19:57:51 [frequency] => 1327 [appears] => 1 [confidence] => 99.89 ) )
                    $stopforumspam = self::$json;
                    if($stopforumspam['success'])
                    {
                        $stopforumspam = $stopforumspam['ip']; // Array ( [lastseen] => 2013-04-26 19:57:51 [frequency] => 1327 [appears] => 1 [confidence] => 99.89 )
                        $stopforumspam_data_db = string_to_array(convert::UTF8_Reverse(base64_decode($get['data'])));
                        if($stopforumspam['appears'] == '1' && $stopforumspam['confidence'] >= self::$confidence && $stopforumspam['frequency'] >= self::$frequency && self::$autoblock)
                        {
                            print_r($stopforumspam);
                            $stopforumspam_data_db['confidence'] = $stopforumspam['confidence'];
                            $stopforumspam_data_db['frequency'] = $stopforumspam['frequency'];
                            $stopforumspam_data_db['lastseen'] = $stopforumspam['lastseen'];
                            $stopforumspam_data_db['banned_msg'] = 'Autoblock by stopforumspam.com';
                            db("UPDATE `".dba::get('ipban')."` SET `time` = '".time()."', `typ` = '1', `data` = '".base64_encode(convert::UTF8(array_to_string($stopforumspam_data_db)))."' WHERE `id` = '".$get['id']."';");
                            self::$blockuser = true;
                        }
                        else
                        {
                            $stopforumspam_data_db['appears'] = $stopforumspam['appears'];
                            db("UPDATE `".dba::get('ipban')."` SET `time` = '".time()."', `typ` = '0', `data` = '".base64_encode(convert::UTF8(array_to_string($stopforumspam_data_db)))."' WHERE `id` = '".$get['id']."';");
                            self::$blockuser = false;
                        }
                    }
                }
                else if($get['typ'] == 1)
                    self::$blockuser = true;
                else
                    self::$blockuser = false;
            }
            else
            {
                //typ: 0 = Off, 1 = GSL, 2 = SysBan, 3 = Ipban
                self::get(array('ip' => $userIP)); //Array ( [success] => 1 [ip] => Array ( [lastseen] => 2013-04-26 19:57:51 [frequency] => 1327 [appears] => 1 [confidence] => 99.89 ) )
                $stopforumspam = self::$json;
                if($stopforumspam['success'])
                {
                    $stopforumspam = $stopforumspam['ip']; // Array ( [lastseen] => 2013-04-26 19:57:51 [frequency] => 1327 [appears] => 1 [confidence] => 99.89 )
                    if($stopforumspam['appears'] == '1' && $stopforumspam['confidence'] >= self::$confidence && $stopforumspam['frequency'] >= self::$frequency && self::$autoblock)
                    {
                        $stopforumspam['banned_msg'] = 'Autoblock by stopforumspam.com';
                        db("INSERT INTO `".dba::get('ipban')."` SET `ip` = '".$userIP."', `time` = '".time()."', `typ` = '1', `data` = '".base64_encode(convert::UTF8(array_to_string($stopforumspam)))."';"); //Banned
                        self::$blockuser = true;
                    }
                    else
                    {
                        $stopforumspam['banned_msg'] = '';
                        db("INSERT INTO `".dba::get('ipban')."` SET `ip` = '".$userIP."', `time` = '".time()."',`typ` = '0', `data` = '".base64_encode(convert::UTF8(array_to_string($stopforumspam)))."';"); //Add to DB
                        self::$blockuser = false;
                    }
                }
            }
        }
    }

    public static function is_spammer()
    { return self::$blockuser; }

    public static function get( $args = array() )
    {
        self::$url = self::$endpoint.'api?f=json&'.http_build_query($args, '', '&');
        if(!self::call_json()) return array('data' => array('success' => '0'));
    }

    protected static function call_json()
    {
        self::$json = fileExists(self::$url);
        if(!self::$json || empty(self::$json))
            return false;

        self::$json = json_decode(self::$json,true);
        return true;
    }
}