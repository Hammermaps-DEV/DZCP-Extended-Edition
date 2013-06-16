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
    $qry = db("SELECT hits,download,url FROM ".dba::get('downloads')."");
    $allhits = 0; $allsize = 0;
    while($get = _fetch($qry))
    {
        $file = preg_replace("#added...#Uis", "../downloads/files/", $get['url']);

        if(strpos($get['url'],"http://") != 0)
            $rawfile = @basename($file);
        else
            $rawfile = re($get['download']);

        $size = @filesize($file);
        $hits = $get['hits'];
        $allhits += $hits;
        $allsize += $size;
    }

    if(strlen(@round(($allsize/1048576)*$allhits,0)) >= 4)
        $alltraffic = @round(($allsize/1073741824)*$allhits,2).' GB';
    else
        $alltraffic = @round(($allsize/1048576)*$allhits,2).' MB';

    if(strlen(@round(($allsize/1048576),0)) >= 4)
        $allsize = @round(($allsize/1073741824),2).' GB';
    else
        $allsize = @round(($allsize/1048576),2).' MB';

    $stats = show($dir."/downloads", array("head" => _site_dl,
            "files" => _site_stats_files,
            "nfiles" => cnt(dba::get('downloads')),
            "size" => _stats_dl_size,
            "hosted" => _stats_hosted,
            "allsize" => $allsize,
            "traffic" => _stats_dl_traffic,
            "ntraffic" => $alltraffic,
            "hits" => _stats_dl_hits,
            "nhits" => $allhits));

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