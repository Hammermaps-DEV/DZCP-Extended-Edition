<?php
/**
 * <DZCP-Extended Edition>
 * @package: DZCP-Extended Edition
 * @author: DZCP Developer Team || Hammermaps.de Developer Team
 * @link: http://www.dzcp.de || http://www.hammermaps.de
 */

## Install ##
Cache::installType('mysql',array('TypeName' => 'MySQL Cache','CallTag' => 'mysqlc_','Class' => 'cache_mysql','InitCache' => false,'SetServer' => false,'Required' => 'mysql', 'CacheType' => 'mem'));

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
        if(empty($ttl) || !is_int($ttl)) $ttl = 5;

        //Array Erkennung
        if(is_array($data))
        {
            $data = array_to_string($data);
            $is_array = '1';
        }
        else
        {
            $data = convert::UTF8($data);
            $is_array = '0';
        }

        $data = bin2hex($data);
        if(db("SELECT qry FROM `".dba::get('cache')."` WHERE `qry` = '".md5($key)."' LIMIT 1", true))
        {
            if(db("UPDATE `".dba::get('cache')."` SET `data` = '".$data."', `timestamp` = '".time()."', `cacheTime` = '".$ttl."', `array` = '".$is_array."' WHERE `qry` = '".md5($key)."'"))
                return true;
        }
        else
        {
            if(db("INSERT INTO `".dba::get('cache')."` (`qry` ,`data` ,`timestamp` ,`cacheTime` ,`array`, `stream_hash`, `original_file`) VALUES ( '".md5($key)."', '".$data."', '".time()."', '".$ttl."', '".$is_array."', '', '');"))
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
        $key = 'bin_'.$key;
        if((empty($ttl) && $ttl != 0) || !is_int($ttl)) $ttl = 5;
        $original_file = (!$original_file || empty($original_file) ? '' : $original_file);
        $file_hash = $original_file && !empty($original_file) ? md5_file(basePath.'/'.$original_file) : false; $data = bin2hex($binary);
        if(db("SELECT qry FROM `".dba::get('cache')."` WHERE `qry` = '".md5($key)."' LIMIT 1", true))
        {
            if(db("UPDATE `".dba::get('cache')."` SET `data` = '".$data."', `timestamp` = '".time()."', `cacheTime` = '".$ttl."', `array` = '0', `stream_hash` = '".$file_hash."', `original_file` = '".$original_file."' WHERE `qry` = '".md5($key)."'"))
                return true;
        }
        else
        {
            if(db("INSERT INTO `".dba::get('cache')."` (`qry` ,`data` ,`timestamp` ,`cacheTime` ,`array`, `stream_hash`, `original_file`) VALUES ( '".md5($key)."', '".$data."', '".time()."', '".$ttl."', '0', '".$file_hash."', '".$original_file."');"))
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
        $GetCache=db('SELECT data,array FROM '.dba::get('cache').' WHERE qry="'.md5($key).'" LIMIT 1', false, true);

        if(!isset($GetCache['data']))
            return false;

        if ($GetCache['data']!='' && !empty($GetCache['data']))
        {
            $data = hextobin($GetCache['data']);
            return $GetCache['array'] ? string_to_array($data) : convert::UTF8_Reverse($data);
        }

        return false;
    }

    /**
     * *Binary* Lese Binary Code aus der MYSQL Cache.
     *
     * @return binary or boolean
     */
    public static function mysqlc_get_binary($key)
    {
        $key = 'bin_'.$key;
        $GetCache=db('SELECT data FROM '.dba::get('cache').' WHERE qry="'.md5($key).'" LIMIT 1', false, true);

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
        $sqlCache=db('SELECT cacheTime FROM '.dba::get('cache').' WHERE qry="'.md5($key).'" LIMIT 1');

        if(!_rows($sqlCache))
            return true;

        $GetCache = _fetch($sqlCache);
        if(!isset($GetCache['cacheTime']))
            return true;

        $IsValid=db('SELECT qry FROM '.dba::get('cache').' WHERE qry="'.md5($key).'" AND timestamp>'.(time()-$GetCache['cacheTime']).' LIMIT 1', true);
        return ($IsValid ? false : true);
    }

    /**
     * *Binary* Prüft ob Wert verfügbar ist und nicht abgelaufen oder verändert.
     *
     * @return boolean
     */
    public static function mysqlc_check_binary($key)
    {
        $key = 'bin_'.$key;
        $sqlCache=db('SELECT cacheTime,stream_hash,original_file FROM '.dba::get('cache').' WHERE qry="'.md5($key).'" LIMIT 1');

        if(!_rows($sqlCache))
            return true;

        $GetCache = _fetch($sqlCache);

        if(empty($GetCache['stream_hash']) && !empty($GetCache['original_file']))
            return true;

        if(!empty($GetCache['original_file']) && !file_exists(basePath.'/'.$GetCache['original_file']))
            return true;

        if(!empty($GetCache['original_file']) && convert::ToString(md5_file(basePath.'/'.$GetCache['original_file'])) != $GetCache['stream_hash'])
            return true;

        if(!isset($GetCache['cacheTime']))
            return true;

        if($GetCache['cacheTime'] != 0)
        {
            $IsValid=db('SELECT qry FROM '.dba::get('cache').' WHERE qry="'.md5($key).'" AND timestamp > '.(time()-$GetCache['cacheTime']).' LIMIT 1', true);
            return ($IsValid ? false : true);
        }

        return false;
    }

    /**
     * Lösche Werte vom MYSQL Cache.
     *
     * @return boolean
     */
    public static function mysqlc_delete($key)
    {
        return db('DELETE FROM '.dba::get('cache').' WHERE qry="'.md5($key).'" LIMIT 1') ? true : false;
    }

    /**
     * *Binary* Lösche Werte vom MYSQL Cache.
     *
     * @return boolean
     */
    public static function mysqlc_delete_binary($key)
    {
        $key = 'bin_'.$key;
        return db('DELETE FROM '.dba::get('cache').' WHERE qry="'.md5($key).'" LIMIT 1') ? true : false;
    }

    /**
     * CleanUp vom MYSQL Cache.
     *
     * @return boolean
     */
    public static function mysqlc_clean()
    {
        return db('TRUNCATE TABLE '.dba::get('cache')) ? true : false;
    }
}