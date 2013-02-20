<?php
/**
 * <DZCP-Extended Edition>
 * @package: DZCP-Extended Edition API Core
 * @author: DZCP Developer Team || Hammermaps.de Developer Team
 * @link: http://www.dzcp.de || http://www.hammermaps.de
 */

// Use Class API::XXXXX
class API_CORE
{
    public static $addon_index = array();
    public static $MobileDevice = false;
    private static $UserAgent = '';
    private static $MobileAgent = array('sony','symbian','nokia','samsung','mobile','windows ce','epoc','opera mini','opera mobi',
            'mini','nitro','j2me','midp-','cldc-','netfront','mot','up.browser','up.link','audiovox',
            'blackberry','ericsson,','panasonic','philips','sanyo','sharp','sie-',
            'portalmmm','blazer','avantgo','danger','palm','series60','palmsource','pocketpc',
            'smartphone','rover','ipaq','au-mic,','alcatel','ericy','vodafone','wap1.','wap2.','iphone','android');

    public static function init()
    {
        /**
         *  Addons auflisten und Index zusammenstellen
         */
        DebugConsole::insert_initialize('API_CORE::init()', 'DZCP API-Core'); //Debug Log
        global $language,$tmpdir,$ajaxThumbgen;
        if(modapi_enabled && !$ajaxThumbgen)
        {
            $addons = get_files(basePath.'/inc/additional-addons/',true);
            if($addons && count($addons) >= 1) // Mehr >= 1 Addon vorhanden
            {
                foreach($addons as $addon)
                {
                    $additional_functions = get_files(basePath.'/inc/additional-addons/'.$addon.'/functions/',false,true,array('php'));
                    $additional_languages_global = get_files(basePath.'/inc/additional-addons/'.$addon.'/languages/',false,true,array('php'));
                    $additional_languages = get_files(basePath.'/inc/additional-addons/'.$addon.'/languages/'.$language.'/',false,true,array('php'));
                    $additional_tpl = get_files(basePath.'/inc/additional-addons/'.$addon.'/_templates_/',true); $addon_infos = array();
                    $additional_pages = get_files(basePath.'/inc/additional-addons/'.$addon.'/',true,false,array(),array('_templates_','functions','languages'));
                    $addon_infos = array();

                    if(file_exists(basePath.'/inc/additional-addons/'.$addon.'/addon_info.xml'))
                    {
                        $moduleName = 'addon_'.$addon; $info_array = array();
                        if(xml::openXMLfile($moduleName, 'inc/additional-addons/'.$addon.'/addon_info.xml',true))
                        {
                            $xml = xml::getXMLvalue($moduleName,'/info');
                            $info_array['xml_addon_name'] = convert::ToString($xml->addon_name);
                            $info_array['xml_addon_autor'] = convert::ToString($xml->addon_autor);
                            $info_array['xml_addon_autor_url'] = convert::ToString($xml->addon_autor_url);
                            $info_array['xml_addon_autor_mail'] = convert::ToString($xml->addon_autor_mail);
                            $info_array['xml_addon_info'] = convert::ToString($xml->addon_info);
                            $info_array['xml_addon_version'] = convert::ToString($xml->addon_version);
                            $info_array['xml_addon_init_call'] = convert::ToString($xml->addon_init_call);
                            $info_array['xml_addon_msrv_id'] = convert::ToString($xml->addon_check_server_id);
                            $info_array['xml_addon_obj'] = $xml;
                        }

                        $addon_infos['xml'] = $info_array;
                        unset($xml,$info_array);
                    }
                    else
                        continue;

                    $addon_infos['dir'] = basePath.'/inc/additional-addons/'.$addon.'/';

                    $addon_infos['include_functions'] = (!empty($additional_functions) && count($additional_functions) >= 1 ? true : false);
                    $addon_infos['additional-functions'] = (!empty($additional_functions) && count($additional_functions) >= 1 ? $additional_functions : array());
                    unset($additional_functions);

                    $addon_infos['include_languages'] = (count($additional_languages) >= 1 && !empty($additional_languages) || count($additional_languages_global) && !empty($additional_languages_global) ? true : false);
                    $addon_infos['additional-languages-global'] = (count($additional_languages_global) >= 1 && !empty($additional_languages_global) ? $additional_languages_global : array());
                    $addon_infos['additional-languages'] = (count($additional_languages) >= 1 && !empty($additional_languages) ? $additional_languages : array());
                    unset($additional_languages,$additional_languages_global);

                    $addon_infos['include_tpl'] = (count($additional_tpl) >= 1 && !empty($additional_tpl) && array_var_exists($tmpdir,$additional_tpl) ? true : false);
                    unset($additional_tpl);

                    $addon_infos['additional_pages'] = (count($additional_pages) >= 1 && !empty($additional_pages) ? true : false);
                    unset($additional_pages);

                    self::$addon_index[$addon] = $addon_infos;
                }
            }
        }

        /**
         *  Mobilgeräte erkennen
         */
        self::$UserAgent = strtolower($_SERVER['HTTP_USER_AGENT']);
        self::detectMobileAgent();
    }

