<?php
/**
 * <DZCP-Extended Edition>
 * @package: DZCP-Extended Edition
 * @author: DZCP Developer Team || Hammermaps.de Developer Team
 * @link: http://www.dzcp.de || http://www.hammermaps.de
 */

if(_adminMenu != 'true') exit();

$qry = db("SELECT id,url FROM ".dba::get('links')." ORDER BY banner DESC"); $color = 1;
while($get = _fetch($qry))
{
    $edit = show("page/button_edit_single", array("id" => $get['id'], "action" => "index=admin&amp;admin=links&amp;do=edit&amp;type=links", "title" => _button_title_edit));
    $delete = show("page/button_delete_single", array("id" => $get['id'], "action" => "index=admin&amp;admin=links&amp;do=delete&amp;type=links", "title" => _button_title_del, "del" => _confirm_del_link));
    $class = ($color % 2) ? "contentMainSecond" : "contentMainFirst"; $color++;
    $show .= show($dir."/links_show", array("link" => links(cut(string::decode($get['url']),40)), "class" => $class, "type" => "links", "edit" => $edit, "delete" => $delete));
}

if(empty($show))
    $show = show(_no_entrys_yet, array("colspan" => "3"));

$show = show($dir."/links", array("show" => $show));