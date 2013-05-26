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
    ##################
    ## Usergätebuch ##
    ##################
    $where = _site_user_profil;
    if(db("SELECT id FROM ".dba::get('users')." WHERE `id` = '".convert::ToInt($_GET['id'])."'",true) != 0)
    {
        switch($do)
        {
            case 'add':
                if(!empty($userid) && $userid != 0 && convert::ToInt($userid) != 0)
                    $toCheck = empty($_POST['eintrag']);
                else
                    $toCheck = empty($_POST['nick']) || empty($_POST['email']) || empty($_POST['eintrag']) || !check_email($_POST['email']) || $_POST['secure'] != $_SESSION['sec_'.$dir] || empty($_SESSION['sec_'.$dir]);

                if($toCheck)
                {
                    if(!empty($userid) && $userid != 0)
                    {
                        if(empty($_POST['eintrag']))
                            $error = _empty_eintrag;

                        $form = show("page/editor_regged", array("nick" => autor(convert::ToInt($userid))));
                    }
                    else
                    {
                        if(($_POST['secure'] != $_SESSION['sec_'.$dir]) || empty($_SESSION['sec_'.$dir]))
                            $error = _error_invalid_regcode;
                        else if(empty($_POST['nick']))
                            $error = _empty_nick;
                        else if(empty($_POST['email']))
                            $error = _empty_email;
                        else if(!check_email($_POST['email']))
                            $error = _error_invalid_email;
                        else if(check_email_trash_mail($_POST['email']))
                            $error = _error_trash_mail;
                        else if(empty($_POST['eintrag']))
                            $error = _empty_eintrag;

                        $form = show("page/editor_notregged", array("postemail" => $_POST['email'], "posthp" => $_POST['hp'], "postnick" => $_POST['nick']));
                    }

                    $error = show("errors/errortable", array("error" => $error));
                    $index = show($dir."/usergb_add", array("ed" => "&uid=".$_GET['id'],
                                                            "whaturl" => "add",
                                                            "id" => $_GET['id'],
                                                            "reg" => $_POST['reg'],
                                                            "form" => $form,
                                                            "posteintrag" => re_bbcode($_POST['eintrag']),
                                                            "error" => $error));
                }
                else
                {

                    if(!empty($userid) && $userid != 0 && convert::ToInt($userid) != 0)
                    {
                        $userdata = data(convert::ToInt($userid), array('email','nick','hp'));
                        db("INSERT INTO ".dba::get('usergb')." SET
                               `user`       = '".convert::ToInt($_GET['id'])."',
                               `datum`      = '".time()."',
                               `nick`       = '".convert::ToString(up($userdata['nick']))."',
                               `email`      = '".convert::ToString(up($userdata['email']))."',
                               `hp`         = '".convert::ToString(links($userdata['hp']))."',
                               `reg`        = '".convert::ToInt($userid)."',
                               `nachricht`  = '".convert::ToString(up($_POST['eintrag'],1))."',
                               `ip`         = '".convert::ToString(visitorIp())."'");
                        unset($userdata);
                    }
                    else
                    {
                        db("INSERT INTO ".dba::get('usergb')." SET
                               `user`       = '".convert::ToInt($_GET['id'])."',
                               `datum`      = '".time()."',
                               `nick`       = '".convert::ToString(up($_POST['nick']))."',
                               `email`      = '".convert::ToString(up($_POST['email']))."',
                               `hp`         = '".convert::ToString(links($_POST['hp']))."',
                               `reg`        = '".convert::ToInt($userid)."',
                               `nachricht`  = '".convert::ToString(up($_POST['eintrag'],1))."',
                               `ip`         = '".convert::ToString(visitorIp())."'");
                    }

                    wire_ipcheck("mgbid(".$_GET['id'].")");
                    $index = info(_usergb_entry_successful, "?action=user&amp;id=".$_GET['id']."&show=gb");
                }
            break;
            default:
                if($_POST['reg'] == convert::ToInt($userid) || permission('editusers'))
                {
                    $addme = (!$_POST['reg'] ? "`nick` = '".up($_POST['nick'])."', `email` = '".up($_POST['email'])."', `hp` = '".links($_POST['hp'])."'," : '');
                    $editedby = show(_edited_by, array("autor" => autor(convert::ToInt($userid)), "time" => date("d.m.Y H:i", time())._uhr));
                    db("UPDATE ".dba::get('usergb')." SET ".$addme." `nachricht` = '".convert::ToString(up($_POST['eintrag'],1))."', `reg` = '".convert::ToInt($_POST['reg'])."', `editby` = '".convert::ToString(addslashes($editedby))."' WHERE id = '".convert::ToInt($_GET['gbid'])."'");
                    $index = info(_gb_edited, "?action=user&show=gb&id=".$_GET['id']);
                }
                else
                    $index = error(_error_edit_post,1);
            break;
        }
    }
    else
        $index = error(_user_dont_exist,1);
}