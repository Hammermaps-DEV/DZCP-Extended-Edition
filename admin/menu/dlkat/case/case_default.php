<?php
/**
 * <DZCP-Extended Edition>
 * @package: DZCP-Extended Edition
 * @author: DZCP Developer Team || Hammermaps.de Developer Team
 * @link: http://www.dzcp.de || http://www.hammermaps.de
 */

if(_adminMenu != 'true') exit();

$qry = db("SELECT * FROM ".dba::get('dl_kat')." ORDER BY name"); $color = 1;
while($get = _fetch($qry))
{
    $edit = show("page/button_edit_single", array("id" => $get['id'], "action" => "admin=dlkat&amp;do=edit", "title" => _button_title_edit));
    $delete = show("page/button_delete_single", array("id" => $get['id'],
                                                      "action" => "admin=dlkat&amp;do=delete",
                                                      "title" => _button_title_del,
                                                      "del" => _confirm_del_kat));
    $class = ($color % 2) ? "contentMainSecond" : "contentMainFirst"; $color++;
    $show .= show($dir."/dlkats_show", array("gameicon" => $gameicon,
                                             "edit" => $edit,
                                             "name" => string::decode($get['name']),
                                             "class" => $class,
                                             "delete" => $delete));
}

$show = show($dir."/dlkats", array("head" => _admin_dlkat,
                                     "show" => $show,
                                     "add" => _dl_new_head,
                                     "whatkat" => 'dlkat',
                                     "download" => _admin_download_kat,
                                     "edit" => _editicon_blank,
                                     "delete" => _deleteicon_blank));