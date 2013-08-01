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
    $ges = cnt(dba::get('awards'));
    $place_1 = cnt(dba::get('awards'), " WHERE place = '1' ");
    $place_2 = cnt(dba::get('awards'), " WHERE place = '2' ");
    $place_3 = cnt(dba::get('awards'), " WHERE place = '3' ");

    $stats = show($dir."/awards", array("head" => _site_awards,
            "p1" => _stats_place." 1",
            "p2" => _stats_place." 2",
            "p3" => _stats_place." 3",
            "p" => _stats_place_misc,
            "awards" => _stats_awards,
            "nawards" => $ges,
            "np1" => $place_1,
            "np2" => $place_2,
            "np3" => $place_3,
            "np" => $ges-$place_1-$place_2-$place_3));

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