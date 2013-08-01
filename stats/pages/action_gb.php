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
    // First
    $get = db("SELECT email,reg,nick,datum FROM ".dba::get('gb')." ORDER BY datum ASC LIMIT 1",false,true);

    if($get['reg'] != "0")
        $first = date("d.m.Y H:i", $get['datum'])."h "._from." ".autor($get['reg']);
    else
        $first = date("d.m.Y H:i", $get['datum'])."h "._from." ".autor($get['reg'],'',$get['nick'],$get['email']);

    // Last
    $get = db("SELECT email,reg,nick,datum FROM ".dba::get('gb')." ORDER BY datum DESC LIMIT 1",false,true);

    if($get['reg'] != "0")
        $last = date("d.m.Y H:i", $get['datum'])."h "._from." ".autor($get['reg']);
    else
        $last = date("d.m.Y H:i", $get['datum'])."h "._from." ".autor($get['reg'],'',$get['nick'],$get['email']);

    $stats = show($dir."/gb", array("head" => _site_gb,
            "all" => _stats_gb_all,
            "poster" => _stats_gb_poster,
            "nposter" => cnt(dba::get('gb')," WHERE reg = 0")."/".cnt(dba::get('gb')," WHERE reg != 0"),
            "nall" => cnt(dba::get('gb')),
            "first" => _stats_gb_first,
            "nfirst" => $first,
            "last" => _stats_gb_last,
            "nlast" => $last));

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