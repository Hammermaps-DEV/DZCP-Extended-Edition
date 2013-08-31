<?php
/**
 * <DZCP-Extended Edition>
 * @package: DZCP-Extended Edition
 * @author: DZCP Developer Team || Hammermaps.de Developer Team
 * @link: http://www.dzcp.de || http://www.hammermaps.de
 */

if (!defined('IS_DZCP')) exit();

#####################
## Links Übersicht ##
#####################
if (_version < '1.0')
    $index = _version_for_page_outofdate;
else
{
    $qry = db("SELECT * FROM ".dba::get('links')." ORDER BY banner DESC"); $show="";
    while($get = _fetch($qry))
    {
        $banner = ($get['banner'] ? show(_links_bannerlink, array("id" => $get['id'], "banner" => string::decode($get['blink']))) : show(_links_textlink, array("id" => $get['id'], "text" => str_replace('http://','',string::decode($get['url'])))));
        $show .= show($dir."/links_show", array("beschreibung" => bbcode::parse_html(string::decode($get['beschreibung'])), "hits" => $get['hits'], "hit" => _hits, "banner" => $banner),$get['id'].'_links');
    }

    $index = show($dir."/links", array("head" => _links_head, "show" => $show));
}