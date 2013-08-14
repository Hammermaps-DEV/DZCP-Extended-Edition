<?php
/**
 * <DZCP-Extended Edition>
 * @package: DZCP-Extended Edition
 * @author: DZCP Developer Team || Hammermaps.de Developer Team
 * @link: http://www.dzcp.de || http://www.hammermaps.de
 */

class Cache
{
    private static $cacheInstalled=array();
    private static $cacheToInstall=array();
    private static $cacheType = 'file';
    private static $dummy=array();
    private static $caches=array();
    private static $dummy_overwrite=false;

    /**
     * L�dt die n�tigen Cache Classen
     */
    public static function loadClasses()
    {
        DebugConsole::insert_initialize('Cache::loadClasses()', 'DZCP Cache-Core');

        ## Include Classes ##
        $files = get_files(basePath . '/inc/additional-kernel/cache',false,true,array("php"));
        if($files != false && count($files) >= 1)
        {
            foreach($files as $func)
            {
                if(!require_once(basePath . '/inc/additional-kernel/cache/'.$func))
                    die('CMSKernel: Can not include "inc/additional-kernel/cache/'.$func.'"!');
            }

            self::$dummy_overwrite = false;
        }
        else
            self::$dummy_overwrite = true;

        if(!function_exists('gzuncompress') || !function_exists('gzcompress'))
            self::$dummy_overwrite = true;
    }

    /**
     * Installiert einen Cache Type
     *
     * @return boolean
     */
    public static function installType($typeShort='',$install=array())
    {
        if(!array_key_exists($typeShort, self::$cacheInstalled))
        {
            if(empty($install['Required']) || extension_loaded($install['Required']))
            {
                if(show_cache_debug)
                    DebugConsole::insert_loaded('inc/additional-kernel/cache/class_'.$install['Class'].'.php', $install['TypeName']);

                self::$cacheInstalled[$typeShort] = $install;
            }
            else if((!empty($install['Required']) || !extension_loaded($install['Required'])) && show_cache_debug)
            {
                DebugConsole::insert_info('inc/cache.php', 'PHP-Extension: "'.$install['Required'].'" not loaded, required for Cache Type: "'.$install['TypeName'].'"');
                self::$dummy_overwrite = true;
            }
            else if(!extension_loaded($install['Required']))
            {
                DebugConsole::insert_info('inc/cache.php', 'PHP-Extension: "'.$install['Required'].'" not loaded, required for Cache Type: "'.$install['TypeName'].'"');
                self::$dummy_overwrite = true;
            }
        }

        if(!array_key_exists($typeShort, self::$cacheToInstall))
            self::$cacheToInstall[$typeShort] = $install;
    }

    /**
     * Gibt eine liste f�r die Administration
     *
     * @return boolean
     */
    public static function GetConfigMenu()
    {
        global $cache_engine;
        $cache_select = show(_select_field, array("value" => '', "what" => _cache_none, "sel" => ''));
        foreach(self::$cacheInstalled as $typeShort => $install)
        { $cache_select .= show(_select_field, array("value" => $typeShort, "what" => $install['TypeName'], "sel" => ($cache_engine == $typeShort ? 'selected="selected"' : ''))); }
        return $cache_select;
    }

    /**
     * Gibt den verwendeten Cache Type zur�ck *Support*
     *
     * @return boolean
     */
    public static function getType($tag=null)
    { return self::$cacheInstalled[$tag]['TypeName']; }

    /**
     * Setzt die Tags und Caches Typen
     *
     * @return boolean
     */
    public static function setType($cacheType=null)
    {
        if(self::$dummy_overwrite)
        {
            if(show_cache_debug)
                DebugConsole::insert_error('inc/cache.php', 'Use Cache Dummy-Overwrite!');

            self::$cacheType = 'dummy';
            return true;
        }

        if(array_key_exists($cacheType, self::$cacheInstalled))
            self::$cacheType = $cacheType;
        else
        {
            self::$cacheType = 'dummy';
            self::$dummy_overwrite = true;

            if(show_cache_debug)
                DebugConsole::insert_error('inc/cache.php', 'Use Cache Dummy-Overwrite! Cache Type: "'.$cacheType.'" not loaded!');

            return true;
        }
    }

