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
else if(!isset($_GET['id']) || empty($_GET['id']) || !db("SELECT id FROM ".dba::get('downloads')." WHERE id = ".$dl_id=convert::ToInt($_GET['id']),true))
    $index = error(show(_id_dont_exist_dl,array('id' => $dl_id)));
else
{
    if(settings("reg_dl") == 1 && checkme() == "unlogged")
        $index = error(_error_unregistered);
    else
    {
        $downloadcomconfig = settings(array('f_downloadcom','m_comments'));
        #################################### do case ####################################
        $error = '';
        $get = db("SELECT comments FROM ".dba::get('downloads')." WHERE id = '".$dl_id."'",false,true);
        if($get['comments'])
        {
            switch($do)
            {
                case 'add':
                    if(db("SELECT `id` FROM ".dba::get('downloads')." WHERE `id` = '".$dl_id."'",true) != 0)
                    {
                        if(settings("reg_dlcomments") && checkme() == "unlogged")
                            $index = error(_error_have_to_be_logged);
                        else
                        {
                            if(!ipcheck("dlid(".$dl_id.")", $downloadcomconfig['f_downloadcom']))
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
                                    db("INSERT INTO ".dba::get('dl_comments')."
                                       SET `download`     = '".$dl_id."',
                                           `datum`    = '".time()."',
                                           ".(isset($_POST['email']) ? "`email` = '".string::encode($_POST['email'])."'," : '')."
                                           ".(isset($_POST['nick']) ? "`nick` = '".string::encode($_POST['nick'])."'," : '')."
                                           ".(isset($_POST['hp']) ? "`hp` = '".links($_POST['hp'])."'," : '')."
                                           `editby`   = '',
                                           `reg`      = '".userid()."',
                                           `comment`  = '".string::encode($_POST['comment'])."',
                                           `ip`       = '".visitorIp()."'");

                                    wire_ipcheck("dlid(".$dl_id.")");
                                    $index = info(_comment_added, "?action=download&amp;id=".$dl_id."");
                                }
                            }
                            else
                                $index = error(show(_error_flood_post, array("sek" => $downloadcomconfig['f_downloadcom'])));
                        }
                    }
                    else
                        $index = error(_id_dont_exist);
                    break;
                case 'edit':
                    $get = db("SELECT * FROM ".dba::get('dl_comments')." WHERE id = '".convert::ToInt($_GET['cid'])."'",false,true);
                    if($get['reg'] == userid() || permission('downloads'))
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
                                "prevurl" => '../downloads/?action=compreview&amp;do=edit&amp;id='.$dl_id.'&amp;cid='.$_GET['cid'],
                                "action" => '?action=download&amp;do=editcom&amp;id='.$dl_id.'&amp;cid='.$_GET['cid'],
                                "ip" => _iplog_info,
                                "id" => $dl_id,
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
                    $get = db("SELECT reg FROM ".dba::get('dl_comments')." WHERE id = '".convert::ToInt($_GET['cid'])."'",false,true);
                    if($get['reg'] == userid() || permission('downloads'))
                    {
                        $editedby = show(_edited_by, array("autor" => autor(), "time" => date("d.m.Y H:i", time())._uhr));
                        db("UPDATE ".dba::get('dl_comments')." SET
                                ".(isset($_POST['nick']) ? " `nick`     = '".string::encode($_POST['nick'])."'," : "")."
                                ".(isset($_POST['email']) ? " `email`   = '".string::encode($_POST['email'])."'," : "")."
                                ".(isset($_POST['hp']) ? " `hp`         = '".links($_POST['hp'])."'," : "")."
                               `comment`  = '".string::encode($_POST['comment'])."',
                               `editby`   = '".addslashes($editedby)."'
                           WHERE id = '".convert::ToInt($_GET['cid'])."'");

                        $index = info(_comment_edited, "?action=download&amp;id=".$dl_id."");
                    }
                    else
                        $index = error(_error_edit_post);
                    break;
                case 'delete':
                    $get = db("SELECT reg FROM ".dba::get('dl_comments')." WHERE id = '".convert::ToInt($_GET['cid'])."'",false,true);
                    if($get['reg'] == userid() || permission('downloads'))
                    {
                        db("DELETE FROM ".dba::get('dl_comments')." WHERE id = '".convert::ToInt($_GET['cid'])."'");
                        $index = info(_comment_deleted, "?action=download&amp;id=".$dl_id."");
                    }
                    else
                        $index = error(_error_wrong_permissions);
                break;
            }
        }

        #################################### SHOW ####################################
        if(empty($index))
        {
            $get = db("SELECT * FROM ".dba::get('downloads')." WHERE id = '".$dl_id."'",false,true);
            $size = filesize_extended(basePath.'/'.$get['url']);
            $getfile = show(_dl_getfile, array("file" => string::decode($get['download'])));

            if(!$size)
            { $dlsize = $traffic = 'n/a'; $br1 = '<!--'; $br2 = '-->'; }
            else
            {
                if(strlen(@round(($size/1048576)*$get['hits'],0)) >= 4)
                    $traffic = @round(($size/1073741824)*$get['hits'],2).' GB';
                else
                    $traffic = @round(($size/1048576)*$get['hits'],2).' MB';

                $dlsize = round($size/1048576,2).' MB ('.round($size/1024,2).' KB)';
                $br1 = '';
                $br2 = '';
            }

            //////////////////////////////////////////////////////////////////////////////////////////

            if($get['comments'])
            {
                $entrys = cnt(dba::get('dl_comments'), " WHERE download = ".$dl_id);
                $i = $entrys-($page - 1)*$downloadcomconfig['m_comments'];

                $qryc = db("SELECT * FROM ".dba::get('dl_comments')." WHERE download = ".$dl_id." ORDER BY datum DESC LIMIT ".($page - 1)*$downloadcomconfig['m_comments'].",".$downloadcomconfig['m_comments'].""); $comments = '';
                while($getc = _fetch($qryc))
                {
                    $edit = ""; $delete = ""; $hp = ""; $email = ""; $onoff = "";
                    if((checkme() != 'unlogged' && $getc['reg'] == userid()) || permission("downloads"))
                    {
                        $edit = show("page/button_edit_single", array("id" => $get['id'], "action" => "action=download&amp;do=edit&amp;cid=".$getc['id'], "title" => _button_title_edit));
                        $delete = show("page/button_delete_single", array("id" => $dl_id, "action" => "action=download&amp;do=delete&amp;cid=".$getc['id'], "title" => _button_title_del, "del" => _confirm_del_entry));
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

                if(settings("reg_dlcomments") && checkme() == "unlogged")
                    $add = _error_unregistered_nc;
                else
                {
                    if(userid() != 0)
                        $form = show("page/editor_regged", array("nick" => autor()));
                    else
                        $form = show("page/editor_notregged", array("postemail" => "", "posthp" => "", "postnick" => ""));

                    $add = '';
                    if(!ipcheck("dlid(".$dl_id.")", $downloadcomconfig['f_downloadcom']))
                    {
                        $add = show("page/comments_add", array( "titel" => _download_comments_write_head,
                                                                "form" => $form,
                                                                "what" => _button_value_add,
                                                                "ip" => _iplog_info,
                                                                "preview" => _preview,
                                                                "sec" => $dir,
                                                                "security" => _register_confirm,
                                                                "action" => '?action=download&amp;do=add&amp;id='.$dl_id,
                                                                "prevurl" => '../downloads/?action=compreview&id='.$dl_id,
                                                                "postemail" => (isset($_POST['email']) && !empty($error) ? $_POST['email'] : ''),
                                                                "posthp" => (isset($_POST['hp']) && !empty($error) ? $_POST['hp'] : ''),
                                                                "postnick" => (isset($_POST['nick']) && !empty($error) ? string::decode($_POST['nick']) : ''),
                                                                "posteintrag" => (isset($_POST['comment']) && !empty($error) ? string::decode($_POST['comment']) : ''),
                                                                "error" => $error));
                    }
                }

                $seiten = nav($entrys,$downloadcomconfig['m_comments'],"?action=download&amp;id=".$dl_id."");
                $showmore = show($dir."/comments",array("head" => _comments_head, "show" => $comments, "seiten" => $seiten, "icq" => "", "add" => $add));
            }
            else
                $showmore = show("page/comments_no_enabled");

            //////////////////////////////////////////////////////////////////////////////////////////

            $where = $where.': '.string::decode($get['download']);
            $rawfile = (!links_check_url($get['url']) ? @basename($get['url']) : string::decode($get['download']));
            $date = (empty($get['date']) ? (!$size ? 'n/a' : date("d.m.Y H:i",@filemtime($get['url']))._uhr) : date("d.m.Y H:i",$get['date'])._uhr);
            $lastdate = date("d.m.Y H:i",$get['last_dl'])._uhr;
            $index = show($dir."/info", array("getfile" => $getfile,
                                              "br1" => $br1,
                                              "br2" => $br2,
                                              "file" => $rawfile,
                                              "date" => $date,
                                              "lastdate" => $lastdate,
                                              "id" => $_GET['id'],
                                              "dlname" => string::decode($get['download']),
                                              "loaded" => $get['hits'],
                                              "traffic" => $traffic,
                                              "dsl_speed" => download_time($size),
                                              "size" => $dlsize,
                                              "showmore" => $showmore,
                                              "besch" => bbcode::parse_html($get['beschreibung'])));
        }
    }
}