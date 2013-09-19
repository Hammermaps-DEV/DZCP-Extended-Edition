<?php
/**
 * <DZCP-Extended Edition>
 * @package: DZCP-Extended Edition API Core
 * @author: DZCP Developer Team || Hammermaps.de Developer Team
 * @link: http://www.dzcp.de || http://www.hammermaps.de
 */

class API_CORE
{
    public static $addon_index = array();
    public static $addon_index_xml = array();
    public static $MobileDevice = false;
    public static $MobileClass = '';
    public static $bbcode_index = array();

    public static function init()
    {
        /**
         *  Addons auflisten und Index zusammenstellen
         */
        DebugConsole::insert_initialize('API_CORE::init()', 'DZCP API-Core'); //Debug Log
        global $tmpdir,$ajaxThumbgen;
        if(modapi_enabled && !$ajaxThumbgen)
        {
            $addons = get_files(basePath.'/inc/additional-addons/',true);
            if($addons && count($addons) >= 1) // Mehr >= 1 Addon vorhanden
            {
                foreach($addons as $addon)
                {
                    $additional_functions = get_files(basePath.'/inc/additional-addons/'.$addon.'/functions/',false,true,array('php'));
                    $additional_kernel = get_files(basePath.'/inc/additional-addons/'.$addon.'/',false,true,array('php'));
                    $additional_languages_global = get_files(basePath.'/inc/additional-addons/'.$addon.'/languages/',false,true,array('php'));
                    $additional_languages = get_files(basePath.'/inc/additional-addons/'.$addon.'/languages/'.language::get_language().'/',false,true,array('php'));
                    $additional_tpl = get_files(basePath.'/inc/additional-addons/'.$addon.'/_templates_/',true); $addon_infos = array();
                    $additional_pages = get_files(basePath.'/inc/additional-addons/'.$addon.'/',true,false,array(),false,array('_templates_','functions','languages'));

                    $additional_admin = array();
                    if(file_exists(basePath.'/inc/additional-addons/'.$addon.'/admin') && file_exists(basePath.'/inc/additional-addons/'.$addon.'/admin/menu'))
                    {
                        $additional_admin_dirs = get_files(basePath.'/inc/additional-addons/'.$addon.'/admin/menu/',true);
                        foreach ($additional_admin_dirs as $additional_admin_dir)
                        {
                            $con_dir = basePath.'/inc/additional-addons/'.$addon.'/admin/menu/'.$additional_admin_dir;
                            if(!file_exists($con_dir.'/config.xml'))
                                continue;

                            $additional_admin[] = $additional_admin_dir;
                        }

                        unset($additional_admin_dirs);
                    }

                    $addon_infos = array(); $addon_xml_infos = '';
                    if(file_exists(basePath.'/inc/additional-addons/'.$addon.'/addon_info.xml'))
                    {
                        $moduleName = 'addon_'.$addon; $info_array = array();
                        if(xml::openXMLfile($moduleName, 'inc/additional-addons/'.$addon.'/addon_info.xml',true))
                        {
                            $xml_array = convert::objectToArray(xml::getXMLvalue($moduleName,'/info'));
                            if(!array_key_exists('addon_name', $xml_array) || !array_key_exists('addon_autor', $xml_array) || !array_key_exists('addon_autor_url', $xml_array) ||
                               !array_key_exists('addon_autor_mail', $xml_array) || !array_key_exists('addon_version', $xml_array) || !array_key_exists('addon_info', $xml_array) ||
                               !array_key_exists('addon_init_call', $xml_array))
                                DebugConsole::insert_warning('API_CORE::init()', 'The addon: "'.$addon.'" has a incomplete addon_info.xml');

                            $info_array['xml_addon_name'] = $xml_array['addon_name'];
                            $info_array['xml_addon_autor'] = $xml_array['addon_autor'];
                            $info_array['xml_addon_autor_url'] = $xml_array['addon_autor_url'];
                            $info_array['xml_addon_autor_mail'] = $xml_array['addon_autor_mail'];
                            $info_array['xml_addon_version'] = $xml_array['addon_version'];
                            $info_array['xml_addon_info'] = $xml_array['addon_info'];
                            $info_array['xml_addon_init_call'] = $xml_array['addon_init_call'];

                            //Updater
                            $addon_xml_infos['addon_name'] = $xml_array['addon_name'];
                            $addon_xml_infos['addon_autor'] = $xml_array['addon_autor'];
                            $addon_xml_infos['addon_version'] = $xml_array['addon_version'];
                            $addon_xml_infos['addon_build_rev'] = $xml_array['addon_build_rev'];
                            unset($xml_array);
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

                    $addon_infos['include_kernel_functions'] = (!empty($additional_kernel) && count($additional_kernel) >= 1 ? true : false);
                    $addon_infos['additional-kernel-functions'] = (!empty($additional_kernel) && count($additional_kernel) >= 1 ? $additional_kernel : array());
                    unset($additional_kernel);

                    $addon_infos['include_languages'] = (count($additional_languages) >= 1 && !empty($additional_languages) || count($additional_languages_global) && !empty($additional_languages_global) ? true : false);
                    $addon_infos['additional-languages-global'] = (count($additional_languages_global) >= 1 && !empty($additional_languages_global) ? $additional_languages_global : array());
                    $addon_infos['additional-languages'] = (count($additional_languages) >= 1 && !empty($additional_languages) ? $additional_languages : array());
                    $addon_infos['additional-admin'] = (count($additional_admin) >= 1 && !empty($additional_admin) ? $additional_admin : array());
                    unset($additional_languages,$additional_languages_global);

                    $addon_infos['include_tpl'] = (count($additional_tpl) >= 1 && !empty($additional_tpl) && (array_var_exists($tmpdir,$additional_tpl) || array_var_exists('default',$additional_tpl)) ? true : false);
                    unset($additional_tpl);

                    $addon_infos['additional_pages'] = (count($additional_pages) >= 1 && !empty($additional_pages) ? true : false);
                    unset($additional_pages);

                    $addon_infos['additional_admin'] = (count($additional_admin) >= 1 && !empty($additional_admin) ? true : false);
                    unset($additional_admin);

                    self::$addon_index[$addon] = $addon_infos;
                    self::$addon_index_xml[$addon] = $addon_xml_infos;
                }
            }
        }

        //Core Sort
        self::core_sort();

        /**
         *  Mobilgeräte erkennen
         */
        self::$MobileClass = new Mobile_Detect();
        self::$MobileDevice = self::$MobileClass->isMobile();
    }

    /**
     * Gibt eine Liste der zusäzlichen Administrations Menus aus
     * @return array
     */
    public static function load_additional_adminmenu()
    {
        $index = array();
        foreach (self::$addon_index as $addon => $addon_infos)
        {
            if(!$addon_infos['additional_admin']) continue;
            foreach ($addon_infos['additional-admin'] as $dir)
            { $index[] = array('dir' => $addon, 'file_dir' => $dir); }
        }

        return $index;
    }

    /**
     * Werden zusäzliche Administration Menus verwendet
     * @return boolean
     */
    public static function is_additional_adminmenu()
    { if(!modapi_enabled) return false; return count(self::$addon_index) >= 1 ? true : false; }

    public static function call_additional_adminmenu($menu='')
    {
        if(!modapi_enabled) return false;
        foreach (self::$addon_index as $addon => $addon_infos)
        {
            if(!$addon_infos['additional_admin']) continue;
            foreach ($addon_infos['additional-admin'] as $dir)
            {
                if($dir != $menu) continue;
                return array('require_indexes' => basePath.'/inc/additional-addons/'.$addon.'/admin/menu/'.$dir,
                             'require_functions' => self::load_additional_admin_functions($menu,true,$addon),
                             'require_languages' => self::load_additional_admin_languages($menu,true,$addon),
                             'require_case_dir' => self::load_admin_case_dir($menu,true,$addon),
                             'require_index_file' => file_exists(basePath.'/inc/additional-addons/'.$addon.'/admin/menu/'.$dir.'/index.php'), //For Old Menu
                             'require_header_file' => file_exists(basePath.'/inc/additional-addons/'.$addon.'/admin/menu/'.$dir.'/header.php'),
                             'require_footer_file' => file_exists(basePath.'/inc/additional-addons/'.$addon.'/admin/menu/'.$dir.'/footer.php'),
                             'addon_dir' => '../inc/additional-addons/'.$addon);
            }
        }

        return false;
    }

    /**
     * Gibt eine Liste der Addon XML files für die Administration aus
     * @return boolean|Ambigous <boolean, multitype:string >
     */
    public static function call_additional_adminmenu_xml()
    {
        if(!modapi_enabled) return false; $return = array(); $i=0;
        foreach (self::$addon_index as $addon => $addon_infos)
        {
            if(!$addon_infos['additional_admin']) continue;
            foreach ($addon_infos['additional-admin'] as $dir)
            {
                if(file_exists(basePath.'/inc/additional-addons/'.$addon.'/admin/menu/'.$dir.'/config.xml'))
                {
                    $return[$i]['dir'] = '/inc/additional-addons/'.$addon.'/admin/menu/'.$dir.'/config.xml';
                    $return[$i]['name'] = $dir;
                    $i++;
                }
            }
        }

        return count($return) >= 1 ? $return : false;
    }

    /**
     * Gibt eine Liste der für die Administration zusäzlichen funktionen aus
     * @param string $menu
     * @param string $addon
     * @param string $addon_dir
     * @return Ambigous <boolean, multitype:string >|boolean
     */
    public static function load_additional_admin_functions($menu='',$addon=false,$addon_dir='')
    {
        $dir = ($addon ? basePath.'/inc/additional-addons/'.$addon_dir.'/admin/menu/'.$menu.'/functions' : basePath.'/admin/menu/'.$menu.'/functions');
        if(is_dir($dir))
        {
            $files = get_files($dir.'/',false,true,array('php')); $inc_files = array();
            if($files != false && count($files) >= 1)
            {
                foreach ($files as $file) { $inc_files[] = ($addon ? basePath.'/inc/additional-addons/'.$addon_dir.'/' : basePath.'/').'admin/menu/'.$menu.'/functions/'.$file; }
                return count($inc_files) >= 1 ? $inc_files : false;
            }
        }

        return false;
    }

    /**
     * Gibt eine Liste der für die Administration zusäzlichen Sprachen aus
     * @param string $menu
     * @param string $addon
     * @param string $addon_dir
     * @return Ambigous <boolean, multitype:string >|boolean
     */
    public static function load_additional_admin_languages($menu='',$addon=false,$addon_dir='')
    {
        $dir = ($addon ? basePath.'/inc/additional-addons/'.$addon_dir.'/admin/menu/'.$menu.'/languages' : basePath.'/admin/menu/'.$menu.'/languages');
        if(is_dir($dir))
        {
            $dir = ($addon ? basePath.'/inc/additional-addons/'.$addon_dir.'/admin/menu/'.$menu.'/languages/'.language::get_language() : basePath.'/admin/menu/'.$menu.'/languages/'.language::get_language());
            if(is_dir($dir))
            {
                $files = get_files($dir.'/',false,true,array('php')); $inc_files = array();
                if($files != false && count($files) >= 1)
                {
                    foreach ($files as $file) { $inc_files[] = ($addon ? basePath.'/inc/additional-addons/'.$addon_dir.'/' : basePath.'/').'admin/menu/'.$menu.'/languages/'.language::get_language().'/'.$file; }
                    return count($inc_files) >= 1 ? $inc_files : false;
                }
            }
        }

        return false;
    }

    /**
     * Laden der Administration Cases
     * @param string $menu
     * @param string $addon
     * @param string $addon_dir
     * @return string|boolean
     */
    public static function load_admin_case_dir($menu='',$addon=false,$addon_dir='')
    {
        $dir = ($addon ? basePath.'/inc/additional-addons/'.$addon_dir.'/admin/menu/'.$menu.'/case' : basePath.'/admin/menu/'.$menu.'/case');
        if(is_dir($dir)) return $dir; return false;
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
     *  Additional Kernel Functions listen
     *
     *  @return array
     */
    public static function load_additional_kernel_functions()
    {
        global $ajaxThumbgen; $return = false;
        if(modapi_enabled && !$ajaxThumbgen)
        {
            if(count(self::$addon_index))
            {
                foreach(self::$addon_index as $addon)
                {
                    $dir = $addon['dir'];
                    if($addon['include_kernel_functions'])
                    {
                        $return = array();
                        $functions = $addon['additional-kernel-functions'];
                        foreach($functions as $function)
                        {
                            if(file_exists($dir.$function))
                                $return[] = $dir.$function;
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
        global $ajaxThumbgen; $languages_array = false;
        if(allow_additional && !$ajaxThumbgen)
        {
            $additional_languages_global = get_files(basePath.'/inc/additional-languages/',false,true,array('php'));
            if(count($additional_languages_global) >= 1 && !empty($additional_languages_global))
            { foreach($additional_languages_global as $lang_g) { if(file_exists(basePath.'/inc/additional-languages/'.$lang_g)) $languages_array[] = basePath.'/inc/additional-languages/'.$lang_g; } }

            $additional_languages = get_files(basePath.'/inc/additional-languages/'.language::get_language().'/',false,true,array('php'));
            if(count($additional_languages) >= 1 && !empty($additional_languages))
            { foreach($additional_languages as $lang) { if(file_exists(basePath.'/inc/additional-languages/'.language::get_language().'/'.$lang))
              $languages_array[] = basePath.'/inc/additional-languages/'.language::get_language().'/'.$lang; } }
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
                        $languages_globals = $addon['additional-languages-global'];
                        if(count($languages_globals) >= 1)
                        {
                            foreach($languages_globals as $global)
                            {
                                if(file_exists($dir.'languages/'.$global))
                                    $languages_array[] = $dir.'languages/'.$global;
                            }
                            unset($global,$languages_globals);
                        }

                        $languages = $addon['additional-languages'];
                        if(count($languages) >= 1)
                        {
                            foreach($languages as $lang)
                            {
                                if(file_exists($dir.'languages/'.language::get_language().'/'.$lang))
                                    $languages_array[] = ($dir.'languages/'.language::get_language().'/'.$lang);
                            }

                            unset($lang,$languages);
                        }
                    }
                }
            }
        }

        return $languages_array;
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
            if(!count(self::$addon_index)) return false;
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
                        return array('file' => $dir.$page_dir.'/pages/action_'.$action.'.php', 'dir' => $dir);
                }
            }
        }

        return false;
    }

    /**
     *  *RUN* Additional BBCODE
     *
     *  @return string
     */
    public static function run_additional_bbcode($txt)
    {
        global $ajaxThumbgen;
        if(modapi_enabled)
        {
            foreach (self::$bbcode_index as $key => $data)
            { $txt = preg_replace($data['code'],$data['rep'],$txt); }
        }

        return $txt;
    }

    /**
     *  Add Additional BBCODE
     *
     *  @return string
     */
    public static function add_additional_bbcode($bbcode=array(),$rep=array())
    { self::$bbcode_index[] = array('code' => $bbcode, 'rep' => $rep); }

    /**
     * Sortiert HM-DZCP-Core an erste Stelle
     *
     * @param array
     * @return array:
     */
    private static function core_sort()
    {
        $array_core_addons = array();
        $array_core = array(); $array_normal = array();
        foreach (self::$addon_index as $key => $var)
        { key_exists($key, $array_core_addons) ? $array_core[$key] = $var : $array_normal[$key] = $var; }
        self::$addon_index = array_merge($array_core,$array_normal);
    }

    /**
     * Eine neue Einstellung in die Datenbank schreiben mit dem Prefix *addon_*
     *
     * @param string $what
     * @param string/int $var
     * @param string/int $default
     * @param int $length
     * @param boolean $int
     * @return boolean
     */
    public static function create_settings($key='',$var='',$default='',$length=50,$int=false)
    {
        if(empty($key) || empty($var) || !$length || !is_bool($int))
            return false;

        if(settings::is_exists('addons_'.$key))
        {
            DebugConsole::insert_error('API_CORE::create_settings()', 'Setting "'.'addons_'.$key.'" is already exists!');
            return false;
        }

        return settings::add('addons_'.$key,$var,$default,$length,$int);
    }

    /**
     * Setzt einen Wert auf den Standard zurück.
     *
     * @param string $key
     * @return boolean
     */
    public static function reset_settings($key='')
    {
        if(empty($key) || !settings::is_exists('addons_'.$key))
            return false;

        $default = settings::get_default('addons_'.$key);
        if(!empty($default))
            return settings::set('addons_'.$key,$default);
    }

    /**
     * Löscht eine Einstellung aus der dzcp_settings Datenbank.
     *
     * @param string $key
     * @return boolean
     */
    public static function remove_settings($key='')
    {
        if(empty($key) || !settings::is_exists('addons_'.$key))
            return false;

        return settings::remove('addons_'.$key);
    }

    /**
     * Gibt Einstellungen aus der Settings Tabelle zurück.
     *
     * @param string $keys
     * @return boolean|mixed
     */
    public static function get_settings($keys='')
    {
        if(is_array($keys))
        {
            if(empty($keys) || !count($keys))
                return false;

            $keys_new = array();
            foreach ($keys as $key)
            { $keys_new[$key] = settings::get('addons_'.$key); }

            return $keys_new;
        }
        else
        {
            if(empty($keys) && settings::is_exists('addons_'.$keys))
                return false;

            return settings::get('addons_'.$keys);
        }
    }
}