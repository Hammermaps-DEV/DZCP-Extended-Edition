<?php
/**
 * <DZCP-Extended Edition>
 * @package: DZCP-Extended Edition
 * @author: DZCP Developer Team || Hammermaps.de Developer Team
 * @link: http://www.dzcp.de || http://www.hammermaps.de
 */

#client_api_encode::init();
#client_api_encode::client_encode_cryptkey('test12345');

#client_api_encode::set_options('encode_hex',false);
#client_api_encode::set_options('encode_gzip',false);
#client_api_encode::set_options('encode_crypt',false);
#client_api_encode::set_options('encode_base',false);

#die(client_api_encode::client_encode(array('test' => '123451', 'hhhh' => 'sdfsdfsd')));
// 789c014000bfff65b15eaee679a7ddc9944400b8657b52f677761a92dd7d7d8e2de5a0af12dd7d7345816c3fe2fea6ef4f8586becdabd1cedecf6f9d4914ab5c5a3099e0b675b0806123d5|12321412312

/*
 * Hex | GZip | Crypt | JSON | File Stream | Prozesstime | Data
 * 1|1|1|1|0|1231243245|789c014000bfff65b15eaee679a7ddc9944400b8657b52f677761a92dd7d7d8e2de5a0af12dd7d7345816c3fe2fea6ef4f8586becdabd1cedecf6f9d4914ab5c5a3099e0b675b0806123d5
 */

#client_api_communicate::send();

final class client_api_communicate
{
    private static $stream = null; //Hex + Control
    private static $data_stream = null; //Hex etc.
    private static $data = array();
    private static $options = array();
    private static $cryptkey = '';
    private static $apihost = 'localhost:80';
    private static $ident = 'adsdaasd';

    public static final function send($data='')
    {
        if(!fsockopen_support()) return false;
        if(!client_api_encode::init()) return false;
        if(!client_api_decode::init()) return false;
        DebugConsole::insert_info('client_api_communicate::send()', 'Send request');

        self::$data = $data;
        if(!self::encode()) return false;
        if(!self::wire_control()) return false;
        if(!(use_curl && extension_loaded('curl') ? self::send_curl() : self::send_fsockopen())) return false;
        if(!self::read_control()) return false;
        if(!self::decode()) return false;
        return (self::$data != false && !empty(self::$data) ? self::$data : false);
    }

