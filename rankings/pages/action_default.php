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
    $qry = db("SELECT s1.id,s1.lastranking,s1.rank,s1.squad,s1.league,s1.url,s2.name
             FROM ".dba::get('rankings')." AS s1
             LEFT JOIN ".dba::get('squads')." AS s2
             ON s1.squad = s2.id
             ORDER BY s1.postdate DESC");
    if(_rows($qry))
    {
        $show = ''; $color = 1;
        while($get = _fetch($qry))
        {
            $squad = '<a href="../squads/?showsquad='.$get['squad'].'">'.string::decode($get['name']).'</a>';
            $league = '<a href="'.$get['url'].'" target="_blank">'.$get['league'].'</a>';
            $class = ($color % 2) ? "contentMainSecond" : "contentMainFirst"; $color++;
            $show .= show($dir."/rankings_show", array("class" => $class, "squad" => $squad, "league" => $league, "old" => $get['lastranking'], "place" => $get['rank']));
        }
    }
    else
        $show = show(_no_entrys_yet, array("colspan" => "5"));

    $index = show($dir."/rankings", array("show" => $show));
}