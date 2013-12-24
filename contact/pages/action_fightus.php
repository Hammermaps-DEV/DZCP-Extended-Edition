<?php
/**
 * <DZCP-Extended Edition>
* @package: DZCP-Extended Edition
* @author: DZCP Developer Team || Hammermaps.de Developer Team
* @link: http://www.dzcp.de || http://www.hammermaps.de
*/

if (!defined('IS_DZCP')) exit();

if(checkme() != "unlogged")
    $index = error(_error_user_already_in);
else
{
    $error = '';
    if(isset($_POST['text']))
    {
        if(!$securimage->check($_POST['secure']))
            $error = captcha_mathematic ? _error_invalid_regcode_mathematic : _error_invalid_regcode;
        elseif(empty($_POST['clan']))
            $error = _error_empty_clanname;
        elseif(empty($_POST['email']))
            $error = _empty_email;
        elseif(empty($_POST['maps']))
            $error = _empty_fightus_map;
        elseif(!check_email($_POST['email']))
            $error = _error_invalid_email;
        else if(check_email_trash_mail($_POST['email']))
            $error = _error_trash_mail;
        elseif(empty($_POST['nick']))
            $error = _empty_nick;
        else
        {
            $sqlAnd = '';
            $icq = preg_replace("=-=Uis","", $_POST['icq']);
            $email = show(_email_mailto, array("email" => $_POST['email']));
            $hp = show(_contact_hp, array("hp" => links($_POST['hp'])));

            if(!empty($_POST['t']) && $_POST['j'] == date("Y", time()))
                $date = $_POST['t'].".".$_POST['m'].".".$_POST['j']."&nbsp;".$_POST['h'].":".$_POST['min']._uhr;

            $getsq = db("SELECT name FROM ".dba::get('squads')." WHERE id = '".convert::ToInt($_POST['squad'])."'",false,true);
            $msg = show(_contact_text_fightus, array("icq" => $icq,
                                                     "email" => $email,
                                                     "text" => $_POST['text'],
                                                     "clan" => $_POST['clan'],
                                                     "hp" => $hp,
                                                     "squad" => $getsq['name'],
                                                     "us" => $_POST['us'],
                                                     "to" => $_POST['to'],
                                                     "date" => $date,
                                                     "map" => $_POST['maps'],
                                                     "nick" => $_POST['nick']));

            if(checkme() != 4) $add = " AND s2.squad = '".convert::ToInt($_POST['squad'])."'";
            $who = db("SELECT s1.user FROM ".dba::get('permissions')." AS s1 LEFT JOIN ".dba::get('squaduser')." AS s2 ON s1.user = s2.user WHERE s1.receivecws = '1' AND s1.`user` != '0' ".$add." GROUP BY s1.`user`");
            while($get = _fetch($who))
            {
                $sqlAnd .= " AND s2.`user` != '".convert::ToInt($get['user'])."'";
                $qry = db("INSERT INTO ".dba::get('msg')."
                   SET `datum`      = '".time()."',
                       `von`        = '0',
                       `an`         = '".convert::ToInt($get['user'])."',
                       `titel`      = '".string::encode(_contact_title_fightus)."',
                       `nachricht`  = '".string::encode($msg)."'");

            }

            $qry = db("SELECT s3.`user` FROM ".dba::get('permissions')." AS s1 LEFT JOIN ".dba::get('userpos')." AS s2 ON s1.`pos` = s2.`posi` LEFT JOIN ".dba::get('squaduser')." AS s3 ON s2.user = s3.user WHERE s1.`receivecws` = '1' AND s2.`posi` != '0'".$sqlAnd.$add." GROUP BY s2.`user`");
            while($get = _fetch($qry))
            {
                $qry = db("INSERT INTO ".dba::get('msg')."
                   SET `datum`      = '".time()."',
                       `von`        = '0',
                       `an`         = '".convert::ToInt($get['user'])."',
                       `titel`      = '".string::encode(_contact_title_fightus)."',
                       `nachricht`  = '".string::encode($msg)."'");
            }

            $index = info(_contact_fightus_sended, "?index=news");
        }
    }

    if(empty($index))
    {
        $qry = db("SELECT id,name,game FROM ".dba::get('squads')." WHERE status = 1 ORDER BY name"); $squads = '';
        while($get = _fetch($qry))
        {
            $squads .= show(_select_field_fightus, array("id" => $get['id'], "squad" => string::decode($get['name']), "game" => string::decode($get['game'])));
        }

        $dropdown_date = show(_dropdown_date, array("day" => dropdown("day",date("d",time())), "month" => dropdown("month",date("m",time())), "year" => dropdown("year",date("Y",time()))));
        $dropdown_time = show(_dropdown_time, array("hour" => dropdown("hour",date("H",time())), "minute" => dropdown("minute",date("i",time())), "uhr" => _uhr));
        $index = show($dir."/fightus", array("datum" => $dropdown_date,
                                            "squads" => $squads,
                                            "zeit" => $dropdown_time,
                                            "year" => date("Y", time()),
                                            "sid" => mkpwd(4),
                                            "error" => !empty($error) ? show("errors/errortable", array("error" => $error)) : '',
                                            "nick" => isset($_POST['nick']) ? $_POST['nick'] : '',
                                            "email" => isset($_POST['email']) ? $_POST['email'] : '',
                                            "icq" => isset($_POST['icq']) ? $_POST['icq'] : '',
                                            "clan" => isset($_POST['clan']) ? $_POST['clan'] : '',
                                            "hp" => isset($_POST['hp']) ? $_POST['hp'] : '',
                                            "maps" => isset($_POST['maps']) ? $_POST['maps'] : '',
                                            "text" => isset($_POST['text']) ? $_POST['text'] : '',
                                            "us" => isset($_POST['us']) ? $_POST['us'] : '1',
                                            "to" => isset($_POST['to']) ? $_POST['to'] : '1'));
    }
}