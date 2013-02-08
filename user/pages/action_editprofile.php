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
else if($chkMe == "unlogged")
    $index = error(_error_have_to_be_logged, 1);
else
{
    ######################
    ## Profil editieren ##
    ######################
    $where = _site_user_editprofil;
    $gallery_do = (isset($_GET['gallery']) ? $_GET['gallery'] : '');

    #####################
    ## Functions START ##
    #####################

    //Generate custom profil fields
    function custom_fields($userid,$kid)
    {
        global $db;
        $qrycustom = db("SELECT * FROM ".$db['profile']." WHERE kid = ".convert::ToInt($kid)." AND shown = '1' ORDER BY id ASC"); $custom = '';
        while($getcustom = _fetch($qrycustom))
        {
            $getcontent = db("SELECT ".$getcustom['feldname']." FROM ".$db['users']." WHERE id = '".$userid."'",false,true);
            $custom .= show(_profil_edit_custom, array("name" => pfields_name($getcustom['name']).":", "feldname" => $getcustom['feldname'], "value" => re($getcontent[$getcustom['feldname']])));
        } //while end
        unset($qrycustom,$getcustom);
        return $custom;
    }

    ###################
    ## Functions END ##
    ###################

    switch($do)
    {
        case 'edit':
            $check_user = 0; $check_nick = 0; $check_email = 0;
            if(db("SELECT user,nick,email FROM ".$db['users']." AS u1 WHERE (u1.user = '".$_POST['user']."' OR u1.nick = '".$_POST['nick']."' OR u1.email = '".$_POST['email']."') AND id != ".$userid,true))
            {
                $check_user = db("SELECT id FROM ".$db['users']." WHERE user = '".$_POST['user']."' AND id != '".$userid."'",true);
                $check_nick = db("SELECT id FROM ".$db['users']." WHERE nick = '".$_POST['nick']."' AND id != '".$userid."'",true);
                $check_email = db("SELECT id  FROM ".$db['users']." WHERE email = '".$_POST['email']."' AND id != '".$userid."'",true);
            }

            if(empty($_POST['user']))
                $index = error(_empty_user, 1);
            else if(empty($_POST['nick']))
                $index = error(_empty_nick, 1);
            else if(empty($_POST['email']))
                $index = error(_empty_email, 1);
            else if(!check_email($_POST['email']))
                $index = error(_error_invalid_email, 1);
            else if($check_user)
                $index = error(_error_user_exists, 1);
            else if($check_nick)
                $index = error(_error_nick_exists, 1);
            else if($check_email)
                $index = error(_error_email_exists, 1);
            else
            {
                if(isset($_POST['pwd']) && !empty($_POST['pwd']))
                {
                    $newpwd = "`pwd` = '".($passwd=pass_hash($_POST['pwd'],($default_pwd_encoder = settings('default_pwd_encoder'))))."', `pwd_encoder` = ".$default_pwd_encoder.",";
                    $_SESSION['pwd'] = $passwd; unset($passwd);
                    $index = info(_info_edit_profile_done, "?action=user&amp;id=".$userid."");
                }
                else
                {
                    $newpwd = "";
                    $index = info(_info_edit_profile_done, "?action=user&amp;id=".$userid."");
                }

                $icq = preg_replace("=-=Uis","",$_POST['icq']);
                $bday = (isset($_POST['t']) && isset($_POST['m']) && isset($_POST['j']) ? cal($_POST['t']).".".cal($_POST['m']).".".$_POST['j'] : '');

                $qrycustom = db("SELECT feldname,type FROM ".$db['profile']);
                $customfields = '';
                if(_rows($qrycustom))
                {
                    while($getcustom = _fetch($qrycustom))
                    {
                        if($getcustom['type'] == 2)
                            $customfields .= " ".$getcustom['feldname']." = '".convert::ToString(links($_POST[$getcustom['feldname']]))."', ";
                        else
                            $customfields .= " ".$getcustom['feldname']." = '".convert::ToString(up($_POST[$getcustom['feldname']]))."', ";
                    } //while end
                }

                db("UPDATE ".$db['users']." SET	".$newpwd." ".$customfields."
                                                 `country`      = '".convert::ToString($_POST['land'])."',
                                                 `user`         = '".convert::ToString(up($_POST['user']))."',
                                                 `nick`         = '".convert::ToString(up($_POST['nick']))."',
                                                 `rlname`       = '".convert::ToString(up($_POST['rlname']))."',
                                                 `sex`          = '".convert::ToInt($_POST['sex'])."',
                                                 `status`       = '".convert::ToInt($_POST['status'])."',
                                                 `bday`         = '".convert::ToString($bday)."',
                                                 `email`        = '".convert::ToString(up($_POST['email']))."',
                                                 `nletter`      = '".convert::ToInt($_POST['nletter'])."',
                                                 `pnmail`       = '".convert::ToInt($_POST['pnmail'])."',
                                                 `city`         = '".convert::ToString(up($_POST['city']))."',
                                                 `gmaps_koord`  = '".convert::ToString(up($_POST['gmaps_koord']))."',
                                                 `hp`           = '".convert::ToString(links($_POST['hp']))."',
                                                 `icq`          = '".convert::ToInt($icq)."',
                                                 `xfire`        = '".convert::ToString(up($_POST['xfire']))."',
                                                 `signatur`     = '".convert::ToString(up($_POST['sig'],1))."',
                                                 `beschreibung` = '".convert::ToString(up($_POST['ich'],1))."'
                                                  WHERE id = ".$userid);
            }
        break;
        case 'delete':
            $getdel = db("SELECT id,nick,email,hp FROM ".$db['users']." WHERE id = '".$userid."'",false,true);

            db("UPDATE ".$db['f_threads']."
                                         SET `t_nick`   = '".$getdel['nick']."',
                                                 `t_email`  = '".$getdel['email']."',
                                                 `t_hp`			= '".$getdel['hp']."',
                                                 `t_reg`		= '0'
                                         WHERE t_reg = '".$getdel['id']."'");

            db("UPDATE ".$db['f_posts']."
                                         SET `nick`   = '".$getdel['nick']."',
                                                 `email`  = '".$getdel['email']."',
                                                 `hp`			= '".$getdel['hp']."',
                                                 `reg`		= '0'
                                         WHERE reg = '".$getdel['id']."'");

            db("UPDATE ".$db['newscomments']."
                                         SET `nick`     = '".$getdel['nick']."',
                                                 `email`    = '".$getdel['email']."',
                                                 `hp`       = '".$getdel['hp']."',
                                                 `reg`			= '0'
                                         WHERE reg = '".$getdel['id']."'");

            db("UPDATE ".$db['acomments']."
                                         SET `nick`     = '".$getdel['nick']."',
                                                 `email`    = '".$getdel['email']."',
                                                 `hp`       = '".$getdel['hp']."',
                                                 `reg`			= '0'
                                         WHERE reg = '".$getdel['id']."'");

            db("DELETE FROM ".$db['msg']." WHERE von = '".$getdel['id']."' OR an = '".$getdel['id']."'");
            db("DELETE FROM ".$db['news']." WHERE autor = '".$getdel['id']."'");
            db("DELETE FROM ".$db['permissions']." WHERE user = '".$getdel['id']."'");
            db("DELETE FROM ".$db['squaduser']." WHERE user = '".$getdel['id']."'");
            db("DELETE FROM ".$db['buddys']." WHERE user = '".$getdel['id']."' OR buddy = '".$getdel['id']."'");
            db("UPDATE ".$db['usergb']." SET `reg` = 0 WHERE reg = ".$getdel['id']."");
            db("DELETE FROM ".$db['userpos']." WHERE user = '".$getdel['id']."'");
            db("DELETE FROM ".$db['userstats']." WHERE user = '".$getdel['id']."'");

            foreach($picformat as $tmpendung)
            {
                if(file_exists(basePath."/inc/images/uploads/userpics/".$getdel['id'].".".$tmpendung))
                    @unlink(basePath."/inc/images/uploads/userpics/".$getdel['id'].".".$tmpendung);

                if(file_exists(basePath."/inc/images/uploads/useravatare/".$getdel['id'].".".$tmpendung))
                    @unlink(basePath."/inc/images/uploads/useravatare/".$getdel['id'].".".$tmpendung);
            }

            $qrygl = db("SELECT pic FROM ".$db['usergallery']." WHERE user = '".$getdel['id']."'");
            if(_rows($qrygl) >= 1)
            {
                while($getgl = _fetch($qrygl))
                {
                    @unlink(basePath."inc/images/uploads/usergallery/".$getdel['id']."_".$getgl['pic']);
                } //while end

                db("DELETE FROM ".$db['usergallery']." WHERE user = '".$getdel['id']."'");
            }

            db("DELETE FROM ".$db['users']." WHERE id = '".$getdel['id']."'");
            $index = info(_info_account_deletet, '../news/');
        break;
        default: ## Profil editieren ##
            if($gallery_do == "delete")
            {
                $qrygl = db("SELECT gid FROM ".$db['usergallery']." WHERE user = '".$userid."' AND id = '".convert::ToInt($_GET['gid'])."'");
                if(_rows($qrygl))
                {
                    while($getgl = _fetch($qrygl))
                    {
                        db("DELETE FROM ".$db['usergallery']." WHERE id = '".convert::ToInt($_GET['gid'])."'");
                    } //while end
                }

                $index = info(_info_edit_gallery_done, "?action=editprofile&show=gallery");
            }
            else
            {
                $qry = db("SELECT * FROM ".$db['users']." WHERE id = '".$userid."'");
                if(!_rows($qry))
                    $index = error(_user_dont_exist, 1);
                else
                {
                    $get = _fetch($qry);
                    $sex = ($get['sex'] == 1 ? _pedit_male : ($get['sex'] == 2 ? _pedit_female : _pedit_sex_ka));
                    $status = ($get['status'] ? _pedit_aktiv : _pedit_inaktiv);

                    ## Clan ##
                    if($get['level'] == 1)
                        $clan = '<input type="hidden" name="status" value="1" />';
                    else
                    {
                        $custom_clan = custom_fields($userid,2);
                        $clan = show($dir."/edit_clan", array("status" => $status, "custom_clan" => $custom_clan));
                    }

                    $bdayday = 0; $bdaymonth = 0; $bdayyear = 0;
                    if(!empty($get['bday']))
                        list($bdayday, $bdaymonth, $bdayyear) = explode('.', $get['bday']);

                    if(isset($_GET['show']) && $_GET['show'] == "gallery")
                    {
                        $qrygl = db("SELECT * FROM ".$db['usergallery']." WHERE user = '".$userid."' ORDER BY id DESC");
                        $color = 1; $gal = '';
                        if(_rows($qrygl) >= 1)
                        {
                            while($getgl = _fetch($qrygl))
                            {
                                $pic = show(_gallery_pic_link, array("img" => $getgl['pic'], "user" => $userid));
                                $delete = show(_gallery_deleteicon, array("id" => $getgl['id']));
                                $edit = show(_gallery_editicon, array("id" => $getgl['id']));
                                $class = ($color % 2) ? "contentMainSecond" : "contentMainFirst"; $color++;
                                $gal .= show($dir."/edit_gallery_show", array("picture" => img_size("inc/images/uploads/usergallery"."/".$userid."_".$getgl['pic']),
                                                                              "beschreibung" => bbcode($getgl['beschreibung']),
                                                                              "class" => $class,
                                                                              "delete" => $delete,
                                                                              "edit" => $edit));
                            } //while end
                        }

                        $gal = empty($gal) ? show(_no_entrys_yet_all, array("colspan" => "3")) : $gal;
                        $show = show($dir."/edit_gallery", array("showgallery" => $gal));
                    }
                    else
                    {
                        $dropdown_age = show(_dropdown_date, array("day" => dropdown("day",$bdayday,1), "month" => dropdown("month",$bdaymonth,1), "year" => dropdown("year",$bdayyear,1)));

                        $icq = (!empty($get['icq']) && $get['icq'] != 0 ? $get['icq'] : '');
                        $gmaps = show('membermap/geocoder', array('form' => 'editprofil'));
                        $pnl = ($get['nletter'] ? 'checked="checked"' : '');
                        $pnm = ($get['pnmail'] ? 'checked="checked"' : '');
                        $pic = userpic($get['id']); $avatar = useravatar($get['id']);
                        $deletepic = (!preg_match("#nopic#",$pic) ? "| "._profil_delete_pic : '');
                        $deleteava = (!preg_match("#noavatar#",$avatar) ? "| "._profil_delete_ava : '');

                        $delete = ($userid == $rootAdmin ? _profil_del_admin : show("page/button_delete_account", array("id" => $get['id'],"action" => "action=editprofile&amp;do=delete", "value" => _button_title_del_account, "del" => convSpace(_confirm_del_account))));
                        $show = show($dir."/edit_profil", array("country" => show_countrys($get['country']),
                                "city" => re($get['city']),
                                "pnl" => $pnl,
                                "pnm" => $pnm,
                                "pwd" => "",
                                "dropdown_age" => $dropdown_age,
                                "ava" => $avatar,
                                "hp" => re($get['hp']),
                                "gmaps" => $gmaps,
                                "nick" => re($get['nick']),
                                "name" => re($get['user']),
                                "gmaps_koord" => re($get['gmaps_koord']),
                                "rlname" => re($get['rlname']),
                                "bdayday" => $bdayday,
                                "bdaymonth" => $bdaymonth,
                                "bdayyear" =>$bdayyear,
                                "sex" => $sex,
                                "email" => re($get['email']),
                                "icqnr" => $icq,
                                "sig" => re_bbcode($get['signatur']),
                                "xfire" => $get['xfire'],
                                "clan" => $clan,
                                "pic" => $pic,
                                "deleteava" => $deleteava,
                                "deletepic" => $deletepic,
                                "position" => getrank($get['id']),
                                "value" => _button_value_edit,
                                "status" => $status,
                                "custom_about" => custom_fields($userid,1),
                                "custom_contact" => custom_fields($userid,3),
                                "custom_favos" => custom_fields($userid,4),
                                "custom_hardware" => custom_fields($userid,5),
                                "ich" => re_bbcode($get['beschreibung']),
                                "delete" => $delete));
                    }

                    $index = show($dir."/edit", array("show" => $show), array("nick" => autor($get['id'])));
                }
            }
        break;
    }
}
?>
