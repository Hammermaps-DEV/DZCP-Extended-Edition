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
else if(!isset($_GET['id']) || empty($_GET['id']) || !db("SELECT id FROM ".dba::get('artikel')." WHERE id = ".$artikel_id=convert::ToInt($_GET['id']),true))
    $index = error(_id_dont_exist);
else
{
    $flood_artikelcom = config('f_artikelcom');
    $check = db("SELECT public FROM ".dba::get('artikel')." WHERE id = ".$artikel_id,false,true);
    if(!permission("artikel") && !$check['public'])
        $index = error(_error_wrong_permissions);
    else
    {
        #################################### do case ####################################
        $error = '';
        $get = db("SELECT comments FROM ".dba::get('artikel')." WHERE id = '".$artikel_id."'",false,true);
        if($get['comments'])
        {
            switch($do)
            {
                case 'add':
                    if(db("SELECT `id` FROM ".dba::get('artikel')." WHERE `id` = '".$artikel_id."'",true) != 0)
                    {
                        if(settings("reg_artikel") == "1" && checkme() == "unlogged")
                            $index = error(_error_have_to_be_logged);
                        else
                        {
                            if(!ipcheck("artid(".$artikel_id.")", config('f_artikelcom')))
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
                                    db("INSERT INTO ".dba::get('acomments')."
                                       SET `artikel`  = '".$artikel_id."',
                                           `datum`    = '".time()."',
                                           ".(isset($_POST['email']) ? "`email` = '".string::encode($_POST['email'])."'," : '')."
                                           ".(isset($_POST['nick']) ? "`nick` = '".string::encode($_POST['nick'])."'," : '')."
                                           ".(isset($_POST['hp']) ? "`hp` = '".links($_POST['hp'])."'," : '')."
                                           `editby`   = '',
                                           `reg`      = '".userid()."',
                                           `comment`  = '".string::encode($_POST['comment'])."',
                                           `ip`       = '".visitorIp()."'");

                                    wire_ipcheck("artid(".$artikel_id.")");
                                    $index = info(_comment_added, "?action=show&amp;id=".$artikel_id."");
                                }
                            }
                            else
                                $index = error(show(_error_flood_post, array("sek" => $flood_artikelcom)));
                        }
                    }
                    else
                        $index = error(_id_dont_exist);
                break;
                case 'edit':
                    $get = db("SELECT * FROM ".dba::get('acomments')." WHERE id = '".convert::ToInt($_GET['cid'])."'",false,true);
                    if($get['reg'] == userid() || permission('artikel'))
                    {
                        if($get['reg'] != 0)
                            $form = show("page/editor_regged", array("nick" => autor($get['reg'])));
                        else
                            $form = show("page/editor_notregged", array("postemail" => $get['email'], "posthp" => links($get['hp']), "postnick" => string::decode($get['nick'])));

                        $index = show("page/comments_add", array("titel" => _comments_edit,
                                "nickhead" => _nick,
                                "emailhead" => _email,
                                "sec" => $dir,
                                "security" => _register_confirm,
                                "hphead" => _hp,
                                "form" => $form,
                                "preview" => _preview,
                                "prevurl" => '../artikel/?action=compreview&amp;do=edit&amp;id='.$artikel_id.'&amp;cid='.$_GET['cid'],
                                "action" => '?action=show&amp;do=editcom&amp;id='.$artikel_id.'&amp;cid='.$_GET['cid'],
                                "ip" => _iplog_info,
                                "id" => $artikel_id,
                                "what" => _button_value_edit,
                                "show" => "",
                                "posteintrag" => string::decode($get['comment']),
                                "error" => "",
                                "eintraghead" => _eintrag));
                    }
                    else
                        $index = error(_error_edit_post);
                break;
                case 'editcom':
                    $get = db("SELECT reg FROM ".dba::get('acomments')." WHERE id = '".convert::ToInt($_GET['cid'])."'",false,true);
                    if($get['reg'] == userid() || permission('artikel'))
                    {
                        $editedby = show(_edited_by, array("autor" => autor(), "time" => date("d.m.Y H:i", time())._uhr));
                        db("UPDATE ".dba::get('acomments')." SET
                               ".(isset($_POST['nick']) ? " `nick`     = '".string::encode($_POST['nick'])."'," : "")."
                               ".(isset($_POST['email']) ? " `email`   = '".string::encode($_POST['email'])."'," : "")."
                               ".(isset($_POST['hp']) ? " `hp`         = '".links($_POST['hp'])."'," : "")."
                               `comment`  = '".string::encode($_POST['comment'])."',
                               `editby`   = '".addslashes($editedby)."'
                           WHERE id = '".convert::ToInt($_GET['cid'])."'");

                        $index = info(_comment_edited, "?action=show&amp;id=".$artikel_id."");
                    }
                    else
                        $index = error(_error_edit_post);
                break;
                case 'delete':
                    $get = db("SELECT reg FROM ".dba::get('acomments')." WHERE id = '".convert::ToInt($_GET['cid'])."'",false,true);
                    if($get['reg'] == userid() || permission('artikel'))
                    {
                        db("DELETE FROM ".dba::get('acomments')." WHERE id = '".convert::ToInt($_GET['cid'])."'");
                        $index = info(_comment_deleted, "?action=show&amp;id=".$artikel_id."");
                    }
                    else
                        $index = error(_error_wrong_permissions);
                break;
            }
        }

        #################################### SHOW ####################################
        if(empty($index))
        {
            //Update viewed
            if(count_clicks('artikel',$artikel_id))
                db("UPDATE ".dba::get('artikel')." SET `viewed` = viewed+1 WHERE id = '".$artikel_id."'");

            $get = db("SELECT * FROM ".dba::get('artikel')." WHERE id = '".$artikel_id."'",false,true);
            $links1 = (!empty($get['url1']) ? show(_artikel_link, array("link" => string::decode($get['link1']), "url" => $get['url1'])) : '');
            $links2 = (!empty($get['url2']) ? show(_artikel_link, array("link" => string::decode($get['link2']), "url" => $get['url2'])) : '');
            $links3 = (!empty($get['url3']) ? show(_artikel_link, array("link" => string::decode($get['link3']), "url" => $get['url3'])) : '');
            $links = (!empty($links1) || !empty($links2) || !empty($links3) ? show(_artikel_links, array("link1" => $links1, "link2" => $links2, "link3" => $links3, "rel" => _related_links)) : '');

            $getkat = db("SELECT katimg FROM ".dba::get('newskat')." WHERE id = '".convert::ToInt($get['kat'])."'",false,true);
            $artikelimage = '../inc/images/uploads/newskat/'.string::decode($getkat['katimg']);
            if($get['custom_image'])
            {
                foreach($picformat AS $end)
                {
                    if(file_exists(basePath.'/inc/images/uploads/news/'.$get['id'].'.'.$end))
                        break;
                }

                if(file_exists(basePath.'/inc/images/uploads/news/'.$get['id'].'.'.$end))
                    $artikelimage = '../inc/images/uploads/news/'.$get['id'].'.'.$end;
            }

            if($get['comments'])
            {
                $entrys = cnt(dba::get('acomments'), " WHERE artikel = ".$artikel_id);
                $i = $entrys-($page - 1)*($maxcomments=config('m_comments'));

                $qryc = db("SELECT * FROM ".dba::get('acomments')." WHERE artikel = ".$artikel_id." ORDER BY datum DESC LIMIT ".($page - 1)*$maxcomments.",".$maxcomments.""); $comments = '';
                while($getc = _fetch($qryc))
                {
                    $edit = ""; $delete = ""; $hp = ""; $email = ""; $onoff = "";
                    if((checkme() != 'unlogged' && $getc['reg'] == userid()) || permission("artikel"))
                    {
                        $edit = show("page/button_edit_single", array("id" => $get['id'], "action" => "action=show&amp;do=edit&amp;cid=".$getc['id'], "title" => _button_title_edit));
                        $delete = show("page/button_delete_single", array("id" => $artikel_id, "action" => "action=show&amp;do=delete&amp;cid=".$getc['id'], "title" => _button_title_del, "del" => _confirm_del_entry));
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
                                                                  "editby" => bbcode::parse_html($getc['editby']),
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
                    $comments = show("page/comments_no_entry");

                if(settings("reg_artikel") == "1" && checkme() == "unlogged")
                    $add = _error_unregistered_nc;
                else
                {
                    if(userid() != 0)
                        $form = show("page/editor_regged", array("nick" => autor()));
                    else
                        $form = show("page/editor_notregged", array("postemail" => "", "posthp" => "", "postnick" => ""));

                    $add = '';
                    if(!ipcheck("artid(".$artikel_id.")", $flood_artikelcom))
                    {
                        $add = show("page/comments_add", array( "titel" => _artikel_comments_write_head,
                                                                "form" => $form,
                                                                "what" => _button_value_add,
                                                                "ip" => _iplog_info,
                                                                "preview" => _preview,
                                                                "sec" => $dir,
                                                                "security" => _register_confirm,
                                                                "action" => '?action=show&amp;do=add&amp;id='.$artikel_id,
                                                                "prevurl" => '../artikel/?action=compreview&id='.$artikel_id,
                                                                "postemail" => (isset($_POST['email']) && !empty($error) ? $_POST['email'] : ''),
                                                                "posthp" => (isset($_POST['hp']) && !empty($error) ? $_POST['hp'] : ''),
                                                                "postnick" => (isset($_POST['nick']) && !empty($error) ? string::decode($_POST['nick']) : ''),
                                                                "posteintrag" => (isset($_POST['comment']) && !empty($error) ? string::decode($_POST['comment']) : ''),
                                                                "error" => $error));
                    }
                }

                $seiten = nav($entrys,$maxcomments,"?action=show&amp;id=".$artikel_id."");
                $showmore = show($dir."/comments",array("head" => _comments_head, "show" => $comments, "seiten" => $seiten, "icq" => "", "add" => $add));
            }
            else
                $showmore = show("page/comments_no_enabled");

            $index = show($dir."/show_more", array("titel" => string::decode($get['titel']),
                                                    "id" => $get['id'],
                                                    "comments" => "",
                                                    "display" => "inline",
                                                    "artikelimage" => $artikelimage,
                                                    "showmore" => $showmore,
                                                    "icq" => "",
                                                    "text" => bbcode::parse_html($get['text']),
                                                    "datum" => date("j.m.y H:i", convert::ToInt($get['datum']))._uhr,
                                                    "links" => $links,
                                                    "autor" => autor($get['autor']) ));
        }
    }
}