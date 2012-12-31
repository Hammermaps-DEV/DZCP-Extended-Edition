<?php
####################################
## Wird in einer Index ausgeführt ##
####################################
if (!defined('IS_DZCP'))
    exit();

######################
## Userprofil Seite ##
######################

#####################
## Functions START ##
#####################

//Generate custom profil fields
function custom_fields($userid,$kid,$get_array)
{
    global $db;
    $qry = db("SELECT * FROM ".$db['profile']." WHERE kid = '".$kid."' AND shown = '1' ORDER BY id ASC"); $custom = ''; $count=0;
    if(_rows($qry))
    {
        while($getcustom = _fetch($qry))
        {
            $getcontent = db("SELECT ".$getcustom['feldname']." FROM ".$db['users']." WHERE `id` = '".$userid."' LIMIT 1",false,true);
            if(!empty($getcontent[$getcustom['feldname']]))
            {
                switch($getcustom['type'])
                {
                    case 2: $custom .= show(_profil_custom_url, array("name" => re(pfields_name($getcustom['name'])), "value" => re($getcontent[$getcustom['feldname']]))); break;
                    case 3: $custom .= show(_profil_custom_mail, array("name" => re(pfields_name($getcustom['name'])), "value" => eMailAddr(re($getcontent[$getcustom['feldname']])))); break;
                    default: $custom .= show(_profil_custom, array("name" => re(pfields_name($getcustom['name'])), "value" => re($getcontent[$getcustom['feldname']]))); break;
                }

                $count++;
            }
        }
    }

    return $get_array ? array('custom' => $custom, 'count' => $count) : $custom;
}

###################
## Functions END ##
###################

$where = _user_profile_of;
if(!isset($_GET['id']) || empty($_GET['id']) || !exist($_GET['id']))
    $index = error(_user_dont_exist, 1);
