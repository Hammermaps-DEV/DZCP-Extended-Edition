<?php
/**
 * <DZCP-Extended Edition>
 * @package: DZCP-Extended Edition
 * @author: DZCP Developer Team || Hammermaps.de Developer Team
 * @link: http://www.dzcp.de || http://www.hammermaps.de
 */

if(_adminMenu != 'true') exit();

$get = db("SELECT * FROM `".dba::get('startpage')."` WHERE id = '".convert::ToInt($_GET['id'])."'",false,true); $error = '';
if(isset($_POST['name']) && isset($_POST['url']) && isset($_POST['level']))
{
    if(empty($_POST['name']))
        $error = _admin_startpage_no_name;
    else if(empty($_POST['url']))
        $error = _admin_startpage_no_url;
    else
    {
        db("UPDATE `".dba::get('startpage')."` SET `name` = '".string::encode($_POST['name'])."', `url` = '".string::encode($_POST['url'])."', `level` = '".convert::ToInt($_POST['level'])."' WHERE id = '".convert::ToInt($_GET['id'])."'");
        $show = info(_admin_startpage_editd, "?admin=startpage");
    }
}

if(empty($show))
    $show = show($dir."/startpage_form", array("head" => _admin_startpage_edit,
                                                "do" => "edit&amp;id=".$_GET['id'],
                                                "name" => (isset($_POST['name']) && !empty($_POST['name']) ? $_POST['name'] : string::decode($get['name'])),
                                                "url" => (isset($_POST['url']) ? $_POST['url'] : string::decode($get['url'])),
                                                "level" => get_level_dropdown_menu((isset($_POST['level']) && !empty($_POST['level']) ? $_POST['level'] : $get['level']),0,true,'banned'),
                                                "what" => _button_value_edit,
                                                "error" => (!empty($error) ? show("errors/errortable", array("error" => $error)) : "")));