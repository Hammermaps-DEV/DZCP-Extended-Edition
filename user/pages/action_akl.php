<?php
/**
 * <DZCP-Extended Edition>
 * @package: DZCP-Extended Edition
 * @author: DZCP Developer Team || Hammermaps.de Developer Team
 * @link: http://www.dzcp.de || http://www.hammermaps.de
 */

if (!defined('IS_DZCP')) exit();
if (_version < '1.0')
    $index = _version_for_page_outofdate;
else
{
    switch ($do)
    {
        case 'send':
            if(isset($_SESSION['akl_id']) && !empty($_SESSION['akl_id']))
                $qry = db_stmt("SELECT user,id,email,level,actkey FROM `".dba::get('users')."` WHERE `id` = ?",array('i', $_SESSION['akl_id']));
            else
                $qry = db_stmt("SELECT user,id,email,level,actkey FROM `".dba::get('users')."` WHERE `email` = ?",array('s', isset($_GET['email']) ? $_GET['email'] : 'empty'));

            if(_rows($qry) == 1)
            {
                if(isset($_SESSION['akl_id']) && !empty($_SESSION['akl_id'])) $_SESSION['akl_id'] = '';
                $get = _fetch($qry);

                if(!$get['level'] && !empty($get['actkey']))
                {
                    db("UPDATE ".dba::get('userstats')." SET akl=akl+1 WHERE user = ".$get['id']);
                    db("UPDATE `".dba::get('users')."` SET `actkey` = '".($guid=GenGuid())."' WHERE `id` = ".$get['id']);
                    $akl_link = 'http://'.$httphost.'/?index=user&amp;action=akl&do=activate&key='.$guid;
                    $akl_link_page = 'http://'.$httphost.'/?index=user&amp;action=akl&do=activate';
                    mailmgr::AddContent(string::decode(settings('eml_akl_register_subj')), show(string::decode(settings('eml_akl_register')), array("nick" => $get['user'], "link_page" => '<a href="'.$akl_link_page.'" target="_blank">'.$akl_link_page.'</a>', "guid" => $guid, "link" => '<a href="'.$akl_link.'" target="_blank">Link</a>')));
                    mailmgr::AddAddress($get['email']);
                    $index = info(show(_reg_akl_sended,array('email' => string::decode($get['email']))), "?index=user&amp;action=login");
                }
                else if(!$get['level'] && empty($get['actkey']))
                    $index = info(_reg_akl_locked, "?index=news");
                else
                {
                    db("UPDATE `".dba::get('users')."` SET `actkey` = '' WHERE `id` = ".$get['id']);
                    $index = info(_reg_akl_activated, "?index=news");
                }
            }
            else
                $index = info(_reg_akl_email_nf, "?index=news");
        break;

        case 'activate':
            if((isset($_GET['key']) && !empty($_GET['key'])) || (isset($_POST['key']) && !empty($_POST['key'])))
            {
                $qry = db_stmt("SELECT id FROM `".dba::get('users')."` WHERE `actkey` = ?",array('s', strtoupper(trim(isset($_POST['key']) ? $_POST['key'] : $_GET['key']))));
                if(_rows($qry) >= 1)
                {
                    $get = _fetch($qry);
                    db("UPDATE `".dba::get('users')."` SET `level` = 1, `status` = 1, `actkey` = '' WHERE `id` = ".$get['id']);
                    $index = info(_reg_akl_valid, "?index=user&amp;action=login");
                }
                else
                    $index = info(_reg_akl_invalid, "?index=news");
            }
            else
                $index = show($dir."/activate_code", array("value" => _button_value_activate));
        break;

        default:
            $index = show($dir."/activate_code", array("value" => _button_value_activate));
        break;
    }
}