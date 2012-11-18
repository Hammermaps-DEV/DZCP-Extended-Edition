<?php
class Cache
{
	private static $cacheType=array();
	private static $dummy=array();
	private static $caches=array();
	private static $dummy_overwrite=false;

	/**
	 * Lädt die nötigen Cache Classen
	 */
	public static function loadClasses()
	{
		## Include Classes ##
		$files = get_files(basePath . '/inc/additional-kernel/cache',false,true,array("php"));
		if($files && count($files) >= 1)
		{
			foreach($files as $func)
			{
				if(!require_once(basePath . '/inc/additional-kernel/cache/'.$func))
					die('CMSKernel: Can not include "inc/additional-kernel/cache/'.$func.'"!');
				
				$func = explode('.',$func);
				$func = explode('class_',$func[0]);
				self::$caches[$func[1]] = true;
			}

			self::$dummy_overwrite = false;
		}
		else
			self::$dummy_overwrite = true;
		
		if(!function_exists('gzuncompress') || !function_exists('gzcompress'))
			self::$dummy_overwrite = true;
	}

	/**
	 * Gibt den verwendeten Cache Type zuruck
	 *
	 * @return boolean
	 */
	public static function getType($tag=null)
	{

		## Cache Typen ##
		switch (self::$cacheType[$tag])
		{
			case 1: //File
				return 'File';
			break;
			case 2: //MySQL
				return 'MySQL';
			break;
			case 3: //Memcache
				return 'Memcache';
			break;
			default:
				return 'Keinen';
			break;
		}
	}
	
	/**
	 * Setzt die Tags und Caches Typen
	 *
	 * @return boolean
	 */
	public static function setType($tag=null,$cacheType=null)
	{
		if(self::$dummy_overwrite)
		{
			self::$cacheType[$tag] = 0;
			return true;
		}
		
		## Cache Typen ##
		switch ($cacheType)
		{
			case 3: //Memcache
				if(self::$caches['cache_memcache'] && function_exists("memcache_connect"))
				{
					self::$cacheType[$tag] = 3;
					return true;
				}
				else
				{
					self::$cacheType[$tag] = 0;
					return false;
				}
				break;
			case 2: //MySQL
				if(self::$caches['cache_mysql'])
				{
					self::$cacheType[$tag] = 2;
					return true;
				}
				else
				{
					self::$cacheType[$tag] = 0;
					return false;
				}
				break;
			case 1: //File
				if(self::$caches['cache_file'])
				{
					self::$cacheType[$tag] = 1;
					return true;
				}
				else
				{
					self::$cacheType[$tag] = 0;
					return false;
				}
				break;
			default:
				self::$cacheType[$tag] = 0;
				return true;
			break;
		}
	}

	/**
	 * Initialisiert die Classen
	 */
	public static function init($tag=null)
	{
		global $db;
		
		if(self::$dummy_overwrite)
			return;
		
		## Cache Typen ##
		switch (self::$cacheType[$tag])
		{
			case 3:
				$settings = settings(array('memcache_host','memcache_port'));
				if(empty($settings['memcache_host']) || empty($settings['memcache_port']) || !extension_loaded('Memcache'))
				{
					self::$dummy_overwrite = true;
					return;
				}

				cache_memcache::mem_server($settings['memcache_host'],$settings['memcache_port']);
				if(!cache_memcache::initC())
				{
					self::$dummy_overwrite = true;
					return;
				}
			break;
			case 1:
				cache_file::file_server("/inc/_cache");
				cache_file::initC();
			break;
			case 0: break;
		}
	}
	
	/**
	 * Gibt die geladenen Cache Classe aus
	 *
	 * @return array
	 */	
	public static function get_caches()
	{ return self::$caches; }

	/**
	 * Speichert Daten im Cache
	 *
	 * @return boolean
	 */	
	public static function set($tag=null, $key=null, $data=null, $ttl = 3600)
	{
		if(self::$dummy_overwrite)
		{
			self::$dummy[$tag."_".$key] = $data;
			return true;
		}
		
		## Cache Typen ##
		switch (self::$cacheType[$tag])
		{
			case 3:
				return cache_memcache::mem_set($tag."_".$key, $data, $ttl);
			break;
			case 2:
				return cache_mysql::mysqlc_set($tag."_".$key, $data, $ttl);
			break;
			case 1:
				return cache_file::file_set($tag."_".$key, $data, $ttl);
			break;
			case 0:
				self::$dummy[$tag."_".$key] = $data;
				return true;
			break;
		}
	}

	/**
	 * Liest Daten im Cache aus
	 *
	 * @return mixed
	 */
	public static function get($tag=null,$key=null)
	{
		if(self::$dummy_overwrite)
			return self::$dummy[$tag."_".$key];
		
		## Cache Typen ##
		switch (self::$cacheType[$tag])
		{
			case 3:
				return cache_memcache::mem_get($tag."_".$key);
			break;
			case 2:
				return cache_mysql::mysqlc_get($tag."_".$key);
			break;
			case 1:
				return cache_file::file_get($tag."_".$key);
			break;
			case 0:
				return self::$dummy[$tag."_".$key];
			break;
		}
	}

	/**
	 * Prüft ob die Daten im Cache gültig sind
	 *
	 * @return boolean
	 */	
	public static function check($tag=null,$key=null)
	{
		if(self::$dummy_overwrite)
			return true;
		
		if(is_debug && !cache_in_debug)
			return true;
		
		## Cache Typen ##
		switch (self::$cacheType[$tag])
		{
			case 3:
				return cache_memcache::mem_check($tag."_".$key);
			break;
			case 2:
				return cache_mysql::mysqlc_check($tag."_".$key);
			break;
			case 1:
				return cache_file::file_check($tag."_".$key);
			break;
			case 0:
				return true;
			break;
		}
	}

	/**
	 * Löscht Werte und Keys im Cache
	 *
	 * @return boolean
	 */	
	public static function delete($tag=null,$key=null)
	{
		if(self::$dummy_overwrite)
		{
			unset(self::$dummy[$tag."_".$key]);
			return true;
		}
		
		## Cache Typen ##
		switch (self::$cacheType[$tag])
		{
			case 3:
				return cache_memcache::mem_delete($tag."_".$key);
			break;
			case 2:
				return cache_mysql::mysqlc_delete($tag."_".$key);
			break;
			case 1:
				return cache_file::file_delete($tag."_".$key);
			break;
			case 0:
				unset(self::$dummy[$tag."_".$key]);
				return true;
			break;
		}
	}

	/**
	 * Leert den gesamten Cache
	 *
	 * @return boolean
	 */	
	public static function clean($tag=null)
	{
		if(self::$dummy_overwrite)
		{
			self::$dummy = array();
			return true;
		}
		
		## Cache Typen ##
		switch (self::$cacheType[$tag])
		{
			case 3:
				return cache_memcache::mem_clean();
			break;
			case 2:
				return cache_mysql::mysqlc_clean();
			break;
			case 1:
				return cache_file::file_clean();
			break;
			case 0:
				self::$dummy = array();
				return true;
			break;
		}
	}
}
?>