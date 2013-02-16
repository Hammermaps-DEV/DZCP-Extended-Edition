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
    if($_GET['what'] == "addgb")
    {
        if(!empty($userid) && $userid != 0)
        {
            $toCheck = empty($_POST['eintrag']);
        } else {
            $toCheck = empty($_POST['nick']) || empty($_POST['email']) || empty($_POST['eintrag']) || !check_email($_POST['email']) || $_POST['secure'] != $_SESSION['sec_'.$dir] || $_SESSION['sec_'.$dir] == NULL;
        }
        if($toCheck)
        {
            if(!empty($userid) && $userid != 0)
            {
                if(empty($_POST['eintrag'])) $error = _empty_eintrag;
                $form = show("page/editor_regged", array("nick" => autor(convert::ToInt($userid))));
            } else {
                if(($_POST['secure'] != $_SESSION['sec_'.$dir]) || $_SESSION['sec_'.$dir] == NULL) $error = _error_invalid_regcode;
                elseif(empty($_POST['nick'])) $error = _empty_nick;
                elseif(empty($_POST['email'])) $error = _empty_email;
                elseif(!check_email($_POST['email'])) $error = _error_invalid_email;
                elseif(empty($_POST['eintrag']))$error = _empty_eintrag;
                $form = show("page/editor_notregged", array("postemail" => "", "posthp" => "", "postnick" => ""));
            }

            $error = show("errors/errortable", array("error" => $error));

            $index = show($dir."/add", array("titel" => _eintragen_titel,
                    "nickhead" => _nick,
                    "emailhead" => _email,
                    "hphead" => _hp,
                    "preview" => _preview,
                    "security" => _register_confirm,
                    "add_head" => _gb_add_head,
                    "ed" => "",
                    "whaturl" => "addgb",
                    "what" => _button_value_add,
                    "form" => $form,
                    "reg" => "",
                    "ip" => _iplog_info,
                    "id" => $_GET['id'],
                    "postemail" => $_POST['email'],
                    "posthp" => links($_POST['hp']),
                    "postnick" => $_POST['nick'],
                    "posteintrag" => re_bbcode($_POST["eintrag"]),
                    "error" => $error,
                    "eintraghead" => _eintrag));
        } else {
            $qry = db("INSERT INTO ".$db['gb']."
                 SET `datum`      = '".time()."',
                       `editby`     = '',
                       `public`     = 0,
                     `nick`       = '".up($_POST['nick'])."',
                     `email`      = '".up($_POST['email'])."',
                     `hp`         = '".links($_POST['hp'])."',
                     `reg`        = '".convert::ToInt($userid)."',
                     `nachricht`  = '".up($_POST['eintrag'], 1)."',
                     `ip`         = '".visitorIp()."'");

            wire_ipcheck('gb');

            $index = info(_gb_entry_successful, "../gb/");
        }
    }
    elseif($_GET['what'] == 'set')
    {
        if(permission('gb'))
        {
            db("UPDATE ".$db['gb']." SET `public` = '1' WHERE id = '".convert::ToInt($_GET['id'])."'");
            header("Location: ../gb/");
        }
        else
            $index = error(_error_edit_post,1);
    }
    elseif($_GET['what'] == 'unset')
    {
        if(permission('gb'))
        {
            db("UPDATE ".$db['gb']." SET `public` = '0' WHERE id = '".convert::ToInt($_GET['id'])."'");
            header("Location: ../gb/");
        }
        else
            $index = error(_error_edit_post,1);
    }
    elseif($_GET['what'] == "delete")
    {
        $qry = db("SELECT * FROM ".$db['gb']." WHERE id = '".convert::ToInt($_GET['id'])."'");
        $get = _fetch($qry);

        if($get['reg'] == convert::ToInt($userid) && $chkMe != "unlogged" or permission('gb'))
        {
            db("DELETE FROM ".$db['gb']." WHERE id = '".convert::ToInt($_GET['id'])."'");
            $index = info(_gb_delete_successful, "../gb/");
        }
        else
            $index = error(_error_edit_post,1);

    }
    elseif($_GET['what'] == "edit")
    {
        $qry = db("SELECT * FROM ".$db['gb']."  WHERE id = '".convert::ToInt($_GET['id'])."'");
        $get = _fetch($qry);

        if($get['reg'] == convert::ToInt($userid) && $chkMe != "unlogged" or permission('gb'))
        {
            if($get['reg'] != 0)
                $form = show("page/editor_regged", array("nick" => autor($get['reg'])));
            else
                $form = show("page/editor_notregged", array("postemail" => re($get['email']), "posthp" => re($get['hp']), "postnick" => re($get['nick'])));

            $index = show($dir."/add", array("titel" => _eintragen_titel,
                    "nickhead" => _nick,
                    "add_head" => _gb_edit_head,
                    "emailhead" => _email,
                    "what" => _button_value_edit,
                    "security" => _register_confirm,
                    "reg" => $get['reg'],
                    "whaturl" => "editgb&amp;id=".$get['id'],
                    "hphead" => _hp,
                    "ed" => "&edit=".$get['id'],
                    "preview" => _preview,
                    "id" => $get['id'],
                    "form" => $form,
                    "posteintrag" => re_bbcode($get['nachricht']),
                    "ip" => _iplog_info,
                    "error" => "",
                    "eintraghead" => _eintrag));
        } else {
            $index = error(_error_edit_post,1);
        }
    } elseif($_GET['what'] == 'editgb') {
        if($_POST['reg'] == convert::ToInt($userid) || permission('gb'))
        {
            if($_POST['reg'] == 0)
            {
                $addme = "`nick`       = '".up($_POST['nick'])."',
                     `email`      = '".up($_POST['email'])."',
                     `hp`         = '".links($_POST['hp'])."',";
            }

            $editedby = show(_edited_by, array("autor" => autor(convert::ToInt($userid)),
                    "time" => date("d.m.Y H:i", time())._uhr));

            $upd = db("UPDATE ".$db['gb']."
                   SET ".$addme."
                       `nachricht`  = '".up($_POST['eintrag'], 1)."',
                       `reg`        = '".convert::ToInt($_POST['reg'])."',
                       `editby`     = '".addslashes($editedby)."'
                   WHERE id = '".convert::ToInt($_GET['id'])."'");

            $index = info(_gb_edited, "../gb/");
        } else {
            $index = error(_error_edit_post,1);
        }
    }
}
?>