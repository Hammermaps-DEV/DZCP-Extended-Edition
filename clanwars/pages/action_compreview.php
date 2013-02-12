<?php
/**
 * <DZCP-Extended Edition>
 * @package: DZCP-Extended Edition
 * @author: DZCP Developer Team || Hammermaps.de Developer Team
 * @link: http://www.dzcp.de || http://www.hammermaps.de
 */

####################################
## Wird in einer Index ausgeführt ##
####################################
if (!defined('IS_DZCP'))
    exit();

if (_version < '1.0') //Mindest Version pruefen
    $index = _version_for_page_outofdate;
else
{
    header("Content-type: text/html; charset=utf-8");
    if($_GET['do'] == 'edit')
    {
        $qry = db("SELECT * FROM ".$db['cw_comments']."
               WHERE id = '".convert::ToInt($_GET['cid'])."'");
        $get = _fetch($qry);

        $get_id = '?';
        $get_userid = $get['reg'];
        $get_date = $get['datum'];

        if($get['reg'] == 0) $regCheck = false;
        else {
            $regCheck = true;
            $pUId = $get['reg'];
        }

        $editedby = show(_edited_by, array("autor" => cleanautor(convert::ToInt($userid)),
                "time" => date("d.m.Y H:i", time())._uhr));
    } else {

        $get_id = cnt($db['cw_comments'], " WHERE cw = ".convert::ToInt($_GET['id'])."")+1;
        $get_userid = convert::ToInt($userid);
        $get_date = time();

        if($chkMe == 'unlogged') $regCheck = false;
        else {
            $regCheck = true;
            $pUId = convert::ToInt($userid);
        }
    }

    $get_hp = $_POST['hp'];
    $get_email = $_POST['email'];
    $get_nick = $_POST['nick'];

    if(!$regCheck)
    {
        if($get_hp) $hp = show(_hpicon_forum, array("hp" => links($get_hp)));
        if($get_email) $email = '<br />'.show(_emailicon_forum, array("email" => eMailAddr($get_email)));
        $onoff = "";
        $avatar = "";
        $nick = show(_link_mailto, array("nick" => re($get_nick),
                "email" => $get_email));
    } else {
        $hp = "";
        $email = "";
        $onoff = onlinecheck($get_userid);
        $nick = cleanautor($get_userid);
    }

    $titel = show(_eintrag_titel, array("postid" => $get_id,
            "datum" => date("d.m.Y", $get_date),
            "zeit" => date("H:i", $get_date)._uhr,
            "edit" => $edit,
            "delete" => $delete));

    $index = show("page/comments_show", array("titel" => $titel,
            "comment" => bbcode($_POST['comment'],1),
            "nick" => $nick,
            "editby" => bbcode($editedby,1),
            "email" => $email,
            "hp" => $hp,
            "avatar" => useravatar($get_userid),
            "onoff" => $onoff,
            "rank" => getrank($get_userid),
            "ip" => visitorIp()._only_for_admins));

    update_user_status_preview();
    exit('<table class="mainContent" cellspacing="1">'.$index.'</table>');
}
?>