    /**
     * Initialisiert die Classen
     */
    public static function init()
    {
        if(self::$dummy_overwrite || self::$cacheType == 'dummy' || empty(self::$cacheType))
            return;

        if(array_key_exists(self::$cacheType, self::$cacheInstalled))
        {
            $install = self::$cacheInstalled[self::$cacheType];
            if($install['SetServer'])
            {
                if(!call_user_func($install['Class'].'::'.$install['CallTag'].'server'))
                {
                    self::$cacheType = 'dummy';
                    self::$dummy_overwrite = true;

                    if(show_cache_debug)
                        DebugConsole::insert_error('inc/cache.php', 'Use Cache Dummy-Overwrite! '.$install['Class'].'::server()'.' is FALSE');
                }
            }

            if($install['InitCache'])
            {
                if(!call_user_func($install['Class'].'::initC'))
                {
                    self::$cacheType = 'dummy';
                    self::$dummy_overwrite = true;

                    if(show_cache_debug)
                        DebugConsole::insert_error('inc/cache.php', 'Use Cache Dummy-Overwrite! '.$install['Class'].'::initC()'.' is FALSE');
                }
            }
        }
        else if(show_cache_debug)
            DebugConsole::insert_error('inc/cache.php', 'Use Cache Dummy-Overwrite! Cache Type: "'.self::$cacheType.'" not loaded!');
    }

    /**
     * Pr�ft ob ein Memory Cache verwendet wird
     */
    public static function is_mem()
    {
        if(self::$dummy_overwrite || self::$cacheType == 'dummy' || empty(self::$cacheType))
            return false;

        return self::$cacheInstalled[self::$cacheType]['CacheType'] == 'mem' ? true : false;
    }

    /**
     * Gibt die liste geladenen Cache Classe aus *Support*
     *
     * @return array
     */
    public static function get_cache_support()
    {
        $support = '';
        foreach(self::$cacheToInstall as $typeShort => $install)
        {
            if(!empty($install['Required']))
                $support .= $install['TypeName']." Erweiterung: ".(extension_loaded($install['Required']) ? 'Ist verf&uuml;gbar' : 'Deaktiviert')."\r\n";

            $support .= $install['TypeName'].": ".(array_key_exists($typeShort, self::$cacheInstalled) ? 'Ist verf&uuml;gbar' : 'Deaktiviert')."\r\n";
        }

        return $support;
    }

    /**
     * Speichert Daten im Cache
     *
     * @return boolean
     */
    public static function set($key=null, $data=null, $ttl = 3600)
    {
        if(self::$dummy_overwrite || self::$cacheType == 'dummy')
        {
            self::$dummy[$key] = $data;
            return true;
        }

        if(array_key_exists(self::$cacheType, self::$cacheInstalled))
        {
            $install = self::$cacheInstalled[self::$cacheType];

            if(show_cache_debug)
            {
                DebugConsole::insert_info('inc/cache.php', 'Set Cache => Key: '.$key);
                DebugConsole::insert_info('inc/cache.php', 'Call: '.$install['Class'].'::'.$install['CallTag'].'set | Keys: '.$key.' | '.$ttl);
            }

            return call_user_func($install['Class'].'::'.$install['CallTag'].'set',$key,$data,$ttl);
        }

        return false;
    }

    /**
     * *Binary* Speichert Daten im Cache
     *
     * @return boolean
     */
    public static function set_binary($key=null, $binary=null, $original_file=false, $ttl = 0)
    {
        if(self::$dummy_overwrite || self::$cacheType == 'dummy')
        {
            self::$dummy['bin_'.$key] = $binary;
            return true;
        }

        if(array_key_exists(self::$cacheType, self::$cacheInstalled))
        {
            $install = self::$cacheInstalled[self::$cacheType]; $ttl = ($ttl == 0 ? 86400 : $ttl);

            if(show_cache_debug)
            {
                DebugConsole::insert_info('inc/cache.php', 'Set Binary Cache => Key: '.$key);
                DebugConsole::insert_info('inc/cache.php', 'Call: '.$install['Class'].'::'.$install['CallTag'].'set_binary | Keys: '.$key.' | '.$original_file.' | '.$ttl);
            }

            return call_user_func($install['Class'].'::'.$install['CallTag'].'set_binary',$key,$binary,$original_file,$ttl);
        }

        return false;
    }

    /**
     * Liest Daten im Cache aus
     *
     * @return mixed
     */
    public static function get($key=null)
    {
        if(self::$dummy_overwrite || self::$cacheType == 'dummy')
            return self::$dummy[$key];

        if(array_key_exists(self::$cacheType, self::$cacheInstalled))
        {
            $install = self::$cacheInstalled[self::$cacheType];

            if(show_cache_debug)
            {
                DebugConsole::insert_info('inc/cache.php', 'Get Cache => Key: '.$key);
                DebugConsole::insert_info('inc/cache.php', 'Call: '.$install['Class'].'::'.$install['CallTag'].'get | Keys: '.$key);
            }

            return call_user_func($install['Class'].'::'.$install['CallTag'].'get',$key);
        }

        return false;
    }

