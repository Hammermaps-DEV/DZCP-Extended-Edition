﻿<?php
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
    if(isset($_POST['monat'])) $monat = $_POST['monat'];
    elseif(isset($_GET['m']))  $monat = $_GET['m'];
    else $monat = date("m");

    if(isset($_POST['jahr'])) $jahr = $_POST['jahr'];
    elseif(isset($_GET['y'])) $jahr = $_GET['y'];
    else $jahr = date("Y");

    for($i = 1; $i <= 12; $i++)
    {
    if($monat == $i) $sel = "selected=\"selected\"";
    else $sel = "";

            $mname = array("1" => _jan,
                    "2" => _feb,
                    "3" => _mar,
                    "4" => _apr,
                    "5" => _mai,
                    "6" => _jun,
                    "7" => _jul,
                    "8" => _aug,
                    "9" => _sep,
                    "10" => _okt,
                    "11" => _nov,
                    "12" => _dez);

                    $month .= show(_select_field, array("value" => cal($i),
                            "sel" => $sel,
                            "what" => $mname[$i]));
    }

    for( $i = date("Y")-5; $i < date("Y")+3; $i++)
    {
    if($jahr == $i) $sel = "selected=\"selected\"";
        else $sel = "";

        $year .= show(_select_field, array("value" => $i,
        "sel" => $sel,
        "what" => $i));
    }

    $ktoday = mktime(0,0,0,date("n"),date("d"),date("Y"));
    $i = 1;
    while($i <= 31 && checkdate($monat, $i, $jahr))
    {
        unset($data);
        for($iw = 1; $iw <= 7; $iw++)
        {
        unset($bdays, $cws, $infoBday, $infoCW, $infoEvent);
        $datum = mktime(0,0,0,$monat,$i,$jahr);
        $wday = getdate($datum);
            $wday = $wday['wday'];

            if(!$wday) $wday = 7;

            if($wday != $iw)
            {
            $data .= '<td class="calDay"></td>';
        } else {
        $qry = db("SELECT id,bday,nick FROM ".$db['users']."
        WHERE bday LIKE '".cal($i).".".$monat.".____"."'");
        if(_rows($qry))
        {
          while($get = _fetch($qry)) $infoBday .= jsconvert(_kal_birthday.rawautor($get['id']));

              $info = ' onmouseover="DZCP.showInfo(\''.$infoBday.'\')" onmouseout="DZCP.hideInfo()"';
              $bdays = '<a href="../user/?action=userlist&amp;show=bday&amp;time='.$datum.'"'.$info.'><img src="../inc/images/bday.gif" alt="" /></a>';
              } else {
              $bdays = "";
        }

          $qry = db("SELECT datum,gegner FROM ".$db['cw']."
                     WHERE DATE_FORMAT(FROM_UNIXTIME(datum), '%d.%m.%Y') = '".cal($i).".".$monat.".".$jahr."'");
                         if(_rows($qry))
                         {
          while($get = _fetch($qry)) $infoCW .= jsconvert(_kal_cw.re($get['gegner']));

                      $info = ' onmouseover="DZCP.showInfo(\''.$infoCW.'\')" onmouseout="DZCP.hideInfo()"';
                      $cws = '<a href="../clanwars/?action=kalender&amp;time='.$datum.'"'.$info.'><img src="../inc/images/cw.gif" alt="" /></a>';
        } else {
          $cws = "";
                      }

                      $qry = db("SELECT datum,title FROM ".$db['events']."
                      WHERE DATE_FORMAT(FROM_UNIXTIME(datum), '%d.%m.%Y') = '".cal($i).".".$monat.".".$jahr."'");
        if(_rows($qry))
        {
          while($get = _fetch($qry)) $infoEvent .= jsconvert(_kal_event.re($get['title']));

              $info = ' onmouseover="DZCP.showInfo(\''.$infoEvent.'\')" onmouseout="DZCP.hideInfo()"';
              $event = '<a href="?action=show&amp;time='.$datum.'"'.$info.'><img src="../inc/images/event.gif" alt="" /></a>';
              } else {
          $event = "";
              }

              $events = $bdays." ".$cws." ".$event;


              if($_GET['hl'] == $i) $day = '<span class="fontMarked">'.cal($i).'</span>';
              else $day = cal($i);

              if(!checkdate($monat, $i, $jahr))
        {
          $data .= '<td class="calDay"></td>';
    } elseif($datum == $ktoday) {
    $data .= show($dir."/day", array("day" => $day,
                  "event" => $events,
                          "class" => "calToday"));
              } else {
              $data .= show($dir."/day", array("day" => $day,
                      "event" => $events,
                      "class" => "calDay"));
              }
              $i++;
              }
              }
              $show .= "<tr>".$data."</tr>";
              }

              $index = show($dir."/kalender", array("monate" => $month,
              "jahr" => $year,
              "show" => $show,
              "what" => _button_value_show,
              "montag" => _montag,
              "dienstag" => _dienstag,
              "mittwoch" => _mittwoch,
              "donnerstag" => _donnerstag,
              "freitag" => _freitag,
              "samstag" => _samstag,
              "sonntag" => _sonntag,
              "head" => _kalender_head));
}
?>