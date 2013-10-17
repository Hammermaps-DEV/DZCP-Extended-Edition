<?php
/**
 * <DZCP-Extended Edition>
 * @package: DZCP-Extended Edition
 * @author: DZCP Developer Team || Hammermaps.de Developer Team
 * @link: http://www.dzcp.de || http://www.hammermaps.de
 */

if(_adminMenu != 'true') exit();

$color = 0; $show_servers = '';
$qry = db("SELECT id,ip,port,pwd,name,game,navi,custom_icon FROM ".dba::get('server')." ORDER BY id");
while($get = _fetch($qry))
{
    $gameicon = show(_gameicon, array("icon" => 'unknown.gif'));
    if(!empty($get['custom_icon']))
    {
        if(file_exists(basePath.'/inc/images/gameicons/custom/'.$get['custom_icon']))
            $gameicon = show(_gameicon, array('icon' => $get['custom_icon']));
    }
    else
    {
        foreach($picformat AS $end)
        {
            if(file_exists(basePath.'/inc/images/gameicons/'.$get['game'].'.'.$end))
            {
                $gameicon = show(_gameicon, array('icon' => $get['game'].'.'.$end));
                break;
            }
        }
    }

    $edit = show("page/button_edit_single", array("id" => $get['id'], "action" => "admin=server&amp;do=edit", "title" => _button_title_edit));
    $delete = show("page/button_delete_single", array("id" => $get['id'], "action" => "admin=server&amp;do=delete", "title" => _button_title_del, "del" => _confirm_del_server));
    $class = ($color % 2) ? "contentMainSecond" : "contentMainFirst"; $color++;
    $menu = ($get['navi'] ? show(_server_menu_icon_no, array("id" => $get['id'])) : show(_server_menu_icon_yes, array("id" => $get['id'])));

    $show_servers .= show($dir."/server_show", array("gameicon" => $gameicon,
                                                     "serverip" => string::decode($get['ip']).":".$get['port'],
                                                     "serverpwd" => string::decode($get['pwd']),
                                                     "menu" => $menu,
                                                     "edit" => $edit,
                                                     "name" => string::decode($get['name']),
                                                     "class" => $class,
                                                     "delete" => $delete));
}

if(empty($show_servers))
    $show_servers = show(_no_entrys_yet, array("colspan" => "4"));

$show = show($dir."/server", array("show" => $show_servers));