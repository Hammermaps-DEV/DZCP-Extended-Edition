<?php
#####################
## Admin Menu-File ##
#####################
if(_adminMenu != 'true')
    exit();

    $where = $where.': '._nletter;
        if($_GET['do'] == 'preview')
    {
      $show = show($dir."/nletter_prev", array("head" => _nletter_prev_head,
                                               "text" => bbcode::nletter($_POST['eintrag'])));
      echo '<table class="mainContent" cellspacing="1">'.$show.'</table>';
      exit;
    } elseif($_GET['do'] == "send") {
        if(empty($_POST['eintrag']) || $_POST['to'] == "-")
          {
            if(empty($_POST['eintrag'])) $error = _empty_eintrag;
            elseif($_POST['to'] == "-") $error = _empty_to;

            $error = show("errors/errortable", array("error" => $error));

            $qry = db("SELECT id,name FROM ".dba::get('squads')." ORDER BY name");
            while($get = _fetch($qry))
            {
                $squads .= show(_to_squads, array("id" => $get['id'], "sel" => ($_POST['to'] == $get['id'] ? 'selected="selected"' : ''), "name" => string::decode($get['name'])));
            }

            $show = show($dir."/nletter", array("von" => convert::ToInt($userid),
                                                "an" => _to,
                                                "who" => _msg_global_who,
                                                "reg" => _msg_global_reg,
                                                "selr" => ($_POST['to'] == "reg" ? 'selected="selected"' : ''),
                                                "selm" => ($_POST['to'] == "member" ? 'selected="selected"' : ''),
                                                "sell" => ($_POST['to'] == "leader" ? 'selected="selected"' : ''),
                                                "value" => _button_value_nletter,
                                                "preview" => _preview,
                                                "allmembers" => _msg_global_all,
                                                "all_leader" => _msg_all_leader,
                                                "leader" => _msg_leader,
                                                "squad" => _msg_global_squad,
                                                "squads" => $squads,
                                                "posteintrag" => string::decode($_POST['eintrag']),
                                                "titel" => _nletter_head,
                                                "nickhead" => _nick,
                                                "error" => $error,
                                                "eintraghead" => _eintrag));
          } else {
        if($_POST['to'] == "reg")
        {
                  $message = show(string::decode(settings('eml_nletter')), array("text" => bbcode::nletter($_POST['eintrag'])));
                  $subject = string::decode(settings('eml_nletter_subj'));

          $qry = db("SELECT email FROM ".dba::get('users')."
                     WHERE nletter = 1");
          while($get = _fetch($qry))
          {
            sendMail($get['email'],$subject,$message);
          }

              $qry = db("UPDATE ".dba::get('userstats')."
                         SET `writtenmsg` = writtenmsg+1
                         WHERE user = ".convert::ToInt($userid));

              $show = info(_msg_reg_answer_done, "?admin=nletter");

        } elseif($_POST['to'] == "member") {
          $message = show(string::decode(settings('eml_nletter')), array("text" => bbcode::nletter($_POST['eintrag'])));
                  $subject = string::decode(settings('eml_nletter_subj'));

          $qry = db("SELECT email FROM ".dba::get('users')."
                     WHERE level >= 2");
          while($get = _fetch($qry))
          {
            sendMail($get['email'],$subject,$message);
          }

              $qry = db("UPDATE ".dba::get('userstats')."
                        SET `writtenmsg` = writtenmsg+1
                        WHERE user = ".convert::ToInt($userid));

              $show = info(_msg_member_answer_done, "?admin=nletter");
        } elseif($_POST['to'] == "leader") {
          $message = show(string::decode(settings('eml_nletter')), array("text" => bbcode::nletter($_POST['eintrag'])));
                  $subject = string::decode(settings('eml_nletter_subj'));

          $qry = db("SELECT s2.email	FROM ".dba::get('squaduser')." AS s1
                     LEFT JOIN ".dba::get('users')." AS s2 ON s2.id=s1.user
                     LEFT JOIN ".dba::get('userpos')." AS s3 ON s3.squad=s1.squad AND s3.user=s1.user
                     LEFT JOIN ".dba::get('pos')." AS s4 ON s4.id=s3.posi
                     WHERE s4.nletter = '1'");

          while($get = _fetch($qry))
          {
            sendMail($get['email'],$subject,$message);
          }

              $qry = db("UPDATE ".dba::get('userstats')."
                          SET `writtenmsg` = writtenmsg+1
                          WHERE user = ".convert::ToInt($userid));

              $show = info(_msg_member_answer_done, "?admin=nletter");
        } else {
          $message = show(string::decode(settings('eml_nletter')), array("text" => bbcode::nletter($_POST['eintrag'])));
                  $subject = string::decode(settings('eml_nletter_subj'));

          $qry = db("SELECT s2.email FROM ".dba::get('squaduser')." AS s1
                     LEFT JOIN ".dba::get('users')." AS s2
                     ON s1.user = s2.id
                     WHERE s1.squad = '".$_POST['to']."'");
          while($get = _fetch($qry))
          {
            sendMail($get['email'],$subject,$message);
          }

              $qry = db("UPDATE ".dba::get('userstats')."
                          SET `writtenmsg` = writtenmsg+1
                          WHERE user = ".convert::ToInt($userid));

              $show = info(_msg_squad_answer_done, "?admin=nletter");
        }
      }
    } else {
      $qry = db("SELECT id,name FROM ".dba::get('squads')."
         ORDER BY name");
          while($get = _fetch($qry))
          {
              $squads .= show(_to_squads, array("id" => $get['id'],
                                                "sel" => "",
                                                   "name" => string::decode($get['name'])));
          }

      $show = show($dir."/nletter", array("von" => convert::ToInt($userid),
                                             "an" => _to,
                                            "selr" => "",
                                          "selm" => "",
                                          "who" => _msg_global_who,
                                            "squads" => $squads,
                                          "preview" => _preview,
                                          "reg" => _msg_global_reg,
                                          "allmembers" => _msg_global_all,
                                          "all_leader" => _msg_all_leader,
                                          "leader" => _msg_leader,
                                          "squad" => _msg_global_squad,
                                               "titel" => _nletter_head,
                                          "value" => _button_value_nletter,
                                          "nickhead" => _nick,
                                          "eintraghead" => _eintrag,
                                          "error" => "",
                                          "posteintrag" => ""));
      }