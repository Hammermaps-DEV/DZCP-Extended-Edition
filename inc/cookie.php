<?php
/**
 * <DZCP-Extended Edition>
 * @package: DZCP-Extended Edition
 * @author: DZCP Developer Team || Hammermaps.de Developer Team
 * @link: http://www.dzcp.de || http://www.hammermaps.de
 */

final class cookie
{
    private static $cname = "";
    private static $val = array();
    private static $expires;
    private static $dir = '/';
    private static $site = '';

    /**
    * Setzt die Werte für ein Cookie und erstellt es.
    */
    public final static function init($cname, $cexpires=false, $cdir="/", $csite="")
    {
        global $mysql_salt;
        if(array_key_exists('PHPSESSID', $_SESSION) && array_key_exists('PHPSESSID', $_COOKIE))
        {
            self::$cname=$cname;
            self::$expires = ($cexpires ? $cexpires : (time()+cookie_expires));
            self::$dir=$cdir;
            self::$site=$csite;
            self::$val=array();
            self::extract();
        }
    }

    /**
    * Extraktiert ein gespeichertes Cookie
    */
    public final static function extract($cname="")
    {
        global $mysql_salt;
        if(array_key_exists('PHPSESSID', $_SESSION) && array_key_exists('PHPSESSID', $_COOKIE))
        {
            $cname=(empty($cname) ? self::$cname : $cname);
            if(!empty($_COOKIE[$cname]))
            {
                $arr = unserialize(get_magic_quotes_gpc() ? stripslashes($_COOKIE[$cname]) : $_COOKIE[$cname]);
                if($arr!==false && is_array($arr))
                {
                    foreach($arr as $var => $val)
                    { $_COOKIE[$var]=$val; }
                }

                self::$val=$arr;
            }

            unset($_COOKIE[$cname]);
        }
    }

    /**
    * Liest und gibt einen Wert aus dem Cookie zurück
    *
    * @return string
    */
    public final static function get($var)
    {
        global $mysql_salt;
        if(array_key_exists('PHPSESSID', $_SESSION) && array_key_exists('PHPSESSID', $_COOKIE))
        {
            if(!isset(self::$val) || empty(self::$val))
                return false;

            if(!array_key_exists($var, self::$val))
                return false;

            return convert::UTF8_Reverse(self::$val[$var]);
        }

        return false;
    }

    /**
    * Setzt ein neuen Key und Wert im Cookie
    */
    public final static function put($var, $value)
    {
        global $mysql_salt;
        if(array_key_exists('PHPSESSID', $_SESSION) && array_key_exists('PHPSESSID', $_COOKIE))
        {
            self::$val[$var]=convert::UTF8($value);
            $_COOKIE[$var]=self::$val[$var];
            if(empty($value)) unset(self::$val[$var]);
        }
    }

    /**
    * Leert das Cookie
    */
    public final static function clear()
    { self::$val=array(); self::save(); }

    /**
    * Speichert das Cookie
    */
    public final static function save()
    {
        global $mysql_salt;
        if(array_key_exists('PHPSESSID', $_SESSION) && array_key_exists('PHPSESSID', $_COOKIE))
        {
            $cookie_val = (empty(self::$val) ? '' : serialize(self::$val));
            if(strlen($cookie_val)>4*1024)
                trigger_error("The cookie ".self::$cname." exceeds the specification for the maximum cookie size.  Some data may be lost", E_USER_WARNING);

            setcookie(self::$cname, $cookie_val, self::$expires, self::$dir, self::$site);
        }
    }
}