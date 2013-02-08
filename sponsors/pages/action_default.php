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

    $qry = db("SELECT * FROM ".$db['sponsoren']."
               WHERE site = 1
             ORDER BY pos");
    while($get = _fetch($qry))
    {
        if(empty($get['slink']))
        {
            $banner = show(_sponsors_bannerlink, array("id" => $get['id'],
                    "title" => str_replace('http://', '', re($get['link'])),
                    "banner" => "../banner/sponsors/site_".$get['id'].".".re($get['send'])));
        } else {
            $banner = show(_sponsors_bannerlink, array("id" => $get['id'],
                    "title" => str_replace('http://', '', re($get['link'])),
                    "banner" => $get['slink']));
        }

        $show .= show($dir."/sponsors_show", array("class" => $class,
                "beschreibung" => bbcode($get['beschreibung']),
                "hits" => $get['hits'],
                "hit" => _hits,
                "banner" => $banner));
    }

    $index = show($dir."/sponsors", array("head" => _sponsor_head,
            "show" => $show));
}
?>