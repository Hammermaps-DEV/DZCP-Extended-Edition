<?php
/**
 * <DZCP-Extended Edition>
 * @package: DZCP-Extended Edition
 * @author: DZCP Developer Team || Hammermaps.de Developer Team
 * @link: http://www.dzcp.de || http://www.hammermaps.de
 */

if(_adminMenu != 'true') exit();

if(isset($_GET['startup']) && !empty($_GET['startup']))
{
    $get = db("SELECT enable,installed,dir FROM `".dba::get('addons')."` WHERE `id` = '".convert::ToInt($_GET['startup'])."' LIMIT 1",false,true);
    if($get['enable']) { db("UPDATE `".dba::get('addons')."` SET `enable` = '0' WHERE `id` = ".convert::ToInt($_GET['startup']).";"); }
    else if(!$get['enable'] && !API_CORE::$addon_index_all[$get['dir']]['xml']['xml_addon_installer'])
    { db("UPDATE `".dba::get('addons')."` SET `enable` = '1' WHERE `id` = ".convert::ToInt($_GET['startup']).";"); }
    else if(!$get['enable'] && API_CORE::$addon_index_all[$get['dir']]['xml']['xml_addon_installer'] && $get['installed'])
    { db("UPDATE `".dba::get('addons')."` SET `enable` = '1' WHERE `id` = ".convert::ToInt($_GET['startup']).";"); }
    else { db("UPDATE `".dba::get('addons')."` SET `enable` = '0' WHERE `id` = ".convert::ToInt($_GET['startup']).";"); }
    header('Location: ../admin/?admin=addonsmgr#viewcontent'); //Reload
}

$show_list = '';
foreach (API_CORE::$addon_index_all as $addon_dir => $addon)
{
    $get = db("SELECT enable,installed,id FROM `".dba::get('addons')."` WHERE `dir` = '".string::encode($addon_dir)."' LIMIT 1",false,true);
    $running = false;
    if(!$addon['xml']['xml_addon_installer'] && $get['enable'] or ($addon['xml']['xml_addon_installer'] && $get['installed'] && $get['enable']))
    {
        $info_msg = '<font color="green">'._addonsmgr_running.'</font>';
        $running = true;
    }
    else if($addon['xml']['xml_addon_installer'] && !$get['installed'])
        $info_msg = '<font color="gray">'._addonsmgr_install_required.'</font>';
    else
        $info_msg = '<font color="red">'._addonsmgr_disabled.'</font>';

    $installer = $addon['xml']['xml_addon_installer'] && !$get['installed'] ? '<a href="?installer&addon='.$addon_dir.'#viewcontent"><img title="'._addonsmgr_addon_wiz.'" src="../inc/images/installer.png" width="16" height="16" border="0" /></a>' : '<img src="../inc/images/installer_disabled.png" width="16" height="16" border="0" />';
    $autor_url = !empty($addon['xml']['xml_addon_autor_url']) ? '<a href="'.$addon['xml']['xml_addon_autor_url'].'" target="_blank"><img title="'._addonsmgr_addon_hp.'" src="../inc/images/hp.gif" alt="" width="16" height="16" border="0" /></a>' : '<img src="../inc/images/hp_disabled.png" alt="" width="16" height="16" border="0" />';
    $show_list .= show($dir."/addons_mgr_show",array('name' => bbcode::parse_html($addon['xml']['xml_addon_name']),
                                                     'autor' => bbcode::parse_html($addon['xml']['xml_addon_autor']),
                                                     'autor_mail' => $addon['xml']['xml_addon_autor_mail'],
                                                     'build_rev' => $addon['xml']['xml_addon_build_rev'],
                                                     'version' => $addon['xml']['xml_addon_version'],
                                                     'id' => convert::ToString($get['id']),
                                                     'autor_url' => $autor_url,
                                                     'installer' => $installer,
                                                     'info_msg' => $info_msg,
                                                     'status' => $get['enable'] ? ( !$get['installed'] && $addon['xml']['xml_addon_installer'] ? 'static.png' :'online.png') : 'offline.png',
                                                     'startup' => $running ? 'stop.png' : 'run.png',

    ));
}

if(empty($show_list))
    $show_list = show(_no_entrys_yet, array("colspan" => "8"));

$show = show($dir."/addons_mgr", array('show' => $show_list));