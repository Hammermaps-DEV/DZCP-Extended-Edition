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
    $maxawards = config('m_awards');
    $qry = db("SELECT * FROM ".dba::get('awards')." WHERE id = '".convert::ToInt($_GET['id'])."'");
    while($get = _fetch($qry))
    {
        if(isset($_GET['showsquad']))
        {
            if($_GET['showsquad'] == $get['id'])
            {
                $shown = show(_klapptext_show, array("id" => $get['id']));
                $display = "";
            } else {
                $shown = show(_klapptext_dont_show, array("id" => $get['id']));
                $display = "none";
            }
        } else {
            if($get['shown'] == "1" || $get['shown'] == "0")
            {
                $shown = show(_klapptext_show, array("id" => $get['id']));
                $display = "";
            } else {
                $shown = show(_klapptext_dont_show, array("id" => $get['id']));
                $display = "none";
            }
        }

        $squad = show(_member_squad_squadlink, array("squad" => re($get['name']),
                "id" => $get['id']));
        $img = show(_gameicon, array("icon" => re($get['icon'])));

        $qrym = db("SELECT s1.id,s1.squad,s1.date,s1.place,s1.prize,s1.url,s1.event,s2.icon,s2.name FROM ".dba::get('awards')." AS s1
                LEFT JOIN ".dba::get('awards')." AS s2 ON s1.squad = s2.id
                WHERE s1.squad='".$get['id']."'
                ORDER BY s1.date DESC
                    LIMIT ".($page - 1)*$maxawards.",".$maxawards."");

        $entrys = cnt(dba::get('awards'), " WHERE squad = ".$get['id']);
        $i = $entrys-($page - 1)*$maxawards;

        $awards = "";
        while($getm = _fetch($qrym))
        {
            $class = ($color % 2) ? "contentMainSecond" : "contentMainFirst"; $color++;

            if($getm['place'] == "1") $replace = _awards_erster_img;
            elseif($getm['place'] == "2") $replace = _awards_zweiter_img;
            elseif($getm['place'] == "3") $replace = _awards_dritter_img;
            else $replace = $getm['place'];

            $event = show(_awards_event, array("event" => $getm['event'],
                    "url" => $getm['url']));

            $awards .= show($dir."/awards_show", array("class" => $class,
                    "date" => date("d.m.Y", $getm['date']),
                    "place" => $replace,
                    "prize" => $getm['prize'],
                    "event" => $event));

        }

        $nav = nav($entrys,$maxawards,"?action=showall&amp;id=".$get['id']."");
        $showawards = show($dir."/awards_show_all", array("squad" => _awards_head_squad,
                "date" => _awards_head_date,
                "place" => _awards_head_place,
                "prize" => _awards_head_prize,
                "url" => _awards_head_link,
                "nav" => $nav,
                "awards" => $awards));

        if(cnt(dba::get('awards'), " WHERE squad = ".$get['id']) != 0)
        {
            $show .= show($dir."/squads_show_all", array("id" => $get['id'],
                    "shown" => $shown,
                    "display" => $display,
                    "awards" => $showawards,
                    "squad" => $squad." (".cnt(dba::get('awards'), " WHERE squad = ".$get['id']).")",
                    "img" => $img));
        }
    }

    $qry = db("SELECT game,icon FROM ".dba::get('awards')."
             GROUP BY game
             ORDER BY game ASC");
    while($get = _fetch($qry))
    {
        $class = ($color % 2) ? "contentMainSecond" : "contentMainFirst"; $color++;
        $img = squad($get['icon']);
        $legende .= show(_awards_legende, array("game" => re($get['game']),
                "img" => $img,
                "class" => $class));
    }

    $legende = show($dir."/legende", array("legende_head" => _awards_head_legende,
            "legende" => $legende));

    $stats = show(_awards_stats, array("anz" => cnt(dba::get('awards'))));

    $index = show($dir."/main", array("head" => _awards_head,
            "stats" => $stats,
            "legende" => $legende,
            "show" => $show));
}