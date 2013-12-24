<?php
/**
 * <DZCP-Extended Edition>
 * @package: DZCP-Extended Edition
 * @author: DZCP Developer Team || Hammermaps.de Developer Team
 * @link: http://www.dzcp.de || http://www.hammermaps.de
 */

if(_adminMenu != 'true') exit();
$qry = db("SELECT * FROM ".dba::get('ts')." ORDER BY id"); $color = 1;
while($get = _fetch($qry))
{
    $edit = show("page/button_edit_single", array("id" => $get['id'],"action" => "index=admin&amp;admin=teamspeak&amp;do=edit","title" => _button_title_edit));
    $delete = show("page/button_delete_single", array("id" => $get['id'],"action" => "index=admin&amp;admin=teamspeak&amp;do=delete","title" => _button_title_del,"del" => _confirm_del_server));

    $class = ($color % 2) ? "contentMainSecond" : "contentMainFirst"; $color++;
    $menu = (!$get['show_navi'] ? show(_teamspeak_menu_icon_yes, array("id" => $get['id'])) : show(_teamspeak_menu_icon_no, array("id" => $get['id'])));
    $default = ($get['default_server'] ? show(_teamspeak_default_icon_yes, array("id" => $get['id'])) : show(_teamspeak_default_icon_no, array("id" => $get['id'])));
    $show .= show($dir."/teamspeak_show", array("serverip" => cut(string::decode($get['host_ip_dns']),26,true),
                                                "serverport" => $get['server_port'],
                                                "serverqport" => $get['query_port'],
                                                "menu" => $menu,
                                                "default" => $default,
                                                "edit" => $edit,
                                                "class" => $class,
                                                "delete" => $delete));
}

if(empty($show))
    $show = show(_no_entrys_yet, array("colspan" => "4"));

$show = show($dir."/teamspeak", array("show" => $show));