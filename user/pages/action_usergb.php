<?php
####################################
## Wird in einer Index ausgeführt ##
####################################
if (!defined('IS_DZCP'))
    exit();

#####################
## Userlogin Seite ##
#####################
$where = _site_user_profil;
if(db("SELECT id FROM ".$db['users']." WHERE `id` = '".(int)$_GET['id']."'",true) != 0)
{
    switch($do)
    {
        case 'add':
            if(isset($userid) && $userid != 0)
                $toCheck = empty($_POST['eintrag']);
            else
                $toCheck = empty($_POST['nick']) || empty($_POST['email']) || empty($_POST['eintrag']) || !check_email($_POST['email']) || $_POST['secure'] != $_SESSION['sec_'.$dir] || empty($_SESSION['sec_'.$dir]);

            if($toCheck)
            {
                if(isset($userid))
                {
                    if(empty($_POST['eintrag']))
                        $error = _empty_eintrag;

                    $form = show("page/editor_regged", array("nick" => autor($userid), "von" => _autor));
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
                    else if(empty($_POST['eintrag']))
                        $error = _empty_eintrag;

                    $form = show("page/editor_notregged", array("nickhead" => _nick, "emailhead" => _email, "hphead" => _hp,));
                }

                $error = show("errors/errortable", array("error" => $error));
                $index = show($dir."/usergb_add", array("titel" => _eintragen_titel,
                                                        "nickhead" => _nick,
                                                        "add_head" => _gb_add_head,
                                                        "emailhead" => _email,
                                                        "preview" => _preview,
                                                        "ed" => "&amp;uid=".$_GET['id'],
                                                        "whaturl" => "add",
                                                        "security" => _register_confirm,
                                                        "what" => _button_value_add,
                                                        "hphead" => _hp,
                                                        "id" => $_GET['id'],
                                                        "reg" => $_POST['reg'],
                                                        "form" => $form,
                                                        "postemail" => $_POST['email'],
                                                        "posthp" => $_POST['hp'],
                                                        "postnick" => re($_POST['nick']),
                                                        "posteintrag" => re_bbcode($_POST['eintrag']),
                                                        "error" => $error,
                                                        "eintraghead" => _eintrag));
            }
            else
            {

                if(isset($userid) && $userid != 0)
                {
                    $userdata = data($userid, array('email','nick','hp'));
                    db("INSERT INTO ".$db['usergb']." SET
                           `user`       = '".((int)$_GET['id'])."',
                           `datum`      = '".((int)time())."',
                           `nick`       = '".up($userdata['nick'])."',
                           `email`      = '".up($userdata['email'])."',
                           `hp`         = '".links($userdata['hp'])."',
                           `reg`        = '".((int)$userid)."',
                           `nachricht`  = '".up($_POST['eintrag'],1)."',
                           `ip`         = '".visitorIp()."'");
                    unset($userdata);
                }
                else
                {
                    db("INSERT INTO ".$db['usergb']." SET
                           `user`       = '".((int)$_GET['id'])."',
                           `datum`      = '".((int)time())."',
                           `nick`       = '".up($_POST['nick'])."',
                           `email`      = '".up($_POST['email'])."',
                           `hp`         = '".links($_POST['hp'])."',
                           `reg`        = '".((int)$userid)."',
                           `nachricht`  = '".up($_POST['eintrag'],1)."',
                           `ip`         = '".visitorIp()."'");
                }

                wire_ipcheck("mgbid(".$_GET['id'].")");
                $index = info(_usergb_entry_successful, "?action=user&amp;id=".$_GET['id']."&show=gb");
            }
        break;
        default:
            if($_POST['reg'] == $userid || permission('editusers'))
            {
                $addme = (!$_POST['reg'] ? "`nick` = '".up($_POST['nick'])."', `email` = '".up($_POST['email'])."', `hp` = '".links($_POST['hp'])."'," : '');
                $editedby = show(_edited_by, array("autor" => autor($userid), "time" => date("d.m.Y H:i", time())._uhr));
                db("UPDATE ".$db['usergb']." SET ".$addme." `nachricht` = '".up($_POST['eintrag'],1)."', `reg` = '".((int)$_POST['reg'])."', `editby` = '".addslashes($editedby)."' WHERE id = '".intval($_GET['gbid'])."'");
                $index = info(_gb_edited, "?action=user&show=gb&id=".$_GET['id']);
            }
            else
                $index = error(_error_edit_post,1);
        break;
    }
}
else
    $index = error(_user_dont_exist,1);
?>