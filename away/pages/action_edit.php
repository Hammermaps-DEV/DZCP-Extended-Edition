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
    if($chkMe == "unlogged" || $chkMe < "2")
    {
        $index = error(_error_wrong_permissions, 1);
    } else {
        $qry = db("SELECT * FROM ".dba::get('away')." WHERE id = '".convert::ToInt($_GET['id'])."'");
        $get = _fetch($qry);

        $date1 = show(_dropdown_date, array("day" => dropdown("day",date("d",$get['start'])),
                "month" => dropdown("month",date("m",$get['start'])),
                "year" => dropdown("year",date("Y",$get['start']))));

        $date2 = show(_dropdown_date2, array("tag" => dropdown("day",date("d",$get['end'])),
                "monat" => dropdown("month",date("m",$get['end'])),
                "jahr" => dropdown("year",date("Y",$get['end']))));

        $index = show($dir."/form_away", array("head" => _away_edit_head,
                "action" => "edit&amp;do=set&amp;id=".$get['id'],
                "error" => "",
                "reason" => _away_reason,
                "from" => _from,
                "to" => _away_to,
                "date1" => $date1,
                "date2" => $date2,
                "comment" => _news_kommentar,
                "titel" => $get['titel'],
                "text" => $get['reason'],
                "submit" => _button_value_edit));

        $abdata = mktime(0,0,0,$_POST['m'],$_POST['t'],$_POST['j']);
        $bisdata = mktime(0,0,0,$_POST['monat'],$_POST['tag'],$_POST['jahr']);
        if($_GET['do'] == "set")
        {
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
                        "action" => "edit&amp;do=set&amp;id=".$get['id'],
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
                $editedby = show(_edited_by, array("autor" => autor(convert::ToInt($userid)),
                        "time" => date("d.m.Y H:i", time())._uhr));

                $qry = db("UPDATE ".dba::get('away')."
                    SET `start`= '".convert::ToInt($abdata)."',
                          `end`= '".convert::ToInt($time)."',
                        `titel`= '".up($_POST['titel'])."',
                        `reason`= '".up($_POST['reason'], 1)."',
                        `lastedit`= '".addslashes($editedby)."'
                        WHERE id = '".convert::ToInt($_GET['id'])."'");

                $index = info(_away_successful_edit, "../away/");
            }
        }
    }
}
?>