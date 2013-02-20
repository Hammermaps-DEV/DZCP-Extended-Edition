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
include(basePath."/inc/bbcode.php");
include(basePath."/admin/helper.php");

## SETTINGS ##
$where = _site_config;
$dir = "admin";
$show = "";
$cache_cleanup = false;
$wysiwyg = "";
$rootmenu = "";
$settingsmenu = "";
$contentmenu = "";

## SECTIONS ##
if(!admin_perms($_SESSION['id']))
    $index = error(_error_wrong_permissions, 1); //Keine Rechte
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
        xml::openXMLfile($XMLTag,"admin/menu/".$file);
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

            if($settings['Rights'] != 'done')
                $permission = permission($settings['Rights']);
            else
                $permission = false;

            foreach($picformat AS $end)
            {
                if(file_exists(basePath.'/admin/menu/'.$settings['file_name'].'.'.$end))
                    break;
            }

            if(($permission && !$settings['Only_Admin'] && !$settings['Only_Root']) || ($chkMe == 4 && $settings['Only_Admin'] && !$settings['Only_Root']) || ($settings['Only_Root'] && convert::ToInt($userid) == convert::ToInt($rootAdmin)))
                $amenu[$settings['Typ']][$link] = show(_holder, array("link" => $link, 'name' => $settings['file_name'], "end" => $end));

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

    if(isset($_GET['admin']))
    {
        if(file_exists(basePath."/admin/menu/".($inc_file=((string)$_GET['admin']).".php")))
        {
            unset($settings); $settings = array();
            $XMLTag = 'admin_'.((string)$_GET['admin']);
            $settings['Only_Admin'] = xml::bool(xml::getXMLvalue($XMLTag, 'Only_Admin'));
            $settings['Only_Root'] = xml::bool(xml::getXMLvalue($XMLTag, 'Only_Root'));
            $permission = permission(((string)xml::getXMLvalue($XMLTag, 'Rights')));

            if(($permission && !$settings['Only_Admin'] && !$settings['Only_Root']) || ($chkMe == 4 && $settings['Only_Admin'] && !$settings['Only_Root']) || ($settings['Only_Root'] && convert::ToInt($userid) == convert::ToInt($rootAdmin)))
                require_once(basePath."/admin/menu/".$inc_file);
            else
                $show = error(_error_wrong_permissions, 1);
        }
    }

    $dzcp_version = show_dzcp_version();
    $index = show($dir."/admin", array("head" => _config_head,
            "version" => $dzcp_version['version'],
            "old" => $dzcp_version['old'],
            "einst" => _config_einst,
            "content" => _content,
            "rootadmin" => _rootadmin,
            "rootmenu" => $rootmenu,
            "settingsmenu" => $settingsmenu,
            "contentmenu" => $contentmenu,
            "radmin1" => $radmin1,
            "radmin2" => $radmin2,
            "adminc1" => $adminc1,
            "adminc2" => $adminc2,
            "content1" => $contentadmin1,
            "content2" => $contentadmin2,
            "show" => $show));
}

## SETTINGS ##
$time_end = generatetime();
$time = round($time_end - $time_start,4);
$title = $pagetitle." - ".$where."";
page($index, $title, $where ,$time,$wysiwyg);

if($cache_cleanup)
    Cache::clean();

## OUTPUT BUFFER END ##
gz_output();
?>