else
{
    $view_userID = intval($_GET['id']); //UserID
    $where = _user_profile_of.'autor_'.$view_userID;

    if(count_clicks('userprofil',$view_userID))
        db("UPDATE ".$db['userstats']." SET `profilhits` = profilhits+1 WHERE user = '".$view_userID."'"); //Update Userstats

    $get = db("SELECT * FROM ".$db['users']." WHERE id = '".$view_userID."'",false,true);

    ######### DO ##########

    switch($do)
    {
        case 'delete':
            if($chkMe == 4 || $view_userID == $userid)
            {
                db("DELETE FROM ".$db['usergb']." WHERE user = '".$view_userID."' AND id = '".intval($_GET['gbid'])."'");
                $index = info(_gb_delete_successful, "?action=user&amp;id=".$view_userID."&show=gb");
            }
            else
                $index = error(_error_wrong_permissions, 1);
        break;
        case 'edit':
            $get = db("SELECT * FROM ".$db['usergb']." WHERE id = '".intval($_GET['gbid'])."'",false,true);
            if($get['reg'] == $userid || permission('editusers'))
            {
                if($get['reg'] != 0)
                    $form = show("page/editor_regged", array("nick" => autor($get['reg']), "von" => _autor));
                else
                {
                    $form = show("page/editor_notregged", array(
                            "nickhead" => _nick,
                            "emailhead" => _email,
                            "hphead" => _hp,
                            "postemail" => re($get['email']),
                            "posthp" => re($get['hp']),
                            "postnick" => re($get['nick'])));
                }

                $index = show($dir."/usergb_add", array("nickhead" => _nick,
                        "add_head" => _gb_edit_head,
                        "bbcodehead" => _bbcode,
                        "emailhead" => _email,
                        "preview" => _preview,
                        "whaturl" => "edit&gbid=".$_GET['gbid'],
                        "ed" => "&amp;do=edit&amp;uid=".$_GET['id']."&amp;gbid=".$_GET['gbid'],
                        "security" => _register_confirm,
                        "b1" => $u_b1,
                        "b2" => $u_b2,
                        "what" => _button_value_edit,
                        "reg" => $get['reg'],
                        "hphead" => _hp,
                        "id" => $_GET['id'],
                        "form" => $form,
                        "postemail" => $get['email'],
                        "posthp" => $get['hp'],
                        "postnick" => re($get['nick']),
                        "posteintrag" => re_bbcode($get['nachricht']),
                        "error" => '',
                        "ip" => _iplog_info,
                        "eintraghead" => _eintrag));
            } else {
                $index = error(_error_edit_post,1);
            }
        break;
    }

    ######### SHOW ##########

    if(empty($index))
    {
        switch(isset($_GET['show']) ? $_GET['show'] : false)
        {
            case 'gallery':
                $qrygl = db("SELECT * FROM ".$db['usergallery']." WHERE user = '".$view_userID."' ORDER BY id DESC"); $color = 1; $gal = '';
                if(_rows($qrygl) >= 1)
                {
                    while($getgl = _fetch($qrygl))
                    {
                        $class = ($color % 2) ? "contentMainSecond" : "contentMainFirst"; $color++;
                        $gal .= show($dir."/profil_gallery_show", array("picture" => img_size("inc/images/uploads/usergallery"."/".$_GET['id']."_".$getgl['pic']), "beschreibung" => bbcode($getgl['beschreibung']), "class" => $class));
                    }
                }

                $show = show($dir."/profil_gallery", array("showgallery" => $gal));
            break;

            case 'gb':
                $addgb = show(_usergb_eintragen, array("id" => $view_userID));
                $qrygb = db("SELECT * FROM ".$db['usergb']." WHERE user = ".$view_userID." ORDER BY datum DESC LIMIT ".($page - 1)*$maxusergb.",".$maxusergb."");
                $entrys = cnt($db['usergb'], " WHERE user = ".$view_userID);
                $i = $entrys-($page - 1)*$maxusergb;

                if(_rows($qrygb) >= 1)
                {
                    while($getgb = _fetch($qrygb))
                    {
                        $gbhp = (!empty($getgb['hp']) ? show(_hpicon, array("hp" => $getgb['hp'])) : '');
                        $gbemail = (!empty($getgb['email']) ? show(_emailicon, array("email" => eMailAddr($getgb['email']))) : '');
                        $posted_ip = ($chkMe == 4 ? $get['ip'] : _logged);

                        $edit = ""; $delete = "";
                        if(permission('editusers') || $view_userID == $userid)
                        {
                            $edit = show("page/button_edit_single", array("id" => $get['id'], "action" => "action=user&amp;show=gb&amp;do=edit&amp;gbid=".$getgb['id'], "title" => _button_title_edit));
                            $delete = show("page/button_delete_single", array("id" => $_GET['id'], "action" => "action=user&amp;show=gb&amp;do=delete&amp;gbid=".$getgb['id'], "title" => _button_title_del, "del" => convSpace(_confirm_del_entry)));
                        }

                        if(!$getgb['reg'])
                        {
                            if($getgb['hp']) $hp = show(_hpicon_forum, array("hp" => $getgb['hp']));
                            else $hp = "";
                            if($getgb['email']) $email = '<br />'.show(_emailicon_forum, array("email" => eMailAddr($getgb['email'])));
                            else $email = "";
                            $onoff = "";
                            $avatar = "";
                            $nick = show(_link_mailto, array("nick" => re($getgb['nick']),
                                    "email" => eMailAddr($getgb['email'])));
                        }
                        else
                        {
                            $www = data($getgb['reg'], "hp");
                            $hp = empty($www) ? '' : show(_hpicon_forum, array("hp" => $www));
                            $email = '<br />'.show(_emailicon_forum, array("email" => eMailAddr(data($getgb['reg'], "email"))));
                            $onoff = onlinecheck($getgb['reg']);
                            $nick = autor($getgb['reg']);
                        }

                        $titel = show(_eintrag_titel, array("postid" => $i,
                                "datum" => date("d.m.Y", $getgb['datum']),
                                "zeit" => date("H:i", $getgb['datum'])._uhr,
                                "edit" => $edit,
                                "delete" => $delete));

                        if($chkMe == 4) $posted_ip = $getgb['ip'];
                        else            $posted_ip = _logged;

                        $membergb .= show("page/comments_show", array("titel" => $titel,
                                "comment" => bbcode($getgb['nachricht']),
                                "nick" => $nick,
                                "hp" => $hp,
                                "editby" => bbcode($getgb['editby']),
                                "email" => $email,
                                "avatar" => useravatar($getgb['reg']),
                                "onoff" => $onoff,
                                "rank" => getrank($getgb['reg']),
                                "ip" => $posted_ip));
                        $i--;
                    }
                }

                if(!ipcheck("mgbid(".$_GET['id'].")", $flood_membergb))
                {
                    if(isset($userid))
                    {
                        $form = show("page/editor_regged", array("nick" => autor($userid),
                                "von" => _autor));
                    } else {
                        $form = show("page/editor_notregged", array("nickhead" => _nick,
                                "emailhead" => _email,
                                "hphead" => _hp,
                                "postemail" => ""));
                    }
                    $add = show($dir."/usergb_add", array("titel" => _eintragen_titel,
                            "nickhead" => _nick,
                            "bbcodehead" => _bbcode,
                            "emailhead" => _email,
                            "hphead" => _hp,
                            "form" => $form,
                            "security" => _register_confirm,
                            "preview" => _preview,
                            "ed" => "&amp;uid=".$_GET['id'],
                            "whaturl" => "add",
                            "reg" => "",
                            "b1" => $u_b1,
                            "b2" => $u_b2,
                            "id" => $_GET['id'],
                            "postemail" => $postemail,
                            "add_head" => _gb_add_head,
                            "what" => _button_value_add,
                            "lang" => $language,
                            "ip" => _iplog_info,
                            "posthp" => $posthp,
                            "postnick" => $postnick,
                            "posteintrag" => "",
                            "error" => "",
                            "eintraghead" => _eintrag));
                } else {
                    $add = "";
                }

                $seiten = nav($entrys,$maxusergb,"?action=user&amp;id=".$_GET['id']."&show=gb");

                $show = show($dir."/profil_gb",array("gbhead" => _membergb,
                        "show" => $membergb,
                        "seiten" => $seiten,
                        "entry" => $add));
            break;

            default:
                $sex = ($get['sex'] != 0 ? ($get['sex'] == 2 ? _female : _male) : '-');
                $hp = (!empty($get['hp']) ? '<a href="'.links($get['hp']).'" target="_blank">'.$get['hp'].'</a>' : '-');
                $pn = show(_pn_write, array("id" => $_GET['id'], "nick" => $get['nick']));
                $bday = ($get['bday'] == ".." || $get['bday'] == 0 || empty($get['bday']) ? '-' : $get['bday']);
                $icq = (!empty($get['icq']) ? show(_icqstatus, array("uin" => $get['icq'])) : '-');
                $icqnr = (!empty($get['icq']) ? re($get['icq']) : '');
                $status = ($get['status'] == 1 || ($getl['level'] != 1 && isset($_GET['sq'])) ? _aktiv_icon : _inaktiv_icon);
                $buddyadd = show(_addbuddyicon, array("id" => $_GET['id']));
                $edituser = (permission("editusers") ? str_replace("&amp;id=","",show("page/button_edit_single", array("id" => "", "action" => "action=admin&amp;edit=".$view_userID, "title" => _button_title_edit))) : '');
                $xfire = (!empty($get['xfire']) ? '<div id="infoXfire_'.re($get['xfire']).'"><div style="width:100%;text-align:center"><img src="../inc/images/ajax-loader-mini.gif" alt="" /></div><script language="javascript" type="text/javascript">DZCP.initXfire("'.re($get['xfire']).'");</script></div>' : '-');
                $rlname = (!empty($get['rlname']) ? re($get['rlname']) : '-');

                //Zeige Clan Informationen an
                $clan = "";
                if($get['level'] != 1 || isset($_GET['sq']))
                {
                    $sq = db("SELECT * FROM ".$db['userpos']." WHERE user = '".$view_userID."'");
                    $cnt = cnt($db['userpos'], " WHERE user = '".$get['id']."'"); $i=1;
                    if(_rows($sq) && !isset($_GET['sq']))
                    {
                        while($getsq = _fetch($sq))
                        {
                            $br = ($i == $cnt ? '' : '-');
                            $pos .= " ".getrank($get['id'],$getsq['squad'],1)." ".$br;
                            $i++;
                        }
                    }
                    else if(isset($_GET['sq']))
                        $pos = getrank($get['id'],intval($_GET['sq']),true);
                    else
                        $pos = getrank($get['id']);

                    //Custom profil fields
                    $custom_clan = custom_fields($view_userID,2,false);
                    //Custom profil fields

                    $clan = show($dir."/clan", array("position" => $pos, "status" => $status, "custom_clan" => $custom_clan));
                }

                //Custom profil fields
                $custom_about = custom_fields($view_userID,1,false);
                $custom_contact = custom_fields($view_userID,3,false);

                $custom_favos_data = custom_fields($view_userID,4,true);
                $custom_favos = $custom_favos_data['custom'];
                $favos_head = ($custom_favos_data['count'] != 0 ? show(_profil_head_cont, array("what" => _profil_favos)) : '');

                $custom_hardware_data = custom_fields($view_userID,5,true);
                $custom_hardware = $custom_hardware_data['custom'];
                $hardware_head = ($custom_hardware_data['count'] != 0 ? show(_profil_head_cont, array("what" => _profil_hardware)) : '');

                unset($custom_favos_data,$custom_hardware_data);
                //Custom profil fields

                $show = show($dir."/profil_show",array(
                        "hardware_head" => $hardware_head,
                        "city" => re($get['city']),
                        "logins" => userstats($_GET['id'], "logins"),
                        "hits" => userstats($_GET['id'], "hits"),
                        "msgs" => userstats($_GET['id'], "writtenmsg"),
                        "forenposts" => userstats($_GET['id'], "forumposts"),
                        "votes" => userstats($_GET['id'], "votes"),
                        "regdatum" => date("d.m.Y H:i", $get['regdatum'])._uhr,
                        "lastvisit" => date("d.m.Y H:i", userstats($_GET['id'], "lastvisit"))._uhr,
                        "hp" => $hp,
                        "xfire_name" => re($get['xfire']),
                        "xfire" => $xfire,
                        "buddyadd" => $buddyadd,
                        "nick" => autor($get['id']),
                        "rlname" => $rlname,
                        "bday" => $bday,
                        "age" => getAge($get['bday']),
                        "sex" => $sex,
                        "icq" => $icq,
                        "icqnr" => $icqnr,
                        "pn" => $pn,
                        "edituser" => $edituser,
                        "onoff" => onlinecheck($get['id']),
                        "clan" => $clan,
                        "picture" => userpic($get['id']),
                        "favos_head" => $favos_head,
                        "position" => getrank($get['id']),
                        "status" => $status,
                        "ich" => bbcode($get['beschreibung']),
                        "custom_about" => $custom_about,
                        "custom_contact" => $custom_contact,
                        "custom_favos" => $custom_favos,
                        "custom_hardware" => $custom_hardware));
            break;
        }

        $navi_profil = show(_profil_navi_profil, array("id" => $_GET['id']));
        $navi_gb = show(_profil_navi_gb, array("id" => $_GET['id']));
        $navi_gallery = show(_profil_navi_gallery, array("id" => $_GET['id']));
        $profil_head = show(_profil_head, array("profilhits" => userstats($_GET['id'],"profilhits")));
        $index = show($dir."/profil", array("profilhead" => $profil_head, "show" => $show, "nick" => autor($_GET['id']), "profil" => $navi_profil, "gb" => $navi_gb, "gallery" => $navi_gallery));
    }
}
?>

