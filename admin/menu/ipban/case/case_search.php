<?php
/**
 * <DZCP-Extended Edition>
 * @package: DZCP-Extended Edition
 * @author: DZCP Developer Team || Hammermaps.de Developer Team
 * @link: http://www.dzcp.de || http://www.hammermaps.de
 */

if(_adminMenu != 'true') exit();

$qry_search = db("SELECT * FROM ".dba::get('ipban')." WHERE ip LIKE '%".$_POST['ip']."%' ORDER BY ip ASC"); //Suche
$color = 1; $show_search = '';
while($get = _fetch($qry_search))
{
    $data_array = string_to_array(hextobin($get['data']));
    $edit =$get['typ'] == '3' ? show("page/button_edit_single", array("id" => $get['id'], "action" => "admin=ipban&amp;do=edit", "title" => _button_title_edit)) : '';
    $action = "?admin=ipban&amp;do=enable&amp;id=".$get['id']."&amp;ub_side=".(isset($_GET['ub_side']) ? $_GET['ub_side'] : 1)."&amp;sys_side=".(isset($_GET['sys_side']) ? $_GET['sys_side'] : 1)."&amp;sfs_side=".(isset($_GET['sfs_side']) ? $_GET['sfs_side'] : 1);
    $unban = ($get['enable'] ? show(_ipban_menu_icon_enable, array("id" => $get['id'], "action" => $action, "info" => show(_confirm_disable_ipban,array('ip'=>$get['ip'])))) : show(_ipban_menu_icon_disable, array("id" => $get['id'], "action" => $action, "info" => convSpace(show(_confirm_enable_ipban,array('ip'=>$get['ip']))))));
    $delete = show("page/button_delete_single", array("id" => $get['id'], "action" => "admin=ipban&amp;do=delete", "title" => _button_title_del, "del" => _confirm_del_ipban));
    $class = ($color % 2) ? "contentMainSecond" : "contentMainFirst"; $color++;
    $show_search .= show($dir."/ipban_show_user", array("ip" => string::decode($get['ip']), "bez" => string::decode($data_array['banned_msg']), "rep" => convert::ToString($data_array['frequency']), "zv" => convert::ToString($data_array['confidence']).'%', "class" => $class, "delete" => $delete, "edit" => $edit, "unban" => $unban));
}

if(empty($show_search))
    $show_search = '<tr><td colspan="7" class="contentMainSecond">'._no_entrys.'</td></tr>';

$show = show($dir."/ipban_search", array("value" => _button_value_save, "show" => $show_search,  "edit" => _editicon_blank, "delete" => _deleteicon_blank ));