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
    if(isset($_POST['monat'])) $monat = $_POST['monat'];
    elseif(isset($_GET['m'])) $monat = $_GET['m'];
    else $monat = date("m");

    if(isset($_POST['jahr'])) $jahr = $_POST['jahr'];
    elseif(isset($_GET['y'])) $jahr = $_GET['y'];
    else $jahr = date("Y");

    $month = '';
    for($i = 1; $i <= 12; $i++)
    {
        $sel = ($monat == $i ? 'selected="selected"' : '');
        $mname = array("1" => _jan, "2" => _feb, "3" => _mar, "4" => _apr, "5" => _mai, "6" => _jun,
         "7" => _jul, "8" => _aug, "9" => _sep, "10" => _okt, "11" => _nov, "12" => _dez);
        $month .= show(_select_field, array("value" => cal($i), "sel" => $sel, "what" => $mname[$i]));
    }

    $year = '';
    for( $i = date("Y")-5; $i < date("Y")+3; $i++)
    {
        $sel = ($jahr == $i ? 'selected="selected"' : '');
        $year .= show(_select_field, array("value" => $i, "sel" => $sel,"what" => $i));
    }

    $ktoday = mktime(0,0,0,date("n"),date("d"),date("Y")); $i = 1; $show = '';
    while($i <= 31 && checkdate($monat, $i, $jahr))
    {
        $data = '';
        for($iw = 1; $iw <= 7; $iw++)
        {
            unset($bdays, $cws, $infoBday, $infoCW, $infoEvent);
            $datum = mktime(0,0,0,$monat,$i,$jahr);
            $wday = getdate($datum);
            $wday = $wday['wday'];

            if(!$wday) $wday = 7;

            if($wday != $iw)
                $data .= '<td class="calDay"></td>';
            else
            {
                if((isset($_GET['hl']) ? $_GET['hl'] : false) == $i)
                    $day = '<span class="fontMarked">'.cal($i).'</span>';
                else
                    $day = cal($i);

                if(!checkdate($monat, $i, $jahr))
                    $data .= '<td class="calDay"></td>';
                else if($datum == $ktoday)
                    $data .= show($dir."/day", array("day" => $day, "event" => kalender_show_events($i,$monat,$jahr,$datum), "class" => "calToday"));
                else
                    $data .= show($dir."/day", array("day" => $day, "event" => kalender_show_events($i,$monat,$jahr,$datum), "class" => "calDay"));

                $i++;
            }
        }

        $show .= "<tr>".$data."</tr>";
    }

    $index = show($dir."/kalender", array("monate" => $month, "jahr" => $year, "show" => $show));
}