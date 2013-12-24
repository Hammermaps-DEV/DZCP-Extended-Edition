<?php
/**
 * <DZCP-Extended Edition>
 * @package: DZCP-Extended Edition
 * @author: DZCP Developer Team || Hammermaps.de Developer Team
 * @link: http://www.dzcp.de || http://www.hammermaps.de
 */

if (!defined('IS_DZCP')) exit();

$error = '';
if(isset($_POST['text']))
{
    if(checkme() == "unlogged" && !$securimage->check($_POST['secure']))
        $error = captcha_mathematic ? _error_invalid_regcode_mathematic : _error_invalid_regcode;
    elseif(empty($_POST['text']))
        $error = _error_empty_nachricht;
    elseif(checkme() == "unlogged" && empty($_POST['email']))
        $error = _empty_email;
    elseif(checkme() == "unlogged" && !check_email($_POST['email']))
        $error = _error_invalid_email;
    else if(checkme() == "unlogged" && check_email_trash_mail($_POST['email']))
        $error = _error_trash_mail;
    elseif(checkme() == "unlogged" && empty($_POST['nick']))
        $error = _empty_nick;
    else
    {
        $sqlAnd = '';
        $icq = preg_replace("=-=Uis","",(checkme() == "unlogged" && !empty($_POST['icq']) ? $_POST['icq'] : data(false, 'icq')));
        $email = show(_email_mailto, array("email" => (checkme() == "unlogged" && !empty($_POST['email']) ? $_POST['email'] : data(false, 'email'))));
        $text = show(_contact_text, array("icq" => $icq, "email" => $email, "text" => $_POST['text'], "nick" => (checkme() == "unlogged" && !empty($_POST['nick']) ? $_POST['nick'] : data(false, 'nick'))));

        $qry = db("SELECT s1.id FROM ".dba::get('users')." AS s1 LEFT JOIN ".dba::get('permissions')." AS s2 ON s1.id = s2.user WHERE s2.contact = '1' AND s1.`user` != '0 GROUP BY s1.`id`'");
        while($get = _fetch($qry))
        {
            $sqlAnd .= " AND s2.`user` != '".convert::ToInt($get['id'])."'";
            $qrys = db("INSERT INTO ".dba::get('msg')."
                        SET `datum`     = '".time()."',
                            `von`       = '".userid()."',
                            `an`        = '".convert::ToInt($get['id'])."',
                            `titel`     = '".string::encode(_contact_title)."',
                            `nachricht` = '".string::encode($text)."'");
        }

        $qry = db("SELECT s2.`user` FROM ".dba::get('permissions')." AS s1 LEFT JOIN ".dba::get('userpos')." AS s2 ON s1.`pos` = s2.`posi` WHERE s1.`contact` = '1' AND s2.`posi` != '0'".$sqlAnd." GROUP BY s2.`user`");
        while($get = _fetch($qry))
        {
            $qrys = db("INSERT INTO ".dba::get('msg')."
                        SET `datum`     = '".time()."',
                            `von`       = '".userid()."',
                            `an`        = '".convert::ToInt($get['user'])."',
                            `titel`     = '".string::encode(_contact_title)."',
                            `nachricht` = '".string::encode($text)."'");
        }

        $index = info(_contact_sended, "?index=news");
    }
}

if(empty($index))
    $index = show($dir."/contact", array("head" => _site_contact,
                                         "joinus" => '',
                                         "why" => '',
                                         "ext_url" => '',
                                         "sid" => mkpwd(4),
                                         "error" => !empty($error) ? show("errors/errortable", array("error" => $error)) : '',
                                         "nick" => isset($_POST['nick']) ? $_POST['nick'] : '',
                                         "email" => isset($_POST['email']) ? $_POST['email'] : '',
                                         "icq" => isset($_POST['icq']) ? $_POST['icq'] : '',
                                         "text" => isset($_POST['text']) ? $_POST['text'] : ''));