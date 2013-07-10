<?php
/**
 * <DZCP-Extended Edition>
 * @package: DZCP-Extended Edition
 * @author: DZCP Developer Team || Hammermaps.de Developer Team
 * @link: http://www.dzcp.de || http://www.hammermaps.de
 */

#####################
##### Menu-File #####
#####################

function kalender($month="",$year="")
{
    $menu_xml = get_menu_xml('kalender');
    if(!empty($month) && !empty($year))
    {
      $monat = cal($month);
      $jahr = $year;
    } else {
      $monat = date("m");
      $jahr = date("Y");
    }

    $cache_tag = 'nav_kalender_month'.$monat.'_year'.$jahr;
    if(!Cache::is_mem() || !$menu_xml['xml'] || Cache::check($cache_tag))
    {
        for($i = 1; $i <= 12; $i++)
        {
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

          if($monat == $i) $month = $mname[$i];
        }

        $today = mktime(0,0,0,date("n"),date("d"),date("Y"));
        $i = 1; $show = "";
        while($i <= 31 && checkdate($monat, $i, $jahr))
        {
          $event = ""; $data = ""; $bdays = ""; $cws = "";
          for($iw = 1; $iw <= 7; $iw++)
          {
            $titlecw = ""; $titlebd = ""; $titleev = "";
            $datum = mktime(0,0,0,$monat,$i,$jahr);
            $wday = getdate($datum);
            $wday = $wday['wday'];

            if(!$wday) $wday = 7;

            if($wday != $iw)
            {
              $data .= "<td class=\"navKalEmpty\"></td>";
            } else {
              $qry = db("SELECT id,bday FROM ".dba::get('users')." WHERE bday LIKE '".cal($i).".".$monat.".____"."'");
              if(_rows($qry))
              {
                while($get = _fetch($qry))
                {
                  $bdays = "set";
                  $titlebd .= jsconvert(_kal_birthday.rawautor($get['id']));
                }
              } else {
                $bdays = "";
                $titlebd = "";
              }

              $qry = db("SELECT datum,gegner FROM ".dba::get('cw')." WHERE DATE_FORMAT(FROM_UNIXTIME(datum), '%d.%m.%Y') = '".cal($i).".".$monat.".".$jahr."'");
              if(_rows($qry))
              {
                while($get = _fetch($qry))
                {
                  $cws = "set";
                  $titlecw .= jsconvert(_kal_cw.string::decode($get['gegner']));
                }
              } else {
                $cws = "";
                $titlecw = "";
              }

              $qry = db("SELECT datum,title FROM ".dba::get('events')." WHERE DATE_FORMAT(FROM_UNIXTIME(datum), '%d.%m.%Y') = '".cal($i).".".$monat.".".$jahr."'");
              if(_rows($qry))
              {
                while($get = _fetch($qry))
                {
                  $event = "set";
                  $titleev .= jsconvert(_kal_event.string::decode($get['title']));
                }
              } else {
                $event = "";
                $titleev = "";
              }

              $info = 'onmouseover="DZCP.showInfo(\''.cal($i).'.'.$monat.'.'.$jahr.'\', \''.$titlebd.$titlecw.$titleev.'\')" onmouseout="DZCP.hideInfo()"';

              if($event == "set" || $cws == "set" || $bdays == "set")
                $day = '<a class="navKal" href="../kalender/?m='.$monat.'&amp;y='.$jahr.'&amp;hl='.$i.'" '.$info.'>'.cal($i).'</a>';
              else $day = cal($i);

              if(!checkdate($monat, $i, $jahr))
              {
                $data .= '<td class="navKalEmpty"></td>';
              } elseif($datum == $today) {
                $data .= show("menu/kal_day", array("day" => $day,
                                                    "id" => "navKalToday"));
              } else {
                $data .= show("menu/kal_day", array("day" => $day,
                                                    "id" => "navKalDays"));
              }
              $i++;
            }
          }
          $show .= "<tr>".$data."</tr>";
        }

        if(($monat+1) == 13)
        {
          $nm = 1;
          $ny = $jahr+1;
        } else {
          $nm = $monat+1;
          $ny = $jahr;
        }

        if(($monat-1) == 0)
        {
          $lm = 12;
          $ly = $jahr-1;
        } else {
          $lm = $monat-1;
          $ly = $jahr;
        }

        $kalender = show("menu/kalender", array("monat" => $month,
                                                "show" => $show,
                                                "year" => $jahr,
                                                "nm" => $nm,
                                                "ny" => $ny,
                                                "lm" => $lm,
                                                "ly" => $ly,
                                                "montag" => _nav_montag,
                                                "dienstag" => _nav_dienstag,
                                                "mittwoch" => _nav_mittwoch,
                                                "donnerstag" => _nav_donnerstag,
                                                "freitag" => _nav_freitag,
                                                "samstag" => _nav_samstag,
                                                "sonntag" => _nav_sonntag));

        if(Cache::is_mem() && $menu_xml['xml'] && $menu_xml['config']['update'] != '0') //Only Memory Cache
            Cache::set($cache_tag,$kalender,$menu_xml['config']['update']);
    }
    else
        $kalender = Cache::get($cache_tag);

  return '<div id="navKalender">'.$kalender.'</div>';
}