<?php
/**
 * <DZCP-Extended Edition>
 * @package: DZCP-Extended Edition
 * @author: DZCP Developer Team || Hammermaps.de Developer Team
 * @link: http://www.dzcp.de || http://www.hammermaps.de
 */

## OUTPUT BUFFER START ##
include("../inc/buffer.php");

## INCLUDES ##
include(basePath."/inc/debugger.php");
include(basePath."/inc/config.php");
include(basePath."/inc/common.php");
include(basePath."/admin/helper.php");

## SETTINGS ##
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

    //Lade XML Daten
    $amenu = array();
    $files = get_files(basePath.'/admin/menu/',false,true,array('xml'));
    foreach($files AS $file)
    {
        ## XML Auslesen ##
        $XMLTag = 'admin_'.str_ireplace('.xml', '', $file);
        if(!xml::openXMLfile($XMLTag,"admin/menu/".$file)) continue;
        $settings = array();
        $settings['Typ'] = ((string)xml::getXMLvalue($XMLTag, 'Menu'));
        $settings['Rights'] = ((string)xml::getXMLvalue($XMLTag, 'Rights'));
        $settings['Only_Admin'] = xml::bool(xml::getXMLvalue($XMLTag, 'Only_Admin'));
        $settings['Only_Root'] = xml::bool(xml::getXMLvalue($XMLTag, 'Only_Root'));
        $settings['file_name'] = str_replace('.xml', '', $file);
        $settings['file_name_php'] = str_replace('.xml', '.php', $file);

        if(file_exists(basePath."/admin/menu/".$settings['file_name_php']))
        {
            ## Menu ##
            eval("\$link = _config_".$settings['file_name'].";");
            $permission =  ($settings['Rights'] != 'done' ? permission($settings['Rights']) : false);

            foreach($picformat AS $end)
            {
                if(file_exists(basePath.'/admin/menu/'.$settings['file_name'].'.'.$end))
                    break;
            }

            if(!file_exists(basePath.'/admin/menu/'.$settings['file_name'].'.'.$end))
            {
                $settings['file_name'] = 'unknown';
                $end = 'gif';
            }

            if(($permission && !$settings['Only_Admin'] && !$settings['Only_Root']) || (checkme() == 4 && $settings['Only_Admin'] && !$settings['Only_Root']) || ($settings['Only_Root'] && userid() == convert::ToInt($rootAdmin)))
                $amenu[$settings['Typ']][$link] = show(_holder, array("link" => $link, 'name' => $settings['file_name'], "end" => $end));

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
            $xml_file = str_ireplace('.php', '.xml', $file['file']);
            $XMLTag = 'admin_'.str_ireplace('.xml', '', $xml_file);
            if(!xml::openXMLfile($XMLTag,'inc/additional-addons/'.$file['dir']."/admin/menu/".$xml_file)) continue;

            $settings = array();
            $settings['Typ'] = ((string)xml::getXMLvalue($XMLTag, 'Menu'));
            $settings['Rights'] = ((string)xml::getXMLvalue($XMLTag, 'Rights'));
            $settings['Only_Admin'] = xml::bool(xml::getXMLvalue($XMLTag, 'Only_Admin'));
            $settings['Only_Root'] = xml::bool(xml::getXMLvalue($XMLTag, 'Only_Root'));
            $settings['file_name'] = str_replace('.php', '', $file['file']);
            $settings['file_name_php'] = $file['file'];

            if(file_exists(basePath.'/inc/additional-addons/'.$file['dir'].'/admin/menu/'.$settings['file_name_php']))
            {
                ## Menu ##
                eval("\$link = _config_".$settings['file_name'].";");
                $permission =  ($settings['Rights'] != 'done' ? permission($settings['Rights']) : false);

                foreach($picformat AS $end)
                {
                    if(file_exists(basePath.'/inc/additional-addons/'.$file['dir'].'/admin/menu/'.$settings['file_name'].'.'.$end))
                        break;
                }

                if(!file_exists(basePath.'/inc/additional-addons/'.$file['dir'].'/admin/menu/'.$settings['file_name'].'.'.$end))
                {
                    $settings['file_name'] = 'unknown';
                    $end = 'gif';
                }

                if(($permission && !$settings['Only_Admin'] && !$settings['Only_Root']) || (checkme() == 4 && $settings['Only_Admin'] && !$settings['Only_Root']) || ($settings['Only_Root'] && userid() == convert::ToInt($rootAdmin)))
                    $amenu[$settings['Typ']][$link] = show(_holder_addons, array("pdir" => 'inc/additional-addons/'.$file['dir'].'/admin/menu', "link" => $link, 'name' => $settings['file_name'], "end" => $end));

                unset($settings,$XMLTag,$link,$permission);
            }
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
        if(file_exists(basePath."/admin/menu/".($inc_file=((string)$_GET['admin']).".php")))
            $basic_require = true;

        if(modapi_enabled)
        {
            if(($inc_file_addons = API_CORE::call_additional_adminmenu(((string)$_GET['admin']))) != false)
            {
                $inc_file = $inc_file_addons;
                $basic_require = false;
                unset($inc_file_addons);
            }
        }

        if(file_exists($basic_require ? basePath."/admin/menu/".$inc_file : $inc_file))
        {
            unset($settings); $settings = array();
            $XMLTag = 'admin_'.((string)$_GET['admin']);
            $settings['Only_Admin'] = xml::bool(xml::getXMLvalue($XMLTag, 'Only_Admin'));
            $settings['Only_Root'] = xml::bool(xml::getXMLvalue($XMLTag, 'Only_Root'));
            $permission = permission(((string)xml::getXMLvalue($XMLTag, 'Rights')));
            $do = (isset($_GET['do']) ? $_GET['do'] : (isset($_POST['do']) ? $_POST['do'] : '') );
            $page = (isset($_GET['page']) ? $_GET['page'] : '1');

            if(($permission && !$settings['Only_Admin'] && !$settings['Only_Root']) || (checkme() == 4 && $settings['Only_Admin'] && !$settings['Only_Root']) || ($settings['Only_Root'] && userid() == convert::ToInt($rootAdmin)))
                require_once(($basic_require ? basePath."/admin/menu/".$inc_file : $inc_file));
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

## SETTINGS ##
$time_end = generatetime();
$time = round($time_end - $time_start,4);
$title = $pagetitle." - ".$where."";
page($index, $title, $where ,$time);

if($cache_cleanup)
    Cache::clean();

## OUTPUT BUFFER END ##
gz_output();