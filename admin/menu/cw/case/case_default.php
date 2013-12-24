<?php
/**
 * <DZCP-Extended Edition>
 * @package: DZCP-Extended Edition
 * @author: DZCP Developer Team || Hammermaps.de Developer Team
 * @link: http://www.dzcp.de || http://www.hammermaps.de
 */

if(_adminMenu != 'true') exit();

$whereqry = '';
if(isset($_GET['squad']) && is_numeric($_GET['squad']))
    $whereqry = ' WHERE squad_id = '.convert::ToInt($_GET['squad']).' ';

$qry = db("SELECT * FROM ".dba::get('cw')." ".$whereqry." ORDER BY datum DESC LIMIT ".(($page - 1) * 10).",10");
$entrys = cnt(dba::get('cw'));
$qrys = db("SELECT * FROM ".dba::get('squads')." WHERE status = '1' ORDER BY game ASC");

$squads = show(_cw_edit_select_field_squads, array("name" => _all, "sel" => "", "id" => "?index=admin&amp;admin=cw"));
while($gets = _fetch($qrys))
{
    $sel = (isset($_GET['squad']) && $gets['id'] == $_GET['squad'] ? ' class="dropdownKat"' : '');
    $squads .= show(_cw_edit_select_field_squads, array("name" => string::decode($gets['name']), "sel" => $sel, "id" => "?admin=cw&amp;squad=".$gets['id'].""));
}

$show_list = ''; $color = 0;
while($get = _fetch($qry))
{
    $top = empty($get['top'])
        ? '<a href="?index=admin&amp;admin=cw&amp;do=top&amp;set=1&amp;id='.$get['id'].'"><img src="inc/images/no.gif" alt="" title="'._cw_admin_top_set.'" /></a>'
        : '<a href="?index=admin&amp;admin=cw&amp;do=top&amp;set=0&amp;id='.$get['id'].'"><img src="inc/images/yes.gif" alt="" title="'._cw_admin_top_unset.'" /></a>';

    $edit = show("page/button_edit_single", array("id" => $get['id'], "action" => "index=admin&amp;admin=cw&amp;do=edit", "title" => _button_title_edit));
    $delete = show("page/button_delete_single", array("id" => $get['id'], "action" => "index=admin&amp;admin=cw&amp;do=delete", "title" => _button_title_del, "del" => _confirm_del_cw));

    $class = ($color % 2) ? "contentMainSecond" : "contentMainFirst"; $color++;
    $show_list .= show($dir."/clanwars_show", array("class" => $class,
                                                    "cw" => string::decode($get['clantag'])." - ".string::decode($get['gegner']),
                                                    "datum" => date("d.m.Y H:i",$get['datum'])._uhr,
                                                    "top" => $top,
                                                    "id" => $get['id'],
                                                    "edit" => $edit,
                                                    "delete" => $delete));
}

$show = show($dir."/clanwars", array("show" => $show_list, "navi" => nav($entrys,10,"?index=admin&amp;admin=cw". (isset($_GET['squad']) ? '&amp;squad='.$_GET['squad'] : '') )));