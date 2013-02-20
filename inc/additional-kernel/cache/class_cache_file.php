<?php
/**
 * <DZCP-Extended Edition>
 * @package: DZCP-Extended Edition
 * @author: DZCP Developer Team || Hammermaps.de Developer Team
 * @link: http://www.dzcp.de || http://www.hammermaps.de
 */

## Install ##
Cache::installType('file',array('TypeName' => 'File Cache','CallTag' => 'file_','Class' => 'cache_file','InitCache' => true,'SetServer' => false,'Required' => ''));

class cache_file extends Cache
{
    private static $_file;
    private static $_dir;

    public static function initC()
    {
        self::$_dir = "/inc/_cache";

        if(!is_dir(basePath . self::$_dir))
            @mkdir(basePath . self::$_dir);

        xml::openXMLfile('cache_index', self::$_dir . "/cache.index");
        xml::openXMLfile('cache_index_binary', self::$_dir . "/binary/cache.index");
        return true;
    }

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

        if(file_put_contents(self::$_file, gzcompress(base64_encode(convert::UTF8($data)))))
            return true;
        else
            return false;
    }

    /**
     * *Binary* Speichert Binary Code im FileCache.
     *
     * @return boolean
     */
    public static function file_set_binary($key, $binary, $original_file=false, $ttl = 3600)
    {
        global $prev;
        $hash = md5($key.$prev);
        $file_hash = $original_file && !empty($original_file) ? md5_file(basePath.'/'.$original_file) : '';
        $file_hash_check = convert::ToString(xml::getXMLvalue('cache_index_binary', '/cache_index_binary/file[@hash="'.$hash.'"]/stream_hash'));

        if(!empty($file_hash_check))
        {
            xml::changeXMLvalue('cache_index_binary', '/cache_index_binary/file[@hash="'.$hash.'"]', 'ttl', $ttl);
            xml::changeXMLvalue('cache_index_binary', '/cache_index_binary/file[@hash="'.$hash.'"]', 'stream_hash', $file_hash);
            xml::changeXMLvalue('cache_index_binary', '/cache_index_binary/file[@hash="'.$hash.'"]', 'original_file', $original_file);
        }
        else
        {
            xml::createXMLnode('cache_index_binary', '/cache_index_binary', 'file', array('hash'=>$hash));
            xml::createXMLnode('cache_index_binary', '/cache_index_binary/file[@hash="'.$hash.'"]', 'ttl', array(), $ttl);
            xml::createXMLnode('cache_index_binary', '/cache_index_binary/file[@hash="'.$hash.'"]', 'stream_hash', array(), $file_hash);
            xml::createXMLnode('cache_index_binary', '/cache_index_binary/file[@hash="'.$hash.'"]', 'original_file', array(), $original_file);
        }

        xml::saveXMLfile('cache_index_binary');
        self::$_file = basePath . self::$_dir.'/binary/'.$hash.'.bin';

        if(file_put_contents(self::$_file, gzcompress(bin2hex($binary))))
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
        self::$_file = basePath . self::$_dir.'/'.$hash.'.cache';
        $ttl=xml::getXMLvalue('cache_index','/cache_index/file[@hash="' . $hash . '"]/ttl');

        if(!file_exists(self::$_file))
            return true;

        if($ttl != 0)
            return ((time()-@filemtime(self::$_file)) > $ttl ? true : false);
        else
            return true;
    }

    /**
     * *Binary* Prüft ob Wert verfügbar ist und nicht abgelaufen oder verändert.
     *
     * @return boolean
     */
    public static function file_check_binary($key)
    {
        global $prev;
        $hash = md5($key.$prev);
        $ttl=xml::getXMLvalue('cache_index_binary','/cache_index_binary/file[@hash="' . $hash . '"]/ttl');
        $file_hash=convert::ToString(xml::getXMLvalue('cache_index_binary','/cache_index_binary/file[@hash="' . $hash . '"]/stream_hash'));
        $original_file=convert::ToString(xml::getXMLvalue('cache_index_binary','/cache_index_binary/file[@hash="' . $hash . '"]/original_file'));
        self::$_file = basePath . self::$_dir.'/binary/'.$hash.'.bin';

        if(empty($original_file) && empty($file_hash) && !$ttl && !file_exists(self::$_file))
            return true;

        if(!empty($original_file) && !file_exists(basePath.'/'.$original_file))
            return true;

        if(!empty($original_file) && convert::ToString(md5_file(basePath.'/'.$original_file)) != $file_hash)
            return true;

        if(!file_exists(self::$_file))
            return true;

        if($ttl != 0)
            return ((time()-@filemtime(self::$_file)) > $ttl ? true : false);
        else
            return false;
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

            return xml::getXMLvalue('cache_index','/cache_index/file[@hash="' . $hash . '"]/array') == 'yes' ? string_to_array(convert::UTF8_Reverse($stream)) : convert::UTF8_Reverse($stream);
        }
        else
            return false;
    }

    /**
     * *Binary* Lese Binary Code aus der FileCache.
     *
     * @return binary or boolean
     */
    public static function file_get_binary($key)
    {
        global $prev;
        $hash = md5($key.$prev);
        self::$_file = basePath . self::$_dir.'/binary/'.$hash.'.bin';
        if(file_exists(self::$_file))
        {
            $stream = file_get_contents(self::$_file);
            if(!$stream) return false;

            if(!$stream = @gzuncompress($stream))
                return false;

            if(!$stream = @hextobin($stream))
                return false;

            return $stream;
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
     * *Binary* Lösche Werte vom FileCache.
     *
     * @return boolean
     */
    public static function file_delete_binary($key)
    {
        global $prev;
        $hash = md5($key.$prev);
        self::$_file = basePath . self::$_dir.'/binary/'.$hash.'.bin';
        if(file_exists(self::$_file))
        {
            unlink(self::$_file);
            xml::deleteXMLattribut('cache_index_binary', '/cache_index_binary', 'hash', $hash );
            xml::saveXMLfile('cache_index_binary');
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

        $files = get_files(self::$_file.'binary/', false, true, array("bin","index"));
        if(count($files) >= 1)
        {
            foreach($files as $file)
            { @unlink(self::$_file.'binary/' . $file); }
        }
    }
}
?>