    /**
     * *Binary* Liest Daten im Cache aus
     *
     * @return mixed
     */
    public static function get_binary($key=null)
    {
        if(self::$dummy_overwrite || self::$cacheType == 'dummy')
            return self::$dummy['bin_'.$key];

        if(array_key_exists(self::$cacheType, self::$cacheInstalled))
        {
            $install = self::$cacheInstalled[self::$cacheType];

            if(show_cache_debug)
            {
                DebugConsole::insert_info('inc/cache.php', 'Get Binary Cache => Key: '.$key);
                DebugConsole::insert_info('inc/cache.php', 'Call: '.$install['Class'].'::'.$install['CallTag'].'get_binary | Keys: '.$key);
            }

            return call_user_func($install['Class'].'::'.$install['CallTag'].'get_binary',$key);
        }

        return false;
    }

    /**
     * Pr�ft ob die Daten im Cache g�ltig sind
     *
     * @return boolean
     */
    public static function check($key=null)
    {
        if(self::$dummy_overwrite || self::$cacheType == 'dummy')
            return true;

        if(is_debug && !cache_in_debug)
            return true;

        if(array_key_exists(self::$cacheType, self::$cacheInstalled))
        {
            $install = self::$cacheInstalled[self::$cacheType];

            if(show_cache_debug)
            {
                DebugConsole::insert_info('inc/cache.php', 'Check Cache => Key: '.$key);
                DebugConsole::insert_info('inc/cache.php', 'Call: '.$install['Class'].'::'.$install['CallTag'].'check | Keys: '.$key);
            }

            return call_user_func($install['Class'].'::'.$install['CallTag'].'check',$key);
        }

        return false;
    }

    /**
     * *Binary* Pr�ft ob die Daten im Cache g�ltig sind
     *
     * @return boolean
     */
    public static function check_binary($key=null)
    {
        if(self::$dummy_overwrite || self::$cacheType == 'dummy')
            return true;

        if(is_debug && !cache_in_debug)
            return true;

        if(array_key_exists(self::$cacheType, self::$cacheInstalled))
        {
            $install = self::$cacheInstalled[self::$cacheType];

            if(show_cache_debug)
            {
                DebugConsole::insert_info('inc/cache.php', 'Check Binary Cache => Key: '.$key);
                DebugConsole::insert_info('inc/cache.php', 'Call: '.$install['Class'].'::'.$install['CallTag'].'check_binary | Keys: '.$key);
            }

            return call_user_func($install['Class'].'::'.$install['CallTag'].'check_binary',$key);
        }

        return false;
    }

    /**
     * L�scht Werte und Keys im Cache
     *
     * @return boolean
     */
    public static function delete($key=null)
    {
        if(self::$dummy_overwrite || self::$cacheType == 'dummy')
        {
            unset(self::$dummy[$key]);
            return true;
        }

        if(array_key_exists(self::$cacheType, self::$cacheInstalled))
        {
            $install = self::$cacheInstalled[self::$cacheType];

            if(show_cache_debug)
            {
                DebugConsole::insert_info('inc/cache.php', 'Delete Cache => Key: '.$key);
                DebugConsole::insert_info('inc/cache.php', 'Call: '.$install['Class'].'::'.$install['CallTag'].'delete | Keys: '.$key);
            }

            return call_user_func($install['Class'].'::'.$install['CallTag'].'delete',$key);
        }

        return false;
    }

    /**
     * *Binary* L�scht Werte und Keys im Cache
     *
     * @return boolean
     */
    public static function delete_binary($key=null)
    {
        if(self::$dummy_overwrite || self::$cacheType == 'dummy')
        {
            unset(self::$dummy['bin_'.$key]);
            return true;
        }

        if(array_key_exists(self::$cacheType, self::$cacheInstalled))
        {
            $install = self::$cacheInstalled[self::$cacheType];

            if(show_cache_debug)
            {
                DebugConsole::insert_info('inc/cache.php', 'Delete Binary Cache => Key: '.$key);
                DebugConsole::insert_info('inc/cache.php', 'Call: '.$install['Class'].'::'.$install['CallTag'].'delete_binary | Keys: '.$key);
            }

            return call_user_func($install['Class'].'::'.$install['CallTag'].'delete_binary',$key);
        }

        return false;
    }

    /**
     * Leert den gesamten Cache
     *
     * @return boolean
     */
    public static function clean()
    {
        if(self::$dummy_overwrite || self::$cacheType == 'dummy')
        {
            self::$dummy = array();
            return true;
        }

        if(array_key_exists(self::$cacheType, self::$cacheInstalled))
        {
            $install = self::$cacheInstalled[self::$cacheType];

            if(show_cache_debug)
            {
                DebugConsole::insert_info('inc/cache.php', 'Clean Cache');
                DebugConsole::insert_info('inc/cache.php', 'Call: '.$install['Class'].'::'.$install['CallTag'].'clean');
            }

            return call_user_func($install['Class'].'::'.$install['CallTag'].'clean');
        }
    }
}