<?php
/**
 * <DZCP-Extended Edition>
 * @package: DZCP-Extended Edition
 * @author: DZCP Developer Team || Hammermaps.de Developer Team
 * @link: http://www.dzcp.de || http://www.hammermaps.de
 */

####################################
## Wird in einer Index ausgef�hrt ##
####################################
if (!defined('IS_DZCP'))
    exit();

##############
## Vorschau ##
##############
if (_version < '1.0') //Mindest Version pruefen
    $index = _version_for_page_outofdate;
else
{
    header("Content-type: text/html; charset=utf-8");
    if($do == 'edit')
    {
        $get = db("SELECT reg,datum FROM ".$db['usergb']." WHERE id = '".convert::ToInt($_GET['gbid'])."'",false,true);

        $get_id = '?';
        $get_userid = $get['reg'];
        $get_date = $get['datum'];
        $regCheck = (!$get['reg'] ? true : false);
        $editby = show(_edited_by, array("autor" => cleanautor($userid), "time" => date("d.m.Y H:i", time())._uhr));
    }
    else
    {
        $get_id = cnt($db['usergb'], "WHERE user = ".(isset($_GET['uid']) ? (convert::ToInt($_GET['uid'])+1) : 1));
        $get_userid = $userid;
        $get_date = time();
        $regCheck = ($chkMe == 'unlogged' ? true : false);
        $editby = '';
    }

    if($regCheck)
    {
        $get_hp = $_POST['hp'];
        $get_email = $_POST['email'];
        $get_nick = show(_link_mailto, array("nick" => re($_POST['nick']), "email" => eMailAddr($get_email)));
    }
    else
    {
        $get_hp = data($userid,'hp');
        $get_email = data($userid,'email');
        $get_nick = autor($userid);
    }

    $gbhp = (!empty($get_hp) ? show(_hpicon, array("hp" => links($get_hp))) : '');
    $gbemail = (!empty($get_email) ? show(_emailicon, array("email" => eMailAddr($get_email))) : '');
    $titel = show(_eintrag_titel, array("postid" => $get_id, "datum" => date("d.m.Y", time()), "zeit" => date("H:i", time())._uhr, "edit" => '', "delete" => ''));
    $posted_ip = ($chkMe == 4 ? visitorIp() : _logged);

    $index = show("page/comments_show", array("titel" => $titel,
                                              "comment" => bbcode($_POST['eintrag']),
                                              "nick" => $get_nick,
                                              "hp" => $gbhp,
                                              "editby" => $editby,
                                              "email" => $gbemail,
                                              "avatar" => useravatar($userid),
                                              "onoff" => onlinecheck($userid),
                                              "rank" => getrank($userid),
                                              "ip" => $posted_ip));

    update_user_status_preview();
    exit('<table class="mainContent" cellspacing="1">'.$index.'</table>');
}
?>