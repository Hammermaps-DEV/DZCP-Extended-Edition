<?php
/**
 * <DZCP-Extended Edition>
 * @package: DZCP-Extended Edition
 * @author: DZCP Developer Team || Hammermaps.de Developer Team
 * @link: http://www.dzcp.de || http://www.hammermaps.de
 */

if(_adminMenu != 'true') exit();

$qry = db("SELECT * FROM ".dba::get('sponsoren')." ORDER BY pos"); $color = 1;
while($get = _fetch($qry))
{
    $edit = show("page/button_edit_single", array("id" => $get['id'], "action" => "index=admin&admin=sponsors&amp;do=edit", "title" => _button_title_edit));
    $delete = show("page/button_delete_single", array("id" => $get['id'], "action" => "index=admin&admin=sponsors&amp;do=delete", "title" => _button_title_del, "del" => _confirm_del_link));
    $class = ($color % 2) ? "contentMainSecond" : "contentMainFirst"; $color++;

    $show .= show($dir."/sponsors_show", array("link" => cut(string::decode($get['link']),40), "class" => $class, "name" => $get['name'], "edit" => $edit, "delete" => $delete));
}

$show = show($dir."/sponsors", array("show" => $show));