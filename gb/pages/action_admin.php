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
    switch($do)
    {
        case 'addcomment':
            $error = '';
            if(isset($_GET['save']))
            {
                if(empty($_POST['eintrag']))
                {
                    if(empty($_POST['eintrag']))
                        $error = show("errors/errortable", array("error" => _empty_eintrag));
                }
                else
                {
                    db("INSERT INTO ".dba::get('gb_comments')." SET
                            `gbe`     = '".convert::ToInt($_GET['id'])."',
                            `datum`    = '".time()."',
                            `editby`   = '',
                            `reg`      = '".userid()."',
                            `comment`  = '".string::encode($_POST['eintrag'])."',
                            `ip`       = '".visitorIp()."'");
                    $index = info(_gb_comment_added, "../gb/");
                }
            }

            if(empty($index))
            {
                $get = db("SELECT * FROM ".dba::get('gb')." WHERE id = '".convert::ToInt($_GET['id'])."'",false,true);
                $gbhp = ($get['hp'] ? show(_hpicon, array("hp" => $get['hp'])) : '');
                $gbemail = ($get['email'] ? show(_emailicon, array("email" => eMailAddr($get['email']))) : '');

                if(!$get['reg'])
                {
                    $gbtitel = show(_gb_titel_noreg, array("postid" => "?",
                                                           "nick" => string::decode($get['nick']),
                                                           "edit" => "",
                                                           "delete" => "",
                                                           "comment" => "",
                                                           "public" => "",
                                                           "email" => $gbemail,
                                                           "datum" => date("d.m.Y", $get['datum']),
                                                           "zeit" => date("H:i", $get['datum']),
                                                           "hp" => $gbhp));
                }
                else
                {
                    $gbtitel = show(_gb_titel, array("postid" => "?",
                                                     "nick" => data($get['reg'], "nick"),
                                                     "edit" => "",
                                                     "public" => "",
                                                     "delete" => "",
                                                     "comment" => "",
                                                     "id" => $get['reg'],
                                                     "email" => $gbemail,
                                                     "datum" => date("d.m.Y", $get['datum']),
                                                     "zeit" => date("H:i", $get['datum']),
                                                     "hp" => $gbhp));
                }


                $entry = show($dir."/gb_show", array("comments" => '', "gbtitel" => $gbtitel, "nachricht" => show(bbcode::parse_html($get['nachricht']),array(),array('gb_addcomment_from' => _gb_addcomment_from)), "editby" => bbcode::parse_html($get['editby']), "ip" => $get['ip']));
                $index = show($dir."/gb_addcomment", array("error" => $error, "entry" => $entry, "id" => $_GET['id'], "ed" => ""));
            }
        break;
        case 'set':
            if(permission('gb'))
            {
                db("UPDATE ".dba::get('gb')." SET `public` = '1' WHERE id = '".convert::ToInt($_GET['id'])."'");
                header("Location: ../gb/");
            }
            else
                $index = error(_error_edit_post);
        break;
        case 'unset':
            if(permission('gb'))
            {
                db("UPDATE ".dba::get('gb')." SET `public` = '0' WHERE id = '".convert::ToInt($_GET['id'])."'");
                header("Location: ../gb/");
            }
            else
                $index = error(_error_edit_post);
        break;
        case 'delete':
            $get = db("SELECT reg FROM ".dba::get('gb')." WHERE id = '".convert::ToInt($_GET['id'])."'",false,true);
            if($get['reg'] == userid() && checkme() != "unlogged" || permission('gb'))
            {
                db("DELETE FROM ".dba::get('gb')." WHERE id = '".convert::ToInt($_GET['id'])."'");
                db("DELETE FROM ".dba::get('gb_comments')." WHERE gbe = '".convert::ToInt($_GET['id'])."'");
                $index = info(_gb_delete_successful, "../gb/");
            }
            else
                $index = error(_error_edit_post);
        break;
        case 'cdelete':
            $get = db("SELECT reg FROM ".dba::get('gb_comments')." WHERE id = '".convert::ToInt($_GET['id'])."'",false,true);
            if($get['reg'] == userid() && checkme() != "unlogged" || permission('gb'))
            {
                db("DELETE FROM ".dba::get('gb_comments')." WHERE id = '".convert::ToInt($_GET['id'])."'");
                $index = info(_comment_deleted, "../gb/");
            }
            else
                $index = error(_error_edit_post);
        break;
        case 'cedit':
            $get = db("SELECT * FROM ".dba::get('gb_comments')."  WHERE id = '".convert::ToInt($_GET['id'])."'",false,true);
            if($get['reg'] == userid() && checkme() != "unlogged" || permission('gb'))
            {
                if($get['reg'] != 0)
                    $form = show("page/editor_regged", array("nick" => autor($get['reg'])));
                else
                    $form = show("page/editor_notregged", array("postemail" => $get['email'], "posthp" => string::decode($get['hp']), "postnick" => string::decode($get['nick'])));

                $index = show($dir."/edit_com", array("whaturl" => "editgbc&amp;id=".$get['id'],
                                                     "ed" => "&edit=".$get['id']."&postid=".$_GET['postid'],
                                                     "id" => $get['id'],
                                                     "form" => $form,
                                                     "posteintrag" => string::decode($get['comment']),
                                                     "eintraghead" => _eintrag));
            }
            else
                $index = error(_error_edit_post);
        break;
        case 'edit':
            $get = db("SELECT * FROM ".dba::get('gb')."  WHERE id = '".convert::ToInt($_GET['id'])."'",false,true);
            if($get['reg'] == userid() && checkme() != "unlogged" || permission('gb'))
            {
                if($get['reg'] != 0)
                    $form = show("page/editor_regged", array("nick" => autor($get['reg'])));
                else
                    $form = show("page/editor_notregged", array("postemail" => string::decode($get['email']), "posthp" => string::decode($get['hp']), "postnick" => string::decode($get['nick'])));

                $index = show($dir."/add", array("what" => _button_value_edit,
                                                 "reg" => $get['reg'],
                                                 "whaturl" => "action=admin&amp;do=editgb&amp;id=".$get['id'],
                                                 "hphead" => _hp,
                                                 "ed" => "&edit=".$get['id']."&id=".$_GET['postid'],
                                                 "id" => $get['id'],
                                                 "form" => $form,
                                                 "posteintrag" => string::decode($get['nachricht']),
                                                 "error" => "",
                                                 "eintraghead" => _gb_edit_head));
            }
            else
                $index = error(_error_edit_post);
        break;
        case 'editgb':
            if(convert::ToInt($_POST['reg']) == userid() || permission('gb'))
            {
                if(!convert::ToInt($_POST['reg']))
                    $addme = "`nick` = '".string::encode($_POST['nick'])."', `email` = '".string::encode($_POST['email'])."', `hp` = '".string::encode($_POST['hp'])."',";

                $editedby = show(_edited_by, array("autor" => autor(), "time" => date("d.m.Y H:i", time())._uhr));
                db("UPDATE ".dba::get('gb')." SET ".$addme." `nachricht`  = '".string::encode($_POST['eintrag'])."', `reg` = '".convert::ToInt($_POST['reg'])."', `editby` = '".addslashes($editedby)."' WHERE id = '".convert::ToInt($_GET['id'])."'");
                $index = info(_gb_edited, "../gb/");
            }
            else
                $index = error(_error_edit_post);
        break;
        case 'editgbc':
            $get = db("SELECT reg FROM ".dba::get('gb_comments')." WHERE id = '".convert::ToInt($_GET['id'])."'",false,true);
            if($get['reg'] == userid() || permission('gb'))
            {
                $editedby = show(_edited_by, array("autor" => autor(), "time" => date("d.m.Y H:i", time())._uhr));
                db("UPDATE ".dba::get('gb_comments')." SET
                    ".(isset($_POST['nick']) ? " `nick`     = '".string::encode($_POST['nick'])."'," : "")."
                    ".(isset($_POST['email']) ? " `email`   = '".string::encode($_POST['email'])."'," : "")."
                    ".(isset($_POST['hp']) ? " `hp`         = '".string::encode($_POST['hp'])."'," : "")."
                    `comment`  = '".string::encode($_POST['eintrag'])."',
                    `editby`   = '".addslashes($editedby)."'
                   WHERE id = '".convert::ToInt($_GET['id'])."'");

                $index = info(_gb_comment_edited, "../gb/");
            }
            else
                $index = error(_error_edit_post);
        break;
    }
}