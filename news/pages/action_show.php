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
else if(!isset($_GET['id']) || empty($_GET['id']) || !db("SELECT id FROM ".$db['news']." WHERE id = ".$news_id=convert::ToInt($_GET['id']),true))
    $index = error(_id_dont_exist, 1);
else
{
    $c = db("SELECT intern,public FROM ".$db['news']." WHERE id = ".$news_id,false,true);
    $flood_newscom = config('f_newscom');

    if(!permission("news") && !$c['public'])
        $index = error(_error_wrong_permissions, 1);
    else if($c['intern'] && !permission("intnews"))
        $index = error(_error_wrong_permissions, 1);
    else
    {
        if(_rows(($qry = db("SELECT * FROM ".$db['news']." WHERE id = '".$news_id."'".(!permission("news") ? " AND public = 1" : "")))) == 0)
            $index = error(_id_dont_exist,1);
        else
        {
            #################################### do case ####################################
            $error = '';
            switch($do)
            {
                case 'add':
                    if(settings("reg_newscomments") == "1" && $chkMe == "unlogged")
                        $index = error(_error_have_to_be_logged, 1);
                    else
                    {
                        if(!ipcheck("ncid(".$news_id.")", $flood_newscom))
                        {
                            if(isset($userid))
                                $toCheck = empty($_POST['comment']);
                            else
                                $toCheck = empty($_POST['nick']) || empty($_POST['email']) || empty($_POST['comment']) || !check_email($_POST['email']) || $_POST['secure'] != $_SESSION['sec_'.$dir] || empty($_SESSION['sec_'.$dir]);

                            if($toCheck)
                            {
                                if(isset($userid))
                                {
                                    if(empty($_POST['eintrag']))
                                        $error = show("errors/errortable", array("error" => _empty_eintrag));
                                }
                                else
                                {
                                    if(($_POST['secure'] != $_SESSION['sec_'.$dir]) || empty($_SESSION['sec_'.$dir]))
                                        $error = show("errors/errortable", array("error" => _error_invalid_regcode));
                                    else if(empty($_POST['nick']))
                                        $error = show("errors/errortable", array("error" => _empty_nick));
                                    else if(empty($_POST['email']))
                                        $error = show("errors/errortable", array("error" => _empty_email));
                                    else if(!check_email($_POST['email']))
                                        $error = show("errors/errortable", array("error" => _error_invalid_email));
                                    else if(empty($_POST['eintrag']))
                                        $error = show("errors/errortable", array("error" => _empty_eintrag));
                                }
                            }
                            else
                            {
                                db("INSERT INTO ".$db['newscomments']."
                                   SET `news`     = '".$news_id."',
                                       `datum`    = '".time()."',
                                       ".(isset($_POST['email']) ? "`email` = '".up($_POST['email'])."'," : '')."
                                       ".(isset($_POST['nick']) ? "`nick` = '".up($_POST['nick'])."'," : '')."
                                       ".(isset($_POST['hp']) ? "`hp` = '".links($_POST['hp'])."'," : '')."
                                       `editby`   = '',
                                       `reg`      = '".convert::ToInt($userid)."',
                                       `comment`  = '".up($_POST['comment'])."',
                                       `ip`       = '".visitorIp()."'");

                                wire_ipcheck("ncid(".$news_id.")");
                                $index = info(_comment_added, "?action=show&amp;id=".$news_id."");
                            }
                        }
                        else
                            $index = error(show(_error_flood_post, array("sek" => $flood_newscom)), 1);
                    }
                break;
                case 'delete':
                    $get = db("SELECT * FROM ".$db['newscomments']." WHERE id = '".convert::ToInt($_GET['cid'])."'",false,true);
                    if($get['reg'] == convert::ToInt($userid) || permission('news'))
                    {
                        $qry = db("DELETE FROM ".$db['newscomments']." WHERE id = '".convert::ToInt($_GET['cid'])."'");
                        $index = info(_comment_deleted, "?action=show&amp;id=".$news_id."");
                    }
                    else
                        $index = error(_error_wrong_permissions, 1);
                break;
                case 'editcom':
                    if(isset($userid))
                        $toCheck = empty($_POST['comment']);
                    else
                        $toCheck = empty($_POST['nick']) || empty($_POST['email']) || empty($_POST['comment']) || !check_email($_POST['email']) || $_POST['secure'] != $_SESSION['sec_'.$dir] || empty($_SESSION['sec_'.$dir]);

                    if($toCheck)
                    {
                        $get = db("SELECT * FROM ".$db['newscomments']." WHERE id = '".convert::ToInt($_GET['cid'])."'",false,true);

                        if(isset($userid))
                            $form = show("page/editor_regged", array("nick" => autor($get['reg']), "von" => _autor));
                        else
                            $form = show("page/editor_notregged", array("nickhead" => _nick, "emailhead" => _email, "hphead" => _hp));

                        if(isset($userid))
                        {
                            if(empty($_POST['eintrag']))
                                $error = show("errors/errortable", array("error" => _empty_eintrag));
                        }
                        else
                        {
                            if(($_POST['secure'] != $_SESSION['sec_'.$dir]) || empty($_SESSION['sec_'.$dir]))
                                $error = show("errors/errortable", array("error" => _error_invalid_regcode));
                            else if(empty($_POST['nick']))
                                $error = show("errors/errortable", array("error" => _empty_nick));
                            else if(empty($_POST['email']))
                                $error = show("errors/errortable", array("error" => _empty_email));
                            else if(!check_email($_POST['email']))
                                $error = show("errors/errortable", array("error" => _error_invalid_email));
                            else if(empty($_POST['eintrag']))
                                $error = show("errors/errortable", array("error" => _empty_eintrag));
                        }

                        $index = show("page/comments_add", array( "titel" => _comments_edit,
                                "form" => $form,
                                "what" => _button_value_edit,
                                "ip" => '',
                                "preview" => _preview,
                                "sec" => $dir,
                                "security" => _register_confirm,
                                "prevurl" => '../news/?action=compreview&do=edit&id='.$news_id.'&cid='.$_GET['cid'].'&postid='.$_GET['postid'],
                                "action" => '?action=show&amp;do=editcom&amp;id='.$news_id.'&amp;cid='.$_GET['cid'],
                                "postemail" => $get['email'],
                                "posthp" => $get['hp'],
                                "postnick" =>re($get['nick']),
                                "posteintrag" => re_bbcode($get['comment']),
                                "error" => $error));
                    }
                    else
                    {
                        $get = db("SELECT * FROM ".$db['newscomments']." WHERE id = '".convert::ToInt($_GET['cid'])."'",false,true);
                        if($get['reg'] == convert::ToInt($userid) || permission('news'))
                        {
                            $editedby = show(_edited_by, array("autor" => autor(convert::ToInt($userid)), "time" => date("d.m.Y H:i", time())._uhr));
                            db("UPDATE ".$db['newscomments']." SET
                                ".(isset($_POST['email']) ? "`email` = '".up($_POST['email'])."'," : "")."
                                ".(isset($_POST['nick']) ? "`nick` = '".up($_POST['nick'])."'," : "")."
                                ".(isset($_POST['hp']) ? "`hp` = '".links($_POST['hp'])."'," : "")."
                                `comment`  = '".up($_POST['comment'])."',
                                `editby`   = '".addslashes($editedby)."'
                                WHERE id = '".convert::ToInt($_GET['cid'])."'");

                            $index = info(_comment_edited, "?action=show&amp;id=".$news_id."");
                        }
                        else
                            $index = error(_error_edit_post,1);
                    }
                break;
                case 'edit':
                    $get = db("SELECT * FROM ".$db['newscomments']." WHERE id = '".convert::ToInt($_GET['cid'])."'",false,true);
                    if($get['reg'] == convert::ToInt($userid) || permission('news'))
                    {
                        if($get['reg'] != 0)
                            $form = show("page/editor_regged", array("nick" => autor($get['reg']),"von" => _autor));
                        else
                            $form = show("page/editor_notregged", array("nickhead" => _nick, "emailhead" => _email, "hphead" => _hp, "postemail" => $get['email'], "posthp" => links($get['hp']), "postnick" => re($get['nick'])));

                        $index = show("page/comments_add", array("titel" => _comments_edit,
                                "nickhead" => _nick,
                                "security" => _register_confirm,
                                "emailhead" => _email,
                                "form" => $form,
                                "sec" => $dir,
                                "preview" => _preview,
                                "prevurl" => '../news/?action=compreview&do=edit&id='.$news_id.'&cid='.$_GET['cid'].'&postid='.$_GET['postid'],
                                "action" => '?action=show&amp;do=editcom&amp;id='.$news_id.'&amp;cid='.$_GET['cid'],
                                "ip" => _iplog_info,
                                "id" => $news_id,
                                "what" => _button_value_edit,
                                "posteintrag" => re_bbcode($get['comment']),
                                "error" => ""));
                    }
                    else
                        $index = error(_error_edit_post,1);
                break;
            }

            #################################### SHOW ####################################
            if(empty($index))
            {
                //Update viewed
                if(count_clicks('news',$news_id))
                    db("UPDATE ".$db['news']." SET `viewed` = viewed+1 WHERE id = '".$news_id."'");

                $get = _fetch($qry);
                $getkat = db("SELECT katimg FROM ".$db['newskat']." WHERE id = '".$get['kat']."'",false,true);
                $klapp = ($get['klapptext'] ? show(_news_klapplink, array("klapplink" => re($get['klapplink']), "which" => "collapse", "id" => $get['id'])) : '');
                $viewed = show(_news_viewed, array("viewed" => $get['viewed']));
                $links1 = (!empty($get['url1']) ? show(_news_link, array("link" => re($get['link1']), "url" => $get['url1'])) : '');
                $links2 = (!empty($get['url2']) ? show(_news_link, array("link" => re($get['link2']), "url" => $get['url2'])) : '');
                $links3 = (!empty($get['url3']) ? show(_news_link, array("link" => re($get['link3']), "url" => $get['url3'])) : '');
                $links = (!empty($links1) || !empty($links2) || !empty($links3) ? show(_news_links, array("link1" => $links1, "link2" => $links2, "link3" => $links3, "rel" => _related_links)) : '');
                $qryc = db("SELECT * FROM ".$db['newscomments']." WHERE news = ".$news_id." ORDER BY datum DESC LIMIT ".($page - 1)*$maxcomments.",".$maxcomments."");
                $entrys = cnt($db['newscomments'], " WHERE news = ".$news_id);
                $i = $entrys-($page - 1)*$maxcomments;

                $comments = '';
                while($getc = _fetch($qryc))
                {
                    $edit = ""; $delete = ""; $hp = ""; $email = ""; $onoff = "";
                    if(($chkMe != 'unlogged' && $getc['reg'] == convert::ToInt($userid)) || permission("news"))
                    {
                        $edit = show("page/button_edit_single", array("id" => $get['id'], "action" => "action=show&amp;do=edit&amp;cid=".$getc['id']."&amp;postid=".$i, "title" => _button_title_edit));
                        $delete = show("page/button_delete_single", array("id" => $get['id'], "action" => "action=show&amp;do=delete&amp;cid=".$getc['id'], "title" => _button_title_del, "del" => convSpace(_confirm_del_entry)));
                    }

                    if(!$getc['reg'])
                    {
                        $hp = ($getc['hp'] ? show(_hpicon_forum, array("hp" => $getc['hp'])) : '');
                        $email = ($getc['email'] ? '<br />'.show(_emailicon_forum, array("email" => eMailAddr($getc['email']))) : '');
                        $nick = show(_link_mailto, array("nick" => re($getc['nick']), "email" => eMailAddr($getc['email'])));
                    }
                    else
                    {
                        $onoff = onlinecheck($getc['reg']);
                        $nick = autor($getc['reg']);
                    }

                    $titel = show(_eintrag_titel, array("postid" => $i, "datum" => date("d.m.Y", $getc['datum']), "zeit" => date("H:i", $getc['datum'])._uhr, "edit" => $edit, "delete" => $delete));
                    $posted_ip = ($chkMe == 4 ? $getc['ip'] : _logged);
                    $comments .= show("page/comments_show", array("titel" => $titel,
                                                                  "comment" => bbcode($getc['comment']),
                                                                  "nick" => $nick,
                                                                  "hp" => $hp,
                                                                  "editby" => bbcode($getc['editby']),
                                                                  "email" => $email,
                                                                  "avatar" => useravatar($getc['reg']),
                                                                  "onoff" => $onoff,
                                                                  "rank" => getrank($getc['reg']),
                                                                  "ip" => $posted_ip));
                    $i--;
                }

                if(empty($comments))
                    $comments = show("page/comments_no_entry", array());

                if(settings("reg_newscomments") == "1" && $chkMe == "unlogged")
                    $add = _error_unregistered_nc;
                else
                {
                    if(isset($userid))
                        $form = show("page/editor_regged", array("nick" => autor(convert::ToInt($userid)), "von" => _autor));
                    else
                        $form = show("page/editor_notregged", array("nickhead" => _nick, "emailhead" => _email, "hphead" => _hp));

                    $add = '';
                    if(!ipcheck("ncid(".$news_id.")", $flood_newscom))
                    {
                        $add = show("page/comments_add", array( "titel" => _news_comments_write_head,
                                                                "form" => $form,
                                                                "what" => _button_value_add,
                                                                "ip" => _iplog_info,
                                                                "preview" => _preview,
                                                                "sec" => $dir,
                                                                "security" => _register_confirm,
                                                                "action" => '?action=show&amp;do=add&amp;id='.$news_id,
                                                                "prevurl" => '../news/?action=compreview&id='.$news_id,
                                                                "postemail" => (isset($_POST['email']) && !empty($error) ? $_POST['email'] : ''),
                                                                "posthp" => (isset($_POST['hp']) && !empty($error) ? $_POST['hp'] : ''),
                                                                "postnick" => (isset($_POST['nick']) && !empty($error) ? re($_POST['nick']) : ''),
                                                                "posteintrag" => (isset($_POST['comment']) && !empty($error) ? re_bbcode($_POST['comment']) : ''),
                                                                "error" => $error));
                    }
                }

                $seiten = nav($entrys,$maxcomments,"?action=show&amp;id=".$news_id."");
                $showmore = show($dir."/comments",array("head" => _comments_head,
                                                        "show" => $comments,
                                                        "seiten" => $seiten,
                                                        "add" => $add));

                $intern = ($get['intern'] ? _votes_intern : '');
                $title = re($get['titel']).' - '.$title;
                $index = show($dir."/news_show_full", array("titel" => re($get['titel']),
                                                            "kat" => re($getkat['katimg']),
                                                            "id" => $get['id'],
                                                            "comments" => "",
                                                            "dp" => "compact",
                                                            "nautor" => _autor,
                                                            "dir" => $designpath,
                                                            "ndatum" => _datum,
                                                            "sticky" => "",
                                                            "intern" => $intern,
                                                            "ncomments" => "",
                                                            "showmore" => $showmore,
                                                            "klapp" => $klapp,
                                                            "more" => bbcode($get['klapptext']),
                                                            "viewed" => "",
                                                            "text" => bbcode($get['text']),
                                                            "datum" => date("j.m.y H:i", (empty($get['datum']) ? time() : $get['datum']))._uhr,
                                                            "links" => $links,
                                                            "autor" => autor($get['autor'])));
            }
        }
    }
}
?>
