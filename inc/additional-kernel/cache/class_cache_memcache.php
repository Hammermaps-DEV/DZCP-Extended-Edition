<?php
class cache_memcache extends Cache
{
    private static $_memcached;
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
    }

    /**
     * Trage Memcache Server ein
     *
     * @return boolean
     */
    public static function mem_server($ip='127.0.0.1',$port=11211)
    {

        self::$_hash = md5($ip.$port);
        self::$_memconfig[self::$_hash] = array("host" => $ip, "port" => $port);
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

    private static function control_set($key,$ttl,$settings_array=array())
    {
        $control = array_to_string($settings_array);
        $control = gzcompress(utf8_encode($control));

        if(@memcache_get(self::$_memcached,'control_'.md5($key)))
            @memcache_replace(self::$_memcached, 'control_'.md5($key), $control, false, $ttl+1);
        else
            @memcache_set(self::$_memcached, 'control_'.md5($key), $control, false, $ttl+1);
    }

    private static function control_get($key)
    {
        $data = @memcache_get(self::$_memcached,'control_'.md5($key));
        return string_to_array(utf8_decode(gzuncompress($data)));
    }

    /**
     * Prüft ob Wert verfügbar ist und nicht abgelaufen
     *
     * @return boolean
     */
    public static function mem_check($key)
    {
        $data = @memcache_get(self::$_memcached,md5($key));
        return $data && !empty($data) ? false : true;
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

        $data = utf8_decode(gzuncompress($data));
        $control = self::control_get($key);

        //Array Erkennung
        if($control['is_array'])
            $data = string_to_array($data);

        return $data;
    }

    /**
     * Lösche Werte vom Memcache
     *
     * @return boolean
     */
    public static function mem_delete($key)
    { @memcache_delete(self::$_memcached,'control_'.md5($key)); return @memcache_delete(self::$_memcached,md5($key)); }

    /**
     * CleanUp vom Memcache.
     *
     * @return boolean
     */
    public static function mem_clean()
    { return @memcache_flush(self::$_memcached); }
}
?>
