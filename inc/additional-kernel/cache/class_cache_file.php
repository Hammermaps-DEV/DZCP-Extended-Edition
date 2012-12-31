<?php
class cache_file extends Cache
{
    private static $_file;
    private static $_dir;

    public static function initC()
    {
        if(!is_dir(basePath . self::$_dir))
            @mkdir(basePath . self::$_dir);

        xml::openXMLfile('cache_index', self::$_dir . "/cache.index");
    }

    /**
     * Trage Zielordner ein.
     */
    public static function file_server($dir="/inc/_cache")
    { self::$_dir = $dir; return true; }


    /**
     * Speichere Werte im FileCache.
     *
     * @return boolean
     */
    public static function file_set($key, $data, $ttl = 3600)
    {
        global $prev;
        $hash = md5($key.$prev);
        if(xml::getXMLvalue('cache_index', '/cache_index/file[@hash="'.$hash.'"]/ttl'))
            xml::changeXMLvalue('cache_index', '/cache_index/file[@hash="'.$hash.'"]', 'ttl', $ttl);
        else
        {
            xml::createXMLnode('cache_index', '/cache_index', 'file', array('hash'=>$hash));
            xml::createXMLnode('cache_index', '/cache_index/file[@hash="'.$hash.'"]', 'ttl', array(), $ttl);
        }

        //Array Erkennung
        if(is_array($data))
        {
            $data = array_to_string($data);

            if(xml::getXMLvalue('cache_index', '/cache_index/file[@hash="'.$hash.'"]/array'))
                xml::changeXMLvalue('cache_index', '/cache_index/file[@hash="'.$hash.'"]', 'array', 'yes');
            else
                xml::createXMLnode('cache_index', '/cache_index/file[@hash="'.$hash.'"]', 'array', array(), 'yes');
        }
        else
        {
            if(xml::getXMLvalue('cache_index', '/cache_index/file[@hash="'.$hash.'"]/array'))
                xml::changeXMLvalue('cache_index', '/cache_index/file[@hash="'.$hash.'"]', 'array', 'no');
            else
                xml::createXMLnode('cache_index', '/cache_index/file[@hash="'.$hash.'"]', 'array', array(), 'no');
        }

        xml::saveXMLfile('cache_index');
        self::$_file = basePath . self::$_dir.'/'.$hash.'.cache';

        if(file_put_contents(self::$_file, gzcompress(base64_encode(utf8_encode($data)))))
            return true;
        else
            return false;
    }

    /**
     * Prüft ob Wert verfügbar ist und nicht abgelaufen.
     *
     * @return boolean
     */
    public static function file_check($key)
    {
        global $prev;
        $hash = md5($key.$prev);
        $ttl=xml::getXMLvalue('cache_index','/cache_index/file[@hash="' . $hash . '"]/ttl');
        if($ttl != 0)
        {
            self::$_file = basePath . self::$_dir.'/'.$hash.'.cache';
            if((time()-@filemtime(self::$_file)) > $ttl)
            {
                unlink(self::$_file);
                xml::deleteXMLattribut('cache_index', '/cache_index', 'hash', $hash );
                xml::saveXMLfile('cache_index');
                return true;
            }
            else
                return false;
        }
        else
            return true;
    }

    /**
     * Lese Werte aus der FileCache.
     *
     * @return string or boolean
     */
    public static function file_get($key)
    {
        global $prev;
        $hash = md5($key.$prev);
        self::$_file = basePath . self::$_dir.'/'.$hash.'.cache';
        if(file_exists(self::$_file))
        {
            $stream = file_get_contents(self::$_file);
            if(!$stream) return false;

            if(!$stream = @gzuncompress($stream))
                return false;

            if(!$stream = @base64_decode($stream))
                return false;

            return xml::getXMLvalue('cache_index','/cache_index/file[@hash="' . $hash . '"]/array') == 'yes' ? string_to_array(utf8_decode($stream)) : utf8_decode($stream);
        }
        else
            return false;
    }

    /**
     * Lösche Werte vom FileCache.
     *
     * @return boolean
     */
    public static function file_delete($key)
    {
        global $prev;
        $hash = md5($key.$prev);
        self::$_file = basePath . self::$_dir.'/'.$hash.'.cache';
        if(file_exists(self::$_file))
        {
            unlink(self::$_file);
            xml::deleteXMLattribut('cache_index', '/cache_index', 'hash', $hash );
            xml::saveXMLfile('cache_index');
            return true;
        }
        else
            return false;
    }

    /**
     * CleanUp der FileCache.
     */
    public static function file_clean()
    {
        self::$_file = basePath . '/inc/_cache/';
        $files = get_files(self::$_file, false, true, array("cache","index"));
        if(count($files) >= 1)
        {
            foreach($files as $file)
            { @unlink(self::$_file . $file); }
        }
    }
}
?>
