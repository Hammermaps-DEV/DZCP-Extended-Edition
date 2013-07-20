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
    $error = '';
    if($do == 'addgb')
    {
        if(userid() != 0)
            $toCheck = empty($_POST['eintrag']);
        else
            $toCheck = empty($_POST['nick']) || empty($_POST['email']) || empty($_POST['eintrag']) || !check_email($_POST['email']) || $_POST['secure'] != $_SESSION['sec_'.$dir] || $_SESSION['sec_'.$dir] == NULL;

        if($toCheck)
        {
            if(userid() != 0)
            {
                if(empty($_POST['eintrag']))
                    $error = _empty_eintrag;

                $form = show("page/editor_regged", array("nick" => autor()));
            }
            else
            {
                if(($_POST['secure'] != $_SESSION['sec_'.$dir]) || $_SESSION['sec_'.$dir] == NULL)
                    $error = _error_invalid_regcode;
                elseif(empty($_POST['nick']))
                    $error = _empty_nick;
                elseif(empty($_POST['email']))
                    $error = _empty_email;
                elseif(!check_email($_POST['email']))
                    $error = _error_invalid_email;
                else if(check_email_trash_mail($_POST['email']))
                    $error = _error_trash_mail;
                elseif(empty($_POST['eintrag']))
                    $error = _empty_eintrag;

                $form = show("page/editor_notregged", array("postemail" => $_POST['email'], "posthp" => $_POST['hp'], "postnick" => $_POST['nick']));
            }

            $error = show("errors/errortable", array("error" => $error));
        }
        else
        {
            db("INSERT INTO ".dba::get('gb')." SET
                     `datum`      = '".time()."',
                     `editby`     = '',
                     `public`     = 0,
                     `nick`       = '".(isset($_POST['nick']) ? string::encode($_POST['nick']) : '')."',
                     `email`      = '".(isset($_POST['email']) ? string::encode($_POST['email']) : '')."',
                     `hp`         = '".(isset($_POST['hp']) ? string::encode($_POST['hp']) : '')."',
                     `reg`        = '".userid()."',
                     `nachricht`  = '".string::encode($_POST['eintrag'])."',
                     `ip`         = '".visitorIp()."'");

            wire_ipcheck('gb');
            $index = info(_gb_entry_successful, "../gb/");
        }
    }

    if(empty($index))
    {
        $gb_config = config(array('m_gb','f_gb'));
        $activ = (($gb_activ=settings('gb_activ')) && !permission("gb")) ? "WHERE public = 1" : "";
        $qry = db("SELECT * FROM ".dba::get('gb')." ".$activ." ORDER BY datum DESC LIMIT " . ($page - 1) * $gb_config['m_gb'].",".$gb_config['m_gb']."");
        $entrys = cnt(dba::get('gb')); $i = $entrys - ($page - 1) * $gb_config['m_gb'];
        $seiten = nav($entrys,$gb_config['m_gb'],"?action=nav");

        if(_rows($qry))
        {
            $show = '';
            while($get = _fetch($qry))
            {
                $gbhp = ($get['hp'] ? show(_hpicon, array("hp" => links(string::decode($get['hp'])))) : '');
                $gbemail = ($get['email'] ? show(_emailicon, array("email" => eMailAddr($get['email']))) : '');

                $delete = ""; $edit = ""; $comment = "";
                if($get['reg'] == userid() || permission("gb"))
                {
                    $edit = show("page/button_edit_single", array("id" => $get['id'], "action" => "action=admin&amp;do=edit&amp;postid=".$i, "title" => _button_title_edit));
                    $delete = show("page/button_delete_single", array("id" => $get['id'], "action" => "action=admin&amp;do=delete", "title" => _button_title_del, "del" => _confirm_del_entry));
                    $comment = show(_gb_commenticon, array("id" => $get['id'], "title" => _button_title_comment));
                }

                $public = "";
                if(permission("gb") && $gb_activ)
                {
                    $public = ($get['public'])
                    ? '<a href="?action=admin&amp;do=unset&amp;id='.$get['id'].'"><img src="../inc/images/public.gif" alt="" title="nicht ver&ouml;ffentlichen" align="top" style="padding-top:1px"/></a>'
                    : '<a href="?action=admin&amp;do=set&amp;id='.$get['id'].'"><img src="../inc/images/nonpublic.gif" alt="" title="ver&ouml;ffentlichen" align="top" style="padding-top:1px"/></a>';
                }

                if(!$get['reg'])
                {
                    $gbtitel = show(_gb_titel_noreg, array("postid" => $i,
                                                           "nick" => string::decode($get['nick']),
                                                           "edit" => $edit,
                                                           "delete" => $delete,
                                                           "comment" => $comment,
                                                           "public" => $public,
                                                           "email" => $gbemail,
                                                           "datum" => date("d.m.Y", $get['datum']),
                                                           "zeit" => date("H:i", $get['datum']),
                                                           "hp" => $gbhp));
                }
                else
                {
                    $gbtitel = show(_gb_titel, array("postid" => $i,
                                                     "nick" => autor($get['reg']),
                                                     "edit" => $edit,
                                                     "delete" => $delete,
                                                     "comment" => $comment,
                                                     "public" => $public,
                                                     "id" => $get['reg'],
                                                     "email" => $gbemail,
                                                     "datum" => date("d.m.Y", $get['datum']),
                                                     "zeit" => date("H:i", $get['datum']),
                                                     "hp" => $gbhp));
                }

                $qryc = db("SELECT * FROM ".dba::get('gb_comments')." WHERE gbe = ".$get['id']." ORDER BY datum DESC"); $comments = '';
                if(_rows($qryc))
                {
                    while($getc = _fetch($qryc))
                    {
                        $edit = ""; $delete = "";
                        if((checkme() != 'unlogged' && $getc['reg'] == userid()) || permission("gb"))
                        {
                            $edit = show("page/button_edit_single", array("id" => $getc['id'], "action" => "action=admin&amp;do=cedit&amp;postid=".$i, "title" => _button_title_edit));
                            $delete = show("page/button_delete_single", array("id" => $getc['id'], "action" => "action=admin&amp;do=cdelete", "title" => _button_title_del, "del" => _confirm_del_entry));
                        }

                        $nick = (!$getc['reg'] ? show(_link_mailto, array("nick" => string::decode($getc['nick']), "email" => eMailAddr($getc['email']))) : autor($getc['reg']));
                        $comments .= show($dir."/commentlayout", array("nick" => string::decode($nick), "editby" => string::decode($getc['editby']), "datum" => date("d.m.Y H:i", $getc['datum'])._uhr, "comment" => bbcode::parse_html(string::decode($getc['comment'])), "edit" => $edit, "delete" => $delete));
                    } //while end
                }

                $posted_ip = (checkme() == "4" ? $get['ip'] : _logged);
                $show .= show($dir."/gb_show", array("gbtitel" => $gbtitel, "nachricht" => bbcode::parse_html($get['nachricht']), "comments" => $comments, "editby" => bbcode::parse_html($get['editby']), "ip" => $posted_ip));
                $i--;
            } //while end
        }
        else
            $show = show(_no_entrys_yet, array("colspan" => "2"));

        $entry = "";
        if(!ipcheck("gb", $gb_config['f_gb']))
        {
            if(userid() != 0)
                $form = show("page/editor_regged", array("nick" => autor()));
            else
                $form = show("page/editor_notregged", array("postemail" => (isset($_POST['email']) ? $_POST['email'] : ''), "posthp" => (isset($_POST['hp']) ? $_POST['hp'] : ''), "postnick" => (isset($_POST['nick']) ? $_POST['nick'] : '')));

            $entry = show($dir."/add", array("eintraghead" => _gb_add_head, "what" => _button_value_add, "ed" => "", "reg" => "", "whaturl" => "do=addgb", "form" => $form, "posteintrag" => (isset($_POST["eintrag"]) ? string::decode($_POST["eintrag"]) : ''), "error" => $error));
        }

        $index = show($dir."/gb",array("show" => $show, "add" => show(_gb_eintragen), "entry" => $entry, "seiten" => $seiten));
    }
}