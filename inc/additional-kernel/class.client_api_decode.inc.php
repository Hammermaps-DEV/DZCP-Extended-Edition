<?php
/**
 * <DZCP-Extended Edition>
 * @package: DZCP-Extended Edition
 * @author: DZCP Developer Team || Hammermaps.de Developer Team
 * @link: http://www.dzcp.de || http://www.hammermaps.de

/*
### Decode ###
public function client_decode($bzip_stream); # Return: false or array() #
public function client_decode_cryptkey($key); # Return: false or true #
public function set_options($key,$var) # Return: false or true #
*/

####################################################
################# Daten decodieren #################
####################################################
final class client_api_decode
{
    protected static $hex_stream_in = null;
    protected static $gz_stream = null;
    protected static $json_stream = null;
    protected static $mcrypt_string = null;
    protected static $crypt_key = null;
    protected static $output = null;
    private static $options = array();

    public static final function init()
    {
        DebugConsole::insert_initialize('client_api_decode::init()', 'API-Decoder');

        if (!extension_loaded('json'))
        {
            DebugConsole::insert_error('client_api_decode::init()', "Die JSON Erweiterung ist nicht geladen!");
            return false;
        }

        if (!extension_loaded('mcrypt'))
        {
            DebugConsole::insert_error('client_api_decode::init()', "Die Mcrypt Erweiterung ist nicht geladen!");
            return false;
        }

        self::$options['decode_hex'] = true;
        self::$options['decode_gzip'] = true;
        self::$options['decode_crypt'] = true;
        self::$options['decode_base'] = true;

        return true;
    }

    public static final function client_decode_cryptkey($key="")
    {
        if(empty($key)) return false;
        self::$crypt_key = md5($key);
        return (empty(self::$crypt_key) ? false : true);
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

    public static final function client_decode($hex_stream=null)
    {
        self::$hex_stream_in = $hex_stream;
        if(empty(self::$hex_stream_in)) return false;

        if(!self::decode_hex()) return false;
            self::$hex_stream_in = null;

        if(!self::decode_gzip()) return false;
            self::$gz_stream = null;

        if(!self::decode_crypt()) return false;
            self::$mcrypt_string = null;

        if(!self::decode_base()) return false;
            self::$json_stream = null;

        return self::$output;
    }

    private static final function decode_base()
    {
        self::$output = self::$options['decode_base'] ? string_to_array(self::$json_stream) : self::$json_stream;
        return (empty(self::$output) || !is_array(self::$output) || !self::$output ? false : true);
    }

    private static final function decode_crypt()
    {
        if(empty(self::$mcrypt_string)) return false;
        self::$json_stream = self::$options['decode_crypt'] ? decryptData(self::$mcrypt_string,self::$crypt_key) : self::$mcrypt_string;
        return (empty(self::$json_stream) ? false : true);
    }

    private static final function decode_gzip()
    {
        if(empty(self::$gz_stream)) return false;
        self::$mcrypt_string = self::$options['decode_gzip'] ? @gzuncompress(self::$gz_stream) : self::$gz_stream;
        return (empty(self::$mcrypt_string) || !self::$mcrypt_string ? false : true);
    }

    private static final function decode_hex()
    {
        if(empty(self::$hex_stream_in)) return false;
        self::$gz_stream = self::$options['decode_hex'] ? hextobin(self::$hex_stream_in) : self::$hex_stream_in;
        return (empty(self::$gz_stream) || !self::$gz_stream ? false : true);
    }
}