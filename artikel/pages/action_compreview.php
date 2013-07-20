<?php
/**
 * <DZCP-Extended Edition>
 * @package: DZCP-Extended Edition
 * @author: DZCP Developer Team || Hammermaps.de Developer Team
 * @link: http://www.dzcp.de || http://www.hammermaps.de
 */

#############################################
##### Code for 'DZCP - Extended Edition #####
###### DZCP - Extended Edition >= 1.0 #######
#############################################

####################################
## Wird in einer Index ausgeführt ##
####################################
if (!defined('IS_DZCP'))
    exit();

if (_version < '1.0') //Mindest Version pruefen
    $index = _version_for_page_outofdate;
else
{
    header("Content-type: application/x-www-form-urlencoded;charset=utf-8");
    $id = isset($_GET['id']) ? convert::ToInt($_GET['id']) : 0;
    $cid = isset($_GET['cid']) ? convert::ToInt($_GET['cid']) : 0;
    $get_hp = isset($_POST['hp']) ? $_POST['hp'] : '';
    $get_email = isset($_POST['email']) ? $_POST['email'] : '';
    $get_nick = isset($_POST['nick']) ? $_POST['nick'] : '';
    $get_comment = isset($_POST['comment']) ? bbcode::parse_html($_POST['comment']) : '';

    switch (isset($_GET['do']) ? $_GET['do'] : '')
    {
        case 'edit':
            $get = db("SELECT * FROM ".dba::get('acomments')." WHERE id = '".$cid."'",false,true);

            $get_id = '?';
            $get_userid = $get['reg'];
            $get_date = $get['datum'];

            if($get['reg'] == 0)
                $regCheck = false;
            else
            {
                $regCheck = true;
                $pUId = $get['reg'];
            }

            $editedby = show(_edited_by, array("autor" => cleanautor(), "time" => date("d.m.Y H:i", time())._uhr));
        break;
        default:
            $editedby = '';
            $get_id = cnt(dba::get('acomments'), " WHERE artikel = ".$id."")+1;
            $get_userid = userid();
            $get_date = time();

            if(checkme() == 'unlogged')
                $regCheck = false;
            else
            {
                $regCheck = true;
                $pUId = userid();
            }
        break;
    }

    $hp = $regCheck ? '' : (!empty($get_hp) ? show(_hpicon_forum, array("hp" => links($get_hp))) : '');
    $email = $regCheck ? '' : (!empty($get_email) ? '<br />'.show(_emailicon_forum, array("email" => eMailAddr($get_email))) : '');
    $onoff = $regCheck ? onlinecheck($get_userid) : '';
    $nick = $regCheck ? cleanautor($get_userid) : show(_link_mailto, array("nick" => string::decode($get_nick), "email" => $get_email));

    $titel = show(_eintrag_titel, array("postid" => $get_id,
                                        "datum" => date("d.m.Y", $get_date),
                                        "zeit" => date("H:i", $get_date)._uhr,
                                        "edit" => '',
                                        "delete" => ''));

    $index = show("page/comments_show", array("titel" => $titel,
                                              "comment" => $get_comment,
                                              "nick" => $nick,
                                              "editby" => bbcode::parse_html($editedby),
                                              "email" => $email,
                                              "hp" => $hp,
                                              "avatar" => useravatar($get_userid),
                                              "onoff" => $onoff,
                                              "rank" => getrank($get_userid),
                                              "ip" => visitorIp()._ip_only_for_admins));

    update_user_status_preview();
    exit(convert::UTF8('<table class="mainContent" cellspacing="1">'.$index.'</table>'));
}