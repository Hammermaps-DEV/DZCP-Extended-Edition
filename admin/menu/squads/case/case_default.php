<?php
/**
 * <DZCP-Extended Edition>
 * @package: DZCP-Extended Edition
 * @author: DZCP Developer Team || Hammermaps.de Developer Team
 * @link: http://www.dzcp.de || http://www.hammermaps.de
 */

if(_adminMenu != 'true') exit();

$qry = db("SELECT id,name,game,icon FROM ".dba::get('squads')." ORDER BY pos");
$color = 1; $squads = '';
while($get = _fetch($qry))
{
    $edit = show("page/button_edit_single", array("id" => $get['id'], "action" => "admin=squads&amp;do=edit", "title" => _button_title_edit));
    $delete = show("page/button_delete_single", array("id" => $get['id'], "action" => "admin=squads&amp;do=delete", "title" => _button_title_del, "del" => _confirm_del_team));
    $icon = show(_gameicon, array("icon" => $get['icon']));

    $class = ($color % 2) ? "contentMainSecond" : "contentMainFirst"; $color++;
    $squads .= show($dir."/squads_show", array("squad" => '<a href="../squads/?action=shows&amp;id='.$get['id'].'" style="display:block">'.string::decode($get['name']).'</a>',
                                               "game" => string::decode($get['game']),
                                               "members" => convert::ToString(db('SELECT id FROM '.dba::get('squaduser').' WHERE `squad` = '.convert::ToInt($get['id']),true)),
                                               "icon" => $icon,
                                               "edit" => $edit,
                                               "class" => $class,
                                               "delete" => $delete));
}

$show = show($dir."/squads", array("squads" => $squads));