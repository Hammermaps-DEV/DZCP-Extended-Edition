<?php
/**
 * <DZCP-Extended Edition>
 * @package: DZCP-Extended Edition
 * @author: DZCP Developer Team || Hammermaps.de Developer Team
 * @link: http://www.dzcp.de || http://www.hammermaps.de
 */
 
if(_adminMenu != 'true') exit();

        $qry = db("SELECT * FROM ".dba::get('votes')."
                   WHERE id = '".convert::ToInt($_GET['id'])."'");
        $get = _fetch($qry);

        if($get['intern'] == "1") $intern = "checked=\"checked\"";
        if($get['closed'] == "1") $isclosed = "checked=\"checked\"";

        $what = "&amp;do=editvote&amp;id=".$_GET['id']."";

        $show = show($dir."/form_vote", array("head" => _votes_admin_edit_head,
                                              "value" => "edit",
                                              "id" => $_GET['id'],
                                              "what" => $what,
                                              "value" => _button_value_edit,
                                              "br1" => "",
                                              "br2" => "",
                                              "question1" => string::decode($get['titel']),
                                              "a1" => voteanswer("a1",$_GET['id']),
                                              "a2" => voteanswer("a2",$_GET['id']),
                                              "a3" => voteanswer("a3",$_GET['id']),
                                              "a4" => voteanswer("a4",$_GET['id']),
                                              "a5" => voteanswer("a5",$_GET['id']),
                                              "a6" => voteanswer("a6",$_GET['id']),
                                              "a7" => voteanswer("a7",$_GET['id']),
                                              "error" => "",
                                              "a8" => voteanswer("a8",$_GET['id']),
                                              "a9" => voteanswer("a9",$_GET['id']),
                                              "a10" => voteanswer("a10",$_GET['id']),
                                              "intern" => $intern,
                                              "isclosed" => $isclosed,
                                              "closed" => _votes_admin_closed,
                                              "interna" => _votes_admin_intern,
                                              "question" => _votes_admin_question,
                                              "answer" => _votes_admin_answer));
