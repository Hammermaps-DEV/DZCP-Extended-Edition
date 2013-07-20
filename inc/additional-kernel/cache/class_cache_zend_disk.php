<?php
/**
 * <DZCP-Extended Edition>
 * @package: DZCP-Extended Edition
 * @author: DZCP Developer Team || Hammermaps.de Developer Team
 * @link: http://www.dzcp.de || http://www.hammermaps.de
 */

## Install ##
Cache::installType('zenddisk',array('TypeName' => 'ZEND Server - Disk Cache','CallTag' => 'disk_','Class' => 'cache_zend_disk','InitCache' => false,'SetServer' => false,'Required' => 'Zend Data Cache', 'CacheType' => 'file'));

class cache_zend_disk extends Cache
{
    /**
     * Speichere Werte im ZEND Disk
     *
     * @return boolean
     */
    public static function disk_set($key, $data, $ttl = 3600)
    {
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

        return (zend_disk_cache_store(md5($key), gzcompress(bin2hex($data)), $ttl) === true ? true : false);
    }

    /**
     * *Binary* Speichere Werte im ZEND Disk
     *
     * @return boolean
     */
    public static function disk_set_binary($key, $binary, $original_file=false, $ttl = 86400)
    {
        $key = 'bin_'.$key;
        $original_file = (!$original_file || empty($original_file) ? '' : $original_file);
        $file_hash = $original_file && !empty($original_file) ? md5_file(basePath.'/'.$original_file) : false; $binary = bin2hex($binary);
        self::control_set($key,$ttl,array('stream_hash' => $file_hash, 'original_file' => $original_file));
        return (zend_disk_cache_store(md5($key), $binary, $ttl) === true ? true : false);
    }

    private static function control_set($key,$ttl,$settings_array=array())
    {
        $control = array_to_string($settings_array);
        zend_disk_cache_store('control_'.md5($key), gzcompress($control), $ttl+1);
    }

    private static function control_get($key)
    {
        $data = zend_disk_cache_fetch('control_'.md5($key));
        return !empty($data) ? string_to_array(gzuncompress($data)) : false;
    }

    /**
     * Prüft ob Wert verfügbar ist und nicht abgelaufen
     *
     * @return boolean
     */
    public static function disk_check($key)
    {
        $data = zend_disk_cache_fetch(md5($key));
        return $data && !empty($data) ? false : true;
    }

    /**
     * *Binary* Prüft ob Wert verfügbar ist und nicht abgelaufen
     *
     * @return boolean
     */
    public static function disk_check_binary($key)
    {
        $key = 'bin_'.$key;
        $data = zend_disk_cache_fetch(md5($key));
        $control = self::control_get($key);

        if(empty($control) || empty($data))
            return true;

        if(empty($control['stream_hash']) && !empty($control['original_file']))
            return true;

        if(!empty($control['original_file']) && !file_exists(basePath.'/'.$control['original_file']))
            return true;

        if(!empty($control['original_file']) && convert::ToString(md5_file(basePath.'/'.$control['original_file'])) != $control['stream_hash'])
            return true;

        return false;
    }

    /**
     * Lese Werte vom ZEND Disk
     *
     * @return string or boolean
     */
    public static function disk_get($key)
    {
        $data = zend_disk_cache_fetch(md5($key));
        if(!$data || empty($data))
            return '';

        $data = hextobin(gzuncompress($data));
        $control = self::control_get($key);

        //Array Erkennung
        return $control['is_array'] ? string_to_array($data) : convert::UTF8_Reverse($data);
    }

    /**
     * *Binary* Lese Werte vom ZEND Disk
     *
     * @return string or boolean
     */
    public static function disk_get_binary($key)
    {
        $key = 'bin_'.$key;
        $data = zend_disk_cache_fetch(md5($key));

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
     * Lösche Werte vom ZEND Disk
     *
     * @return boolean
     */
    public static function disk_delete($key)
    { zend_disk_cache_delete('control_'.md5($key)); return zend_disk_cache_delete(md5($key)); }

    /**
     * Lösche Werte vom ZEND Disk
     *
     * @return boolean
     */
    public static function disk_delete_binary($key)
    { $key = 'bin_'.$key; zend_disk_cache_delete('control_'.md5($key)); return zend_disk_cache_delete(md5($key)); }

    /**
     * CleanUp vom ZEND Disk
     *
     * @return boolean
     */
    public static function disk_clean()
    { return zend_disk_cache_clear(); }
}