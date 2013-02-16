<?php
/**
 * <DZCP-Extended Edition>
 * @package: DZCP-Extended Edition
 * @author: DZCP Developer Team || Hammermaps.de Developer Team
 * @link: http://www.dzcp.de || http://www.hammermaps.de
 */

class cache_mysql extends Cache
{
    private static $_result = array();

    /**
     * Speichere Werte im MYSQL Cache.
     *
     * @return boolean
     */
    public static function mysqlc_set($key, $data, $ttl = 3600)
    {
        global $db;
        //Array Erkennung
        $is_array = '0';
        if(is_array($data))
        {
            $data = array_to_string($data);
            $is_array = '1';
        }

        $data = base64_encode(convert::UTF8($data));
        if(db("SELECT qry FROM `".$db['cache']."` WHERE `qry` = '".md5($key)."' LIMIT 1", true))
        {
            if(db("UPDATE `".$db['cache']."` SET `data` = '".$data."', `timestamp` = '".time()."', `cacheTime` = '".$ttl."', `array` = '".$is_array."' WHERE `qry` = '".md5($key)."'"))
                return true;
        }
        else
        {
            if(db("INSERT INTO `".$db['cache']."` (`qry` ,`data` ,`timestamp` ,`cacheTime` ,`array`, `stream_hash`, `original_file`) VALUES ( '".md5($key)."', '".$data."', '".time()."', '".$ttl."', '".$is_array."', '', '');"))
                return true;
        }

        return false;
    }

    /**
     * *Binary* Speichert Binary Code im MYSQL Cache.
     *
     * @return boolean
     */
    public static function mysqlc_set_binary($key, $binary, $original_file=false, $ttl = 0)
    {
        global $db;

        $key = 'bin_'.$key;
        $file_hash = $original_file && !empty($original_file) ? md5_file(basePath.'/'.$original_file) : false; $data = bin2hex($binary);
        if(db("SELECT qry FROM `".$db['cache']."` WHERE `qry` = '".md5($key)."' LIMIT 1", true))
        {
            if(db("UPDATE `".$db['cache']."` SET `data` = '".$data."', `timestamp` = '".time()."', `cacheTime` = '".$ttl."', `array` = '0', `stream_hash` = '".$file_hash."', `original_file` = '".$original_file."' WHERE `qry` = '".md5($key)."'"))
                return true;
        }
        else
        {
            if(db("INSERT INTO `".$db['cache']."` (`qry` ,`data` ,`timestamp` ,`cacheTime` ,`array`, `stream_hash`, `original_file`) VALUES ( '".md5($key)."', '".$data."', '".time()."', '".$ttl."', '0', '".$file_hash."', '".$original_file."');"))
                return true;
        }

        return false;
    }

    /**
     * Lese Werte vom MYSQL Cache.
     *
     * @return string or boolean
     */
    public static function mysqlc_get($key)
    {
        global $db;

        $GetCache=db('SELECT data,array FROM '.$db['cache'].' WHERE qry="'.md5($key).'" LIMIT 1', false, true);

        if(!isset($GetCache['data']))
            return false;

        if ($GetCache['data']!='' && !empty($GetCache['data']))
        {
            $data = convert::UTF8_Reverse(base64_decode($GetCache['data']));

            if($GetCache['array'])
                $data = string_to_array($data);

            return $data;
        }
        else
            return false;
    }

    /**
     * *Binary* Lese Binary Code aus der MYSQL Cache.
     *
     * @return binary or boolean
     */
    public static function mysqlc_get_binary($key)
    {
        global $db;

        $key = 'bin_'.$key;
        $GetCache=db('SELECT data FROM '.$db['cache'].' WHERE qry="'.md5($key).'" LIMIT 1', false, true);

        if(!isset($GetCache['data']))
            return false;

        if($GetCache['data']!='' && !empty($GetCache['data']))
        {
            if(!$stream = hextobin($GetCache['data']))
                return false;

            return $stream;
        }
        else
            return false;
    }

    /**
     * Prüft ob Wert verfügbar ist und nicht abgelaufen.
     *
     * @return boolean
     */
    public static function mysqlc_check($key)
    {
        global $db;
        $sqlCache=db('SELECT cacheTime FROM '.$db['cache'].' WHERE qry="'.md5($key).'" LIMIT 1');

        if(!_rows($sqlCache))
            return true;

        $GetCache = _fetch($sqlCache);
        if(!isset($GetCache['cacheTime']))
            return true;

        $IsValid=db('SELECT qry FROM '.$db['cache'].' WHERE qry="'.md5($key).'" AND timestamp>'.(time()-$GetCache['cacheTime']).' LIMIT 1', true);
        return ($IsValid ? false : true);
    }

    /**
     * *Binary* Prüft ob Wert verfügbar ist und nicht abgelaufen oder verändert.
     *
     * @return boolean
     */
    public static function mysqlc_check_binary($key)
    {
        global $db;

        $key = 'bin_'.$key;
        $sqlCache=db('SELECT cacheTime,stream_hash,original_file FROM '.$db['cache'].' WHERE qry="'.md5($key).'" LIMIT 1');

        if(!_rows($sqlCache))
            return true;

        $GetCache = _fetch($sqlCache);

        if(empty($GetCache['stream_hash']) || empty($GetCache['original_file']))
            return true;

        if(!file_exists(basePath.'/'.$GetCache['original_file']))
            return true;

        if(convert::ToString(md5_file(basePath.'/'.$GetCache['original_file'])) != $GetCache['stream_hash'])
            return true;

        if(!isset($GetCache['cacheTime']))
            return true;

        if($GetCache['cacheTime'] != 0)
        {
            $IsValid=db('SELECT qry FROM '.$db['cache'].' WHERE qry="'.md5($key).'" AND timestamp>'.(time()-$GetCache['cacheTime']).' LIMIT 1', true);
            return ($IsValid ? false : true);
        }
        else
            return false;
    }

    /**
     * Lösche Werte vom MYSQL Cache.
     *
     * @return boolean
     */
    public static function mysqlc_delete($key)
    {
        global $db;
        return db('DELETE FROM '.$db['cache'].' WHERE qry="'.md5($key).'" LIMIT 1') ? true : false;
    }

    /**
     * *Binary* Lösche Werte vom MYSQL Cache.
     *
     * @return boolean
     */
    public static function mysqlc_delete_binary($key)
    {
        global $db;
        $key = 'bin_'.$key;
        return db('DELETE FROM '.$db['cache'].' WHERE qry="'.md5($key).'" LIMIT 1') ? true : false;
    }

    /**
     * CleanUp vom MYSQL Cache.
     *
     * @return boolean
     */
    public static function mysqlc_clean()
    {
        global $db;
        return db('TRUNCATE TABLE '.$db['cache']) ? true : false;
    }
}
?>
