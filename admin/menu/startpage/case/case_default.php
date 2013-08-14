<?php
/**
 * <DZCP-Extended Edition>
 * @package: DZCP-Extended Edition
 * @author: DZCP Developer Team || Hammermaps.de Developer Team
 * @link: http://www.dzcp.de || http://www.hammermaps.de
 */

if(_adminMenu != 'true') exit();

$sql = db("SELECT * FROM `".dba::get('startpage')."`;"); $color = 0; $show = '';
while($get = _fetch($sql))
{
    $edit = show("page/button_edit_single", array("id" => $get['id'], "action" => "admin=startpage&amp;do=edit", "title" => _button_title_edit));
    $delete = show("page/button_delete_single", array("id" => $get['id'], "action" => "admin=startpage&amp;do=delete", "title" => _button_title_del, "del" => _confirm_del_entry));
    $class = ($color % 2) ? "contentMainSecond" : "contentMainFirst"; $color++;
    $show .= show($dir."/startpage_show", array("edit" => $edit, "name" => string::decode($get['name']), "url" => string::decode($get['url']), "class" => $class, "delete" => $delete));
}

$show = show($dir."/startpage", array("show" => $show, "add" => _dl_new_head, "edit" => _editicon_blank, "delete" => _deleteicon_blank));