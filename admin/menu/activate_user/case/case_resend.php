<?php
/**
 * <DZCP-Extended Edition>
 * @package: DZCP-Extended Edition
 * @author: DZCP Developer Team || Hammermaps.de Developer Team
 * @link: http://www.dzcp.de || http://www.hammermaps.de
 */

if(_adminMenu != 'true') exit();

if(($id = isset($_GET['id']) ? $_GET['id'] : false) != false)
{
    $get = db_stmt("SELECT user,id,email FROM `".dba::get('users')."` WHERE `id` = ?",array('i', $id),false,true);
    db("UPDATE ".dba::get('userstats')." SET akl=akl+1 WHERE user = ".$get['id']);
    db("UPDATE `".dba::get('users')."` SET `actkey` = '".($guid=GenGuid())."' WHERE `id` = ".$get['id']);
    $akl_link = 'http://'.$httphost.'/user/?action=akl&do=activate&key='.$guid;
    $akl_link_page = 'http://'.$httphost.'/user/?action=akl&do=activate';
    mailmgr::AddContent(string::decode(settings('eml_akl_register_subj')),show(string::decode(settings('eml_akl_register')), array("nick" => $get['user'], "link_page" => '<a href="'.$akl_link_page.'" target="_blank">'.$akl_link_page.'</a>', "guid" => $guid, "link" => '<a href="'.$akl_link.'" target="_blank">Link</a>')));
    mailmgr::AddAddress($get['email']);
    $show = info(show(_admin_akl_resend,array('email' => $get['email'])), "?index=admin&amp;admin=activate_user", 4);
}
