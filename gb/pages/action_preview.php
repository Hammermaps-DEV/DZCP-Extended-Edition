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
    if(isset($_GET['edit']) && !empty($_GET['edit']))
    {
        $qry = db("SELECT * FROM ".$db['gb']."
               WHERE id = '".intval($_GET['edit'])."'");
        $get = _fetch($qry);

        $get_id = '?';
        $get_userid = $get['reg'];
        $get_date = $get['datum'];

        if($get['reg'] == 0) $regCheck = true;
        $editby = show(_edited_by, array("autor" => cleanautor($userid),
                "time" => date("d.m.Y H:i", time())._uhr));
    } else {
        $get_id = cnt($db['gb'])+1;
        $get_userid = $userid;
        $get_date = time();

        if($chkMe == 'unlogged') $regCheck = true;
    }

    $get_hp = $_POST['hp'];
    $get_email = $_POST['email'];
    $get_nick = $_POST['nick'];

    if($get_hp) $gbhp = show(_hpicon, array("hp" => links($get_hp)));
    else $gbhp = "";

    if($get_email) $gbemail = show(_emailicon, array("email" => eMailAddr($get_email)));
    else $gbemail = "";

    if($regCheck)
    {
        $gbtitel = show(_gb_titel_noreg, array("postid" => $get_id,
                "nick" => re($get_nick),
                "edit" => "",
                "delete" => "",
                "comment" => "",
                "public" => "",
                "uhr" => _uhr,
                "email" => $gb_email,
                "datum" => date("d.m.Y",$get_date),
                "zeit" => date("H:i",$get_date),
                "hp" => $gbhp));
    } else {
        $gbtitel = show(_gb_titel, array("postid" => $get_id,
                "nick" => autor($get_userid),
                "edit" => "",
                "uhr" => _uhr,
                "delete" => "",
                "comment" => "",
                "public" => "",
                "id" => $get_userid,
                "email" => $gb_email,
                "datum" => date("d.m.Y",$get_date),
                "zeit" => date("H:i",$get_date),
                "hp" => $gbhp));
    }

    $index = show($dir."/gb_show", array("gbtitel" => $gbtitel,
            "nachricht" => bbcode($_POST['eintrag'],1),
            "editby" => bbcode($editby,1),
            "ip" => visitorIp()._only_for_admins));

    update_user_status_preview();
    exit('<table class="mainContent" cellspacing="1">'.$index.'</table>');
}
?>