<?php
class cache_mysql extends Cache
{
    private static $_result           = array();

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

        $data = base64_encode(utf8_encode($data));
        if(db("SELECT qry FROM `".$db['cache']."` WHERE `qry` = '".md5($key)."' LIMIT 1", true))
        {
            if(db("UPDATE `".$db['cache']."` SET `data` = '".$data."', `timestamp` = '".time()."', `cacheTime` = '".$ttl."', `array` = '".$is_array."' WHERE `qry` = '".md5($key)."'"))
                return true;
        }
        else
        {
            if(db("INSERT INTO `".$db['cache']."` (`qry` ,`data` ,`timestamp` ,`cacheTime` ,`array`) VALUES ( '".md5($key)."', '".$data."', '".time()."', '".$ttl."', '".$is_array."');"))
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

        if ($GetCache['data']!='')
        {
            $data = utf8_decode(base64_decode($GetCache['data']));

            if($GetCache['array'])
                $data = string_to_array($data);

            return $data;
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
