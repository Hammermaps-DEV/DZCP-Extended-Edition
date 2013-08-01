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
  if($_GET['do'] == "edit")
  {
    $qry = db("SELECT * FROM ".dba::get('f_threads')."
               WHERE id = '".convert::ToInt($_GET['id'])."'");
    $get = _fetch($qry);
    if($get['t_reg'] == userid() || permission("forum"))
    {
      if(permission("forum"))
      {
        if($get['sticky'] == 1) $sticky = "checked=\"checked\"";
        if($get['global'] == 1) $global = "checked=\"checked\"";

        $admin = show($dir."/form_admin", array("adminhead" => _forum_admin_head,
                                                "addsticky" => _forum_admin_addsticky,
                                                "sticky" => $sticky,
                                                "addglobal" => _forum_admin_addglobal,
                                                "global" => $global));
      }

        if($get['t_reg'] != 0)
            $form = show("page/editor_regged", array("nick" => autor($get['t_reg'])));
        else
            $form = show("page/editor_notregged", array("postemail" => "", "posthp" => "", "postnick" => ""));

      $qryv = db("SELECT * FROM ".dba::get('votes')." WHERE id = '".$get['vote']."'");
      $getv = _fetch($qryv);

      $toggle = 'collapse';


            $fget = _fetch(db("SELECT s1.intern,s2.id FROM ".dba::get('f_kats')." AS s1
                         LEFT JOIN ".dba::get('f_skats')." AS s2 ON s2.`sid` = s1.id
                         WHERE s2.`id` = '".convert::ToInt($get['kid'])."'"));

            if($getv['intern'] == "1") $intern = 'checked="checked"';
          $intern = ''; $intern_kat = '';
          if($fget['intern'] == "1") { $intern = 'checked="checked"'; $internVisible = 'style="display:none"'; };
      if($getv['closed'] == "1")
          {
            $isclosed = "checked=\"checked\"";
            $display = 'none';
        $toggle = 'expand';
          }

        if(empty($get['vote'])) {
        $vote = show($dir."/form_vote", array("head" => _votes_admin_head,
                                              "value" => _button_value_add,
                                              "what" => "&amp;do=add",
                                              "closed" => "",
                                              "question1" => "",
                                              "a1" => "",
                                              "a2" => "",
                                              "a3" => "",
                                              "a4" => "",
                                              "a5" => "",
                                              "a6" => "",
                                              "a7" => "",
                                              "error" => "",
                                              "br1" => "<!--",
                                              "br2" => "-->",
                                                  "display" => "none",
                                              "a8" => "",
                                              "a9" => "",
                                              "a10" => "",
                                              "intern" => "",
                                              "tgl" => "expand",
                                                  "vote_del" => _forum_vote_del,
                                              "interna" => _votes_admin_intern,
                                              "question" => _votes_admin_question,
                                              "answer" => _votes_admin_answer));
        } elseif(!empty($get['vote'])) {
        $vote = show($dir."/form_vote", array("head" => _votes_admin_edit_head,
                                              "value" => "edit",
                                              "id" => $getv['id'],
                                              "what" => $what,
                                              "value" => _button_value_edit,
                                              "br1" => "",
                                              "br2" => "",
                                              "tgl" => $toggle,
                                                                    "display" => $display,
                                              "question1" => string::decode($getv['titel']),
                                              "a1" => voteanswer("a1", $getv['id']),
                                              "a2" => voteanswer("a2", $getv['id']),
                                              "a3" => voteanswer("a3", $getv['id']),
                                              "a4" => voteanswer("a4", $getv['id']),
                                              "a5" => voteanswer("a5", $getv['id']),
                                              "a6" => voteanswer("a6", $getv['id']),
                                              "a7" => voteanswer("a7", $getv['id']),
                                              "error" => "",
                                              "a8" => voteanswer("a8", $getv['id']),
                                              "a9" => voteanswer("a9", $getv['id']),
                                              "a10" => voteanswer("a10", $getv['id']),
                                              'intern_kat' => $internVisible,
                                              "intern" => $intern,
                                              "isclosed" => $isclosed,
                                                                    "vote_del" => _forum_vote_del,
                                              "closed" => _votes_admin_closed,
                                              "interna" => _votes_admin_intern,
                                              "question" => _votes_admin_question,
                                              "answer" => _votes_admin_answer));

      }
      $dowhat = show(_forum_dowhat_edit_thread, array("id" => $_GET['id']));
      $index = show($dir."/thread", array("titel" => _forum_edit_thread_head,
                                          "nickhead" => _nick,
                                          "topichead" => _forum_topic,
                                          "subtopichead" => _forum_subtopic,
                                          "emailhead" => _email,
                                          "form" => $form,
                                          "reg" => $get['t_reg'],
                                          "id" => "",
                                          "security" => _register_confirm,
                                          "preview" => _preview,
                                          "ip" => _iplog_info,
                                          "eintraghead" => _eintrag,
                                          "what" => _button_value_edit,
                                          "dowhat" => $dowhat,
                                          "error" => "",
                                          "posttopic" => string::decode($get['topic']),
                                          "postsubtopic" => string::decode($get['subtopic']),
                                          "postnick" => string::decode($get['t_nick']),
                                          "postemail" => $get['t_email'],
                                          "posthp" => $get['t_hp'],
                                          "admin" => $admin,
                                          "vote" => $vote,
                                          "posteintrag" => bbcode::parse_html($get['t_text'])));
    } else {
      $index = error(_error_wrong_permissions);
    }
  } elseif($_GET['do'] == "editthread") {
    $qry = db("SELECT * FROM ".dba::get('f_threads')."
               WHERE id = '".convert::ToInt($_GET['id'])."'");
    $get = _fetch($qry);

    if($get['t_reg'] == userid() || permission("forum"))
    {
      if($get['t_reg'] != 0 || permission('forum'))
      {
        $toCheck = empty($_POST['eintrag']);
      } else {
        $toCheck = empty($_POST['topic']) || empty($_POST['nick']) || empty($_POST['email']) || empty($_POST['eintrag']) || $_POST['secure'] != $_SESSION['sec_'.$dir] || empty($_SESSION['sec_'.$dir]);
      }

      if($toCheck)
        {
        if($get['t_reg'] != 0)
        {
          if(empty($_POST['eintrag'])) $error = _empty_eintrag;
          $form = show("page/editor_regged", array("nick" => autor($get['t_reg'])));

        } else {
          if(($_POST['secure'] != $_SESSION['sec_'.$dir]) || empty($_SESSION['sec_'.$dir])) $error = _error_invalid_regcode;
          elseif(empty($_POST['topic'])) $error = _empty_topic;
            elseif(empty($_POST['nick'])) $error = _empty_nick;
            elseif(empty($_POST['email'])) $error = _empty_email;
            elseif(!check_email($_POST['email'])) $error = _error_invalid_email;
            else if(check_email_trash_mail($_POST['email'])) $error = _error_trash_mail;
            elseif(empty($_POST['eintrag'])) $error = _empty_eintrag;

          $form = show("page/editor_notregged", array("nickhead" => _nick,
                                                      "emailhead" => _email,
                                                      "hphead" => _hp));
        }

          $error = show("errors/errortable", array("error" => $error));

        if(permission("forum"))
        {
          if(isset($_POST['sticky'])) $sticky = "checked";
          if(isset($_POST['global'])) $global = "checked";

          $admin = show($dir."/form_admin", array("adminhead" => _forum_admin_head,
                                                  "addsticky" => _forum_admin_addsticky,
                                                  "sticky" => $sticky,
                                                  "addglobal" => _forum_admin_addglobal,
                                                  "global" => $global));
        }
          $qryv = db("SELECT * FROM ".dba::get('votes')."
                    WHERE id = '".$get['vote']."'");
      $getv = _fetch($qryv);

            $fget = _fetch(db("SELECT s1.intern,s2.id FROM ".dba::get('f_kats')." AS s1
                         LEFT JOIN ".dba::get('f_skats')." AS s2 ON s2.`sid` = s1.id
                         WHERE s2.`id` = '".convert::ToInt($_GET['kid'])."'"));

            if($_POST['intern']) $intern = 'checked="checked"';
          $intern = ''; $intern_kat = '';
          if($fget['intern'] == "1") { $intern = 'checked="checked"'; $internVisible = 'style="display:none"'; };
            if($_POST['closed']) $closed = "checked=\"checked\"";

            if(empty($_POST['question'])) $display = "none";
            $display = "";

          $vote = show($dir."/form_vote", array("head" => _votes_admin_head,
                                              "value" => _button_value_add,
                                              "what" => "&amp;do=add",
                                              "question1" => string::decode($_POST['question']),
                                              "a1" => $_POST['a1'],
                                              "closed" => $closed,
                        "tgl" => "expand",
                                              "br1" => "<!--",
                                              "br2" => "-->",
                                              "display" => $display,
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
                                              'intern_kat' => $internVisible,
                                              "intern" => $intern,
                                              "vote_del" => _forum_vote_del,
                                              "interna" => _votes_admin_intern,
                                              "question" => _votes_admin_question,
                                              "answer" => _votes_admin_answer));

        $dowhat = show(_forum_dowhat_edit_thread, array("id" => $_GET['id']));
          $index = show($dir."/thread", array("titel" => _forum_edit_thread_head,
                                                "nickhead" => _nick,
                                            "subtopichead" => _forum_subtopic,
                                            "topichead" => _forum_topic,
                                            "ip" => _iplog_info,
                                            "form" => $form,
                                            "reg" => $_POST['reg'],
                                            "preview" => _preview,
                                                "emailhead" => _email,
                                                "id" => "",
                                            "security" => _register_confirm,
                                            "what" => _button_value_edit,
                                            "dowhat" => $dowhat,
                                            "posthp" => $_POST['hp'],
                                              "postemail" => $_POST['email'],
                                              "postnick" => string::decode($_POST['nick']),
                                              "posteintrag" => string::decode($_POST['eintrag']),
                                            "posttopic" => string::decode($_POST['topic']),
                                            "postsubtopic" => string::decode($_POST['subtopic']),
                                              "error" => $error,
                                            "admin" => $admin,
                                                "vote" => $vote,
                                            "eintraghead" => _eintrag));
      } else {
        $qryt = db("SELECT * FROM ".dba::get('f_threads')."
                    WHERE id = '".convert::ToInt($_GET['id'])."'");
        $gett = _fetch($qryt);
          if(!empty($gett['vote']))
      {
       $qryv = db("SELECT * FROM ".dba::get('vote_results')."
                   WHERE vid = '".$gett['vote']."'");
     $getv = _fetch($qryv);

       $vid = $gett['vote'];

        $upd = db("UPDATE ".dba::get('votes')."
                   SET `titel`  = '".string::encode($_POST['question'])."',
                       `intern` = '".convert::ToInt($_POST['intern'])."',
                       `closed` = '".convert::ToInt($_POST['closed'])."'
                   WHERE id = '".$gett['vote']."'");

        $upd1 = db("UPDATE ".dba::get('vote_results')."
                    SET `sel` = '".string::encode($_POST['a1'])."'
                    WHERE what = 'a1'
                    AND vid = '".$gett['vote']."'");

        $upd2 = db("UPDATE ".dba::get('vote_results')."
                    SET `sel` = '".string::encode($_POST['a2'])."'
                    WHERE what = 'a2'
                    AND vid = '".$gett['vote']."'");

        for($i=3; $i<=10; $i++)
        {
          if(!empty($_POST['a'.$i.'']))
          {
            if(cnt(dba::get('vote_results'), " WHERE vid = '".$gett['vote']."' AND what = 'a".$i."'") != 0)
            {
              $upd = db("UPDATE ".dba::get('vote_results')."
                         SET `sel` = '".string::encode($_POST['a'.$i.''])."'
                         WHERE what = 'a".$i."'
                         AND vid = '".$gett['vote']."'");
            } else {
              $ins = db("INSERT INTO ".dba::get('vote_results')."
                         SET `vid` = '".$gett['vote']."',
                             `what` = 'a".$i."',
                             `sel` = '".string::encode($_POST['a'.$i.''])."'");
            }
          }

          if(cnt(dba::get('vote_results'), " WHERE vid = '".$gett['vote']."' AND what = 'a".$i."'") != 0 && empty($_POST['a'.$i.'']))
          {
            $del = db("DELETE FROM ".dba::get('vote_results')."
                       WHERE vid = '".$gett['vote']."'
                       AND what = 'a".$i."'");
          }
        }
        } elseif(empty($gett['vote']) && !empty($_POST['question'])) {
          $qry = db("INSERT INTO ".dba::get('votes')."
                     SET `datum`  = '".time()."',
                         `titel`  = '".string::encode($_POST['question'])."',
                         `intern` = '".convert::ToInt($_POST['intern'])."',
                                     `forum`  = 1,
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
        } else { $vid = ""; }

        if($_POST['vote_del'] == 1) {
        $qry = db("DELETE FROM ".dba::get('votes')."
                   WHERE id = '".$gett['vote']."'");

        $qry = db("DELETE FROM ".dba::get('vote_results')."
                   WHERE vid = '".$gett['vote']."'");

        $voteid = "vid_".$gett['vote'];
        $qry = db("DELETE FROM ".dba::get('ipcheck')."
                   WHERE what = '".$voteid."'");
        $vid = "";
        }

        $editedby = show(_edited_by, array("autor" => autor(),
                                           "time" => date("d.m.Y H:i", time())._uhr));

          $qry = db("UPDATE ".dba::get('f_threads')."
                             SET `topic`    = '".string::encode($_POST['topic'])."',
                       `subtopic` = '".string::encode($_POST['subtopic'])."',
                       `t_nick`   = '".string::encode($_POST['nick'])."',
                       `t_email`  = '".string::encode($_POST['email'])."',
                       `t_hp`     = '".links($_POST['hp'])."',
                       `t_text`   = '".string::encode($_POST['eintrag'],1)."',
                       `sticky`   = '".convert::ToInt($_POST['sticky'])."',
                       `global`   = '".convert::ToInt($_POST['global'])."',
                                            `vote`     = '".$vid."',
                       `edited`   = '".addslashes($editedby)."'
                   WHERE id = '".convert::ToInt($_GET['id'])."'");

      $checkabo = db("SELECT s1.user,s1.fid,s2.nick,s2.id,s2.email FROM ".dba::get('f_abo')." AS s1
                        LEFT JOIN ".dba::get('users')." AS s2 ON s2.id = s1.user
                      WHERE s1.fid = '".convert::ToInt($_GET['id'])."'");
        while($getabo = _fetch($checkabo))
        {
        if(userid() != $getabo['user'])
        {
          $topic = db("SELECT topic FROM ".dba::get('f_threads')." WHERE id = '".convert::ToInt($_GET['id'])."'");
          $gettopic = _fetch($topic);

          $subj = show(string::decode(settings('eml_fabo_tedit_subj')), array("titel" => $title));

           $message = show(string::decode(settings('eml_fabo_tedit')), array("nick" => string::decode($getabo['nick']),
                                                                "postuser" => fabo_autor(),
                                                            "topic" => $gettopic['topic'],
                                                            "titel" => $title,
                                                            "domain" => $httphost,
                                                            "id" => convert::ToInt($_GET['id']),
                                                            "entrys" => "1",
                                                            "page" => "1",
                                                            "text" => bbcode::parse_html($_POST['eintrag']),
                                                            "clan" => $clanname));

           mailmgr::AddContent($subj,$message);
           mailmgr::AddAddress(string::decode($getabo['email']));
        }
      }

        $index = info(_forum_editthread_successful, "?action=showthread&amp;id=".$gett['id']."");

      }
    } else $index = error(_error_wrong_permissions);
  } elseif($_GET['do'] == "add") {
    if(settings("reg_forum") == "1" && checkme() == "unlogged")
    {
      $index = error(_error_unregistered);
    } else {
      if(!ipcheck("fid(".$_GET['kid'].")", config('f_forum')))
      {
        if(permission("forum"))
        {
          $admin = show($dir."/form_admin", array("adminhead" => _forum_admin_head,
                                                  "addsticky" => _forum_admin_addsticky,
                                                  "sticky" => "",
                                                  "addglobal" => _forum_admin_addglobal,
                                                  "global" => ""));
        } else {
          $admin = "";
        }

        $fget = _fetch(db("SELECT s1.intern,s2.id FROM ".dba::get('f_kats')." AS s1
                       LEFT JOIN ".dba::get('f_skats')." AS s2 ON s2.`sid` = s1.id
                       WHERE s2.`id` = '".convert::ToInt($_GET['kid'])."'"));
                $intern = ''; $intern_kat = '';
                if($fget['intern'] == "1") { $intern = 'checked="checked"'; $internVisible = 'style="display:none"'; };

        if(userid() != 0)
            $form = show("page/editor_regged", array("nick" => autor()));
        else
            $form = show("page/editor_notregged", array("postemail" => "", "posthp" => "", "postnick" => ""));

        $vote = show($dir."/form_vote", array("head" => _votes_admin_head,
                                              "value" => _button_value_add,
                                              "what" => "&amp;do=add",
                                              "closed" => "",
                                              "question1" => "",
                                              "tgl" => "expand",
                                              "a1" => "",
                                              "a2" => "",
                                              "a3" => "",
                                              "a4" => "",
                                              "a5" => "",
                                              "a6" => "",
                                              "a7" => "",
                                              "error" => "",
                                              "br1" => "<!--",
                                              "br2" => "-->",
                                                "display" => "none",
                                              "a8" => "",
                                              "a9" => "",
                                              "a10" => "",
                                              'intern_kat' => $internVisible,
                                              "intern" => $intern,
                                                "vote_del" => _forum_vote_del,
                                              "interna" => _votes_admin_intern,
                                              "question" => _votes_admin_question,
                                              "answer" => _votes_admin_answer));

        $dowhat = show(_forum_dowhat_add_thread, array("kid" => $_GET['kid']));

        $index = show($dir."/thread", array("titel" => _forum_new_thread_head,
                                            "nickhead" => _nick,
                                            "topichead" => _forum_topic,
                                            "subtopichead" => _forum_subtopic,
                                            "emailhead" => _email,
                                            "id" => $_GET['kid'],
                                            "reg" => "",
                                            "security" => _register_confirm,
                                            "ip" => _iplog_info,
                                            "preview" => _preview,
                                            "form" => $form,
                                            "eintraghead" => _eintrag,
                                            "what" => _button_value_add,
                                            "dowhat" => $dowhat,
                                            "error" => "",
                                            "posttopic" => "",
                                            "postsubtopic" => "",
                                            "posthp" => "",
                                            "postnick" => "",
                                            "postemail" => "",
                                            "admin" => $admin,
                                            "vote" => $vote,
                                            "posteintrag" => ""));
      } else {
        $index = error(show(_error_flood_post, array("sek" => config('f_forum'))));
      }
    }
  } elseif($_GET['do'] == "addthread") {
      if(_rows(db("SELECT id FROM ".dba::get('f_skats')." WHERE id = '".convert::ToInt($_GET['kid'])."'")) == 0) {
          $index = error(_id_dont_exist);
      } else {
        if(settings("reg_forum") == "1" && checkme() == "unlogged")
        {
            $index = error(_error_have_to_be_logged);
        } else {
            if(userid() != 0)
                $toCheck = empty($_POST['eintrag']) || empty($_POST['topic']);
            else
                $toCheck = empty($_POST['topic']) || empty($_POST['nick']) || empty($_POST['email']) || empty($_POST['eintrag']) || !check_email($_POST['email']) || $_POST['secure'] != $_SESSION['sec_'.$dir] || empty($_SESSION['sec_'.$dir]);
            if($toCheck)
            {
                if(userid() != 0)
                {
                    if(empty($_POST['eintrag'])) $error = _empty_eintrag;
                    elseif(empty($_POST['topic'])) $error = _empty_topic;
                } else {
                    if(($_POST['secure'] != $_SESSION['sec_'.$dir]) || empty($_SESSION['sec_'.$dir])) $error = _error_invalid_regcode;
                    elseif(empty($_POST['topic'])) $error = _empty_topic;
                    elseif(empty($_POST['nick'])) $error = _empty_nick;
                    elseif(empty($_POST['email'])) $error = _empty_email;
                    elseif(!check_email($_POST['email'])) $error = _error_invalid_email;
                    elseif(check_email_trash_mail($_POST['email'])) $error = _error_trash_mail;
                    elseif(empty($_POST['eintrag'])) $error = _empty_eintrag;
                }

                $error = show("errors/errortable", array("error" => $error));

                if(permission("forum"))
                {
                    if(isset($_POST['sticky'])) $sticky = "checked";
                    if(isset($_POST['global'])) $global = "checked";

                    $admin = show($dir."/form_admin", array("adminhead" => _forum_admin_head,
                                                                                                    "addsticky" => _forum_admin_addsticky,
                                                                                                    "sticky" => $sticky,
                                                                                                    "addglobal" => _forum_admin_addglobal,
                                                                                                    "global" => $global));
                } else {
                    $admin = "";
                }

                if(userid() != 0)
                    $form = show("page/editor_regged", array("nick" => autor()));
                else
                    $form = show("page/editor_notregged", array("postemail" => "", "posthp" => "", "postnick" => ""));

            $fget = _fetch(db("SELECT s1.intern,s2.id FROM ".dba::get('f_kats')." AS s1
                                                 LEFT JOIN ".dba::get('f_skats')." AS s2 ON s2.`sid` = s1.id
                                                 WHERE s2.`id` = '".convert::ToInt($_GET['kid'])."'"));

            if($_POST['intern']) $intern = 'checked="checked"';
            $intern = ''; $intern_kat = '';
            if($fget['intern'] == 1) { $intern = 'checked="checked"'; $internVisible = 'style="display:none"'; };
            if($_POST['closed']) $closed = "checked=\"checked\"";

            if(!empty($_POST['question'])) $display = "";
            $display = "none";

            $vote = show($dir."/form_vote", array("head" => _votes_admin_head,
                            "value" => _button_value_add,
                            "what" => "&amp;do=add",
                            "question1" => string::decode($_POST['question']),
                            "a1" => $_POST['a1'],
                            "closed" => $closed,
                            "br1" => "<!--",
                            "br2" => "-->",
                            "tgl" => "expand",
                            "display" => $display,
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
                            "vote_del" => _forum_vote_del,
                            'intern_kat' => $internVisible,
                            "intern" => $intern,
                            "interna" => _votes_admin_intern,
                            "question" => _votes_admin_question,
                            "answer" => _votes_admin_answer));

                    $dowhat = show(_forum_dowhat_add_thread, array("kid" => $_GET['kid']));
                $index = show($dir."/thread", array("titel" => _forum_new_thread_head,
                                                    "nickhead" => _nick,
                                                                                            "reg" => "",
                                                                                            "subtopichead" => _forum_subtopic,
                                                                                            "topichead" => _forum_topic,
                                                                                            "form" => $form,
                                                    "emailhead" => _email,
                                                    "id" => $_GET['kid'],
                                                                                            "security" => _register_confirm,
                                                                                            "what" => _button_value_add,
                                                                                            "preview" => _preview,
                                                                                            "dowhat" => $dowhat,
                                                                                            "posthp" => $_POST['hp'],
                                                "postemail" => $_POST['email'],
                                                "postnick" => string::decode($_POST['nick']),
                                                                                            "ip" => _iplog_info,
                                                "posteintrag" => string::decode($_POST['eintrag']),
                                                                                            "posttopic" => string::decode($_POST['topic']),
                                                                                            "postsubtopic" => string::decode($_POST['subtopic']),
                                                "error" => $error,
                                                                                            "admin" => $admin,
                                                "vote" => $vote,
                                                    "eintraghead" => _eintrag));
            } else {
                if(!empty($_POST['question']))
                {
                        $fgetvote = _fetch(db("SELECT s1.intern,s2.id FROM ".dba::get('f_kats')." AS s1
                                                                     LEFT JOIN ".dba::get('f_skats')." AS s2 ON s2.`sid` = s1.id
                                                                     WHERE s2.`id` = '".convert::ToInt($_GET['kid'])."'"));

                        if($fgetvote['intern'] == 1) $ivote = "`intern` = '1',";
                        else $ivote = "`intern` = '".convert::ToInt($_POST['intern'])."',";

                        $qry = db("INSERT INTO ".dba::get('votes')."
                                             SET `datum`  = '".time()."',
                                                     `titel`  = '".string::encode($_POST['question'])."',
                                                     ".$ivote."
                                                     `forum`  = 1,
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
            } else { $vid = ""; }

            $qry = db("INSERT INTO ".dba::get('f_threads')."
                                 SET 	`kid`      = '".convert::ToInt($_GET['kid'])."',
                                                `t_date`   = '".time()."',
                                                `topic`    = '".string::encode($_POST['topic'])."',
                                                `subtopic` = '".string::encode($_POST['subtopic'])."',
                                                `t_nick`   = '".string::encode($_POST['nick'])."',
                                                `t_email`  = '".string::encode($_POST['email'])."',
                                                `t_hp`     = '".links($_POST['hp'])."',
                                                `t_reg`    = '".userid()."',
                                                `t_text`   = '".string::encode($_POST['eintrag'])."',
                                                `sticky`   = '".convert::ToInt($_POST['sticky'])."',
                                                `global`   = '".convert::ToInt($_POST['global'])."',
                                                `ip`       = '".visitorIp()."',
                                                `lp`       = '".time()."',
                                                `vote`     = '".$vid."',
                                                `first`	= '1'");
                $thisFID = database::get_insert_id();
                wire_ipcheck("fid(".$_GET['kid'].")");

                $update = db("UPDATE ".dba::get('userstats')."
                                            SET `forumposts` = forumposts+1
                                            WHERE `user`       = '".userid()."'");

                $index = info(_forum_newthread_successful, "?action=showthread&amp;id=".$thisFID."#p1");
            }
        }
  }
  }
}