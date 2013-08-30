<?php
/**
 * <DZCP-Extended Edition>
 * @package: DZCP-Extended Edition
 * @author: DZCP Developer Team || Hammermaps.de Developer Team
 * @link: http://www.dzcp.de || http://www.hammermaps.de
 */

if(_adminMenu != 'true') exit();

$qry = db("SELECT * FROM ".dba::get('downloads')." ORDER BY id");
while($get = _fetch($qry))
{
	$edit = show("page/button_edit_single", array("id" => $get['id'],
	                                              "action" => "admin=dladmin&amp;do=edit",
	                                              "title" => _button_title_edit));
	$delete = show("page/button_delete_single", array("id" => $get['id'],
	                                                  "action" => "admin=dladmin&amp;do=delete",
	                                                  "title" => _button_title_del,
	                                                  "del" => _confirm_del_dl));

	$class = ($color % 2) ? "contentMainSecond" : "contentMainFirst"; $color++;
	$show_ .= show($dir."/downloads_show", array("id" => $get['id'],
	                                             "dl" => string::decode($get['download']),
	                                             "class" => $class,
	                                             "edit" => $edit,
	                                             "delete" => $delete
	                                             ));
}

$show = show($dir."/downloads", array("head" => _dl,
                                             "date" => _datum,
                                             "titel" => _dl_file,
                                             "add" => _downloads_admin_head,
                                             "show" => $show_));