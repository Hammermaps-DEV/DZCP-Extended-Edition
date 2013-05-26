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
    if(cnt(dba::get('cw'), " WHERE datum < ".time()."") != "0")
    {
        $won = cnt(dba::get('cw'), " WHERE punkte > gpunkte");
        $lost = cnt(dba::get('cw'), " WHERE punkte < gpunkte");
        $draw = cnt(dba::get('cw'), " WHERE datum < ".time()." && punkte = gpunkte");
        $ges = cnt(dba::get('cw'), " WHERE datum < ".time()."");

        $wo_p = @round($won*100/$ges, 1);
        $lo_p = @round($lost*100/$ges, 1);
        $dr_p = @round($draw*100/$ges, 1);
    }

    $allp = '<span class="CwWon">'.sum(dba::get('cw'),'',"punkte").'</span> : <span class="CwLost">'.sum(dba::get('cw'),'',"gpunkte").'</span>';
    $stats = show($dir."/cw", array("head" => _site_clanwars,
            "played" => _stats_cw_played,
            "nplayed" => $ges,
            "won" => _stats_cw_won,
            "draw" => _stats_cw_draw,
            "lost" => _stats_cw_lost,
            "nwon" => $won." (".$wo_p."%)",
            "ndraw" => $draw." (".$dr_p."%)",
            "nlost" => $lost." (".$lo_p."%)",
            "points" => _stats_cw_points,
            "npoints" => $allp));

    $index = show($dir."/stats", array("head" => _stats,
            "news" => _site_news,
            "stats" => $stats,
            "user" => _user,
            "dl" => _site_dl,
            "mysql" => _stats_mysql,
            "awards" => _site_awards,
            "cw" => _site_clanwars,
            "gb" =>  _site_gb,
            "forum" => _site_forum));
}
?>