<?php
/**
 * <DZCP-Extended Edition>
 * @package: DZCP-Extended Edition
 * @author: DZCP Developer Team || Hammermaps.de Developer Team
 * @link: http://www.dzcp.de || http://www.hammermaps.de
 */

/* Admin Menu-File */
if(_adminMenu != 'true')
    exit();

$where = $where.': '._config_activate_user;

switch ($do)
{
    case 'activate':
        db_stmt("UPDATE `".dba::get('users')."` SET `level` = 1, `status` = 1, `actkey` = '' WHERE `id` = ?",array('i', $_GET['id']));
        $show = info(_actived, "?admin=activate_user", 2);
    break;
    case 'delete':
        if(($id = isset($_GET['id']) ? $_GET['id'] : false) != false)
        {
            db("DELETE FROM ".dba::get('users')." WHERE id = ".convert::ToInt($id));
            db("DELETE FROM ".dba::get('permissions')." WHERE user = ".convert::ToInt($id));
            db("DELETE FROM ".dba::get('userstats')." WHERE user = ".convert::ToInt($id));
            db("DELETE FROM ".dba::get('rss')." WHERE userid = ".convert::ToInt($id));
            $show = info(_user_deleted, "?admin=activate_user", 4);
        }
    break;
    case 'resend':
        if(($id = isset($_GET['id']) ? $_GET['id'] : false) != false)
        {
            $get = db_stmt("SELECT user,id,email FROM `".dba::get('users')."` WHERE `id` = ?",array('i', $id),false,true);
            db("UPDATE ".dba::get('userstats')." SET akl=akl+1 WHERE user = ".$get['id']);
            db("UPDATE `".dba::get('users')."` SET `actkey` = '".($guid=GenGuid())."' WHERE `id` = ".$get['id']);
            $akl_link = 'http://'.$httphost.'/user/?action=akl&do=activate&key='.$guid;
            $akl_link_page = 'http://'.$httphost.'/user/?action=akl&do=activate';
            sendMail($get['email'],re(settings('eml_akl_register_subj')),show(settings('eml_akl_register'), array("nick" => $get['user'], "link_page" => '<a href="'.$akl_link_page.'" target="_blank">'.$akl_link_page.'</a>', "guid" => $guid, "link" => '<a href="'.$akl_link.'" target="_blank">Link</a>')));
            $show = info(show(_admin_akl_resend,array('email' => $get['email'])), "?admin=activate_user", 4);
        }
        break;
    default:
        $qry = db("SELECT * FROM ".dba::get('users')." WHERE level = 0 AND actkey IS NOT NULL ORDER BY nick LIMIT 25"); $activate = ''; $color = 1;
        while($get = _fetch($qry))
        {
            $resend = show(_emailicon, array("email" => '?admin=activate_user&do=resend&id='.$get['id']));
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
                                                                "onoff" => onlinecheck($get['id'])));
        }

        if(empty($activate))
            $activate = '<tr><td colspan="9" class="contentMainFirst">'._no_entrys.'</td></tr>';

        $show = show($dir."/activate_user", array("value" => _button_value_search, "show" => $activate));
    break;
}