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
else if(!isset($_GET['id']) || empty($_GET['id']) || !db("SELECT id FROM ".dba::get('news')." WHERE id = ".$news_id=convert::ToInt($_GET['id']),true))
    $index = error(_id_dont_exist);
else
{
    $c = db("SELECT intern,public FROM ".dba::get('news')." WHERE id = ".$news_id,false,true);

    if(!permission("news") && !$c['public'])
        $index = error(_error_wrong_permissions);
    else if($c['intern'] && !permission("intnews"))
        $index = error(_error_wrong_permissions);
    else
    {
        if(_rows(($qry = db("SELECT * FROM ".dba::get('news')." WHERE id = '".$news_id."'".(!permission("news") ? " AND public = 1" : "")))) == 0)
            $index = error(_id_dont_exist);
        else
        {
            #################################### do case ####################################
            $error = '';
            switch($do)
            {
                case 'add':
                    $get_ec = _fetch($qry);
                    if($get_ec['comments'])
                    {
                        if(settings("reg_newscomments") == "1" && checkme() == "unlogged")
                            $index = error(_error_have_to_be_logged);
                        else
                        {
                            if(!ipcheck("ncid(".$news_id.")", ($f_newscom=config('f_newscom'))))
                            {
                                if(userid() != 0)
                                    $toCheck = empty($_POST['comment']);
                                else
                                    $toCheck = empty($_POST['nick']) || empty($_POST['email']) || empty($_POST['comment']) || !check_email($_POST['email']) || $_POST['secure'] != $_SESSION['sec_'.$dir] || empty($_SESSION['sec_'.$dir]);

                                if($toCheck)
                                {
                                    if(userid() != 0)
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
                                        else if(check_email_trash_mail($_POST['email']))
                                            $error = show("errors/errortable", array("error" => _error_trash_mail));
                                        else if(empty($_POST['eintrag']))
                                            $error = show("errors/errortable", array("error" => _empty_eintrag));
                                    }
                                }
                                else
                                {
                                    db("INSERT INTO ".dba::get('newscomments')."
                                       SET `news`     = '".$news_id."',
                                           `datum`    = '".time()."',
                                           ".(isset($_POST['email']) ? "`email` = '".string::encode($_POST['email'])."'," : '')."
                                           ".(isset($_POST['nick']) ? "`nick` = '".string::encode($_POST['nick'])."'," : '')."
                                           ".(isset($_POST['hp']) ? "`hp` = '".links($_POST['hp'])."'," : '')."
                                           `editby`   = '',
                                           `reg`      = '".userid()."',
                                           `comment`  = '".string::encode($_POST['comment'])."',
                                           `ip`       = '".visitorIp()."'");

                                    wire_ipcheck("ncid(".$news_id.")");
                                    $index = info(_comment_added, "?action=show&amp;id=".$news_id."");
                                }
                            }
                            else
                                $index = error(show(_error_flood_post, array("sek" => $f_newscom)));
                        }
                    }
                    else
                        $index = error(_no_comments_enabled);
                break;
                case 'delete':
                    $get_ec = _fetch($qry);
                    if($get_ec['comments'])
                    {
                        $get = db("SELECT * FROM ".dba::get('newscomments')." WHERE id = '".convert::ToInt($_GET['cid'])."'",false,true);
                        if($get['reg'] == userid() || permission('news'))
                        {
                            $qry = db("DELETE FROM ".dba::get('newscomments')." WHERE id = '".convert::ToInt($_GET['cid'])."'");
                            $index = info(_comment_deleted, "?action=show&amp;id=".$news_id."");
                        }
                        else
                            $index = error(_error_wrong_permissions);
                    }
                    else
                        $index = error(_no_comments_enabled);
                break;
                case 'editcom':
                    $get_ec = _fetch($qry);
                    if($get_ec['comments'])
                    {
                        if(userid() != 0)
                            $toCheck = empty($_POST['comment']);
                        else
                            $toCheck = empty($_POST['nick']) || empty($_POST['email']) || empty($_POST['comment']) || !check_email($_POST['email']) || $_POST['secure'] != $_SESSION['sec_'.$dir] || empty($_SESSION['sec_'.$dir]);

                        if($toCheck)
                        {
                            $get = db("SELECT * FROM ".dba::get('newscomments')." WHERE id = '".convert::ToInt($_GET['cid'])."'",false,true);

                            if(userid() != 0)
                                $form = show("page/editor_regged", array("nick" => autor($get['reg'])));
                            else
                                $form = show("page/editor_notregged", array("postemail" => "", "posthp" => "", "postnick" => ""));

                            if(userid() != 0)
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
                                else if(check_email_trash_mail($_POST['email']))
                                    $error = show("errors/errortable", array("error" => _error_trash_mail));
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
                                    "postnick" =>string::decode($get['nick']),
                                    "posteintrag" => string::decode($get['comment']),
                                    "error" => $error));
                        }
                        else
                        {
                            $get = db("SELECT * FROM ".dba::get('newscomments')." WHERE id = '".convert::ToInt($_GET['cid'])."'",false,true);
                            if($get['reg'] == userid() || permission('news'))
                            {
                                $editedby = show(_edited_by, array("autor" => autor(), "time" => date("d.m.Y H:i", time())._uhr));
                                db("UPDATE ".dba::get('newscomments')." SET
                                    ".(isset($_POST['email']) ? "`email` = '".string::encode($_POST['email'])."'," : "")."
                                    ".(isset($_POST['nick']) ? "`nick` = '".string::encode($_POST['nick'])."'," : "")."
                                    ".(isset($_POST['hp']) ? "`hp` = '".links($_POST['hp'])."'," : "")."
                                    `comment`  = '".string::encode($_POST['comment'])."',
                                    `editby`   = '".addslashes($editedby)."'
                                    WHERE id = '".convert::ToInt($_GET['cid'])."'");

                                $index = info(_comment_edited, "?action=show&amp;id=".$news_id."");
                            }
                            else
                                $index = error(_error_edit_post);
                        }
                    }
                    else
                        $index = error(_no_comments_enabled);
                break;
                case 'edit':
                    $get = db("SELECT * FROM ".dba::get('newscomments')." WHERE id = '".convert::ToInt($_GET['cid'])."'",false,true);
                    if($get['reg'] == userid() || permission('news'))
                    {
                        if($get['reg'] != 0)
                            $form = show("page/editor_regged", array("nick" => autor($get['reg'])));
                        else
                            $form = show("page/editor_notregged", array("nickhead" => _nick, "emailhead" => _email, "hphead" => _hp, "postemail" => $get['email'], "posthp" => links($get['hp']), "postnick" => string::decode($get['nick'])));

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
                                "posteintrag" => string::decode($get['comment']),
                                "error" => ""));
                    }
                    else
                        $index = error(_error_edit_post);
                break;
            }

            #################################### SHOW ####################################
            if(empty($index))
            {
                //Update viewed
                if(count_clicks('news',$news_id))
                    db("UPDATE ".dba::get('news')." SET `viewed` = viewed+1 WHERE id = '".$news_id."'");

                $get = _fetch($qry);
                $getkat = db("SELECT katimg FROM ".dba::get('newskat')." WHERE id = '".$get['kat']."'",false,true);
                $klapp = ($get['klapptext'] ? show(_news_klapplink, array("klapplink" => string::decode($get['klapplink']), "which" => "collapse", "id" => $get['id'])) : '');
                $viewed = show(_news_viewed, array("viewed" => $get['viewed']));
                $links1 = (!empty($get['url1']) ? show(_news_link, array("link" => string::decode($get['link1']), "url" => $get['url1'])) : '');
                $links2 = (!empty($get['url2']) ? show(_news_link, array("link" => string::decode($get['link2']), "url" => $get['url2'])) : '');
                $links3 = (!empty($get['url3']) ? show(_news_link, array("link" => string::decode($get['link3']), "url" => $get['url3'])) : '');
                $links = (!empty($links1) || !empty($links2) || !empty($links3) ? show(_news_links, array("link1" => $links1, "link2" => $links2, "link3" => $links3, "rel" => _related_links)) : '');

                $newsimage = '../inc/images/uploads/newskat/'.string::decode($getkat['katimg']);
                if($get['custom_image'])
                {
                    foreach($picformat AS $end)
                    {
                        if(file_exists(basePath.'/inc/images/uploads/news/'.$get['id'].'.'.$end))
                            break;
                    }

                    if(file_exists(basePath.'/inc/images/uploads/news/'.$get['id'].'.'.$end))
                        $newsimage = '../inc/images/uploads/news/'.$get['id'].'.'.$end;
                }

                if($get['comments'])
                {
                    $qryc = db("SELECT * FROM ".dba::get('newscomments')." WHERE news = ".$news_id." ORDER BY datum DESC LIMIT ".($page - 1)*($maxcomments=config('m_comments')).",".$maxcomments."");
                    $entrys = cnt(dba::get('newscomments'), " WHERE news = ".$news_id);
                    $i = $entrys-($page - 1)*$maxcomments;

                    $comments = '';
                    while($getc = _fetch($qryc))
                    {
                        $edit = ""; $delete = ""; $hp = ""; $email = ""; $onoff = "";
                        if((checkme() != 'unlogged' && $getc['reg'] == userid()) || permission("news"))
                        {
                            $edit = show("page/button_edit_single", array("id" => $get['id'], "action" => "action=show&amp;do=edit&amp;cid=".$getc['id']."&amp;postid=".$i, "title" => _button_title_edit));
                            $delete = show("page/button_delete_single", array("id" => $get['id'], "action" => "action=show&amp;do=delete&amp;cid=".$getc['id'], "title" => _button_title_del, "del" => _confirm_del_entry));
                        }

                        if(!$getc['reg'])
                        {
                            $hp = ($getc['hp'] ? show(_hpicon_forum, array("hp" => $getc['hp'])) : '');
                            $email = ($getc['email'] ? '<br />'.show(_emailicon_forum, array("email" => eMailAddr($getc['email']))) : '');
                            $nick = show(_link_mailto, array("nick" => string::decode($getc['nick']), "email" => eMailAddr($getc['email'])));
                        }
                        else
                        {
                            $onoff = onlinecheck($getc['reg']);
                            $nick = autor($getc['reg']);
                        }

                        $titel = show(_eintrag_titel, array("postid" => $i, "datum" => date("d.m.Y", $getc['datum']), "zeit" => date("H:i", $getc['datum'])._uhr, "edit" => $edit, "delete" => $delete));
                        $posted_ip = (checkme() == 4 ? $getc['ip'] : _logged);
                        $comments .= show("page/comments_show", array("titel" => $titel,
                                                                      "comment" => bbcode::parse_html($getc['comment']),
                                                                      "nick" => $nick,
                                                                      "hp" => $hp,
                                                                      "editby" => bbcode::parse_html($getc['editby']),
                                                                      "email" => $email,
                                                                      "avatar" => useravatar($getc['reg']),
                                                                      "onoff" => $onoff,
                                                                      "rank" => getrank($getc['reg']),
                                                                      "ip" => $posted_ip));
                        $i--;
                    }

                    if(empty($comments))
                        $comments = show("page/comments_no_entry");

                    if(settings("reg_newscomments") == "1" && checkme() == "unlogged")
                        $add = _error_unregistered_nc;
                    else
                    {
                        if(userid() != 0)
                            $form = show("page/editor_regged", array("nick" => autor()));
                        else
                            $form = show("page/editor_notregged", array("postemail" => "", "posthp" => "", "postnick" => ""));

                        $add = '';
                        if(!ipcheck("ncid(".$news_id.")", config('f_newscom')))
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
                                                                    "postnick" => (isset($_POST['nick']) && !empty($error) ? string::decode($_POST['nick']) : ''),
                                                                    "posteintrag" => (isset($_POST['comment']) && !empty($error) ? string::decode($_POST['comment']) : ''),
                                                                    "error" => $error));
                        }
                    }

                    $seiten = nav($entrys,$maxcomments,"?action=show&amp;id=".$news_id."");
                    $showmore = show($dir."/comments",array("head" => _comments_head,
                                                            "show" => $comments,
                                                            "seiten" => $seiten,
                                                            "add" => $add));
                }
                else
                    $showmore = show("page/comments_no_enabled");

                $intern = ($get['intern'] ? _votes_intern : '');
                $title = string::decode($get['titel']).' - '.$title;
                $index = show($dir."/news_show_full", array("titel" => string::decode($get['titel']),
                                                            "newsimage" => $newsimage,
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
                                                            "more" => bbcode::parse_html($get['klapptext']),
                                                            "viewed" => "",
                                                            "text" => bbcode::parse_html($get['text']),
                                                            "datum" => date("j.m.y H:i", (empty($get['datum']) ? time() : $get['datum']))._uhr,
                                                            "links" => $links,
                                                            "autor" => autor($get['autor'])));
            }
        }
    }
}