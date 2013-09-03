<?php
/**
 * <DZCP-Extended Edition>
 * @package: DZCP-Extended Edition
 * @author: DZCP Developer Team || Hammermaps.de Developer Team
 * @link: http://www.dzcp.de || http://www.hammermaps.de
 */

if(_adminMenu != 'true') exit();

$qry = db("SELECT * FROM ".dba::get('users')." WHERE level = 0 AND actkey IS NOT NULL ORDER BY nick LIMIT 25"); $activate = ''; $color = 1;
while($get = _fetch($qry))
{
    $resend = show(_emailicon_non_mailto, array("email" => '?admin=activate_user&do=resend&id='.$get['id']));
    $class = ($color % 2) ? "contentMainSecond" : "contentMainFirst"; $color++;
    $edit = $edit = str_replace("&amp;id=","",show("page/button_edit_akl", array("id" => $get['id'], "action" => "../user/?action=admin&amp;edit=", "title" => _button_title_edit)));
    $akl = show("page/button_akl", array("id" => $get['id'], "action" => "admin=activate_user&amp;do=activate&amp;id=", "title" => _button_title_akl));
    $delete = show("page/button_delete", array("id" => $get['id'], "action" => "admin=activate_user&amp;do=delete", "title" => _button_title_del));
    $activate .= show($dir."/activate_user_show", array("nick" => autor($get['id'],'', 0, '',25),
                                                        "akt" => $akl,
                                                        "resend" => $resend,
                                                        "age" => getAge($get['bday']),
                                                        "sended" => userstats($get['id'], 'akl'),
                                                        "edit" => $edit,
                                                        "delete" => $delete,
                                                        "class" => $class,
                                                        "id" => $get['id'],
                                                        "onoff" => onlinecheck($get['id'])));
}

if(empty($activate))
    $activate = '<tr><td colspan="9" class="contentMainSecond">'._no_entrys.'</td></tr>';

$show = show($dir."/activate_user", array("value" => _button_value_search, "show" => $activate));