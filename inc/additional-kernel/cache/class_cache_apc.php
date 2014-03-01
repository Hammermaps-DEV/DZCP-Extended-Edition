<?php
/**
 * <DZCP-Extended Edition>
 * @package: DZCP-Extended Edition
 * @author: DZCP Developer Team || Hammermaps.de Developer Team
 * @link: http://www.dzcp.de || http://www.hammermaps.de
 */

## Install ##
Cache::installType('apc',array('TypeName' => 'APC','CallTag' => 'apc_','Class' => 'cache_apc','InitCache' => false,'SetServer' => false,'Required' => 'apc', 'CacheType' => 'mem'));

class cache_apc extends Cache
{
    /**
     * Speichere Werte im Alternative PHP Cache
     *
     * @return boolean
     */
    public static function apc_set($key, $data, $ttl = 3200)
    {
        if(empty($ttl) || !is_int($ttl)) $ttl = 5;

        //Array Erkennung
        if(is_array($data))
        {
            $data = array_to_string($data);
            self::control_set($key,$ttl,array('is_array' => true));
        }
        else
        {
            $data = convert::UTF8($data);
            self::control_set($key,$ttl,array('is_array' => false));
        }

        return (apc_store(md5($key), gzcompress(bin2hex($data)), $ttl) === true ? true : false);
    }

    /**
     * *Binary* Speichere Werte im Alternative PHP Cache
     *
     * @return boolean
     */
    public static function apc_set_binary($key, $binary, $original_file=false, $ttl = 0)
    {
        if((empty($ttl) && $ttl != 0) || !is_int($ttl)) $ttl = 5;
        $key = 'bin_'.$key;
        $original_file = (!$original_file || empty($original_file) ? '' : $original_file);
        $file_hash = $original_file && !empty($original_file) && cache_md5_file_check ? md5_file(basePath.'/'.$original_file) : false; $binary = bin2hex($binary);
        self::control_set($key,$ttl,array('stream_hash' => $file_hash, 'original_file' => $original_file));
        return (apc_store(md5($key), $binary, $ttl) === true ? true : false);
    }

    private static function control_set($key,$ttl,$settings_array=array())
    {
        $control = array_to_string($settings_array);
        apc_store('control_'.md5($key), gzcompress($control), $ttl+1);
    }

    private static function control_get($key)
    {
        $data = apc_fetch('control_'.md5($key));
        return !empty($data) ? string_to_array(gzuncompress($data)) : false;
    }

    /**
     * Prüft ob Wert verfügbar ist und nicht abgelaufen
     *
     * @return boolean
     */
    public static function apc_check($key)
    {
        $data = apc_fetch(md5($key));
        return $data && !empty($data) ? false : true;
    }

    /**
     * *Binary* Prüft ob Wert verfügbar ist und nicht abgelaufen
     *
     * @return boolean
     */
    public static function apc_check_binary($key)
    {
        $key = 'bin_'.$key;
        $data = apc_fetch(md5($key));
        $control = self::control_get($key);

        if(empty($control) || empty($data))
            return true;

        if(empty($control['stream_hash']) && !empty($control['original_file']))
            return true;

        if(cache_md5_file_check)
            if(!empty($control['original_file']) && !file_exists(basePath.'/'.$control['original_file']))
                return true;

        if(cache_md5_file_check)
            if(!empty($control['original_file']) && convert::ToString(md5_file(basePath.'/'.$control['original_file'])) != $control['stream_hash'])
                return true;

        return false;
    }

    /**
     * Lese Werte vom Alternative PHP Cache
     *
     * @return string or boolean
     */
    public static function apc_get($key)
    {
        $data = apc_fetch(md5($key));
        if(!$data || empty($data))
            return '';

        $data = hextobin(gzuncompress($data));
        $control = self::control_get($key);

        //Array Erkennung
        return $control['is_array'] ? string_to_array($data) : convert::UTF8_Reverse($data);
    }

    /**
     * *Binary* Lese Werte vom Alternative PHP Cache
     *
     * @return string or boolean
     */
    public static function apc_get_binary($key)
    {
        $key = 'bin_'.$key;
        $data = apc_fetch(md5($key));

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
     * Lösche Werte vom Alternative PHP Cache
     *
     * @return boolean
     */
    public static function apc_delete($key)
    { apc_delete('control_'.md5($key)); return apc_delete(md5($key)); }

    /**
     * Lösche Werte vom Alternative PHP Cache
     *
     * @return boolean
     */
    public static function apc_delete_binary($key)
    { $key = 'bin_'.$key; apc_delete('control_'.md5($key)); return apc_delete(md5($key)); }

    /**
     * CleanUp vom Alternative PHP Cache
     *
     * @return boolean
     */
    public static function apc_clean()
    { return apc_clear_cache(); }
}