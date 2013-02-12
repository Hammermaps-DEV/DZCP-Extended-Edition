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

if (_version < 1.0) //Mindest Version pruefen
    $index = _version_for_page_outofdate;
else
{
    ######################
    ## User Nachrichten ##
    ######################
    $where = _site_msg;
    $msgID = (isset($_GET['id']) ? convert::ToInt($_GET['id']) : 0);

    switch($do)
    {
        case 'show':
            $qry = db("SELECT * FROM ".$db['msg']." WHERE id = ".$msgID);

            if(!$msgID || empty($msgID) || !_rows($qry))
                $index = error(_id_dont_exist, 1);
            else
            {
                $get = _fetch($qry);
                if($get['von'] == convert::ToInt($userid) || $get['an'] == convert::ToInt($userid) || $chkMe == 4)
                {
                    if(!$get['von'])
                    {
                        $answermsg = show(_msg_answer_msg, array("nick" => "MsgBot"));
                        $answer = "&nbsp;";
                    }
                    else
                    {
                        $answermsg = show(_msg_answer_msg, array("nick" => autor($get['von'])));
                        $answer = show(_msg_answer, array("id" => $get['id']));
                    }

                    $sendnews = '';
                    switch ($get['sendnews'])
                    {
                        case 1: case 2: $sendnews = show(_msg_sendnews_user, array("id" => $get['id'], "datum" => $get['datum'])); break;
                        case 3: $sendnews = show(_msg_sendnews_done, array("user" => autor($get['sendnewsuser']))); break;
                    }

                    if(!$get['readed'])
                        db("UPDATE ".$db['msg']." SET `readed` = 1 WHERE id = ".$msgID);

                    $delete = show(_delete, array("id" => $get['id']));
                    $index = show($dir."/msg_show", array("answermsg" => $answermsg,
                                                          "titel" => re($get['titel']),
                                                          "nachricht" => bbcode($get['nachricht']),
                                                          "answer" => $answer,
                                                          "sendnews" => $sendnews,
                                                          "delete" => $delete));
                }

            }
        break;
        case 'sendnewsdone':
            $qry = db("SELECT * FROM ".$db['msg']." WHERE id = '".$msgID."'");

            if(!$msgID || empty($msgID) || !_rows($qry))
                $index = error(_id_dont_exist, 1);
            else
            {
                while($get = _fetch($qry))
                {
                    db("UPDATE ".$db['msg']." SET `sendnews` = 3, `sendnewsuser` = '".convert::ToInt($userid)."', `readed`= 1 WHERE datum = '".convert::ToInt($_GET['datum'])."'");
                    $index = info(_send_news_done, "?action=msg&do=show&id=".$get['id']."");
                }
            }
        break;
        case 'showsended':
            $qry = db("SELECT * FROM ".$db['msg']." WHERE id = ".$msgID);
            $get = _fetch($qry);

            if($get['von'] == convert::ToInt($userid) || $get['an'] == convert::ToInt($userid) || $chkMe == 4)
            {
                $answermsg = show(_msg_sended_msg, array("nick" => autor($get['an'])));
                $answer = _back;
                $index = show($dir."/msg_show", array("answermsg" => $answermsg,
                                                      "titel" => re($get['titel']),
                                                      "nachricht" => bbcode($get['nachricht']),
                                                      "answer" => $answer,
                                                      "sendnews" => "",
                                                      "delete" => ""));
            }
        break;
        case 'answer':
            $qry = db("SELECT * FROM ".$db['msg']." WHERE id = ".$msgID);

            if(!$msgID || empty($msgID) || !_rows($qry))
                $index = error(_id_dont_exist, 1);
            else
            {
                $get = _fetch($qry);
                if($get['von'] == convert::ToInt($userid) || $get['an'] == convert::ToInt($userid) || $chkMe == 4)
                {
                    $titel = (preg_match("#RE:#is",re($get['titel'])) ? re($get['titel']) : "RE: ".re($get['titel']));
                    $index = show($dir."/answer", array("von" => convert::ToInt($userid),
                                                        "an" => $get['von'],
                                                        "titel" => $titel,
                                                        "headtitel" => _msg_titel_answer,
                                                        "titelhead" => _titel,
                                                        "nickhead" => _to,
                                                        "value" => _button_value_msg,
                                                        "eintraghead" => _answer,
                                                        "nick" => autor($get['von']),
                                                        "zitat" => zitat(autor($get['von']),$get['nachricht'])));
                }
            }
        break;
        case 'pn':
            if($chkMe == "unlogged")
                $index = error(_error_have_to_be_logged);
            else if($msgID == convert::ToInt($userid))
                $index = error(_error_msg_self, 1);
            else
            {
                $titel = show(_msg_from_nick, array("nick" => data(convert::ToInt($userid),"nick")));
                $index = show($dir."/answer", array("von" => convert::ToInt($userid),
                                                    "an" => $msgID,
                                                    "titel" => $titel,
                                                    "value" => _button_value_msg,
                                                    "titelhead" => _titel,
                                                    "headtitel" => _msg_titel,
                                                    "nickhead" => _to,
                                                    "eintraghead" => _answer,
                                                    "nick" => autor($msgID),
                                                    "zitat" => ""));
            }
        break;
        case 'sendanswer':
            if(empty($_POST['titel']))
                $index = error(_empty_titel, 1);
            else if(empty($_POST['eintrag']))
                $index = error(_empty_eintrag, 1);
            else
            {
                db("INSERT INTO ".$db['msg']." SET
                        `datum` = '".time()."',
                        `von` = '".convert::ToInt($_POST['von'])."',
                        `an`  = '".convert::ToInt($_POST['an'])."',
                        `titel` = '".up($_POST['titel'])."',
                        `nachricht` = '".up($_POST['eintrag'], 1)."',
                        `see` = '1'");

                db("UPDATE ".$db['userstats']." SET `writtenmsg` = writtenmsg+1 WHERE user = ".convert::ToInt($userid));
                $index = info(_msg_answer_done, "?action=msg");
            }
        break;
        case 'delete':
            $qry = db("SELECT see,id FROM ".$db['msg']." WHERE an = '".convert::ToInt($userid)."' AND see_u = 0");
            if(_rows($qry) >= 1)
            {
                while($get = _fetch($qry))
                {
                    if(isset($_POST['pe'.$get['id']]))
                    {
                        if(!$get['see'])
                            db("DELETE FROM ".$db['msg']." WHERE id = ".convert::ToInt($_POST['pe'.$get['id']]));
                        else
                            db("UPDATE ".$db['msg']." SET `see_u` = 1 WHERE id = ".convert::ToInt($_POST['pe'.$get['id']]));
                    }
                }
            }

            header("Location: ?action=msg");
        break;
        case 'deletethis':
            $qry = db("SELECT id,see FROM ".$db['msg']." WHERE id = '".$msgID."'");
            if(_rows($qry) >= 1)
            {
                $get = _fetch($qry);
                if(!$get['see'])
                    db("DELETE FROM ".$db['msg']." WHERE id = ".$msgID);
                else
                    db("UPDATE ".$db['msg']." SET `see_u` = 1 WHERE id = ".$msgID);
            }

            $index = info(_msg_deleted, "?action=msg");
        break;
        case 'deletesended':
            $qry = db("SELECT id,see_u FROM ".$db['msg']." WHERE von = '".convert::ToInt($userid)."' AND see = 1");
            if(_rows($qry) >= 1)
            {
                while($get = _fetch($qry))
                {
                    if(isset($_POST['pa'.$get['id']]))
                    {
                        if($get['see_u'])
                            db("DELETE FROM ".$db['msg']." WHERE id = ".convert::ToInt($_POST['pa'.$get['id']]));
                        else
                            db("UPDATE ".$db['msg']." SET `see` = 0 WHERE id = ".convert::ToInt($_POST['pa'.$get['id']]));
                    }
                }
            }

            header("Location: ?action=msg");
        break;
        case 'new':
            $qry = db("SELECT id,nick FROM ".$db['users']." WHERE id != '".convert::ToInt($userid)."' AND `level` != '0' ORDER BY nick"); $users = '';
            if(_rows($qry) >= 1)
            {
                while($get = _fetch($qry))
                {
                    $users .= show(_to_users, array("id" => $get['id'], "selected" => "", "nick" => data($get['id'], "nick")));
                }
            }

            $qry = db("SELECT id,user,buddy FROM ".$db['buddys']." WHERE user = ".convert::ToInt($userid)." ORDER BY user"); $buddys = '';
            if(_rows($qry) >= 1)
            {
                while($get = _fetch($qry))
                {
                    if(db("SELECT id FROM `".$db['users']."` WHERE `id` = ".$get['buddy']." AND `level` != '0'",true))
                        $buddys .= show(_to_buddys, array("id" => $get['buddy'], "selected" => "", "nick" => data($get['buddy'], "nick")));
                }
            }

            $index = show($dir."/new", array("von" => convert::ToInt($userid),
                                             "buddys" => $buddys,
                                             "users" => $users,
                                             "value" => _button_value_msg,
                                             "posttitel" => "",
                                             "error" => "",
                                             "posteintrag" => ""));
        break;
        case 'send':
            if(empty($_POST['titel']) || empty($_POST['eintrag']) || $_POST['buddys'] == "-" && $_POST['users'] == "-" || $_POST['buddys'] != "-" && $_POST['users'] != "-" || $_POST['users'] == convert::ToInt($userid) || $_POST['buddys'] == convert::ToInt($userid))
            {
                if(empty($_POST['titel']))
                    $error = _empty_titel;
                else if(empty($_POST['eintrag']))
                    $error = _empty_eintrag;
                else if($_POST['buddys'] == "-" AND $_POST['users'] == "-")
                    $error = _empty_to;
                else if($_POST['buddys'] != "-" AND $_POST['users'] != "-")
                    $error = _msg_to_just_1;
                else if($_POST['buddys'] OR $_POST['users'] == convert::ToInt($userid))
                    $error = _msg_not_to_me;

                //Error MSG
                $error = show("errors/errortable", array("error" => $error));

                $qry = db("SELECT id FROM ".$db['users']." WHERE id != '".convert::ToInt($userid)."' ORDER BY nick"); $users = '';
                if(_rows($qry) >= 1 )
                {
                    while($get = _fetch($qry))
                    {
                        $selected = ($get['id'] == $_POST['users'] ? 'selected="selected"' : '');
                        $users .= show(_to_users, array("id" => $get['id'], "nick" => data($get['id'], "nick"), "selected" => $selected));
                    }
                }

                $qry = db("SELECT id,user,buddy FROM ".$db['buddys']." WHERE user = ".convert::ToInt($userid)); $buddys = '';
                if(_rows($qry) >= 1 )
                {
                    while($get = _fetch($qry))
                    {
                        $selected = ($get['buddy'] == $_POST['buddys'] ? 'selected="selected"' : '');
                        $buddys .= show(_to_buddys, array("id" => $get['buddy'], "nick" => data($get['buddy'], "nick"), "selected" => $selected));
                    }
                }

                $index = show($dir."/new", array("von" => convert::ToInt($userid),
                                                 "buddys" => $buddys,
                                                 "users" => $users,
                                                 "value" => _button_value_msg,
                                                 "posttitel" => re($_POST['titel']),
                                                 "posteintrag" => re_bbcode($_POST['eintrag']),
                                                 "error" => $error));
              }
              else
              {
                  $to = ($_POST['buddys'] == "-" ? $_POST['users'] : $_POST['buddys']);
                  db("INSERT INTO ".$db['msg']." SET `datum` = '".time()."', `von` = '".convert::ToInt($userid)."', `an` = '".convert::ToInt($to)."', `titel` = '".up($_POST['titel'])."', `nachricht` = '".up($_POST['eintrag'], 1)."', `see` = '1'");
                  db("UPDATE ".$db['userstats']." SET `writtenmsg` = writtenmsg+1 WHERE user = ".convert::ToInt($userid));
                  $index = info(_msg_answer_done, "?action=msg");
              }
        break;
        default:
            ## Posteingang ##
            $qry = db("SELECT * FROM ".$db['msg']." WHERE an = ".convert::ToInt($userid)." AND see_u = '0' ORDER BY datum DESC"); $posteingang = '';
            if(_rows($qry) >= 1)
            {
                $color = 1;
                while($get = _fetch($qry))
                {
                    $absender = (!$get['von'] ? _msg_bot : autor($get['von']));
                    $titel = show(_msg_in_title, array("titel" => re($get['titel'])));
                    $delete = _delete;
                    $date = date("d.m.Y H:i", $get['datum'])._uhr;
                    $new = (!$get['readed'] && !$get['see_u'] ? _newicon : '');

                    $class = ($color % 2) ? "contentMainSecond" : "contentMainFirst"; $color++;
                    $posteingang .= show($dir."/posteingang", array("titel" => $titel,
                                                                    "absender" => $absender,
                                                                    "datum" => $date,
                                                                    "class" => $class,
                                                                    "delete" => $delete,
                                                                    "new" => $new,
                                                                    "id" => $get['id']));
                }
            }

            // Have no Entrys
            if(empty($posteingang))
                $posteingang = show($dir."/posteingang_no_entry", array());

            ## Postausgang ##
            $qry = db("SELECT * FROM ".$db['msg']." WHERE von = ".convert::ToInt($userid)." AND see = 1 ORDER BY datum DESC"); $postausgang = '';
            if(_rows($qry) >= 1)
            {
                $color = 1;
                while($get = _fetch($qry))
                {
                    $titel = show(_msg_out_title, array("titel" => re($get['titel'])));
                    $delete = _msg_delete_sended;
                    $date = date("d.m.Y H:i", $get['datum'])._uhr;
                    $readed = (!$get['readed'] ? _noicon : _yesicon);

                    $class = ($color % 2) ? "contentMainSecond" : "contentMainFirst"; $color++;
                    $postausgang .= show($dir."/postausgang", array("titel" => $titel,
                                                                    "empfaenger" => autor($get['an']),
                                                                    "datum" => $date,
                                                                    "class" => $class,
                                                                    "readed" => $readed,
                                                                    "delete" => $delete,
                                                                    "id" => $get['id']));
                }
            }

            // Have no Entrys
            if(empty($postausgang))
                $postausgang = show($dir."/postausgang_no_entry", array());

            $msghead = show(_msghead, array("nick" => autor(convert::ToInt($userid))));
            $index = show($dir."/msg", array("msghead" => $msghead,
                                             "del" => _msg_del,
                                             "new" => _msg_new,
                                             "showincoming" => $posteingang,
                                             "showsended" => $postausgang));
        break;
    }
}
?>