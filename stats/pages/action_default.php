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
    $allcomments = cnt(dba::get('newscomments'));
    $allnews = cnt(dba::get('news'));
    $allkats = cnt(dba::get('newskat'));

    $qry = db("SELECT * FROM ".dba::get('newskat')."");
    $kats = ''; $i = 1;
    while($get = _fetch($qry))
    {
        $kats .= re($get['kategorie']).($i == $allkats ? '' : ',');
        $i++;
    }

    $get = db("SELECT datum FROM ".dba::get('news')." ORDER BY datum ASC",false,true);

    $time = (time()-$get['datum']);
    $days = @round($time/86400);

    $cpern = @round($allcomments/$allnews,2);
    $npert = @round($allnews/$days,2);

    $stats = show($dir."/news", array("head" => _site_news,
            "kats" => _stats_nkats,
            "nkats" => $kats,
            "npert" => _stats_npert,
            "nnpert" => $npert,
            "cpern" => _stats_cpern,
            "ncpern" => $cpern,
            "comments" => _stats_comments,
            "ncomments" => $allcomments,
            "news" => _stats_news,
            "nnews" => $allnews,
            "cnt" => $allkats));

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