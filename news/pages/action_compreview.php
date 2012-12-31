<?php
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
    header("Content-type: text/html; charset=utf-8");
    $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
    $cid = isset($_GET['cid']) ? intval($_GET['cid']) : 0;
    $get_hp = isset($_POST['hp']) ? $_POST['hp'] : '';
    $get_email = isset($_POST['email']) ? $_POST['email'] : '';
    $get_nick = isset($_POST['nick']) ? $_POST['nick'] : '';
    $get_comment = isset($_POST['comment']) ? bbcode($_POST['comment'],1) : '';

    switch (isset($_GET['do']) ? $_GET['do'] : '')
    {
        case 'edit':
            $get = db("SELECT * FROM ".$db['newscomments']." WHERE id = '".$cid."'",false,true);

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

            $editedby = show(_edited_by, array("autor" => cleanautor($userid), "time" => date("d.m.Y H:i", time())._uhr));
        break;
        default:
            $editedby = '';
            $get_id = cnt($db['newscomments'], " WHERE news = ".$id."")+1;
            $get_userid = $userid;
            $get_date = time();

            if($chkMe == 'unlogged')
                $regCheck = false;
            else
            {
                $regCheck = true;
                $pUId = $userid;
            }
        break;
    }

    $hp = $regCheck ? '' : (!empty($get_hp) ? show(_hpicon_forum, array("hp" => links($get_hp))) : '');
    $email = $regCheck ? '' : (!empty($get_email) ? '<br />'.show(_emailicon_forum, array("email" => eMailAddr($get_email))) : '');
    $onoff = $regCheck ? onlinecheck($get_userid) : '';
    $nick = $regCheck ? cleanautor($get_userid) : show(_link_mailto, array("nick" => re($get_nick), "email" => $get_email));

    $titel = show(_eintrag_titel, array("postid" => $get_id,
                                        "datum" => date("d.m.Y", $get_date),
                                        "zeit" => date("H:i", $get_date)._uhr,
                                        "edit" => '',
                                        "delete" => ''));

    $index = show("page/comments_show", array("titel" => $titel,
                                              "comment" => $get_comment,
                                              "nick" => $nick,
                                                "editby" => bbcode($editedby,1),
                                                "email" => $email,
                                                "hp" => $hp,
                                                "avatar" => useravatar($get_userid),
                                                "onoff" => $onoff,
                                                "rank" => getrank($get_userid),
                                                "ip" => visitorIp()._ip_only_for_admins));

    echo '<table class="mainContent" cellspacing="1">'.$index.'</table>';
    exit();
}
?>
