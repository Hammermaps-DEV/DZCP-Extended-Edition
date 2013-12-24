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
    if($_GET['do'] == "edit")
    {
        $qry = db("SELECT * FROM ".dba::get('f_posts')."
               WHERE id = '".convert::ToInt($_GET['id'])."'");
        $get = _fetch($qry);

        if($get['reg'] == userid() || permission("forum"))
        {
            if($get['reg'] != 0)
                $form = show("page/editor_regged", array("nick" => autor($get['reg'])));
            else
                $form = show("page/editor_notregged", array("postemail" => string::decode($get['email']), "posthp" => string::decode($get['hp']), "postnick" => string::decode($get['nick'])));

            $dowhat = show(_forum_dowhat_edit_post, array("id" => $_GET['id']));
            $index = show($dir."/post", array("titel" => _forum_edit_post_head,
                    "nickhead" => _nick,
                    "emailhead" => _email,
                    "kid" => "",
                    "id" => $_GET['id'],
                    "ip" => _iplog_info,
                    "dowhat" => $dowhat,
                    "form" => $form,
                    "zitat" => $zitat,
                    "preview" => _preview,
                    "br1" => "<!--",
                    "br2" => "-->",
                    "security" => _register_confirm,
                    "lastpost" => "",
                    "last_post" => _forum_no_last_post,
                    "eintraghead" => _eintrag,
                    "error" => "",
                    "what" => _button_value_edit,
                    "posteintrag" => string::decode($get['text'])));
        } else {
            $index = error(_error_wrong_permissions);
        }
    } elseif($_GET['do'] == "editpost") {
        $qry = db("SELECT reg FROM ".dba::get('f_posts')."
               WHERE id = '".convert::ToInt($_GET['id'])."'");
        $get = _fetch($qry);
        if($get['reg'] == userid() || permission("forum"))
        {
            if($get['reg'] != 0 || permission('forum'))
            {
                $toCheck = empty($_POST['eintrag']);
            } else {
                $toCheck = empty($_POST['nick']) || empty($_POST['email']) || empty($_POST['eintrag']) || !$securimage->check($_POST['secure']);
            }

            if($toCheck)
            {
                if($get['reg'] != 0)
                {
                    if(empty($_POST['eintrag'])) $error = _empty_eintrag;
                    $form = show("page/editor_regged", array("nick" => autor()));
                } else {
                    if(!$securimage->check($_POST['secure']) && !userid()) $error = captcha_mathematic ? _error_invalid_regcode_mathematic : _error_invalid_regcode;
                    elseif(empty($_POST['nick'])) $error = _empty_nick;
                    elseif(empty($_POST['email'])) $error = _empty_email;
                    elseif(!check_email($_POST['email'])) $error = _error_invalid_email;
                    else if(check_email_trash_mail($_POST['email'])) $error = _error_trash_mail;
                    elseif(empty($_POST['eintrag']))$error = _empty_eintrag;
                    $form = show("page/editor_notregged", array("postemail" => "", "posthp" => "", "postnick" => ""));
                }

                $error = show("errors/errortable", array("error" => $error));
                $dowhat = show(_forum_dowhat_edit_post, array("id" => $_GET['id']));
                $index = show($dir."/post", array("titel" => _forum_edit_post_head,
                        "nickhead" => _nick,
                        "preview" => _preview,
                        "emailhead" => _email,
                        "zitat" => $zitat,
                        "form" => $form,
                        "dowhat" => $dowhat,
                        "security" => _register_confirm,
                        "what" => _button_value_edit,
                        "ip" => _iplog_info,
                        "id" => $_GET['id'],
                        "kid" => $_GET['kid'],
                        "br1" => "<!--",
                        "br2" => "-->",
                        "postemail" => string::decode($get['email']),
                        "postnick" => string::decode($get['nick']),
                        "posteintrag" => string::decode($_POST['eintrag']),
                        "error" => $error,
                        "eintraghead" => _eintrag));
            } else {
                $qryp = db("SELECT * FROM ".dba::get('f_posts')."
                    WHERE id = '".convert::ToInt($_GET['id'])."'");
                $getp = _fetch($qryp);

                $editedby = show(_edited_by, array("autor" => autor(),
                        "time" => date("d.m.Y H:i", time())._uhr));

                $qry = db("UPDATE ".dba::get('f_posts')."
                   SET `nick`   = '".string::encode($_POST['nick'])."',
                       `email`  = '".string::encode($_POST['email'])."',
                       `text`   = '".string::encode($_POST['eintrag'])."',
                       `hp`     = '".links($_POST['hp'])."',
                       `edited` = '".addslashes($editedby)."'
                   WHERE id = '".convert::ToInt($_GET['id'])."'");

                $checkabo = db("SELECT s1.user,s1.fid,s2.nick,s2.id,s2.email FROM ".dba::get('f_abo')." AS s1
                        LEFT JOIN ".dba::get('users')." AS s2 ON s2.id = s1.user
                      WHERE s1.fid = '".$getp['sid']."'");
                while($getabo = _fetch($checkabo))
                {
                    if(userid() != $getabo['user'])
                    {
                        $topic = db("SELECT topic FROM ".dba::get('f_threads')." WHERE id = '".$getp['sid']."'");
                        $gettopic = _fetch($topic);

                        $entrys = cnt(dba::get('f_posts'), " WHERE `sid` = ".$getp['sid']);

                        if($entrys == "0") $pagenr = "1";
                        else $pagenr = ceil($entrys/settings('m_fposts'));

                        $subj = show(string::decode(settings('eml_fabo_pedit_subj')), array("titel" => $title));

                        $message = show(string::decode(settings('eml_fabo_pedit')), array("nick" => string::decode($getabo['nick']),
                                "postuser" => fabo_autor(),
                                "topic" => $gettopic['topic'],
                                "titel" => $title,
                                "domain" => $httphost,
                                "id" => $getp['sid'],
                                "entrys" => $entrys+1,
                                "page" => $pagenr,
                                "text" => bbcode::parse_html($_POST['eintrag']),
                                "clan" => $clanname));

                        mailmgr::AddContent($subj,$message);
                        mailmgr::AddAddress(string::decode($getabo['email']));
                    }
                }
                $entrys = cnt(dba::get('f_posts'), " WHERE `sid` = ".$getp['sid']);

                if($entrys == "0") $pagenr = "1";
                else $pagenr = ceil($entrys/settings('m_fposts'));

                $lpost = show(_forum_add_lastpost, array("id" => $entrys+1,
                        "tid" => $getp['sid'],
                        "page" => $pagenr));

                $index = info(_forum_editpost_successful, $lpost);
            }
        } else {
            $index = error(_error_wrong_permissions);
        }
    } elseif($_GET['do'] == "add") {
        if(settings("reg_forum") == "1" && checkme() == "unlogged")
        {
            $index = error(_error_unregistered);
        } else {
            if(!ipcheck("fid(".$_GET['kid'].")", settings('f_forum')))
            {
                $check = db("SELECT s2.id,s1.intern FROM ".dba::get('f_kats')." AS s1
                     LEFT JOIN ".dba::get('f_skats')." AS s2
                     ON s2.sid = s1.id
                     WHERE s2.id = '".convert::ToInt($_GET['kid'])."'");
                $checks = _fetch($check);
                if(forumcheck($_GET['id'], "closed"))
                {
                    $index = error(_error_forum_closed);
                } elseif($checks['intern'] == 1 && !permission("intforum") && !fintern($checks['id'])) {
                    $index = error(_error_no_access);
                } else {
                    if(userid() != 0)
                    {
                        $postnick = data(userid(), "nick");
                        $postemail = data(userid(), "email");
                    } else {
                        $postnick = "";
                        $postemail = "";
                    }
                    if($_GET['zitat'])
                    {
                        $qryzitat = db("SELECT nick,reg,text FROM ".dba::get('f_posts')."
                            WHERE id = '".convert::ToInt($_GET['zitat'])."'");
                        $getzitat = _fetch($qryzitat);

                        if($getzitat['reg'] == "0") $nick = $getzitat['nick'];
                        else                        $nick = autor($getzitat['reg']);

                        $zitat = bbcode::zitat($nick, $getzitat['text']);
                    } elseif($_GET['zitatt']) {
                        $qryzitat = db("SELECT t_nick,t_reg,t_text FROM ".dba::get('f_threads')."
                            WHERE id = '".convert::ToInt($_GET['zitatt'])."'");
                        $getzitat = _fetch($qryzitat);

                        if($getzitat['t_reg'] == "0") $nick = $getzitat['t_nick'];
                        else                          $nick = data($getzitat['t_reg'], "nick");

                        $zitat = bbcode::zitat($nick, $getzitat['t_text']);
                    } else {
                        $zitat = "";
                    }

                    $dowhat = show(_forum_dowhat_add_post, array("id" => $_GET['id'],
                            "kid" => $_GET['kid']));

                    $qryl = db("SELECT * FROM ".dba::get('f_posts')."
                      WHERE kid = '".convert::ToInt($_GET['kid'])."'
                      AND sid = '".convert::ToInt($_GET['id'])."'
                      ORDER BY date DESC");
                    if(_rows($qryl))
                    {
                        $getl = _fetch($qryl);

                        if(data($getl['reg'], "signatur")) $sig = _sig.bbcode::parse_html(data($getl['reg'], "signatur"));
                        else                               $sig = "";

                        if($getl['reg'] != "0") $userposts = show(_forum_user_posts, array("posts" => userstats($getl['reg'], "forumposts")));
                        else                    $userposts = "";

                        if($getl['reg'] == "0") $onoff = "";
                        else                    $onoff = onlinecheck($getl['reg']);

                        $text = bbcode::parse_html($getl['text']);

                        if(checkme() == "4") $posted_ip = $getl['ip'];
                        else              $posted_ip = _logged;

                        $titel = show(_eintrag_titel_forum, array("postid" => (cnt(dba::get('f_posts'), " WHERE sid =".convert::ToInt($_GET['id']))+1),
                                "datum" => date("d.m.Y", $getl['date']),
                                "zeit" => date("H:i", $getl['date'])._uhr,
                                "url" => '#',
                                "edit" => "",
                                "delete" => ""));
                        if($getl['reg'] != 0)
                        {
                            $qryu = db("SELECT nick,icq,hp,email FROM ".dba::get('users')."
                          WHERE id = '".$getl['reg']."'");
                            $getu = _fetch($qryu);

                            $email = show(_emailicon_forum, array("email" => eMailAddr($getu['email'])));
                            $pn = _forum_pn_preview;
                            if(empty($getu['icq']) || $getu['icq'] == 0) $icq = "";
                            else {
                                $uin = show(_icqstatus_forum, array("uin" => $getu['icq']));
                                $icq = '<a href="http://www.icq.com/whitepages/about_me.php?uin='.$getu['icq'].'" target="_blank">'.$uin.'</a>';
                            }

                            if(empty($getu['hp'])) $hp = "";
                            else $hp = show(_hpicon_forum, array("hp" => $getu['hp']));
                        } else {
                            $icq = "";
                            $pn = "";
                            $email = show(_emailicon_forum, array("email" => eMailAddr($getl['email'])));
                            if(empty($getl['hp'])) $hp = "";
                            else $hp = show(_hpicon_forum, array("hp" => $getl['hp']));
                        }

                        $lastpost = show($dir."/forum_posts_show", array("nick" => cleanautor($getl['reg'], '', $getl['nick'], $getl['email']),
                                "postnr" => "",
                                "text" => $text,
                                "status" => getrank($getl['reg']),
                                "avatar" => useravatar($getl['reg']),
                                "pn" => $pn,
                                "icq" => $icq,
                                "hp" => $hp,
                                "class" => 'class="commentsRight"',
                                "email" => $email,
                                "titel" => $titel,
                                "p" => ($i+($page-1)*settings('m_fposts')),
                                "ip" => $posted_ip,
                                "edited" => $getl['edited'],
                                "posts" => $userposts,
                                "date" => _posted_by.date("d.m.y H:i", $getl['date'])._uhr,
                                "signatur" => $sig,
                                "zitat" => _forum_zitat_preview,
                                "onoff" => $onoff,
                                "top" => "",
                                "lp" => cnt(dba::get('f_posts'), " WHERE sid = '".convert::ToInt($_GET['id'])."'")+1));
                    } else {
                        $qryt = db("SELECT * FROM ".dba::get('f_threads')."
                        WHERE kid = '".convert::ToInt($_GET['kid'])."'
                        AND id = '".convert::ToInt($_GET['id'])."'");
                        $gett = _fetch($qryt);

                        if(data($gett['t_reg'], "signatur")) $sig = _sig.bbcode::parse_html(data($gett['t_reg'], "signatur"));
                        else $sig = "";

                        if($gett['t_reg'] != "0")
                            $userposts = show(_forum_user_posts, array("posts" => userstats($gett['t_reg'], "forumposts")));
                        else $userposts = "";

                        if($gett['t_reg'] == "0") $onoff = "";
                        else                      $onoff = onlinecheck($gett['t_reg']);

                        $ftxt = hl($gett['t_text'], $_GET['hl']);
                        if($_GET['hl']) $text = bbcode::parse_html($ftxt['text']);
                        else $text = bbcode::parse_html($gett['t_text']);

                        if(checkme() == "4") $posted_ip = $gett['ip'];
                        else                 $posted_ip = _logged;

                        $titel = show(_eintrag_titel_forum, array("postid" => "1",
                                "datum" => date("d.m.Y", $gett['t_date']),
                                "zeit" => date("H:i", $gett['t_date'])._uhr,
                                "url" => '#',
                                "edit" => "",
                                "delete" => ""));
                        if($gett['t_reg'] != 0)
                        {
                            $qryu = db("SELECT nick,icq,hp,email FROM ".dba::get('users')."
                          WHERE id = '".$gett['t_reg']."'");
                            $getu = _fetch($qryu);

                            $email = show(_emailicon_forum, array("email" => eMailAddr($getu['email'])));
                            $pn = show(_pn_write_forum, array("id" => $gett['t_reg'],
                                    "nick" => $getu['nick']));
                            if(empty($getu['icq']) || $getu['icq'] == 0) $icq = "";
                            else {
                                $uin = show(_icqstatus_forum, array("uin" => $getu['icq']));
                                $icq = '<a href="http://www.icq.com/whitepages/about_me.php?uin='.$getu['icq'].'" target="_blank">'.$uin.'</a>';
                            }

                            if(empty($getu['hp'])) $hp = "";
                            else $hp = show(_hpicon_forum, array("hp" => $getu['hp']));
                        } else {
                            $icq = "";
                            $pn = "";
                            $email = show(_emailicon_forum, array("email" => eMailAddr($gett['t_email'])));
                            if(empty($gett['t_hp'])) $hp = "";
                            else $hp = show(_hpicon_forum, array("hp" => $gett['t_hp']));
                        }

                        $lastpost = show($dir."/forum_posts_show", array("nick" => cleanautor($gett['t_reg'], '', $gett['t_nick'], $gett['t_email']),
                                "postnr" => "",
                                "text" => $text,
                                "status" => getrank($gett['t_reg']),
                                "avatar" => useravatar($gett['t_reg']),
                                "pn" => $pn,
                                "icq" => $icq,
                                "class" => $ftxt['class'],
                                "hp" => $hp,
                                "email" => $email,
                                "titel" => $titel,
                                "ip" => $posted_ip,
                                "p" => ($i+($page-1)*settings('m_fposts')),
                                "edited" => $gett['edited'],
                                "posts" => $userposts,
                                "date" => _posted_by.date("d.m.y H:i", $gett['t_date'])._uhr,
                                "signatur" => $sig,
                                "zitat" => "",
                                "onoff" => $onoff,
                                "top" => "",
                                "lp" => cnt(dba::get('f_posts'), " WHERE sid = '".convert::ToInt($_GET['id'])."'")+1));
                    }

                    if(userid() != 0)
                        $form = show("page/editor_regged", array("nick" => autor()));
                    else
                        $form = show("page/editor_notregged", array("postemail" => "", "posthp" => "", "postnick" => ""));

                    $title = string::decode($gett['topic']).' - '.$title;
                    $index = show($dir."/post", array("titel" => _forum_new_post_head,
                            "nickhead" => _nick,
                            "emailhead" => _email,
                            "id" => $_GET['id'],
                            "kid" => $_GET['kid'],
                            "zitat" => $zitat,
                            "last_post" => _forum_lp_head,
                            "preview" => _preview,
                            "lastpost" => $lastpost,
                            "form" => $form,
                            "br1" => "",
                            "security" => _register_confirm,
                            "ip" => _iplog_info,
                            "br2" => "",
                            "what" => _button_value_add,
                            "kid" => $_GET['kid'],
                            "id" => $_GET['id'],
                            "dowhat" => $dowhat,
                            "eintraghead" => _eintrag,
                            "error" => "",
                            "postnick" => $postnick,
                            "postemail" => $postemail,
                            "posthp" => $posthp,
                            "posteintrag" => ""));
                }
            } else {
                $index = error(show(_error_flood_post, array("sek" => settings('f_forum'))));
            }
        }
    } elseif($_GET['do'] == "addpost") {
        $qry_thread = db("SELECT `id`,`kid` FROM ".dba::get('f_threads')." WHERE `id` = '".convert::ToInt($_GET['id'])."'");
        if(_rows($qry_thread) == 0)
        {
            $index = error(_id_dont_exist);
        } else {
            if(settings("reg_forum") == "1" && checkme() == "unlogged")
            {
                $index = error(_error_unregistered);
            } else {
                $get_threadkid = _fetch($qry_thread);
                $check = db("SELECT s2.id,s1.intern FROM ".dba::get('f_kats')." AS s1
                                         LEFT JOIN ".dba::get('f_skats')." AS s2
                                         ON s2.sid = s1.id
                                         WHERE s2.id = '".convert::ToInt($_GET['kid'])."'");
                $checks = _fetch($check);

                if($checks['intern'] == 1 && !permission("intforum") && !fintern($checks['id']))
                    exit;

                if(userid() != 0) $toCheck = empty($_POST['eintrag']);
                else $toCheck = empty($_POST['nick']) || empty($_POST['email']) || empty($_POST['eintrag']) || !check_email($_POST['email']) || !$securimage->check($_POST['secure']);

                if($toCheck)
                {
                    if(userid() != 0)
                    {
                        if(empty($_POST['eintrag'])) $error = _empty_eintrag;
                        $form = show("page/editor_regged", array("nick" => autor()));
                    } else {
                        if(!$securimage->check($_POST['secure'])) $error = captcha_mathematic ? _error_invalid_regcode_mathematic : _error_invalid_regcode;
                        elseif(empty($_POST['nick'])) $error = _empty_nick;
                        elseif(empty($_POST['email'])) $error = _empty_email;
                        elseif(!check_email($_POST['email'])) $error = _error_invalid_email;
                        else if(check_email_trash_mail($_POST['email'])) $error = _error_trash_mail;
                        elseif(empty($_POST['eintrag'])) $error = _empty_eintrag;
                        $form = show("page/editor_notregged", array("postemail" => "", "posthp" => "", "postnick" => ""));
                    }

                    $error = show("errors/errortable", array("error" => $error));
                    $dowhat = show(_forum_dowhat_add_post, array("id" => $_GET['id'],
                            "kid" => $get_threadkid['kid']));
                    $qryl = db("SELECT * FROM ".dba::get('f_posts')."
                                            WHERE kid = '".convert::ToInt($get_threadkid['kid'])."'
                                            AND sid = '".convert::ToInt($_GET['id'])."'
                                            ORDER BY date DESC");
                    if(_rows($qryl))
                    {
                        $getl = _fetch($qryl);

                        if(data($getl['reg'], "signatur")) $sig = _sig.bbcode::parse_html(data($getl['reg'], "signatur"));
                        else $sig = "";

                        if($getl['reg'] != "0") $userposts = show(_forum_user_posts, array("posts" => userstats($getl['reg'], "forumposts")));
                        else $userposts = "";

                        if($getl['reg'] == "0") $onoff = "";
                        else $onoff = onlinecheck($getl['reg']);

                        $ftxt = hl($getl['text'], $_GET['hl']);
                        if($_GET['hl']) $text = bbcode::parse_html($ftxt['text']);
                        else $text = bbcode::parse_html($getl['text']);

                        if(checkme() == "4") $posted_ip = $getl['ip'];
                        else $posted_ip = _logged;

                        $titel = show(_eintrag_titel_forum, array("postid" => (cnt(dba::get('f_posts'), " WHERE sid = ".convert::ToInt($_GET['id']))+1),
                                "datum" => date("d.m.Y", $getl['date']),
                                "zeit" => date("H:i", $getl['date'])._uhr,
                                "url" => '#',
                                "edit" => "",
                                "delete" => ""));

                        if($getl['reg'] != 0)
                        {
                            $qryu = db("SELECT nick,icq,hp,email FROM ".dba::get('users')."
                                                    WHERE id = '".$getl['reg']."'");
                            $getu = _fetch($qryu);

                            $email = show(_emailicon_forum, array("email" => eMailAddr($getu['email'])));
                            $pn = show(_pn_write_forum, array("id" => $getl['reg'],
                                    "nick" => $getu['nick']));
                            if(empty($getu['icq']) || $getu['icq'] == 0) $icq = "";
                            else {
                                $uin = show(_icqstatus_forum, array("uin" => $getu['icq']));
                                $icq = '<a href="http://www.icq.com/whitepages/about_me.php?uin='.$getu['icq'].'" target="_blank">'.$uin.'</a>';
                            }

                            if(empty($getu['hp'])) $hp = "";
                            else $hp = show(_hpicon_forum, array("hp" => $getu['hp']));
                        } else {
                            $icq = "";
                            $pn = "";
                            $email = show(_emailicon_forum, array("email" => eMailAddr($getl['email'])));
                            if(empty($getl['hp'])) $hp = "";
                            else $hp = show(_hpicon_forum, array("hp" => $getl['hp']));
                        }

                        $nick = autor($getl['reg'], '', $getl['nick'], $getl['email']);
                        if(!empty($_GET['hl']) && $_SESSION['search_type'] == 'autor')
                        {
                            if(preg_match("#".$_GET['hl']."#i",$nick)) $ftxt['class'] = 'class="highlightSearchTarget"';
                        }

                        $lastpost = show($dir."/forum_posts_show", array("nick" => $nick,
                                "postnr" => "",
                                "text" => $text,
                                "status" => getrank($getl['reg']),
                                "avatar" => useravatar($getl['reg']),
                                "titel" => $titel,
                                "pn" => $pn,
                                "icq" => $icq,
                                "hp" => $hp,
                                "class" => $ftxt['class'],
                                "email" => $email,
                                "ip" => $posted_ip,
                                "p" => ($i+($page-1)*settings('m_fposts')),
                                "edited" => $getl['edited'],
                                "posts" => $userposts,
                                "signatur" => $sig,
                                "zitat" => "",
                                "onoff" => $onoff,
                                "top" => "",
                                "lp" => cnt(dba::get('f_posts'), " WHERE sid = '".convert::ToInt($_GET['id'])."'")+1));
                    } else {
                        $qryt = db("SELECT * FROM ".dba::get('f_threads')."
                                                WHERE kid = '".convert::ToInt($get_threadkid['kid'])."'
                                                AND id = '".convert::ToInt($_GET['id'])."'");
                        $gett = _fetch($qryt);

                        if(data($gett['t_reg'], "signatur")) $sig = _sig.bbcode::parse_html(data($gett['t_reg'], "signatur"));
                        else $sig = "";

                        if($gett['t_reg'] != "0") $userposts = show(_forum_user_posts, array("posts" => userstats($gett['t_reg'], "forumposts")));
                        else $userposts = "";

                        if($gett['t_reg'] == "0") $onoff = "";
                        else $onoff = onlinecheck($gett['t_reg']);

                        $ftxt = hl($gett['t_text'], $_GET['hl']);
                        if($_GET['hl']) $text = bbcode::parse_html($ftxt['text']);
                        else $text = bbcode::parse_html($gett['t_text']);

                        if(checkme() == "4") $posted_ip = $gett['ip'];
                        else $posted_ip = _logged;

                        if($gett['t_reg'] != 0)
                        {
                            $qryu = db("SELECT nick,icq,hp,email FROM ".dba::get('users')."
                                                    WHERE id = '".$gett['t_reg']."'");
                            $getu = _fetch($qryu);

                            $email = show(_emailicon_forum, array("email" => eMailAddr($getu['email'])));
                            $pn = show(_pn_write_forum, array("id" => $gett['t_reg'],
                                    "nick" => $getu['nick']));
                            if(empty($getu['icq']) || $getu['icq'] == 0) $icq = "";
                            else {
                                $uin = show(_icqstatus_forum, array("uin" => $getu['icq']));
                                $icq = '<a href="http://www.icq.com/whitepages/about_me.php?uin='.$getu['icq'].'" target="_blank">'.$uin.'</a>';
                            }

                            if(empty($getu['hp'])) $hp = "";
                            else $hp = show(_hpicon_forum, array("hp" => $getu['hp']));
                        } else {
                            $icq = "";
                            $pn = "";
                            $email = show(_emailicon_forum, array("email" => eMailAddr($gett['t_email'])));
                            if(empty($gett['t_hp'])) $hp = "";
                            else $hp = show(_hpicon_forum, array("hp" => $gett['t_hp']));
                        }

                        $nick = autor($gett['t_reg'], '', $gett['t_nick'], $gett['t_email']);
                        if(!empty($_GET['hl']) && $_SESSION['search_type'] == 'autor')
                        {
                            if(preg_match("#".$_GET['hl']."#i",$nick)) $ftxt['class'] = 'class="highlightSearchTarget"';
                        }

                        $lastpost = show($dir."/forum_posts_show", array("nick" => $nick,
                                "postnr" => "",
                                "text" => $text,
                                "status" => getrank($gett['t_reg']),
                                "avatar" => useravatar($gett['t_reg']),
                                "ip" => $posted_ip,
                                "pn" => $pn,
                                "class" => $ftxt['class'],
                                "icq" => $icq,
                                "hp" => $hp,
                                "email" => $email,
                                "edit" => "",
                                "p" => ($i+($page-1)*settings('m_fposts')),
                                "delete" => "",
                                "edited" => $gett['edited'],
                                "posts" => $userposts,
                                "date" => _posted_by.date("d.m.y H:i", $gett['t_date'])._uhr,
                                "signatur" => $sig,
                                "zitat" => "",
                                "onoff" => $onoff,
                                "top" => "",
                                "lp" => cnt(dba::get('f_posts'), " WHERE sid = '".convert::ToInt($_GET['id'])."'")+1));
                    }

                    $index = show($dir."/post", array("titel" => _forum_new_post_head,
                            "nickhead" => _nick,
                            "emailhead" => _email,
                            "zitat" => $zitat,
                            "what" => _button_value_add,
                            "preview" => _preview,
                            "form" => $form,
                            "br1" => "",
                            "br2" => "",
                            "security" => _register_confirm,
                            "lastpost" => $lastpost,
                            "last_post" => _forum_lp_head,
                            "dowhat" => $dowhat,
                            "id" => $_GET['id'],
                            "ip" => _iplog_info,
                            "kid" => $_GET['kid'],
                            "postemail" => $_POST['email'],
                            "posthp" => $_POST['hp'],
                            "postnick" => string::decode($_POST['nick']),
                            "posteintrag" => string::decode($_POST['eintrag']),
                            "error" => $error,
                            "eintraghead" => _eintrag));
                } else {
                    $spam = 0;
                    $qrydp = db("SELECT * FROM ".dba::get('f_posts')."
                                             WHERE kid = '".convert::ToInt($get_threadkid['kid'])."'
                                             AND sid = '".convert::ToInt($_GET['id'])."'
                                             ORDER BY date DESC
                                             LIMIT 1");
                    if(_rows($qrydp))
                    {
                        $getdp = _fetch($qrydp);

                        if(userid() != 0)
                        {
                            if(userid() == $getdp['reg'] && settings('double_post')) $spam = 1;
                            else $spam = 0;
                        } else {
                            if($_POST['nick'] == $getdp['nick'] && settings('double_post')) $spam = 1;
                            else $spam = 0;
                        }
                    } else {

                        $qrytdp = db("SELECT * FROM ".dba::get('f_threads')."
                                    WHERE kid = '".convert::ToInt($get_threadkid['kid'])."'
                                    AND id = '".convert::ToInt($_GET['id'])."'");
                        $gettdp = _fetch($qrytdp);

                        if(userid() != 0)
                        {
                            if(userid() == $gettdp['t_reg'] && settings('double_post')) $spam = 2;
                            else $spam = 0;
                        } else {
                            if($_POST['nick'] == $gettdp['t_nick'] && settings('double_post')) $spam = 2;
                            else $spam = 0;
                        }
                    }

                    if($spam == 1)
                    {
                        if(userid() != 0) $fautor = autor();
                        else $fautor = autor('', '', $_POST['nick'], $_POST['email']);

                        $text = show(_forum_spam_text, array("autor" => $fautor,
                                "ltext" => addslashes($getdp['text']),
                                "ntext" => string::encode($_POST['eintrag'])));

                        $qry = db("UPDATE ".dba::get('f_threads')."
                                                                                         SET `lp` = '".time()."'
                                    WHERE kid = '".convert::ToInt($_GET['kid'])."'
                                    AND id = '".convert::ToInt($_GET['id'])."'");

                        $qry = db("UPDATE ".dba::get('f_posts')."
                                                 SET `date`   = '".time()."',
                                                         `text`   = '".$text."'
                                                 WHERE id = '".$getdp['id']."'");
                    } elseif($spam == 2) {
                        if(userid() != 0) $fautor = autor();
                        else $fautor = autor('', '', $_POST['nick'], $_POST['email']);

                        $text = show(_forum_spam_text, array("autor" => $fautor,
                                "ltext" => addslashes($gettdp['t_text']),
                                "ntext" => string::encode($_POST['eintrag'])));

                        $qry = db("UPDATE ".dba::get('f_threads')."
                                                 SET `lp`   = '".time()."',
                                                 `t_text`   = '".$text."'
                                                 WHERE id = '".$gettdp['id']."'");
                    } else {
                        $qry = db("INSERT INTO ".dba::get('f_posts')."
                                         SET `kid`   = '".convert::ToInt($get_threadkid['kid'])."',
                                                 `sid`   = '".convert::ToInt($_GET['id'])."',
                                                 `date`  = '".time()."',
                                                 `nick`  = '".string::encode($_POST['nick'])."',
                                                 `email` = '".string::encode($_POST['email'])."',
                                                 `hp`    = '".links($_POST['hp'])."',
                                                 `reg`   = '".userid()."',
                                                 `text`  = '".string::encode($_POST['eintrag'])."',
                                                 `ip`    = '".visitorIp()."'");

                       db("UPDATE ".dba::get('f_threads')." SET `lp`    = '".time()."', `first` = '0' WHERE id    = '".convert::ToInt($_GET['id'])."'");
                    }

                    wire_ipcheck("fid(".$get_threadkid['kid'].")");

                    $update = db("UPDATE ".dba::get('userstats')."
                                                SET `forumposts` = forumposts+1
                                                WHERE `user`       = '".userid()."'");

                    $checkabo = db("SELECT s1.user,s1.fid,s2.nick,s2.id,s2.email FROM ".dba::get('f_abo')." AS s1
                                    LEFT JOIN ".dba::get('users')." AS s2 ON s2.id = s1.user
                                                    WHERE s1.fid = '".convert::ToInt($_GET['id'])."'");
                    while($getabo = _fetch($checkabo))
                    {
                        if(userid() != $getabo['user'])
                        {
                            $topic = db("SELECT topic FROM ".dba::get('f_threads')." WHERE id = '".convert::ToInt($_GET['id'])."'");
                            $gettopic = _fetch($topic);

                            $entrys = cnt(dba::get('f_posts'), " WHERE `sid` = ".convert::ToInt($_GET['id']));

                            if($entrys == "0") $pagenr = "1";
                            else $pagenr = ceil($entrys/settings('m_fposts'));

                            $subj = show(string::decode(settings('eml_fabo_npost_subj')), array("titel" => $title));

                            $message = show(string::decode(settings('eml_fabo_npost')), array("nick" => string::decode($getabo['nick']),
                                    "postuser" => fabo_autor(),
                                    "topic" => $gettopic['topic'],
                                    "titel" => $title,
                                    "domain" => $httphost,
                                    "id" => convert::ToInt($_GET['id']),
                                    "entrys" => $entrys+1,
                                    "page" => $pagenr,
                                    "text" => bbcode::parse_html($_POST['eintrag']),
                                    "clan" => $clanname));

                            mailmgr::AddContent($subj,$message);
                            mailmgr::AddAddress(string::decode($getabo['email']));
                        }
                    }

                    $entrys = cnt(dba::get('f_posts'), " WHERE `sid` = ".convert::ToInt($_GET['id']));

                    if($entrys == "0") $pagenr = "1";
                    else $pagenr = ceil($entrys/settings('m_fposts'));

                    $lpost = show(_forum_add_lastpost, array("id" => $entrys+1,
                            "tid" => $_GET['id'],
                            "page" => $pagenr));

                    $index = info(_forum_newpost_successful, $lpost);
                }
            }
        }
    } elseif($_GET['do'] == "delete") {
        $qry = db("SELECT * FROM ".dba::get('f_posts')."
               WHERE id = '".convert::ToInt($_GET['id'])."'");
        $get = _fetch($qry);

        if($get['reg'] == userid() || permission("forum"))
        {
            $del = db("DELETE FROM ".dba::get('f_posts')."
                 WHERE id = '".convert::ToInt($_GET['id'])."'");

            $fposts = userstats($get['reg'], "forumposts")-1;
            $upd = db("UPDATE ".dba::get('userstats')."
                 SET `forumposts` = '".convert::ToInt($fposts)."'
                 WHERE user = '".$get['reg']."'");

            $entrys = cnt(dba::get('f_posts'), " WHERE `sid` = ".$get['sid']);

            if($entrys == "0")
            {
                $pagenr = "1";
                $update = db("UPDATE ".dba::get('f_threads')."
                      SET `first` = '1'
                      WHERE kid = '".$get['kid']."'");
            } else {
                $pagenr = ceil($entrys/settings('m_fposts'));
            }

            $lpost = show(_forum_add_lastpost, array("id" => $entrys+1,
                    "tid" => $get['sid'],
                    "page" => $pagenr));

            $index = info(_forum_delpost_successful, $lpost);
        }
    }
}