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
    $error = ''; $fromUser = (checkme() != 'unlogged');
    if(isset($_GET['do']) && $_GET['do'] ==  'send')
    {
        $sec_sendnews = isset($_SESSION['sec_sendnews']) ? $_SESSION['sec_sendnews'] : '';
        $nick = isset($_POST['nick']) && !empty($_POST['nick']) ? $_POST['nick'] : '';
        $email = isset($_POST['email']) && !empty($_POST['email']) ? $_POST['email'] : '';
        $titel = isset($_POST['titel']) && !empty($_POST['titel']) ? $_POST['titel'] : '';
        $text = isset($_POST['text']) && !empty($_POST['text']) ? $_POST['text'] : '';
        $secure = isset($_POST['secure']) && !empty($_POST['secure']) ? $_POST['secure'] : '';
        $info = isset($_POST['info']) && !empty($_POST['info']) ? $_POST['info'] : '';
        $hp = isset($_POST['hp']) && !empty($_POST['hp']) ? $_POST['hp'] : '';

        if(!$fromUser && empty($sec_sendnews) || !$fromUser && ($secure != $sec_sendnews))
            $error = show("errors/errortable", array("error" => captcha_mathematic ? _error_invalid_regcode_mathematic : _error_invalid_regcode));
        if( empty($text))
            $error = show("errors/errortable", array("error" => _error_empty_nachricht));
        else if(empty($titel))
            $error = show("errors/errortable", array("error" => _empty_titel));
        else if(!$fromUser && empty($email))
            $error = show("errors/errortable", array("error" => _empty_email));
        else if(!$fromUser && !check_email($email))
            $error = show("errors/errortable", array("error" => _error_invalid_email));
        else if(check_email_trash_mail($email))
            $error = show("errors/errortable", array("error" => _error_trash_mail));
        else if(!$fromUser && empty($nick))
            $error = show("errors/errortable", array("error" => _empty_nick));
        else
        {
            $hp = show(_contact_hp, array("hp" => links($hp)));
            $nick = !$fromUser ? $nick : fabo_autor(0,_user_link_blank);
            $von_nick = !$fromUser ? '0' : userid();
            $titel = !$fromUser ? show(_news_send_titel, array("nick" => $nick)) : show(_news_send_titel, array("nick" => fabo_autor(0,_user_link_blank)));
            $email = !$fromUser ? show(_email_mailto, array("email" => $email)) : '--';
            $sendnews = !$fromUser ? '1' : '2';
            $user = !$fromUser ? $nick : userid();

            $text = show(_contact_text_sendnews, array("hp" => $hp, "email" => $email, "titel" => $titel, "text" => $text, "info" => $info, "nick" => $nick));
            $qry = db("SELECT id,level FROM ".dba::get('users')."");
            while($get = _fetch($qry))
            {
                if(perm_sendnews($get['id']) || $get['level'] == 4)
                    db("INSERT INTO ".dba::get('msg')." SET `datum` = '".time()."', `von` = '".$von_nick."', `an` = '".convert::ToInt($get['id'])."', `titel` = '".string::encode($titel)."', `nachricht` = '".string::encode($text)."', `sendnews` = '".$sendnews."', `senduser` = '".$user."'");
            }

            $index = info(_news_send_done, "?index=news");
        }
    }

    if(empty($index))
    {
        $form = show($dir."/send_form".($fromUser ? '2' : '1'), array(
                "user" => ($fromUser ? autor() : ''),
                "value" => _button_value_send,
                "sid" => mkpwd(4),
                "s_nick" => (isset($nick) && !empty($nick) ? $nick : ''),
                "s_email" => (isset($email) && !empty($email) ? $email : ''),
                "s_hp" => (isset($hp) && !empty($hp) ? $hp : ''),
                "s_titel" => (isset($titel) && !empty($titel) ? $titel : ''),
                "s_text" => (isset($text) && !empty($text) ? $text : ''),
                "s_info" => (isset($info) && !empty($info) ? $info : ''),));

        $where = _news_send;
        $index = show($dir."/send", array("error" => $error, "form" => $form));
    }
}