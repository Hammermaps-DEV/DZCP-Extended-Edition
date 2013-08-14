<?php
/**
 * <DZCP-Extended Edition>
 * @package: DZCP-Extended Edition
 * @author: DZCP Developer Team || Hammermaps.de Developer Team
 * @link: http://www.dzcp.de || http://www.hammermaps.de
 */
 
if(_adminMenu != 'true') exit();

          if(empty($_POST['question']) || empty($_POST['a1']) || empty($_POST['a2']))
            {
              if(empty($_POST['question'])) $error = _empty_votes_question;
              elseif(empty($_POST['a1']))   $error = _empty_votes_answer;
              elseif(empty($_POST['a2']))   $error = _empty_votes_answer;

              $error = show("errors/errortable", array("error" => $error));

          if($_POST['intern']) $intern = "checked=\"checked\"";

          $show = show($dir."/form_vote", array("head" => _votes_admin_head,
                                                "value" => _button_value_add,
                                                "what" => "&amp;do=add",
                                                "question1" => string::decode($_POST['question']),
                                                "a1" => $_POST['a1'],
                                                "closed" => "",
                                                "br1" => "<!--",
                                                "br2" => "-->",
                                                "a2" => $_POST['a2'],
                                                "a3" => $_POST['a3'],
                                                "a4" => $_POST['a4'],
                                                "a5" => $_POST['a5'],
                                                "a6" => $_POST['a6'],
                                                "a7" => $_POST['a7'],
                                                "error" => $error,
                                                "a8" => $_POST['a8'],
                                                "a9" => $_POST['a9'],
                                                "a10" => $_POST['a10'],
                                                "intern" => $intern,
                                                "interna" => _votes_admin_intern,
                                                "question" => _votes_admin_question,
                                                "answer" => _votes_admin_answer));
        } else {
          $qry = db("INSERT INTO ".dba::get('votes')."
                     SET `datum`  = '".time()."',
                         `titel`  = '".string::encode($_POST['question'])."',
                         `intern` = '".convert::ToInt($_POST['intern'])."',
                         `von`    = '".userid()."'");

          $vid = database::get_insert_id();

          $qry = db("INSERT INTO ".dba::get('vote_results')."
                    SET `vid`   = '".convert::ToInt($vid)."',
                        `what`  = 'a1',
                        `sel`   = '".string::encode($_POST['a1'])."'");

          $qry = db("INSERT INTO ".dba::get('vote_results')."
                     SET `vid`  = '".convert::ToInt($vid)."',
                         `what` = 'a2',
                         `sel`  = '".string::encode($_POST['a2'])."'");

          if(!empty($_POST['a3']))
          {
            $qry = db("INSERT INTO ".dba::get('vote_results')."
                       SET `vid`  = '".convert::ToInt($vid)."',
                           `what` = 'a3',
                           `sel`  = '".string::encode($_POST['a3'])."'");
          }
          if(!empty($_POST['a4']))
          {
            $qry = db("INSERT INTO ".dba::get('vote_results')."
                       SET `vid`  = '".convert::ToInt($vid)."',
                           `what` = 'a4',
                           `sel`  = '".string::encode($_POST['a4'])."'");
          }
          if(!empty($_POST['a5']))
          {
            $qry = db("INSERT INTO ".dba::get('vote_results')."
                       SET `vid`  = '".convert::ToInt($vid)."',
                           `what` = 'a5',
                           `sel`  = '".string::encode($_POST['a5'])."'");
          }
          if(!empty($_POST['a6']))
          {
            $qry = db("INSERT INTO ".dba::get('vote_results')."
                       SET `vid`  = '".convert::ToInt($vid)."',
                           `what` = 'a6',
                           `sel`  = '".string::encode($_POST['a6'])."'");
          }
          if(!empty($_POST['a7']))
          {
            $qry = db("INSERT INTO ".dba::get('vote_results')."
                       SET `vid`  = '".convert::ToInt($vid)."',
                           `what` = 'a7',
                           `sel`  = '".string::encode($_POST['a7'])."'");
          }
          if(!empty($_POST['a8']))
          {
            $qry = db("INSERT INTO ".dba::get('vote_results')."
                       SET `vid`  = '".convert::ToInt($vid)."',
                           `what` = 'a8',
                           `sel`  = '".string::encode($_POST['a8'])."'");
          }
          if(!empty($_POST['a9']))
          {
            $qry = db("INSERT INTO ".dba::get('vote_results')."
                       SET `vid`  = '".convert::ToInt($vid)."',
                           `what` = 'a9',
                           `sel`  = '".string::encode($_POST['a9'])."'");
          }
          if(!empty($_POST['a10']))
          {
            $qry = db("INSERT INTO ".dba::get('vote_results')."
                       SET `vid`  = '".convert::ToInt($vid)."',
                           `what` = 'a10',
                           `sel`  = '".string::encode($_POST['a10'])."'");
          }

          $show = info(_vote_admin_successful, "?admin=votes");
        }
