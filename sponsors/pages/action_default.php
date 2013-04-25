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
    $qry = db("SELECT * FROM ".$db['sponsoren']." WHERE site = 1 ORDER BY pos");
    if(_rows($qry) >= 1)
    {
        $show = '';
        while($get = _fetch($qry))
        {
            $banner = show(_sponsors_bannerlink, array("id" => $get['id'], "title" => str_replace('http://', '', re($get['link'])), "banner" => (empty($get['slink']) ? "../banner/sponsors/site_".$get['id'].".".re($get['send']) : $get['slink'])));
            $show .= show($dir."/sponsors_show", array("beschreibung" => bbcode($get['beschreibung']), "hits" => $get['hits'], "banner" => $banner));
        }
    }
    else
        $show = show(_no_entrys_yet_all, array("colspan" => "2"));

    $index = show($dir."/sponsors", array("show" => $show));
}
?>