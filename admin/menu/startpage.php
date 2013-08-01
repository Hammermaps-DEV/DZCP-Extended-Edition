<?php
/**
 * <DZCP-Extended Edition>
 * @package: DZCP-Extended Edition
 * @author: DZCP Developer Team || Hammermaps.de Developer Team
 * @link: http://www.dzcp.de || http://www.hammermaps.de
 */

#####################
## Admin Menu-File ##
#####################
if(_adminMenu != 'true')
    exit();

$where = $where.': '._admin_dlkat;
switch ($do)
{
    case 'edit':
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
    break;

    case 'delete':
        db("DELETE FROM ".dba::get('startpage')." WHERE id = '".convert::ToInt($_GET['id'])."'");
        $show = info(_admin_startpage_deleted, "?admin=startpage");
    break;

    case 'new':
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
                $show = info(_admin_startpage_added, "?admin=startpage");
            }
        }

        if(empty($show))
            $show = show($dir."/startpage_form", array("head" => _admin_startpage_add_head, "do" => "new", "name" => (isset($_POST['name']) ? $_POST['name'] : ''),
            "url" => (isset($_POST['url']) ? $_POST['url'] : ''), "level" => get_level_dropdown_menu(1,0,true,'banned'), "what" => _button_value_add, "error" => (!empty($error) ? show("errors/errortable", array("error" => $error)) : "")));
    break;

    default:
        $sql = db("SELECT * FROM `".dba::get('startpage')."`;"); $color = 0; $show = '';
        while($get = _fetch($sql))
        {
            $edit = show("page/button_edit_single", array("id" => $get['id'], "action" => "admin=startpage&amp;do=edit", "title" => _button_title_edit));
            $delete = show("page/button_delete_single", array("id" => $get['id'], "action" => "admin=startpage&amp;do=delete", "title" => _button_title_del, "del" => _confirm_del_entry));
            $class = ($color % 2) ? "contentMainSecond" : "contentMainFirst"; $color++;
            $show .= show($dir."/startpage_show", array("edit" => $edit, "name" => string::decode($get['name']), "url" => string::decode($get['url']), "class" => $class, "delete" => $delete));
        }

        $show = show($dir."/startpage", array("show" => $show, "add" => _dl_new_head, "edit" => _editicon_blank, "delete" => _deleteicon_blank));
    break;
}