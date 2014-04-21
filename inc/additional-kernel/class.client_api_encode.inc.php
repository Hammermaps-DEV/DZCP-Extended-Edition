<?php
/**
 * <DZCP-Extended Edition>
 * @package: DZCP-Extended Edition
 * @author: DZCP Developer Team || Hammermaps.de Developer Team
 * @link: http://www.dzcp.de || http://www.hammermaps.de
 */

/*
### Encode ###
public function client_encode($input_array); # Return: false or hexcode #
public function client_encode_cryptkey($key); # Return: false or true #
public function set_options($key,$var) # Return: false or true #
*/

####################################################
################## Daten codieren ##################
####################################################
final class client_api_encode
{
    protected static $hex_stream_out = null;
    protected static $bz_stream_out = null;
    protected static $json_stream = null;
    protected static $input_array_in = null;
    protected static $mcrypt_string = null;
    protected static $crypt_key = null;
    private static $options = array();
    private static $initialize = false;

    public static final function init()
    {
        if(!self::$initialize)
        {
            self::$initialize = true;
            DebugConsole::insert_initialize('client_api_encode::init()', 'API-Encoder');
        }

        if (!extension_loaded('json'))
        {
            DebugConsole::insert_error('client_api_encode::init()', "Die JSON Erweiterung ist nicht geladen!");
            return false;
        }

        if (!extension_loaded('mcrypt'))
        {
            DebugConsole::insert_error('client_api_encode::init()', "Die Mcrypt Erweiterung ist nicht geladen!");
            return false;
        }

        self::$options['encode_hex'] = true;
        self::$options['encode_gzip'] = true;
        self::$options['encode_crypt'] = true;
        self::$options['encode_base'] = true;

        return true;
    }

    public static final function set_options($key="",$var='')
    {
        if(array_key_exists($key, self::$options))
        {
            self::$options[$key] = $var;
            return true;
        }

        return false;
    }

    public static final function client_encode_cryptkey($key="")
    {
        if(empty($key)) return false;
        self::$crypt_key = md5($key);
        return (empty(self::$crypt_key) ? false : true);
    }

    public static final function client_encode($input_array=array())
    {
        if(empty($input_array) || !is_array($input_array)) return false;
        self::$input_array_in = $input_array;
        if(!self::encode_base()) return false;
        if(!self::encode_crypt()) return false;
        if(!self::encode_gzip()) return false;
        if(!self::encode_hex()) return false;
        return self::$hex_stream_out;
    }

    private static final function encode_base()
    {
        self::$json_stream = self::$options['encode_base'] ? array_to_string(self::$input_array_in) : self::$input_array_in;
        return (empty(self::$json_stream) || !self::$json_stream ? false : true);
    }

    private static final function encode_crypt()
    {
        if(empty(self::$json_stream)) return false;
        self::$mcrypt_string = self::$options['encode_crypt'] ? session::encrypt(self::$json_stream,self::$crypt_key) : self::$json_stream;
        return (empty(self::$mcrypt_string) || !self::$mcrypt_string ? false : true);
    }

    private static final function encode_gzip()
    {
        if(empty(self::$mcrypt_string)) return false;
        self::$bz_stream_out = self::$options['encode_gzip'] ? gzcompress(self::$mcrypt_string) : self::$mcrypt_string;
        return (empty(self::$bz_stream_out) || !self::$bz_stream_out ? false : true);
    }

    private static final function encode_hex()
    {
        if(empty(self::$bz_stream_out)) return false;
        self::$hex_stream_out = self::$options['encode_hex'] ? bin2hex(self::$bz_stream_out) : self::$bz_stream_out;
        return (empty(self::$hex_stream_out) || !self::$hex_stream_out ? false : true);
    }
}