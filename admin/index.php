<?php
/**
 * <DZCP-Extended Edition>
 * @package: DZCP-Extended Edition
 * @author: DZCP Developer Team || Hammermaps.de Developer Team
 * @link: http://www.dzcp.de || http://www.hammermaps.de
 */

if(!defined('IS_DZCP'))
{
    include("../inc/buffer.php");
    include(basePath."/inc/debugger.php");
    include(basePath."/inc/config.php");
    include(basePath."/inc/common.php");
    header('Location: ../'.startpage('admin',1));
}

## INCLUDES ##
include(basePath."/admin/helper.php");

## SETTINGS ##
bbcode::use_glossar(false);
$where = _site_config;
$dir = "admin";
$show = "";
$cache_cleanup = false;
$rootmenu = "";
$settingsmenu = "";
$contentmenu = "";
$extendedmenu = "";

## SECTIONS ##
if(!admin_perms($_SESSION['id']))
    $index = error(_error_wrong_permissions); //Keine Rechte
else
{
    define('_adminMenu', true);
    $installer_output = '';

    //Addons Installer
    if(modapi_enabled)
    {
        $installer = (isset($_GET['installer']) && addons_installer::init());

        if($installer)
            $installer_output = installer::installer_output();
    }

    if(!empty($installer_output) && $installer)
        $index = $installer_output;
    else
    {
        //Lade XML Daten
        $amenu = array();
        $dirs = get_files(basePath.'/admin/menu/',true,false);
        foreach($dirs AS $dir_admin)
        {
            if(file_exists(basePath."/admin/menu/".$dir_admin."/config.xml"))
            {
                ## XML Auslesen ##
                $XMLTag = 'admin_'.$dir_admin;
                if(!xml::openXMLfile($XMLTag,"admin/menu/".$dir_admin."/config.xml")) continue;
                $settings = array();
                $settings['Typ'] = convert::ToString(xml::getXMLvalue($XMLTag, 'Menu'));
                $settings['Rights'] = convert::ToString(xml::getXMLvalue($XMLTag, 'Rights'));
                $settings['Only_Admin'] = xml::bool(xml::getXMLvalue($XMLTag, 'Only_Admin'));
                $settings['Only_Root'] = xml::bool(xml::getXMLvalue($XMLTag, 'Only_Root'));

                ## Menu ##
                $link = language::display('_config_'.$dir_admin);
                $permission =  ($settings['Rights'] != 'done' ? permission($settings['Rights']) : false);

                foreach($picformat AS $end)
                {
                    if(file_exists(basePath.'/admin/menu/'.$dir_admin.'/icon.'.$end))
                        break;
                }

                if(!file_exists(basePath.'/admin/menu/'.$dir_admin.'/icon.'.$end))
                {
                    $dir_admin = '';
                    $end = 'gif';
                }

                if(($permission && !$settings['Only_Admin'] && !$settings['Only_Root']) || (checkme() == 4 && $settings['Only_Admin'] && !$settings['Only_Root']) || ($settings['Only_Root'] && userid() == convert::ToInt($rootAdmin)))
                    $amenu[$settings['Typ']][$link] = show(_holder, array("link" => $link, 'name' => $dir_admin, "end" => $end));

                unset($settings,$XMLTag,$link,$permission,$file);
            }
        }

        //Load addons Adminmenu
        if(API_CORE::is_additional_adminmenu())
        {
            $menus = API_CORE::load_additional_adminmenu();
            foreach($menus AS $file)
            {
                ## XML Auslesen ##
                $XMLTag = 'admin_addons_'.$file['file_dir'];
                if(!xml::openXMLfile($XMLTag,'inc/additional-addons/'.$file['dir']."/admin/menu/".$file['file_dir']."/config.xml")) continue;

                $settings = array();
                $settings['Typ'] = convert::ToString(xml::getXMLvalue($XMLTag, 'Menu'));
                $settings['Rights'] = convert::ToString(xml::getXMLvalue($XMLTag, 'Rights'));
                $settings['Only_Admin'] = xml::bool(xml::getXMLvalue($XMLTag, 'Only_Admin'));
                $settings['Only_Root'] = xml::bool(xml::getXMLvalue($XMLTag, 'Only_Root'));
                $settings['file_dir'] = $file['file_dir'];

                ## Menu ##
                $link = language::display('_config_'.$settings['file_dir']);
                $permission =  ($settings['Rights'] != 'done' ? permission($settings['Rights']) : false);

                foreach($picformat AS $end)
                {
                    if(file_exists(basePath.'/inc/additional-addons/'.$file['dir'].'/admin/menu/'.$settings['file_dir'].'/icon.'.$end))
                        break;
                }

                if(!file_exists(basePath.'/inc/additional-addons/'.$file['dir'].'/admin/menu/'.$settings['file_dir'].'/icon.'.$end))
                {
                    $settings['file_dir'] = '';
                    $end = 'gif';
                }

                if(($permission && !$settings['Only_Admin'] && !$settings['Only_Root']) || (checkme() == 4 && $settings['Only_Admin'] && !$settings['Only_Root']) || ($settings['Only_Root'] && userid() == convert::ToInt($rootAdmin)))
                    $amenu[$settings['Typ']][$link] = show(_holder_addons, array("pdir" => 'inc/additional-addons/'.$file['dir'].'/admin/menu/'.$file['file_dir'], "link" => $link, 'name' => $settings['file_dir'], "end" => $end));

                unset($settings,$XMLTag,$link,$permission);
            }
        }

        ## Sortieren ##
        foreach($amenu AS $m => $k)
        {
            natcasesort($k);
            foreach($k AS $l) $$m .= $l;
        }

        ## Root Menu deaktivieren ##
        if(empty($rootmenu))
        {
            $radmin1 = '/*';
            $radmin2 = '*/';
        }
        else
            $radmin2 = ($radmin1 = '');

        ## Settings Menu deaktivieren ##
        if(empty($settingsmenu))
        {
            $adminc1 = '/*';
            $adminc2 = '*/';
        }
        else
            $adminc2 = ($adminc1 = '');

        ## Content Menu deaktivieren ##
        if(empty($contentmenu))
        {
            $contentadmin1 = '/*';
            $contentadmin2 = '*/';
        }
        else
            $contentadmin2 = ($contentadmin1 = '');

        ## Extended Menu deaktivieren ##
        if(empty($extendedmenu))
        {
            $extendedadmin1 = '/*';
            $extendedadmin2 = '*/';
        }
        else
            $extendedadmin2 = ($extendedadmin1 = '');

        if(isset($_GET['admin']))
        {
            $index_require = false; $inc_file = '';
            if(file_exists(basePath."/admin/menu/".($admin_do=((string)$_GET['admin']))."/config.xml"))
            {
                $XMLTag = 'admin_'.$admin_do;
                $inc_file = $admin_do."/index.php";
                $inc_file_header = $admin_do."/header.php";
                $inc_file_footer = $admin_do."/footer.php";
                $index_require = file_exists(basePath."/admin/menu/".$admin_do."/index.php");
                $inc_header = file_exists(basePath."/admin/menu/".$admin_do."/header.php");
                $inc_footer = file_exists(basePath."/admin/menu/".$admin_do."/footer.php");
                $inc_file_functions = API_CORE::load_additional_admin_functions($admin_do,false);
                $inc_file_languages = API_CORE::load_additional_admin_languages($admin_do,false);
                $inc_file_case_dir = API_CORE::load_admin_case_dir($admin_do,false);
                $addon_dir = ''; $basic_require = true;
            }

            if(modapi_enabled)
            {
                if(($inc_file_addons = API_CORE::call_additional_adminmenu(($admin_do=((string)$_GET['admin'])))) != false)
                {
                    $XMLTag = 'admin_addons_'.$admin_do;
                    $inc_file = $inc_file_addons['require_indexes'];
                    $inc_file_functions = $inc_file_addons['require_functions'];
                    $inc_file_languages = $inc_file_addons['require_languages'];
                    $inc_file_case_dir = $inc_file_addons['require_case_dir'];
                    $inc_header = $inc_file_addons['require_header_file'];
                    $inc_footer = $inc_file_addons['require_footer_file'];
                    $index_require = $inc_file_addons['require_index_file'];
                    $addon_dir = $inc_file_addons['addon_dir'];
                    $basic_require = false; unset($inc_file_addons);
                }
            }

            if(($index_require && file_exists($basic_require ? basePath."/admin/menu/".$inc_file : $inc_file.'/index.php')) || !$index_require)
            {
                unset($settings); $settings = array();
                $settings['Only_Admin'] = xml::bool(xml::getXMLvalue($XMLTag, 'Only_Admin'));
                $settings['Only_Root'] = xml::bool(xml::getXMLvalue($XMLTag, 'Only_Root'));
                $permission = permission(convert::ToString(xml::getXMLvalue($XMLTag, 'Rights')));
                $where_ext = convert::ToString(xml::getXMLvalue($XMLTag, 'Where'));
                $do = (isset($_GET['do']) ? strtolower($_GET['do']) : (isset($_POST['do']) ? strtolower($_POST['do']) : '') );
                $page = (isset($_GET['page']) ? $_GET['page'] : '1');

                if(($permission && !$settings['Only_Admin'] && !$settings['Only_Root']) || (checkme() == 4 && $settings['Only_Admin'] && !$settings['Only_Root']) || ($settings['Only_Root'] && userid() == convert::ToInt($rootAdmin)))
                {
                    $modul_file = '';
                    if($inc_header) require_once(($basic_require ? basePath."/admin/menu/".$inc_file_header : $inc_file.'/header.php'));
                    if($inc_file_functions != false) { foreach ($inc_file_functions as $inc_file_function) { require_once($inc_file_function); } }
                    if($inc_file_languages != false) { foreach ($inc_file_languages as $inc_file_language) { require_once($inc_file_language); } }
                    if($index_require) require_once(($basic_require ? basePath."/admin/menu/".$inc_file : $inc_file.'/index.php'));  $where = (empty($where_ext) ? $where : $where.': '.language::display($where_ext));
                    if($inc_file_case_dir != false && file_exists(($modul_file=$inc_file_case_dir.'/case_'.( !empty($do) ? $do : 'default').'.php'))) require_once $modul_file;
                    if(!file_exists($modul_file) && file_exists(($modul_file=$inc_file_case_dir.'/case_default.php'))) require_once $modul_file;
                    if($inc_footer) require_once(($basic_require ? basePath."/admin/menu/".$inc_file_footer : $inc_file.'/footer.php'));
                }
                else
                    $show = error(_error_wrong_permissions);
            }
        }

        $dzcp_version = show_dzcp_version();
        $index = show($dir."/admin", array("head" => _config_head,
                                           "version" => $dzcp_version['version'],
                                           "version_img" => $dzcp_version['version_img'],
                                           "einst" => _config_einst,
                                           "content" => _content,
                                           "rootadmin" => _rootadmin,
                                           "extended" => _extended,
                                           "rootmenu" => $rootmenu,
                                           "settingsmenu" => $settingsmenu,
                                           "contentmenu" => $contentmenu,
                                           "radmin1" => $radmin1,
                                           "radmin2" => $radmin2,
                                           "adminc1" => $adminc1,
                                           "adminc2" => $adminc2,
                                           "content1" => $contentadmin1,
                                           "content2" => $contentadmin2,
                                           "extended1" => $extendedadmin1,
                                           "extended2" => $extendedadmin2,
                                           "extendedmenu" => $extendedmenu,
                                           "show" => $show));
    }
}

if($cache_cleanup) Cache::clean();