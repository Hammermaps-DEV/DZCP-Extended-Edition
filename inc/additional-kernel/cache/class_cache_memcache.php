<?php
/**
 * <DZCP-Extended Edition>
 * @package: DZCP-Extended Edition
 * @author: DZCP Developer Team || Hammermaps.de Developer Team
 * @link: http://www.dzcp.de || http://www.hammermaps.de
 */

## Install ##
Cache::installType('memcache',array('TypeName' => 'Memcache','CallTag' => 'mem_','Class' => 'cache_memcache','InitCache' => true,'SetServer' => true,'Required' => 'memcache'));

class cache_memcache extends Cache
{
    protected static $_memcached = NULL;
    private static $_memconfig;
    private static $_hash;

    public static function initC()
    {
        if(count(self::$_memconfig) == 1)
        {
            if(ping_port(self::$_memconfig[self::$_hash]['host'],self::$_memconfig[self::$_hash]['port'],0.1))
            {
                self::$_memcached = @memcache_connect(self::$_memconfig[self::$_hash]['host'], self::$_memconfig[self::$_hash]['port']);
                return true;
            }

            return false;
        }
        else
        {
            $ok=true;
             foreach (self::$_memconfig as $key =>$wert)
            {
                 if(!@memcache_add_server(self::$_memcached, $wert['host'], $wert['port']))
                     $ok=false;
            }

            return $ok;
        }

        return false;
    }

    /**
     * Trage Memcache Server ein
     *
     * @return boolean
     */
    public static function mem_server()
    {
        $settings = settings(array('memcache_host','memcache_port'));
        if(empty($settings['memcache_host']) || empty($settings['memcache_port']))
            return false;

        self::$_hash = md5($settings['memcache_host'].$settings['memcache_port']);
        self::$_memconfig[self::$_hash] = array("host" => $settings['memcache_host'], "port" => $settings['memcache_port']);
        return true;
    }

    /**
     * Speichere Werte im Memcache
     *
     * @return boolean
     */
    public static function mem_set($key, $data, $ttl = 3600)
    {
        //Array Erkennung
        if(is_array($data))
        {
            $data = array_to_string($data);
            self::control_set($key,$ttl,array('is_array' => true));
        }
        else
            self::control_set($key,$ttl,array('is_array' => false));

        $data = gzcompress(utf8_encode($data));
        if(@memcache_get(self::$_memcached,$key))
        {
            if(@memcache_replace(self::$_memcached, md5($key), $data, false, $ttl))
                return true;
            else
                return false;
        }
        else
        {
            if(@memcache_set(self::$_memcached, md5($key), $data, false, $ttl))
                return true;
            else
                return false;
        }
    }

    /**
     * *Binary* Speichere Werte im Memcache
     *
     * @return boolean
     */
    public static function mem_set_binary($key, $binary, $original_file=false, $ttl = 86400)
    {
        $key = 'bin_'.$key;
        $file_hash = $original_file && !empty($original_file) ? md5_file(basePath.'/'.$original_file) : false; $data = bin2hex($binary);
        self::control_set($key,$ttl,array('stream_hash' => $file_hash, 'original_file' => $original_file));

        if(@memcache_get(self::$_memcached,$key))
        {
            if(@memcache_replace(self::$_memcached, md5($key), $data, MEMCACHE_COMPRESSED, $ttl))
                return true;
            else
                return false;
        }
        else
        {
            if(@memcache_set(self::$_memcached, md5($key), $data, MEMCACHE_COMPRESSED, $ttl))
                return true;
            else
                return false;
        }
    }

    private static function control_set($key,$ttl,$settings_array=array())
    {
        $control = array_to_string($settings_array);
        $control = gzcompress(convert::UTF8($control));

        if(@memcache_get(self::$_memcached,'control_'.md5($key)))
            @memcache_replace(self::$_memcached, 'control_'.md5($key), $control, false, $ttl+1);
        else
            @memcache_set(self::$_memcached, 'control_'.md5($key), $control, false, $ttl+1);
    }

    private static function control_get($key)
    {
        $data = @memcache_get(self::$_memcached,'control_'.md5($key));
        return string_to_array(convert::UTF8_Reverse(gzuncompress($data)));
    }

    /**
     * Pr�ft ob Wert verf�gbar ist und nicht abgelaufen
     *
     * @return boolean
     */
    public static function mem_check($key)
    {
        $data = @memcache_get(self::$_memcached,md5($key));
        return $data && !empty($data) ? false : true;
    }

    /**
     * *Binary* Pr�ft ob Wert verf�gbar ist und nicht abgelaufen
     *
     * @return boolean
     */
    public static function mem_check_binary($key)
    {
        $key = 'bin_'.$key;
        $data = @memcache_get(self::$_memcached,md5($key));
        if($data && !empty($data))
        {
            unset($data);
            $control = self::control_get($key);

            if(empty($control['stream_hash']) || empty($control['original_file']))
                return true;

            if(!file_exists(basePath.'/'.$control['original_file']))
                return true;

            if(convert::ToString(md5_file(basePath.'/'.$control['original_file'])) != $control['stream_hash'])
                return true;

            return false;
        }

        return true;
    }

    /**
     * Lese Werte vom Memcache
     *
     * @return string or boolean
     */
    public static function mem_get($key)
    {
        $data = @memcache_get(self::$_memcached,md5($key));
        if(!$data || empty($data))
            return '';

        $data = convert::UTF8_Reverse(gzuncompress($data));
        $control = self::control_get($key);

        //Array Erkennung
        if($control['is_array'])
            $data = string_to_array($data);

        return $data;
    }

    /**
     * *Binary* Lese Werte vom Memcache
     *
     * @return string or boolean
     */
    public static function mem_get_binary($key)
    {
        $key = 'bin_'.$key;
        $data = @memcache_get(self::$_memcached,md5($key));

        if($data !='' && !empty($data))
        {
            if(!$stream = hextobin($data))
                return false;

            return $stream;
        }
        else
            return false;
    }

    /**
     * L�sche Werte vom Memcache
     *
     * @return boolean
     */
    public static function mem_delete($key)
    { @memcache_delete(self::$_memcached,'control_'.md5($key)); return @memcache_delete(self::$_memcached,md5($key)); }

    /**
     * L�sche Werte vom Memcache
     *
     * @return boolean
     */
    public static function mem_delete_binary($key)
    { $key = 'bin_'.$key; @memcache_delete(self::$_memcached,'control_'.md5($key)); return @memcache_delete(self::$_memcached,md5($key)); }

    /**
     * CleanUp vom Memcache.
     *
     * @return boolean
     */
    public static function mem_clean()
    { return @memcache_flush(self::$_memcached); }
}
?>
