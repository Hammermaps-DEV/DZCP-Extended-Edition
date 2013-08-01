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
    $regCheck = false;
    header("Content-type: application/x-www-form-urlencoded;charset=utf-8");
    if(isset($_GET['view']) ? ($_GET['view'] == 'comment' ? true : false) : false)
    {
        if(isset($_GET['edit']) && !empty($_GET['edit']))
        {
            $get = db("SELECT reg,datum FROM ".dba::get('gb_comments')." WHERE id = '".convert::ToInt($_GET['edit'])."'",false,true);
            $get_id = (isset($_GET['postid']) ? $_GET['postid'] : '?');
            $get_userid = $get['reg']; $get_date = $get['datum'];

            if(!$get['reg'])
                $regCheck = true;

            $editby = show(_edited_by, array("autor" => autor(), "time" => date("d.m.Y H:i", time())._uhr));
        }
        else
        {
            $get_id = cnt(dba::get('gb'))+1;
            $get_userid = userid();
            $get_date = time();

            if(checkme() == 'unlogged')
                $regCheck = true;
        }

        if($regCheck)
        {
            $get_hp = $_POST['hp'];
            $get_email = $_POST['email'];
            $get_nick = show(_link_mailto, array("nick" => string::decode($_POST['nick']), "email" => eMailAddr($get_email)));
        }
        else
            $get_nick = autor();
    }
    else
    {
        if(isset($_GET['edit']) && !empty($_GET['edit']))
        {
            $get = db("SELECT reg,datum FROM ".dba::get('gb')." WHERE id = '".convert::ToInt($_GET['edit'])."'",false,true);
            $get_id = (isset($_GET['id']) ? $_GET['id'] : '?');
            $get_userid = $get['reg']; $get_date = $get['datum'];

            if(!$get['reg'])
                $regCheck = true;

            $editby = show(_edited_by, array("autor" => autor(), "time" => date("d.m.Y H:i", time())._uhr));
        }
        else
        {
            $get_id = cnt(dba::get('gb'))+1;
            $get_userid = userid();
            $get_date = time();

            if(checkme() == 'unlogged')
                $regCheck = true;
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
        $posted_ip = (checkme() == 4 ? visitorIp() : _logged);

        if($regCheck)
        {
            $gbtitel = show(_gb_titel_noreg, array("postid" => $get_id,
                    "nick" => $get_nick,
                    "edit" => "",
                    "delete" => "",
                    "comment" => "",
                    "public" => "",
                    "email" => $gbemail,
                    "datum" => date("d.m.Y",$get_date),
                    "zeit" => date("H:i",$get_date),
                    "hp" => $gbhp));
        }
        else
        {
            $gbtitel = show(_gb_titel, array("postid" => $get_id,
                    "nick" => autor($get_userid),
                    "edit" => "",
                    "delete" => "",
                    "comment" => "",
                    "public" => "",
                    "id" => $get_userid,
                    "email" => $gbemail,
                    "datum" => date("d.m.Y",$get_date),
                    "zeit" => date("H:i",$get_date),
                    "hp" => $gbhp));
        }
    }


    if(isset($_GET['view']) ? ($_GET['view'] == 'comment' ? true : false) : false)
        $index = str_replace("<br /><br />", "", show($dir."/commentlayout", array("nick" => $get_nick, "datum" => date("d.m.Y H:i", $get_date), "comment" => bbcode::parse_html($_POST['eintrag']), "editby" => bbcode::parse_html($editby), "edit" => '', "delete" => '')));
    else
        $index = show($dir."/gb_show", array("gbtitel" => $gbtitel, "nachricht" => bbcode::parse_html($_POST['eintrag']), "editby" => bbcode::parse_html($editby), "ip" => $posted_ip, "comments" => ''));

    update_user_status_preview();
    exit(convert::UTF8('<table class="mainContent" cellspacing="1">'.$index.'</table>'));
}