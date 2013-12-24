<?php
/**
 * <DZCP-Extended Edition>
 * @package: DZCP-Extended Edition
 * @author: DZCP Developer Team || Hammermaps.de Developer Team
 * @link: http://www.dzcp.de || http://www.hammermaps.de
 */

if(_adminMenu != 'true') exit();

$error = '';
if(isset($_POST['name']) && isset($_POST['url']) && isset($_POST['level']))
{
    if(empty($_POST['name']))
        $error = _admin_startpage_no_name;
    else if(empty($_POST['url']))
        $error = _admin_startpage_no_url;
    else
    {
        db("INSERT INTO `".dba::get('startpage')."` SET `name` = '".string::encode($_POST['name'])."', `url` = '".string::encode($_POST['url'])."', `level` = '".convert::ToInt($_POST['level'])."'");
        $show = info(_admin_startpage_added, "?index=admin&amp;admin=startpage");
    }
}

if(empty($show))
    $show = show($dir."/startpage_form", array("head" => _admin_startpage_add_head, "do" => "new", "name" => (isset($_POST['name']) ? $_POST['name'] : ''),
            "url" => (isset($_POST['url']) ? $_POST['url'] : ''), "level" => get_level_dropdown_menu(1,0,true,'banned'), "what" => _button_value_add, "error" => (!empty($error) ? show("errors/errortable", array("error" => $error)) : "")));