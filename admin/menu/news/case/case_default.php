<?php
/**
 * <DZCP-Extended Edition>
 * @package: DZCP-Extended Edition
 * @author: DZCP Developer Team || Hammermaps.de Developer Team
 * @link: http://www.dzcp.de || http://www.hammermaps.de
 */

if(_adminMenu != 'true') exit();

$qry = db("SELECT * FROM ".dba::get('newskat')." ORDER BY `kategorie`");
$kats = ''; $color = 1;
while($get = _fetch($qry))
{
    $edit = show("page/button_edit_single", array("id" => $get['id'], "action" => "index=admin&amp;admin=news&amp;do=edit", "title" => _button_title_edit));
    $delete = show("page/button_delete_single", array("id" => $get['id'], "action" => "index=admin&amp;admin=news&amp;do=delete", "title" => _button_title_del, "del" => _confirm_del_kat));
    $img = show(_config_newskats_img, array("img" => string::decode($get['katimg'])));
    $class = ($color % 2) ? "contentMainSecond" : "contentMainFirst"; $color++;
    $kats .= show($dir."/newskats_show", array("mainkat" => string::decode($get['kategorie']), "class" => $class, "img" => $img, "delete" => $delete, "edit" => $edit));
}
unset($class,$color,$img,$delete,$edit,$qry,$get);

$show = show($dir."/newskats", array("kats" => $kats));