    /**
    *  Additional Functions listen
    *
    *  @return array
    */
    public static function load_additional_functions()
    {
        global $ajaxThumbgen;
        $return = false;
        if(allow_additional && !$ajaxThumbgen)
        {
            $additional_functions = get_files(basePath.'/inc/additional-functions/',false,true,array('php'));
            if(count($additional_functions) >= 1 && !empty($additional_functions))
            { foreach($additional_functions as $function) { if(file_exists(basePath.'/inc/additional-functions/'.$function)) { $return[] = basePath.'/inc/additional-functions/'.$function; } } }
            unset($additional_functions);
        }

        if(modapi_enabled && !$ajaxThumbgen)
        {
            if(count(self::$addon_index))
            {
                foreach(self::$addon_index as $addon)
                {
                    $dir = $addon['dir'];
                    if($addon['include_functions'])
                    {
                        $return = array();
                        $functions = $addon['additional-functions'];
                        foreach($functions as $function)
                        {
                            if(file_exists($dir.'functions/'.$function))
                                $return[] = $dir.'functions/'.$function;
                        }
                    }
                }
            }
        }

        return $return;
    }

    /**
    *  Additional Languages listen
    *
    *  @return array
    */
    public static function load_additional_language()
    {
        global $language,$ajaxThumbgen;
        $return = false;
        if(allow_additional && !$ajaxThumbgen)
        {
            $additional_languages_global = get_files(basePath.'/inc/additional-languages/',false,true,array('php'));
            if(count($additional_languages_global) >= 1 && !empty($additional_languages_global))
            { foreach($additional_languages_global as $lang_g) { if(file_exists(basePath.'/inc/additional-languages/'.$lang_g)) $return[] = basePath.'/inc/additional-languages/'.$lang_g; } }

            $additional_languages = get_files(basePath.'/inc/additional-languages/'.$language.'/',false,true,array('php'));
            if(count($additional_languages) >= 1 && !empty($additional_languages))
            { foreach($additional_languages as $lang) { if(file_exists(basePath.'/inc/additional-languages/'.$language.'/'.$lang))
                $return[] = basePath.'/inc/additional-languages/'.$language.'/'.$lang; } }
            unset($additional_languages,$additional_languages_global);
        }

        if(modapi_enabled && !$ajaxThumbgen)
        {
            if(count(self::$addon_index))
            {
                foreach(self::$addon_index as $addon)
                {
                    $dir = $addon['dir'];
                    if($addon['include_languages'])
                    {
                        $return = array();
                        $languages_globals = $addon['additional-languages-global'];
                        if(count($languages_globals) >= 1)
                        {
                            foreach($languages_globals as $global)
                            {
                                if(file_exists($dir.'languages/'.$global))
                                    $return[] = $dir.'languages/'.$global;
                            }
                            unset($global,$languages_globals);
                        }

                        $languages = $addon['additional-languages'];
                        if(count($languages) >= 1)
                        {
                            foreach($languages as $lang)
                            {
                                if(file_exists($dir.'languages/'.$language.'/'.$lang))
                                    $return[] = $dir.'languages/'.$language.'/'.$lang;
                            }
                            unset($lang,$languages);
                        }
                    }
                }
            }
        }

        return $return;
    }

