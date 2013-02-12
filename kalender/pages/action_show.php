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
    $qry = db("SELECT * FROM ".$db['events']."
             WHERE DATE_FORMAT(FROM_UNIXTIME(datum), '%d.%m.%Y') = '".date("d.m.Y",convert::ToInt($_GET['time']))."'
             ORDER BY datum");
    while($get = _fetch($qry))
    {
        if(permission("editkalender"))
        {
            $edit = show("page/button_edit", array("id" => $get['id'],
                    "action" => "action=admin&amp;do=edit",
                    "title" => _button_title_edit));
        } else {
            $edit = "";
        }

        $events .= show($dir."/event_show", array("event" => _kalender_event,
                "time" => _kalender_uhrzeit,
                "edit" => $edit,
                "show_time" => date("H:i", $get['datum'])._uhr,
                "show_event" => bbcode($get['event']),
                "show_title" => re($get['title'])));
    }

    $head = show(_kalender_events_head, array("datum" => date("d.m.Y",$_GET['time'])));
    $index = show($dir."/event", array("head" => $head,
            "events" => $events));
}
?>