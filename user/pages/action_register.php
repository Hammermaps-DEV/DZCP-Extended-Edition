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
    ## Registrieren ##
    ##################
    $where = _site_reg;

    ## User ist nicht angemeldet ##
    if(checkme() == "unlogged")
    {
        ## Registrations Code anzeigen ##
        $regcode = (settings("regcode") ? show($dir."/register_regcode", array("confirm" => _register_confirm,  "confirm_add" => _register_confirm_add)) : "");

        ## Registrationsformular anzeigen ##
        $index = show($dir."/register", array("error" => "", "r_name" => "", "r_nick" => "", "r_email" => "", "value" => _button_value_reg, "regcode" => $regcode, "pwd2" => _pwd2));
    }
    else
        $index = error(_error_user_already_in); //Error, User ist bereits angemeldet

    ## Registrierung ausführen ##
    if($do == "add")
    {
        ## Whitespaces entfernen ##
        $_POST['user'] = trim($_POST['user']);
        $_POST['nick'] = trim($_POST['nick']);

        ## Prüfung ob Username, Usernick oder E-Mail bereits existiert ##
        $check_user = db("SELECT id FROM ".dba::get('users')." WHERE user = '".$_POST['user']."'");
        $check_nick = db("SELECT id FROM ".dba::get('users')." WHERE nick = '".$_POST['nick']."'");
        $check_email = db("SELECT id FROM ".dba::get('users')." WHERE email = '".$_POST['email']."'");

        ## Gibt es einen Fehler ? ##
        if(empty($_POST['user']) || empty($_POST['nick']) || empty($_POST['email']) || ($_POST['pwd'] != $_POST['pwd2']) || (settings("regcode") == 1 && ($_POST['confirm'] != $_SESSION['sec_reg'] || $_SESSION['sec_reg'] == NULL)) || _rows($check_user) || _rows($check_nick) || _rows($check_email))
        {
            ## Welcher Fehler soll angezeigt werden ##
            if(settings("regcode") && ($_POST['confirm'] != $_SESSION['sec_reg'] || $_SESSION['sec_reg'] == NULL)) $error = show("errors/errortable", array("error" => _error_invalid_regcode));
            else if($_POST['pwd2'] != $_POST['pwd']) $error = show("errors/errortable", array("error" => _wrong_pwd));
            else if(!check_email($_POST['email'])) $error = show("errors/errortable", array("error" => _error_invalid_email));
            else if(check_email_trash_mail($_POST['email'])) $error = show("errors/errortable", array("error" => _error_trash_mail));
            else if(empty($_POST['email'])) $error = show("errors/errortable", array("error" => _empty_email));
            else if(_rows($check_email)) $error = show("errors/errortable", array("error" => _error_email_exists));
            else if(empty($_POST['nick'])) $error = show("errors/errortable", array("error" => _empty_nick));
            else if(_rows($check_nick)) $error = show("errors/errortable", array("error" => _error_nick_exists));
            else if(empty($_POST['user'])) $error = show("errors/errortable", array("error" => _empty_user));
            else if(_rows($check_user)) $error = show("errors/errortable", array("error" => _error_user_exists));
            else $error = ""; // Unbekannt

            $regcode = (settings("regcode") ? show($dir."/register_regcode", array("confirm" => _register_confirm, "confirm_add" => _register_confirm_add)) : "");
            $index = show($dir."/register", array("error" => $error, "pwd2" => _pwd2, "r_name" => $_POST['user'], "r_nick" => $_POST['nick'], "r_email" => $_POST['email'], "value" => _button_value_reg, "regcode" => $regcode));
        }
        else
        {
            $use_akl = config('use_akl');
            ## Wurde ein Passwort eingegeben ##
            $mkpwd = (empty($_POST['pwd']) ? mkpwd() : $_POST['pwd']);

            ## Neuen User in die Datenbank schreiben ##
            db("INSERT INTO ".dba::get('users')." SET
            `user` = '".string::encode($_POST['user'])."',
            `nick` = '".string::encode($_POST['nick'])."',
            `email` = '".string::encode($_POST['email'])."',
            `pwd` = '".pass_hash($mkpwd,settings('default_pwd_encoder'))."',
            `pwd_encoder` = '".settings('default_pwd_encoder')."',
            `regdatum` = '".($time=time())."',
            `level`    = ".($use_akl ? '0' : '1').",
            `time`     = '".$time."',
            `rss_key`  = '".md5(mkpwd())."',
            `actkey`  = '".($use_akl ? ($guid=GenGuid()) : '')."',
            `status`   = ".($use_akl ? '0' : '1')."");

            ## Lese letzte ID aus ##
            $insert_id = database::get_insert_id();

            ## Lege User in der Permissions Tabelle an ##
            db("INSERT INTO ".dba::get('permissions')." SET `user` = '".$insert_id."'");

            ## Lege User in der User-Statistik Tabelle an ##
            db("INSERT INTO ".dba::get('userstats')." SET `user` = '".$insert_id."', `lastvisit`	= '".$time."'");

            ## Lege User in der RSS Config Tabelle an ##
            db("INSERT INTO ".dba::get('rss')." SET `userid` = '".$insert_id."'");

            ## Ereignis in den Adminlog schreiben ##
            wire_ipcheck("reg(".$insert_id.")");

            ## E-Mail zusammenstellen und senden ##
            if($use_akl)
            {
                $akl_link = 'http://'.$httphost.'/user/?action=akl&do=activate&key='.$guid;
                $akl_link_page = 'http://'.$httphost.'/user/?action=akl&do=activate';
                sendMail($_POST['email'],string::decode(settings('eml_akl_register_subj')),show(string::decode(settings('eml_akl_register')), array("nick" => $_POST['user'], "link_page" => '<a href="'.$akl_link_page.'" target="_blank">'.$akl_link_page.'</a>', "guid" => $guid, "link" => '<a href="'.$akl_link.'" target="_blank">Link</a>')));
            }

            sendMail($_POST['email'],string::decode(settings('eml_reg_subj')),show(string::decode(settings('eml_reg')), array("user" => $_POST['user'], "pwd" => $mkpwd)));

            ## Nachricht anzeigen und zum  Userlogin weiterleiten ##
            $index = info(show($use_akl ? _info_reg_valid_akl : _info_reg_valid, array("email" => $_POST['email'])), "../user/?action=login",8);
        }
    }
}