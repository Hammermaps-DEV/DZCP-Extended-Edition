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
    $anz_ges_points = show(_cw_stats_ges_points, array("ges_won" => sum(dba::get('cw'), '', 'punkte'), "ges_lost" => sum(dba::get('cw'), '', 'gpunkte')));
    $anz_ge_wars = '0'; $anz_lo_wars = '0'; $anz_dr_wars = '0'; $anz_ge_wars = '0'; $wo_percent = '0'; $lo_percent = '0'; $dr_percent = '0'; $wo_rawpercent = '0';
    $lo_rawpercent = '0'; $dr_rawpercent = '0';
    if(cnt(dba::get('cw'), " WHERE datum < ".time()."") != "0")
    {
        $anz_wo_wars = cnt(dba::get('cw'), " WHERE punkte > gpunkte");
        $anz_lo_wars = cnt(dba::get('cw'), " WHERE punkte < gpunkte");
        $anz_dr_wars = cnt(dba::get('cw'), " WHERE datum < ".time()." && punkte = gpunkte");
        $anz_ge_wars = cnt(dba::get('cw'), " WHERE datum < ".time()."");

        $wo_percent = @round($anz_wo_wars*100/$anz_ge_wars, 1);
        $lo_percent = @round($anz_lo_wars*100/$anz_ge_wars, 1);
        $dr_percent = @round($anz_dr_wars*100/$anz_ge_wars, 1);

        $wo_rawpercent = @round($anz_wo_wars*100/$anz_ge_wars, 0);
        $lo_rawpercent = @round($anz_lo_wars*100/$anz_ge_wars, 0);
        $dr_rawpercent = @round($anz_dr_wars*100/$anz_ge_wars, 0);
    }

    $wo_balken = show(_votes_balken, array("width" => ($anz_wo_wars != "0" ? $wo_rawpercent : 1)));
    $lo_balken = show(_votes_balken, array("width" => ($anz_lo_wars != "0" ? $lo_rawpercent : 1)));
    $dr_balken = show(_votes_balken, array("width" => ($anz_dr_wars != "0" ? $dr_rawpercent : 1)));
    $anz_ges_wars = show(_cw_stats_ges_wars, array("ge_wars" => $anz_ge_wars));
    $stats_all = show($dir."/stats", array("wo_wars" => $anz_wo_wars,
                                           "lo_wars" => $anz_lo_wars,
                                           "dr_wars" => $anz_dr_wars,
                                           "dr_percent" => $dr_percent,
                                           "lo_percent" => $lo_percent,
                                           "wo_percent" => $wo_percent,
                                           "won_balken" => $wo_balken,
                                           "lost_balken" => $lo_balken,
                                           "draw_balken" => $dr_balken,
                                           "ges_wars" => $anz_ges_wars,
                                           "ges_points" => $anz_ges_points));

    $qry = db("SELECT * FROM ".dba::get('squads')." WHERE status = '1' ORDER BY pos"); $show = '';
    while($get = _fetch($qry))
    {
        $shown = show(_klapptext_dont_show, array("id" => $get['id'])); $display = "none";
        if((isset($_GET['showsquad']) && $_GET['showsquad'] == $get['id']) || (isset($_GET['show']) && $_GET['show'] == $get['id']))
        {
            $shown = show(_klapptext_show, array("id" => $get['id']));
            $display = "";
        }

        $img = show(_gameicon, array("icon" => $get['icon']));
        $wars = ""; $color = 1;

        $qrym = db("SELECT s1.id,s1.datum,s1.clantag,s1.gegner,s1.url,s1.xonx,s1.liga,s1.punkte,s1.gpunkte,s1.maps,s1.serverip,
                       s1.servername,s1.serverpwd,s1.bericht,s1.squad_id,s1.gametype,s1.gcountry,s2.icon,s2.name
                FROM ".dba::get('cw')." AS s1
                LEFT JOIN ".dba::get('squads')." AS s2 ON s1.squad_id = s2.id
                WHERE s1.squad_id='".$get['id']."'
                AND s1.datum < ".time()."
                ORDER BY s1.datum DESC LIMIT ".config('m_clanwars')."");
        while($getm = _fetch($qrym))
        {
            $game = squad($getm['icon']);
            $flagge = flag($getm['gcountry']);
            $gegner = show(_cw_details_gegner, array("gegner" => string::decode(cut($getm['clantag']." - ".$getm['gegner'], config('l_clanwars'))), "url" => '?action=details&amp;id='.$getm['id']));
            $details = show(_cw_show_details, array("id" => $getm['id']));
            $squad = show(_member_squad_squadlink, array("squad" => string::decode($get['name']), "id" => $get['id'], "shown" => $shown));
            $class = ($color % 2) ? "contentMainSecond" : "contentMainFirst"; $color++;
            $wars .= show($dir."/clanwars_show2", array("datum" => date("d.m.Y", $getm['datum']),
                                                        "img" => $img,
                                                        "flagge" => $flagge,
                                                        "gegner" => $gegner,
                                                        "xonx" => string::decode($getm['xonx']),
                                                        "liga" => string::decode($getm['liga']),
                                                        "gametype" => string::decode($getm['gametype']),
                                                        "class" => $class,
                                                        "result" => cw_result_nopic($getm['punkte'], $getm['gpunkte']),
                                                        "details" => $details));
        }

        $anz_ges_points = show(_cw_stats_ges_points, array("ges_won" => sum(dba::get('cw'), ' WHERE squad_id = '.$get['id'], 'punkte'), "ges_lost" => sum(dba::get('cw'), ' WHERE squad_id = '.$get['id'], 'gpunkte')));
        if(cnt(dba::get('cw'), " WHERE squad_id = ".$get['id']." AND datum < ".time()."") != "0")
        {
            $anz_wo_wars = cnt(dba::get('cw'), " WHERE punkte > gpunkte AND squad_id = ".$get['id']."");
            $anz_lo_wars = cnt(dba::get('cw'), " WHERE punkte < gpunkte AND squad_id = ".$get['id']."");
            $anz_dr_wars = cnt(dba::get('cw'), " WHERE datum < ".time()." && punkte = gpunkte AND squad_id = ".$get['id']."");
            $anz_ge_wars = cnt(dba::get('cw'), " WHERE datum < ".time()." AND squad_id = ".$get['id']."");

            $wo_percent = @round($anz_wo_wars*100/$anz_ge_wars, 1);
            $lo_percent = @round($anz_lo_wars*100/$anz_ge_wars, 1);
            $dr_percent = @round($anz_dr_wars*100/$anz_ge_wars, 1);

            $wo_rawpercent = @round($anz_wo_wars*100/$anz_ge_wars, 0);
            $lo_rawpercent = @round($anz_lo_wars*100/$anz_ge_wars, 0);
            $dr_rawpercent = @round($anz_dr_wars*100/$anz_ge_wars, 0);
        }

        $wo_balken = show(_votes_balken, array("width" => ($anz_wo_wars != "0" ? $wo_rawpercent : 1)));
        $lo_balken = show(_votes_balken, array("width" => ($anz_lo_wars != "0" ? $lo_rawpercent : 1)));
        $dr_balken = show(_votes_balken, array("width" => ($anz_dr_wars != "0" ? $dr_rawpercent : 1)));
        $anz_ges_wars = show(_cw_stats_ges_wars_sq, array("ge_wars" => $anz_ge_wars));
        $stats = show($dir."/stats", array("wo_wars" => $anz_wo_wars,
                                           "lo_wars" => $anz_lo_wars,
                                           "dr_wars" => $anz_dr_wars,
                                           "dr_percent" => $dr_percent,
                                           "lo_percent" => $lo_percent,
                                           "wo_percent" => $wo_percent,
                                           "won_balken" => $wo_balken,
                                           "lost_balken" => $lo_balken,
                                           "draw_balken" => $dr_balken,
                                           "ges_wars" => $anz_ges_wars,
                                           "ges_points" => $anz_ges_points));

        $more = (cnt(dba::get('cw'), " WHERE squad_id = ".$get['id']." AND datum < ".time()."") > config('m_clanwars') ? show(_cw_show_all, array("id" => $get['id'])) : '');
        if(cnt(dba::get('cw'), " WHERE squad_id = ".$get['id']." AND datum < ".time()."") > 0)
        {
            $show .= show($dir."/squads_show", array("id" => $get['id'],
                                                     "shown" => $shown,
                                                     "display" => $display,
                                                     "wars" => $wars,
                                                     "squad" => $squad." [".cnt(dba::get('cw'), " WHERE squad_id = ".$get['id']." AND datum < ".time()."")."]",
                                                     "img" => $img,
                                                     "stats" => $stats,
                                                     "more" => $more));
        }
    }

    $qry = db("SELECT game,icon FROM ".dba::get('squads')." WHERE status = '1' GROUP BY game ORDER BY game ASC"); $color = 1; $legende = '';
    while($get = _fetch($qry))
    {
        $class = ($color % 2) ? "contentMainSecond" : "contentMainFirst"; $color++;
        $legende .= show(_cw_legende, array("game" => string::decode($get['game']), "img" => squad($get['icon']), "class" => $class));
    }

    $legende = show($dir."/legende", array("legende" => $legende));
    $index = show($dir."/squads", array("stats" => $stats, "stats_all" => $stats_all, "legende" => $legende, "show" => $show));
}
