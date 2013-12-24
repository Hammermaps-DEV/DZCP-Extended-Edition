<?php
/**
 * <DZCP-Extended Edition>
 * @package: DZCP-Extended Edition
 * @author: DZCP Developer Team || Hammermaps.de Developer Team
 * @link: http://www.dzcp.de || http://www.hammermaps.de
 */

if(_adminMenu != 'true') exit();

$qry = db("SELECT * FROM ".dba::get('awards')." ORDER BY date DESC"); $color = 1; $show = '';
while($get = _fetch($qry))
{
    $edit = show("page/button_edit_single", array("id" => $get['id'], "action" => "index=admin&amp;admin=awards&amp;do=edit", "title" => _button_title_edit));
    $delete = show("page/button_delete_single", array("id" => $get['id'], "action" => "index=admin&amp;admin=awards&amp;do=delete", "title" => _button_title_del, "del" => _confirm_del_award));
    $class = ($color % 2) ? "contentMainSecond" : "contentMainFirst"; $color++;
    $show .= show($dir."/awards_show", array("datum" => date("d.m.Y",$get['date']), "award" => string::decode($get['event']), "id" => $get['squad'], "class" => $class, "edit" => $edit, "delete" => $delete));
}

if(empty($show))
    $show = show(_no_entrys_yet, array("colspan" => "2"));

$show = show($dir."/awards", array("show" => $show));