    /**
     *  Additional Templates listen
     *
     *  @return stream
     */
    public static function load_additional_tpl($tpl)
    {
        global $tmpdir,$ajaxThumbgen;
        if(modapi_enabled && !$ajaxThumbgen)
        {
            if(count(self::$addon_index))
            {
                foreach(self::$addon_index as $addon)
                {
                    $dir = $addon['dir'];
                    if($addon['include_tpl'])
                    {
                        if(file_exists($dir.'_templates_/'.$tmpdir.'/'.$tpl.'.html'))
                            return file_get_contents($dir.'_templates_/'.$tmpdir.'/'.$tpl.'.html');

                        //default folder *for all templates
                        if(file_exists($dir.'_templates_/default/'.$tpl.'.html'))
                            return file_get_contents($dir.'_templates_/default/'.$tpl.'.html');
                    }
                }
            }
        }

        return false;
    }

    /**
     *  Initialisierung aufrufen für Addons
     */
    public static function call_addons_init()
    {
        global $tmpdir,$ajaxThumbgen;
        if(modapi_enabled && !$ajaxThumbgen)
        {
            if(!count(self::$addon_index))
                return false;

            foreach(self::$addon_index as $addon)
            {
                if($addon['xml']['xml_addon_init_call'] != 'false' && !empty($addon['xml']['xml_addon_init_call']))
                {
                    $exp = explode('::', $addon['xml']['xml_addon_init_call']);
                    if(count($exp) == 2)
                    {
                        if(class_exists($exp[0]))
                            call_user_func($addon['xml']['xml_addon_init_call']); ## INIT ##
                    }
                    else
                    {
                        if(function_exists($addon['xml']['xml_addon_init_call']))
                            call_user_func($addon['xml']['xml_addon_init_call']); ## INIT ##
                    }
                }
            }
        }
    }

    /**
     *  Additional Pages
     *
     *  @return filepath
     */
    public static function load_additional_page($page_dir,$action)
    {
        global $ajaxThumbgen;
        if(modapi_enabled  && !$ajaxThumbgen)
        {
            if(!count(self::$addon_index))
                return false;

            foreach(self::$addon_index as $addon)
            {
                $dir = $addon['dir'];
                if($addon['additional_pages'])
                {
                    if(file_exists($dir.$page_dir.'/pages/action_'.$action.'.php'))
                        return $dir.$page_dir.'/pages/action_'.$action.'.php';
                }
            }
        }

        return false;
    }

    /**
     *  Mobilgeräte erkennen
     *
     *  @return boolean
     */
    private static function detectMobileAgent()
    {
        if (isset($_SERVER['HTTP_X_WAP_PROFILE']) || isset($_SERVER['HTTP_PROFILE']))
            self::$MobileDevice = true;

        if (((strpos($_SERVER['HTTP_ACCEPT'], 'text/vnd.wap.wml') > 0) || (strpos($_SERVER['HTTP_ACCEPT'], 'application/vnd.wap.xhtml+xml') > 0)) && !self::$MobileDevice)
            self::$MobileDevice = true;

        if(!self::$MobileDevice)
        {
            foreach(self::$MobileAgent as $key => $value)
            {
                if (strpos(self::$UserAgent,$value) !== false)
                {
                    self::$MobileDevice = true;
                    break;
                }
            }
        }

        if (!self::$MobileDevice)
        {
            if (strpos($_SERVER['HTTP_ACCEPT'],'application/vnd.wap.xhtml+xml') !== false)
                self::$MobileDevice = true;
        }

        if (self::$MobileDevice && !headers_sent())
        {
            header('Cache-Control: no-transform');
            header('Vary: User-Agent, Accept');
        }
    }

    /**
     *  Verbinde zum Update Server
     */
    public static function connect_master($action='',$input_array=array())
    {
        if(!fsockopen_support())
            return false;

        return false;
    }
}
?>