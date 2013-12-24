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
$where = $where.' - '._away_new;
  if(checkme() == "unlogged" || checkme() < "2")
  {
    $index = error(_error_wrong_permissions);
  } else {

     $date1 = show(_dropdown_date, array("day" => dropdown("day",date("d",time())),
                                         "month" => dropdown("month",date("m",time())),
                                         "year" => dropdown("year",date("Y",time()))));

     $date2 = show(_dropdown_date2, array("tag" => dropdown("day",date("d",time())),
                                          "monat" => dropdown("month",date("m",time())),
                                          "jahr" => dropdown("year",date("Y",time()))));

    $index = show($dir."/form_away", array("head" => _away_new_head,
                                            "action" => "new&amp;do=set",
                                           "error" => "",
                                           "reason" => _away_reason,
                                           "from" => _from,
                                           "to" => _away_to,
                                           "date1" => $date1,
                                              "date2" => $date2,
                                           "comment" => _news_kommentar,
                                           "titel" => "",
                                           "text" => "",
                                           "submit" => _button_value_add));

     if($_GET['do'] == "set")
     {
       $abdata = mktime(0,0,0,$_POST['m'],$_POST['t'],$_POST['j']);
       $bisdata = mktime(0,0,0,$_POST['monat'],$_POST['tag'],$_POST['jahr']);

     if(empty($_POST['titel']) || empty($_POST['reason']) || $bisdata == $abdata || $abdata > $bisdata)
     {
        if(empty($_POST['titel'])) $error = show("errors/errortable", array("error" => _away_empty_titel));
        if(empty($_POST['reason'])) $error = show("errors/errortable", array("error" => _away_empty_reason));
        if($bisdata == $abdata) $error = show("errors/errortable", array("error" => _away_error_1));
        if($abdata > $bisdata) $error = show("errors/errortable", array("error" => _away_error_2));

        $date1 = show(_dropdown_date, array("day" => dropdown("day",$_POST['t']),
                                               "month" => dropdown("month",$_POST['m']),
                                               "year" => dropdown("year",$_POST['j'])));

        $date2 = show(_dropdown_date2, array("tag" => dropdown("day",$_POST['tag']),
                                               "monat" => dropdown("month",$_POST['monat']),
                                                "jahr" => dropdown("year",$_POST['jahr'])));

        $index = show($dir."/form_away", array("head" => _away_new_head,
                                               "action" => "new&amp;do=set",
                                               "error" => $error,
                                               "reason" => _away_reason,
                                               "from" => _from,
                                               "to" => _away_to,
                                               "date1" => $date1,
                                                 "date2" => $date2,
                                               "comment" => _news_kommentar,
                                               "titel" => $_POST['titel'],
                                               "text" => $_POST['reason'],
                                               "submit" => _button_value_add));

     } else {

      $time = mktime(23,59,59,$_POST['monat'],$_POST['tag'],$_POST['jahr']);

             $qry = db("INSERT INTO ".dba::get('away')."
                        SET `userid`= '".userid()."',
                                 `start`= '".convert::ToInt($abdata)."',
                                 `end`= '".convert::ToInt($time)."',
                            `titel`= '".string::encode($_POST['titel'])."',
                            `reason`= '".string::encode($_POST['reason'])."',
                            `date`= '".time()."'");


               $index = info(_away_successful_added, "?index=away");
              }
             }
  }
}