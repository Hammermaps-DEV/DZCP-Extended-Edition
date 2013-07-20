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
    if($_GET['what'] == "contact")
    {
        if($_POST['secure'] != $_SESSION['sec_contact'] || empty($_SESSION['sec_contact']))
            $index = error(_error_invalid_regcode);
        elseif(empty($_POST['text']))
        $index = error(_error_empty_nachricht);
        elseif(empty($_POST['email']))
        $index = error(_empty_email);
        elseif(!check_email($_POST['email']))
        $index = error(_error_invalid_email);
        else if(check_email_trash_mail($_POST['email']))
        $index = error(_error_trash_mail);
        elseif(empty($_POST['nick']))
        $index = error(_empty_nick);
        else {
            $icq = preg_replace("=-=Uis","",$_POST['icq']);
            $email = show(_email_mailto, array("email" => $_POST['email']));
            $text = show(_contact_text, array("icq" => $icq,
                    "email" => $email,
                    "text" => $_POST['text'],
                    "nick" => $_POST['nick']));

            $qry = db("SELECT s1.id FROM ".dba::get('users')." AS s1
                 LEFT JOIN ".dba::get('permissions')." AS s2
                 ON s1.id = s2.user
                 WHERE s2.contact = '1' AND s1.`user` != '0 GROUP BY s1.`id`'");
            $sqlAnd = '';
            while($get = _fetch($qry))
            {
                $sqlAnd .= " AND s2.`user` != '".convert::ToInt($get['id'])."'";
                $qrys = db("INSERT INTO ".dba::get('acomments')."
                    SET `datum`     = '".time()."',
                        `von`       = '0',
                        `an`        = '".convert::ToInt($get['id'])."',
                        `titel`     = '"._contact_title."',
                        `nachricht` = '".string::encode($text)."'");
            }

            $qry = db("SELECT s2.`user` FROM ".dba::get('permissions')." AS s1
                 LEFT JOIN ".dba::get('userpos')." AS s2 ON s1.`pos` = s2.`posi`
                 WHERE s1.`contact` = '1' AND s2.`posi` != '0'".$sqlAnd." GROUP BY s2.`user`");
            while($get = _fetch($qry))
            {
                $qrys = db("INSERT INTO ".dba::get('acomments')."
                    SET `datum`     = '".time()."',
                        `von`       = '0',
                        `an`        = '".convert::ToInt($get['user'])."',
                        `titel`     = '"._contact_title."',
                        `nachricht` = '".string::encode($text)."'");
            }
            $index = info(_contact_sended, "../news/");
        }
    } elseif($_GET['what'] == "joinus") {
        if($_POST['secure'] != $_SESSION['sec_joinus'] || empty($_SESSION['sec_joinus']))
            $index = error(_error_invalid_regcode);
        elseif(empty($_POST['text']))
        $index = error(_error_empty_nachricht);
        elseif(empty($_POST['age']))
        $index = error(_error_empty_age);
        elseif(empty($_POST['email']))
        $index = error(_empty_email);
        elseif(!check_email($_POST['email']))
        $index = error(_error_invalid_email);
        else if(check_email_trash_mail($_POST['email']))
        $index = error(_error_trash_mail);
        elseif(empty($_POST['nick']))
        $index = error(_empty_nick);
        else {
            $icq = preg_replace("=-=Uis","",$_POST['icq']);
            $email = show(_email_mailto, array("email" => $_POST['email']));
            $text = show(_contact_text_joinus, array("icq" => $icq,
                    "email" => $email,
                    "age" => $_POST['age'],
                    "text" => $_POST['text'],
                    "nick" => $_POST['nick']));

            $qry = db("SELECT s1.id FROM ".dba::get('users')." AS s1
                 LEFT JOIN ".dba::get('permissions')." AS s2  ON s1.id = s2.user
                 WHERE s2.joinus = '1' AND s1.`user` != '0' GROUP BY s1.`id`");
            $sqlAnd = '';
            while($get = _fetch($qry))
            {
                $sqlAnd .= " AND s2.`user` != '".convert::ToInt($get['id'])."'";

                $qrys = db("INSERT INTO ".dba::get('acomments')."
                    SET `datum`     = '".time()."',
                        `von`       = '0',
                        `an`        = '".convert::ToInt($get['id'])."',
                        `titel`     = '"._contact_title_joinus."',
                        `nachricht` = '".string::encode($text)."'");
            }

            $qry = db("SELECT s2.`user` FROM ".dba::get('permissions')." AS s1
                 LEFT JOIN ".dba::get('userpos')." AS s2 ON s1.`pos` = s2.`posi`
                 WHERE s1.`joinus` = '1' AND s2.`posi` != '0'".$sqlAnd." GROUP BY s2.`user`");
            while($get = _fetch($qry))
            {
                $qrys = db("INSERT INTO ".dba::get('acomments')."
                    SET `datum`     = '".time()."',
                        `von`       = '0',
                        `an`        = '".convert::ToInt($get['user'])."',
                        `titel`     = '"._contact_title_joinus."',
                        `nachricht` = '".string::encode($text)."'");
            }

            $index = info(_contact_joinus_sended, "../news/");
        }
    } elseif($_GET['what'] == "fightus") {
        if($_POST['secure'] != $_SESSION['sec_fightus'] || empty($_SESSION['sec_fightus']))
            $index = error(_error_invalid_regcode);
        elseif(empty($_POST['clan']))
        $index = error(_error_empty_clanname);
        elseif(empty($_POST['email']))
        $index = error(_empty_email);
        elseif(empty($_POST['maps']))
        $index = error(_empty_fightus_map);
        elseif(!check_email($_POST['email']))
        $index = error(_error_invalid_email);
        else if(check_email_trash_mail($_POST['email']))
        $index = error(_error_trash_mail);
        elseif(empty($_POST['nick']))
        $index = error(_empty_nick);
        else {
            $icq = preg_replace("=-=Uis","",$_POST['icq']);
            $email = show(_email_mailto, array("email" => $_POST['email']));
            $hp = show(_contact_hp, array("hp" => links($_POST['hp'])));

            if(!empty($_POST['t']) && $_POST['j'] == date("Y", time()))
            {
                $date = $_POST['t'].".".$_POST['m'].".".$_POST['j']."&nbsp;".$_POST['h'].":".$_POST['min']._uhr;
            }

            $qrysq = db("SELECT name FROM ".dba::get('squads')."
                   WHERE id = '".convert::ToInt($_POST['squad'])."'");
            $getsq = _fetch($qrysq);

            $msg = show(_contact_text_fightus, array("icq" => $icq,
                    "email" => $email,
                    "text" => $_POST['text'],
                    "clan" => $_POST['clan'],
                    "hp" => $hp,
                    "squad" => $getsq['name'],
                    "game" => $_POST['game'],
                    "us" => $_POST['us'],
                    "to" => $_POST['to'],
                    "date" => $date,
                    "map" => $_POST['maps'],
                    "nick" => $_POST['nick']));

            if(checkme() != 4) $add = " AND s2.squad = '".convert::ToInt($_POST['squad'])."'";
            $who = db("SELECT s1.user FROM ".dba::get('permissions')." AS s1
                 LEFT JOIN ".dba::get('squaduser')." AS s2
                 ON s1.user = s2.user
                 WHERE s1.receivecws = '1' AND s1.`user` != '0'
                 ".$add." GROUP BY s1.`user`");
            $sqlAnd = '';
            var_dump($who);
            while($get = _fetch($who))
            {
                $sqlAnd .= " AND s2.`user` != '".convert::ToInt($get['user'])."'";
                $qry = db("INSERT INTO ".dba::get('acomments')."
                   SET `datum`      = '".time()."',
                       `von`        = '0',
                       `an`         = '".convert::ToInt($get['user'])."',
                       `titel`      = '"._contact_title_fightus."',
                       `nachricht`  = '".string::encode($msg)."'");

            }

            $qry = db("SELECT s3.`user` FROM ".dba::get('permissions')." AS s1
                 LEFT JOIN ".dba::get('userpos')." AS s2 ON s1.`pos` = s2.`posi`
                 LEFT JOIN ".dba::get('squaduser')." AS s3 ON s2.user = s3.user
                                 WHERE s1.`receivecws` = '1' AND s2.`posi` != '0'".$sqlAnd.$add." GROUP BY s2.`user`");
            var_dump($qry);
            while($get === _fetch($qry))
            {
                $qry = db("INSERT INTO ".dba::get('acomments')."
                   SET `datum`      = '".time()."',
                       `von`        = '0',
                       `an`         = '".convert::ToInt($get['user'])."',
                       `titel`      = '"._contact_title_fightus."',
                       `nachricht`  = '".string::encode($msg)."'");
            }
            $index = info(_contact_fightus_sended, "../news/");
        }
    }
}