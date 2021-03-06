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
    header("Content-type: application/x-www-form-urlencoded;charset=utf-8");
    if($do == 'edit')
    {
        $get = db("SELECT reg,datum FROM ".dba::get('usergb')." WHERE id = '".convert::ToInt($_GET['gbid'])."'",false,true);

        $get_id = '?';
        $get_userid = $get['reg'];
        $get_date = $get['datum'];
        $regCheck = (!$get['reg'] ? true : false);
        $editby = show(_edited_by, array("autor" => cleanautor(), "time" => date("d.m.Y H:i", time())._uhr));
    }
    else
    {
        $get_id = cnt(dba::get('usergb'), "WHERE user = ".(isset($_GET['uid']) ? (convert::ToInt($_GET['uid'])+1) : 1));
        $get_userid = userid();
        $get_date = time();
        $regCheck = (checkme() == 'unlogged' ? true : false);
        $editby = '';
    }

    if($regCheck)
    {
        $get_hp = $_POST['hp'];
        $get_email = $_POST['email'];
        $get_nick = show(_link_mailto, array("nick" => string::decode($_POST['nick']), "email" => eMailAddr($get_email)));
    }
    else
    {
        $get_hp = data(userid(),'hp');
        $get_email = data(userid(),'email');
        $get_nick = autor();
    }

    $gbhp = (!empty($get_hp) ? show(_hpicon, array("hp" => links($get_hp))) : '');
    $gbemail = (!empty($get_email) ? show(_emailicon, array("email" => eMailAddr($get_email))) : '');
    $titel = show(_eintrag_titel, array("postid" => $get_id, "datum" => date("d.m.Y", time()), "zeit" => date("H:i", time())._uhr, "edit" => '', "delete" => ''));
    $posted_ip = (checkme() == 4 ? visitorIp() : _logged);

    $index = show("page/comments_show", array("titel" => $titel,
                                              "comment" => bbcode::parse_html($_POST['eintrag']),
                                              "nick" => $get_nick,
                                              "hp" => $gbhp,
                                              "editby" => $editby,
                                              "email" => $gbemail,
                                              "avatar" => useravatar(userid()),
                                              "onoff" => onlinecheck(userid()),
                                              "rank" => getrank(userid()),
                                              "ip" => $posted_ip));

    update_user_status_preview();
    exit(convert::UTF8('<table class="mainContent" cellspacing="1">'.$index.'</table>'));
}