    public static function download($url='',$save_to_file='',$return_binary=false)
    {
        if(use_curl && extension_loaded('curl'))
        {
            $timeout = 30; $curl = curl_init();
            if(!$curl) return false;
            curl_setopt($curl, CURLOPT_HEADER, false);
            curl_setopt($curl, CURLOPT_URL, 'http://'.$url);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_CONNECTTIMEOUT , $timeout);
            curl_setopt($curl, CURLOPT_TIMEOUT, $timeout * 2); // x 2
            $stream = curl_exec($curl);
            curl_close($curl);

            if(!empty($stream))
            {
                if($return_binary) return $stream;
                file_put_contents(basePath.'/'.$save_to_file, $stream);
            }
        }
        else
        {
            $snoopy = new Snoopy;
            $snoopy->rawheaders["Pragma"] = "no-cache";
            $snoopy->submit('http://'.$url);
            $stream = $snoopy->results;

            if(!empty($stream))
            {
                if($return_binary) return $stream;
                file_put_contents(basePath.'/'.$save_to_file, $stream);
            }
        }
    }

    public static function set_api_url($host='',$port=80)
    { self::$apihost = $host.':'.$port; }

    public static function set_api_cryptkey($cryptkey='')
    { self::$cryptkey = $cryptkey; }

    public static function set_api_ident($identkey='')
    { self::$ident = $identkey; }

    private static final function send_curl()
    {
        $host_port = explode(':', self::$apihost);
        if(ping_port($host_port[0],$host_port[1],4))
        {
            $timeout = 30; $curl = curl_init();
            if(!$curl) return false;
            curl_setopt($curl, CURLOPT_HEADER, false);
            curl_setopt($curl, CURLOPT_URL, 'http://'.self::$apihost.'/DZCP-EE-API/index.php');
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_CONNECTTIMEOUT , $timeout);
            curl_setopt($curl, CURLOPT_TIMEOUT, $timeout * 2); // x 2
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, array('input' => self::$stream)); //Send Stream
            self::$stream = curl_exec($curl); //Get Stream
            DebugConsole::insert_info('client_api_communicate:send_curl()',self::$stream);
            curl_close($curl); if(!empty(self::$stream)) return true;
        }

        return false;
    }

    private static final function send_fsockopen()
    {
        $host_port = explode(':', self::$apihost);
        if(ping_port($host_port[0],$host_port[1],4))
        {
            $snoopy = new Snoopy;
            $snoopy->rawheaders["Pragma"] = "no-cache";
            $snoopy->submit('http://'.self::$apihost.'/DZCP-EE-API/index.php', array('input' => self::$stream)); //Send Stream
            self::$stream = $snoopy->results; //Get Stream
            DebugConsole::insert_info('client_api_communicate:send_curl()',self::$stream);
            unset($snoopy); if(!empty(self::$stream)) return true; return false;
        }
    }

    private static final function encode()
    {
        if(!empty(self::$cryptkey))
            client_api_encode::client_encode_cryptkey(self::$cryptkey);

        self::$options['encode_hex'] = true;
        self::$options['encode_gzip'] = true;
        self::$options['encode_crypt'] = !empty(self::$cryptkey) ? true : false;
        self::$options['encode_base'] = is_array(self::$data) ? true : false;
        self::$options['file_stream'] = false;
        self::$options['ident'] = self::$ident;

        //Encode
        client_api_encode::set_options('encode_hex',self::$options['encode_hex']);
        client_api_encode::set_options('encode_gzip',self::$options['encode_gzip']);
        client_api_encode::set_options('encode_crypt',self::$options['encode_crypt']);
        client_api_encode::set_options('encode_base',self::$options['encode_base']);
        self::$data_stream = client_api_encode::client_encode(self::$data); self::$data = null;
        if(!empty(self::$data_stream) && self::$data_stream != false) return true;

        return false;
    }

    private static final function decode()
    {
        if(self::$options['decode_crypt'])
            client_api_decode::client_decode_cryptkey(self::$cryptkey);

        client_api_decode::set_options('decode_hex',self::$options['decode_hex']);
        client_api_decode::set_options('decode_gzip',self::$options['decode_gzip']);
        client_api_decode::set_options('decode_crypt',self::$options['decode_crypt']);
        client_api_decode::set_options('decode_base',self::$options['decode_base']);
        self::$data = client_api_decode::client_decode(self::$data_stream); self::$data_stream = null;
        if(!empty(self::$data) && self::$data != false) return true;
        return false;
    }

    private static final function wire_control()
    {
        global $time_start;
        self::$stream =
        (self::$options['encode_hex'] ? '1' : '0').'|'. // Hex
        (self::$options['encode_gzip'] ? '1' : '0').'|'. // GZip
        (self::$options['encode_crypt'] ? '1' : '0').'|'. // Crypt
        (self::$options['encode_base'] ? '1' : '0').'|'. // JSON
        (self::$options['file_stream'] ? '1' : '0').'|'. // File Stream
        (generatetime() - $time_start).'|'. // Prozesstime
        self::$options['ident'].'|'. // Ident
        self::$data_stream; // Data
        self::$data_stream = null;
        if(!empty(self::$stream) && self::$stream != false) return true;
        return false;
    }

    private static final function read_control()
    {
        $data = explode('|', self::$stream, 8);
        self::$options['decode_hex'] = convert::IntToBool($data[0]);
        self::$options['decode_gzip'] = convert::IntToBool($data[1]);
        self::$options['decode_crypt'] = convert::IntToBool($data[2]);
        self::$options['decode_base'] = convert::IntToBool($data[3]);
        self::$options['file_stream'] = convert::IntToBool($data[4]);
        self::$options['prozesstime'] = convert::ToString($data[5]);
        self::$options['ident'] = $data[6];
        self::$data_stream = $data[7]; unset($data);
        self::$stream = null;
        if(!empty(self::$data_stream) && self::$data_stream != false) return true;
        return false;
    }
}