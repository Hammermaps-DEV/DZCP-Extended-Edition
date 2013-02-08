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
    if(!permission("artikel")) {
        $shownews = " AND public = 1";
    }
    $qry = db("SELECT * FROM ".$db['artikel']."
             WHERE id = '".intval($_GET['id'])."'".$shownews);

    if(_rows($qry) == 0) {
        $index = error(_id_dont_exist,1);
    } else {
        while($get = _fetch($qry))
        {
            $qrykat = db("SELECT katimg FROM ".$db['newskat']."
                  WHERE id = '".intval($get['kat'])."'");
            $getkat = _fetch($qrykat);


            if($get['url1'])
            {
                $rel = _related_links;
                $links1 = show(_artikel_link, array("link" => re($get['link1']),
                        "url" => $get['url1']));
            } else {
                $links1 = "";
            }
            if($get['url2'])
            {
                $rel = _related_links;
                $links2 = show(_artikel_link, array("link" => re($get['link2']),
                        "url" => $get['url2']));
            } else {
                $links2 = "";
            }
            if($get['url3'])
            {
                $rel = _related_links;
                $links3 = show(_artikel_link, array("link" => re($get['link3']),
                        "url" => $get['url3']));
            } else {
                $links3 = "";
            }

            if(!empty($links1) || !empty($links2) || !empty($links3))
            {
                $links = show(_artikel_links, array("link1" => $links1,
                        "link2" => $links2,
                        "link3" => $links3,
                        "rel" => $rel));
            } else {
                $links = "";
            }

            if(isset($_GET['page'])) $page = $_GET['page'];
            else $page = 1;

            $entrys = cnt($db['acomments'], " WHERE artikel = ".intval($_GET['id']));
            $qryc = db("SELECT * FROM ".$db['acomments']."
                              WHERE artikel = ".intval($_GET['id'])."
                              ORDER BY datum DESC
                LIMIT ".($page - 1)*$maxcomments.",".$maxcomments."");

            $i = $entrys-($page - 1)*$maxcomments;

            while($getc = _fetch($qryc))
            {
                if($getc['hp']) $hp = show(_hpicon, array("hp" => $getc['hp']));
                else $hp = "";

                if(($chkMe != 'unlogged' && $getc['reg'] == $userid) || permission("artikel"))
                {
                    $edit = show("page/button_edit_single", array("id" => $get['id'],
                            "action" => "action=show&amp;do=edit&amp;cid=".$getc['id'],
                            "title" => _button_title_edit));
                    $delete = show("page/button_delete_single", array("id" => $_GET['id'],
                            "action" => "action=show&amp;do=delete&amp;cid=".$getc['id'],
                            "title" => _button_title_del,
                            "del" => convSpace(_confirm_del_entry)));
                } else {
                    $edit = "";
                    $delete = "";
                }

                if($getc['reg'] == "0")
                {
                    if($getc['hp']) $hp = show(_hpicon_forum, array("hp" => $getc['hp']));
                    else $hp = "";
                    if($getc['email']) $email = '<br />'.show(_emailicon_forum, array("email" => eMailAddr($getc['email'])));
                    else $email = "";
                    $onoff = "";
                    $avatar = "";
                    $nick = show(_link_mailto, array("nick" =>re($getc['nick']),
                            "email" => eMailAddr($getc['email'])));
                } else {
                    $email = "";
                    $hp = "";
                    $onoff = onlinecheck($getc['reg']);
                    $nick = autor($getc['reg']);
                }

                $titel = show(_eintrag_titel, array("postid" => $i,
                        "datum" => date("d.m.Y", $getc['datum']),
                        "zeit" => date("H:i", $getc['datum'])._uhr,
                        "edit" => $edit,
                        "delete" => $delete));

                if($chkMe == "4") $posted_ip = $getc['ip'];
                else $posted_ip = _logged;

                $comments .= show("page/comments_show", array("titel" => $titel,
                        "comment" => bbcode($getc['comment']),
                        "editby" => bbcode($getc['editby']),
                        "nick" => $nick,
                        "email" => $email,
                        "hp" => $hp,
                        "avatar" => useravatar($getc['reg']),
                        "onoff" => $onoff,
                        "rank" => getrank($getc['reg']),
                        "ip" => $posted_ip));
                $i--;
            }

            if(empty($comments))
                $comments = show("page/comments_no_entry", array());

            if(settings("reg_artikel") == "1" && $chkMe == "unlogged")
            {
                $add = _error_unregistered_nc;
            } else {
                if(isset($userid))
                {
                    $form = show("page/editor_regged", array("nick" => autor($userid),
                            "von" => _autor));
                } else {
                    $form = show("page/editor_notregged", array("nickhead" => _nick,
                            "emailhead" => _email,
                            "hphead" => _hp));
                }

                if(!ipcheck("artid(".$_GET['id'].")", $flood_newscom))
                {
                    $add = show("page/comments_add", array("titel" => _artikel_comments_write_head,
                            "form" => $form,
                            "show" => "none",
                            "what" => _button_value_add,
                            "ip" => _iplog_info,"sec" => $dir,
                            "security" => _register_confirm,
                            "preview" => _preview,
                            "action" => '?action=show&amp;do=add&amp;id='.$_GET['id'],
                            "prevurl" => '../artikel/?action=compreview&amp;id='.$_GET['id'],
                            "id" => $_GET['id'],
                            "postemail" => "",
                            "posthp" => "",
                            "postnick" => "",
                            "posteintrag" => "",
                            "error" => "",
                            "eintraghead" => _eintrag));
                } else {
                    $add = "";
                }
            }
            $seiten = nav($entrys,$maxcomments,"?action=show&amp;id=".$_GET['id']."");

            $showmore = show($dir."/comments",array("head" => _comments_head,
                    "show" => $comments,
                    "seiten" => $seiten,
                    "icq" => "",
                    "add" => $add));

            $index = show($dir."/show_more", array("titel" => re($get['titel']),
                    "id" => $get['id'],
                    "comments" => "",
                    "display" => "inline",
                    "nautor" => _autor,
                    "kat" => re($getkat['katimg']),
                    "dir" => $designpath,
                    "ndatum" => _datum,
                    "showmore" => $showmore,
                    "icq" => "",
                    "text" => bbcode($get['text']),
                    "datum" => date("j.m.y H:i", intval($get['datum']))._uhr,
                    "links" => $links,
                    "autor" => autor($get['autor'])));
        }
        if($_GET['do'] == "add")
        {
            if(_rows(db("SELECT `id` FROM ".$db['artikel']." WHERE `id` = '".(int)$_GET['id']."'")) != 0)
            {
                if(settings("reg_artikel") == "1" && $chkMe == "unlogged")
                {
                    $index = error(_error_have_to_be_logged, 1);
                } else {
                    if(!ipcheck("artid(".$_GET['id'].")", $flood_artikelcom))
                    {
                        if(isset($userid))
                            $toCheck = empty($_POST['comment']);
                        else
                            $toCheck = empty($_POST['nick']) || empty($_POST['email']) || empty($_POST['comment']) || !check_email($_POST['email']) || $_POST['secure'] != $_SESSION['sec_'.$dir] || empty($_SESSION['sec_'.$dir]);

                        if($toCheck)
                        {
                            if(isset($userid))
                            {
                                if(empty($_POST['eintrag'])) $error = _empty_eintrag;
                                $form = show("page/editor_regged", array("nick" => autor($userid),
                                        "von" => _autor));
                            } else {
                                if(($_POST['secure'] != $_SESSION['sec_'.$dir])  || empty($_SESSION['sec_'.$dir])) $error = _error_invalid_regcode;
                                elseif(empty($_POST['nick'])) $error = _empty_nick;
                                elseif(empty($_POST['email'])) $error = _empty_email;
                                elseif(!check_email($_POST['email'])) $error = _error_invalid_email;
                                elseif(empty($_POST['eintrag'])) $error = _empty_eintrag;

                                $form = show("page/editor_notregged", array("nickhead" => _nick,
                                        "emailhead" => _email,
                                        "hphead" => _hp));
                            }



                            $error = show("errors/errortable", array("error" => $error));
                            $index = show("page/comments_add", array("titel" => _artikel_comments_write_head,
                                    "nickhead" => _nick,
                                    "sec" => $dir,
                                    "security" => _register_confirm,
                                    "emailhead" => _email,
                                    "form" => $form,
                                    "hphead" => _hp,
                                    "preview" => _preview,
                                    "action" => '?action=show&amp;do=add&amp;id='.$_GET['id'],
                                    "prevurl" => '../artikel/?action=compreview&amp;id='.$_GET['id'],
                                    "id" => $_GET['id'],
                                    "what" => _button_value_add,
                                    "postemail" => $_POST['email'],
                                    "ip" => _iplog_info,
                                    "posthp" => links($_POST['hp']),
                                    "postnick" => re($_POST['nick']),
                                    "show" => "",
                                    "posteintrag" => re_bbcode($_POST['comment']),
                                    "error" => $error,
                                    "eintraghead" => _eintrag));
                        } else {
                            $qry = db("INSERT INTO ".$db['acomments']."
                                             SET `artikel`  = '".((int)$_GET['id'])."',
                                                     `datum`    = '".((int)time())."',
                                                     `nick`     = '".up($_POST['nick'])."',
                                                     `email`    = '".$_POST['email']."',
                                                     `hp`       = '".links($_POST['hp'])."',
                                                     `reg`      = '".((int)$userid)."',
                                                     `comment`  = '".up($_POST['comment'],1)."',
                                                     `editby`   = '',
                                                     `ip`       = '".visitorIp()."'");


                            wire_ipcheck("artid(".$_GET['id'].")");

                            $index = info(_comment_added, "?action=show&amp;id=".$_GET['id']."");
                        }
                    } else {
                        $index = error(show(_error_flood_post, array("sek" => $flood_newscom)), 1);
                    }
                }
            } else{
                $index = error(_id_dont_exist,1);
            }
        } elseif($_GET['do'] == "delete") {
            $qry = db("SELECT * FROM ".$db['acomments']."
               WHERE id = '".intval($_GET['cid'])."'");
            $get = _fetch($qry);

            if($get['reg'] == $userid || permission('artikel'))
            {
                $qry = db("DELETE FROM ".$db['acomments']."
                 WHERE id = '".intval($_GET['cid'])."'");

                $index = info(_comment_deleted, "?action=show&amp;id=".$_GET['id']."");
            } else {
                $index = error(_error_wrong_permissions, 1);
            }
        } elseif($_GET['do'] == "editcom") {
            $qry = db("SELECT * FROM ".$db['acomments']."
               WHERE id = '".intval($_GET['cid'])."'");
            $get = _fetch($qry);

            if($get['reg'] == $userid || permission('artikel'))
            {
                $editedby = show(_edited_by, array("autor" => autor($userid),
                        "time" => date("d.m.Y H:i", time())._uhr));
                $qry = db("UPDATE ".$db['acomments']."
                   SET `nick`     = '".up($_POST['nick'])."',
                       `email`    = '".up($_POST['email'])."',
                       `hp`       = '".links($_POST['hp'])."',
                       `comment`  = '".up($_POST['comment'],1)."',
                       `editby`   = '".addslashes($editedby)."'
                   WHERE id = '".intval($_GET['cid'])."'");

                $index = info(_comment_edited, "?action=show&amp;id=".$_GET['id']."");
            } else {
                $index = error(_error_edit_post,1);
            }
        } elseif($_GET['do'] == "edit") {
            $qry = db("SELECT * FROM ".$db['acomments']."
                 WHERE id = '".intval($_GET['cid'])."'");
            $get = _fetch($qry);

            if($get['reg'] == $userid || permission('artikel'))
            {
                if($get['reg'] != 0)
                {
                    $form = show("page/editor_regged", array("nick" => autor($get['reg']),
                            "von" => _autor));
                } else {
                    $form = show("page/editor_notregged", array("nickhead" => _nick,
                            "emailhead" => _email,
                            "hphead" => _hp,
                            "postemail" => $get['email'],
                            "posthp" => links($get['hp']),
                            "postnick" => re($get['nick']),
                    ));
                }

                $index = show("page/comments_add", array("titel" => _comments_edit,
                        "nickhead" => _nick,
                        "emailhead" => _email,
                        "sec" => $dir,
                        "security" => _register_confirm,
                        "hphead" => _hp,
                        "form" => $form,
                        "preview" => _preview,
                        "prevurl" => '../artikel/?action=compreview&amp;do=edit&amp;id='.$_GET['id'].'&amp;cid='.$_GET['cid'],
                        "action" => '?action=show&amp;do=editcom&amp;id='.$_GET['id'].'&amp;cid='.$_GET['cid'],
                        "ip" => _iplog_info,
                        "id" => $_GET['id'],
                        "what" => _button_value_edit,
                        "show" => "",
                        "posteintrag" => re_bbcode($get['comment']),
                        "error" => "",
                        "eintraghead" => _eintrag));
            } else {
                $index = error(_error_edit_post,1);
            }
        }
    }
}
?>