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
        case 'add':
            if(_rows(db("SELECT `id` FROM ".dba::get('cw')." WHERE `id` = '".convert::ToInt($_GET['id'])."'")) != 0)
            {
                if(settings("reg_cwcomments") == "1" && checkme() == "unlogged")
                {
                    $index = error(_error_have_to_be_logged);
                } else {
                    if(!ipcheck("cwid(".$_GET['id'].")", config('f_cwcom')))
                    {
                        if(userid() != 0)
                            $toCheck = empty($_POST['comment']);
                        else
                            $toCheck = empty($_POST['nick']) || empty($_POST['email']) || empty($_POST['comment']) || !check_email($_POST['email']) || $_POST['secure'] != $_SESSION['sec_'.$dir] || empty($_SESSION['sec_'.$dir]);

                        if($toCheck)
                        {
                            if(userid() != 0)
                            {
                                if(empty($_POST['comment'])) $error = _empty_eintrag;
                                $form = show("page/editor_regged", array("nick" => autor()));
                            } else {
                                if(($_POST['secure'] != $_SESSION['sec_'.$dir]) || empty($_SESSION['sec_'.$dir])) $error = _error_invalid_regcode;
                                elseif(empty($_POST['nick'])) $error = _empty_nick;
                                elseif(empty($_POST['email'])) $error = _empty_email;
                                elseif(!check_email($_POST['email'])) $error = _error_invalid_email;
                                else if(check_email_trash_mail($_POST['email'])) $error = _error_trash_mail;
                                elseif(empty($_POST['comment'])) $error = _empty_eintrag;
                                $form = show("page/editor_notregged", array("postemail" => "", "posthp" => "", "postnick" => ""));
                            }

                            $error = show("errors/errortable", array("error" => $error));
                            $index = show("page/comments_add", array("titel" => _cw_comments_add,
                                    "nickhead" => _nick,
                                    "emailhead" => _email,
                                    "hphead" => _hp,
                                    "ip" => _iplog_info,
                                    "security" => _register_confirm,
                                    "what" => _button_value_add,
                                    "sec" => $dir,
                                    "form" => $form,
                                    "preview" => _preview,
                                    "action" => '?action=details&amp;do=add&amp;id='.$_GET['id'],
                                    "prevurl" => '../clanwars/?action=compreview&id='.$_GET['id'],
                                    "id" => $_GET['id'],
                                    "show" => "",
                                    "postemail" => $_POST['email'],
                                    "posthp" => links($_POST['hp']),
                                    "postnick" => string::decode($_POST['nick']),
                                    "posteintrag" => string::decode($_POST['comment']),
                                    "error" => $error,
                                    "eintraghead" => _eintrag));
                        } else {
                            $qry = db("INSERT INTO ".dba::get('cw_comments')."
                                             SET `cw`       = '".convert::ToInt($_GET['id'])."',
                                                     `datum`    = '".time()."',
                                                     `nick`     = '".string::encode($_POST['nick'])."',
                                                     `email`    = '".string::encode($_POST['email'])."',
                                                     `hp`       = '".links($_POST['hp'])."',
                                                     `reg`      = '".userid()."',
                                                     `comment`  = '".string::encode($_POST['comment'])."',
                                                     `ip`       = '".visitorIp()."'");


                            wire_ipcheck("cwid(".$_GET['id'].")");

                            $index = info(_comment_added, "?action=details&amp;id=".$_GET['id']."");
                        }
                    } else {
                        $index = error(show(_error_flood_post, array("sek" => config('f_cwcom'))));
                    }
                }
            } else{
                $index = error(_id_dont_exist);
            }
        break;

        case 'delete':
            $qry = db("SELECT reg FROM ".dba::get('cw_comments')."
               WHERE id = '".convert::ToInt($_GET['cid'])."'");
            $get = _fetch($qry);

            if($get['reg'] == userid() || permission('clanwars'))
            {
                $qry = db("DELETE FROM ".dba::get('cw_comments')."
                 WHERE id = '".convert::ToInt($_GET['cid'])."'");

                $index = info(_comment_deleted, "?action=details&amp;id=".convert::ToInt($_GET['id'])."");
            } else {
                $index = error(_error_wrong_permissions);
            }
        break;

        case 'editcom':
            $qry = db("SELECT * FROM ".dba::get('cw_comments')."
               WHERE id = '".convert::ToInt($_GET['cid'])."'");
            $get = _fetch($qry);

            if($get['reg'] == userid() || permission('clanwars'))
            {
                $editedby = show(_edited_by, array("autor" => autor(),
                        "time" => date("d.m.Y H:i", time())._uhr));
                $qry = db("UPDATE ".dba::get('cw_comments')."
                   SET `nick`     = '".string::encode($_POST['nick'])."',
                       `email`    = '".string::encode($_POST['email'])."',
                       `hp`       = '".links($_POST['hp'])."',
                       `comment`  = '".string::encode($_POST['comment'])."',
                       `editby`   = '".addslashes($editedby)."'
                   WHERE id = '".convert::ToInt($_GET['cid'])."'");

                $index = info(_comment_edited, "?action=details&amp;id=".$_GET['id']."");
            } else {
                $index = error(_error_edit_post);
            }
        break;

        case 'edit':
            $get = db("SELECT * FROM ".dba::get('cw_comments')." WHERE id = '".convert::ToInt($_GET['cid'])."'",false,true);
            if($get['reg'] == userid() || permission('clanwars'))
            {
                $form = $get['reg'] != 0 ? show("page/editor_regged", array("nick" => autor($get['reg']))) : show("page/editor_notregged", array("postemail" => $get['email'], "posthp" => links($get['hp']), "postnick" => string::decode($get['nick'])));
                $index = show("page/comments_add", array("titel" => _comments_edit,
                        "sec" => $dir,
                        "form" => $form,
                        "prevurl" => '../clanwars/?action=compreview&do=edit&id='.$_GET['id'].'&amp;cid='.$_GET['cid'],
                        "action" => '?action=details&amp;do=editcom&amp;id='.$_GET['id'].'&amp;cid='.$_GET['cid'],
                        "id" => $_GET['id'],
                        "what" => _button_value_edit,
                        "show" => "",
                        "posteintrag" => string::decode($get['comment']),
                        "error" => ""));
            }
            else
                $index = error(_error_edit_post);
        break;

        default:
            $get = db("SELECT s1.id,s1.datum,s1.clantag,s1.gegner,s1.url,s1.xonx,s1.liga,s1.punkte,s1.gpunkte,s1.maps,s1.serverip,s1.servername,
               s1.serverpwd,s1.bericht,s1.squad_id,s1.gametype,s1.gcountry,s1.lineup,s1.glineup,s1.matchadmins,s2.icon,s2.name,s2.game
               FROM ".dba::get('cw')." AS s1 LEFT JOIN ".dba::get('squads')." AS s2 ON s1.squad_id = s2.id WHERE s1.id = '".convert::ToInt($_GET['id'])."'",false,true);

            $serverpwd = ""; $players = "";
            if(checkme() != "1" && checkme() != "unlogged" && $get['punkte'] == "0" && $get['gpunkte'] == "0")
            {
                $serverpwd = "";
                if($get['datum'] > time())
                {
                    $qryp = db("SELECT * FROM ".dba::get('cw_player')." WHERE cwid = '".convert::ToInt($_GET['id'])."' ORDER BY status");
                    $color = 0; $show_players = '';
                    while($getp = _fetch($qryp))
                    {
                        if($getp['status'] == "0") $status = _cw_player_want;
                        elseif($getp['status'] == "1") $status = _cw_player_dont_want;
                        else $status = _cw_player_dont_know;

                        $sely = ""; $seln = ""; $selm = "";
                        if($getp['member'] == userid())
                        {
                            if($getp['status'] == "0")
                                $sely = "checked=\"checked\"";
                            else if($getp['status'] == "1")
                                $seln = "checked=\"checked\"";
                            else if($getp['status'] == "2")
                                $selm = "checked=\"checked\"";
                        }

                        $class = ($color % 2) ? "contentMainSecond" : "contentMainFirst"; $color++;
                        $show_players .= show($dir."/players_show", array("nick" => autor($getp['member']), "class" => $class, "status" => $status));
                    }

                    $cntPlayers = cnt(dba::get('cw_player'), " WHERE cwid = '".convert::ToInt($_GET['id'])."' AND member = '".userid()."'", "cwid");
                    $value = ($cntPlayers ? _button_value_edit : _button_value_add);
                    $players = show($dir."/players", array("show_players" => $show_players,
                            "admin" => (permission('clanwars') ? '<input id="contentSubmitAdmin" type="button" value="'._cw_reset_button.'" class="submit" onclick="DZCP.submitButton(\'contentSubmitAdmin\');DZCP.goTo(\'?action=resetplayers&amp;id='.convert::ToInt($_GET['id']).'\')" />' : ''),
                            "sely" => (empty($sely) && empty($seln) && empty($selm) ? 'checked="checked"' : $sely),
                            "seln" => $seln,
                            "selm" => $selm,
                            "id" => convert::ToInt($_GET['id']),
                            "value" => $value));

                    $serverpwd = show(_cw_serverpwd, array("cw_serverpwd" => string::decode($get['serverpwd'])));
                }
            }

            $img = squad($get['icon']);
            $show = show(_cw_details_squad, array("game" => string::decode($get['game']), "name" => string::decode($get['name']), "id" => $get['squad_id'], "img" => $img));
            $flagge = flag($get['gcountry']);
            $gegner = show(_cw_details_gegner_blank, array("gegner" => string::decode($get['clantag']." - ".$get['gegner']), "url" => !empty($get['url']) ? string::decode($get['url']) : "#"));
            $server = show(_cw_details_server, array("servername" => string::decode($get['servername']), "serverip" => string::decode($get['serverip'])));
            $result = ($get['punkte'] == "0" && $get['gpunkte'] == "0" ? _cw_no_results : cw_result_details($get['punkte'], $get['gpunkte']));
            $editcw = permission("clanwars") ? show("page/button_edit_single", array("id" => $get['id'], "action" => "action=admin&amp;do=edit", "title" => _button_title_edit)) : '';
            $bericht = ($get['bericht'] ? bbcode::parse_html($get['bericht']) : "&nbsp;");
            $screens = cw_screenshots(convert::ToInt($_GET['id']));
            $qryc = db("SELECT * FROM ".dba::get('cw_comments')." WHERE cw = ".convert::ToInt($_GET['id'])." ORDER BY datum DESC LIMIT ".($page - 1)*($maxcwcomments=config('m_cwcomments')).",".$maxcwcomments."");
            $entrys = cnt(dba::get('cw_comments'), " WHERE cw = ".convert::ToInt($_GET['id']));
            $i = $entrys-($page - 1)*$maxcwcomments;

            $comments = '';
            while($getc = _fetch($qryc))
            {
                $edit = ""; $delete = "";
                if((checkme() != 'unlogged' && $getc['reg'] == userid()) || permission("clanwars"))
                {
                    $edit = show("page/button_edit_single", array("id" => $get['id'], "action" => "action=details&amp;do=edit&amp;cid=".$getc['id'], "title" => _button_title_edit));
                    $delete = show("page/button_delete_single", array("id" => $_GET['id'], "action" => "action=details&amp;do=delete&amp;cid=".$getc['id'], "title" => _button_title_del, "del" => _confirm_del_entry));
                }

                if(!$getc['reg'])
                {
                    $hp = ($getc['hp'] ? show(_hpicon_forum, array("hp" => $getc['hp'])) : '');
                    $email = ($getc['email'] ? '<br />'.show(_emailicon_forum, array("email" => eMailAddr($getc['email']))) : '');
                    $onoff = ""; $avatar = "";
                    $nick = show(_link_mailto, array("nick" => string::decode($getc['nick']), "email" => $getc['email']));
                }
                else
                {
                    $hp = ($getc['hp'] ? show(_hpicon, array("hp" => $getc['hp'])) : '');
                    $email = "";
                    $onoff = onlinecheck($getc['reg']);
                    $nick = autor($getc['reg']);
                }

                $titel = show(_eintrag_titel, array("postid" => $i, "datum" => date("d.m.Y", $getc['datum']), "zeit" => date("H:i", $getc['datum'])._uhr, "edit" => $edit, "delete" => $delete));
                $comments .= show("page/comments_show", array("titel" => $titel,
                        "comment" => bbcode::parse_html($getc['comment']),
                        "editby" => bbcode::parse_html($getc['editby']),
                        "nick" => $nick,
                        "hp" => $hp,
                        "email" => $email,
                        "avatar" => useravatar($getc['reg']),
                        "onoff" => $onoff,
                        "rank" => getrank($getc['reg']),
                        "ip" => (checkme() == "4" ? $getc['ip'] : _logged)));
                $i--;
            }

            if(settings("reg_cwcomments") == "1" && checkme() == "unlogged")
                $add = _error_unregistered_nc;
            else
            {
                $add = "";
                if(!ipcheck("cwid(".$_GET['id'].")", config('f_cwcom')))
                {
                    $form = (userid() != 0 ? show("page/editor_regged", array("nick" => autor())) : show("page/editor_notregged", array("postemail" => '', "posthp" => '', "postnick" => '')));
                    $add = show("page/comments_add", array("titel" => _cw_comments_add,
                            "sec" => $dir,
                            "show" => "none",

                            "action" => '?action=details&amp;do=add&amp;id='.$_GET['id'],
                            "prevurl" => '../clanwars/?action=compreview&id='.$_GET['id'],
                            "id" => $_GET['id'],
                            "what" => _button_value_add,
                            "form" => $form,
                            "posteintrag" => "",
                            "error" => "",
                            "eintraghead" => _eintrag));
                }
            }

            $seiten = nav($entrys,$maxcwcomments,"?action=details&amp;id=".$_GET['id']."");
            $comments = show($dir."/comments",array("show" => $comments, "seiten" => $seiten, "add" => $add));

            $logo_squad = '_defaultlogo.jpg'; $logo_gegner = '_defaultlogo.jpg';
            foreach($picformat AS $end)
            {
                if(file_exists(basePath.'/inc/images/uploads/clanwars/'.$get['id'].'_logo.'.$end))
                    $logo_gegner = $get['id'].'_logo.'.$end;

                if(file_exists(basePath.'/inc/images/uploads/squads/'.$get['squad_id'].'_logo.'.$end))
                    $logo_squad = $get['squad_id'].'_logo.'.$end;
            }

            $logos = ($logo_squad == '_defaultlogo.jpg') && ($logo_gegner == '_defaultlogo.jpg');
            $pagetitle = string::decode($get['name']).' vs. '.string::decode($get['gegner']).' - '.$pagetitle;

            $info ='';
            #$info = 'onmouseover="DZCP.showInfo(\'\', \'\', \'\', \''.hovermappic($get['maps'],$get['game']).'\')" onmouseout="DZCP.hideInfo()"'; //TODO: Mappics
            $index = show($dir."/details", array("head" => _cw_head_details,
            "result_head" => _cw_head_results,
            "lineup_head" => _cw_head_lineup,
            "admin_head" => _cw_head_admin,
            "gametype_head" => _cw_head_gametype,
            "squad_head" => _cw_head_squad,
            "flagge" => $flagge,
            "info" => $info,
            "br1" => ($logos ? '<!--' : ''),
            "br2" => ($logos ? '-->' : ''),
            "logo_squad" => $logo_squad,
            "logo_gegner" => $logo_gegner,
            "squad" => $show,
            "squad_name" => string::decode($get['name']),
            "gametype" => empty($get['gametype']) ? '-' : string::decode($get['gametype']),
            "lineup" => preg_replace("#\,#","<br />",string::decode($get['lineup'])),
            "glineup" => preg_replace("#\,#","<br />",string::decode($get['glineup'])),
            "match_admins" => empty($get['matchadmins']) ? '-' : string::decode($get['matchadmins']),
            "datum" => _datum,
            "gegner" => _cw_head_gegner,
            "xonx" => _cw_head_xonx,
            "liga" => _cw_head_liga,
            "maps" => _cw_maps,
            "server" => _server,
            "result" => _cw_head_result,
            "players" => $players,
            "edit" => $editcw,
            "comments" => $comments,
            "bericht" => _cw_bericht,
            "serverpwd" => $serverpwd,
            "cw_datum" => date("d.m.Y H:i", $get['datum'])._uhr,
            "cw_gegner" => $gegner,
            "cw_xonx" => empty($get['xonx']) ? '-' : string::decode($get['xonx']),
            "cw_liga" => empty($get['liga']) ? '-' : string::decode($get['liga']),
            "cw_maps" => empty($get['maps']) ? '-' : string::decode($get['maps']),
            "cw_server" => $server,
            "cw_result" => $result,
            "cw_bericht" => $bericht,
            "screenshots" => $screens));
        break;
    }
}