<?php
/**
 * <DZCP-Extended Edition>
 * @package: DZCP-Extended Edition
 * @author: DZCP Developer Team || Hammermaps.de Developer Team
 * @link: http://www.dzcp.de || http://www.hammermaps.de
 */

#############################################
##### Code for 'DZCP - Extended Edition #####
###### DZCP - Extended Edition >= 1.0 #######
#############################################

####################################
## Wird in einer Index ausgeführt ##
####################################
if (!defined('IS_DZCP'))
    exit();

if (_version < '1.0') //Mindest Version pruefen
    $index = _version_for_page_outofdate;
else
{
    $qry = db("SELECT * FROM ".dba::get('artikel')." WHERE public = 1 ORDER BY datum DESC LIMIT ".($page - 1)*($martikel=config('m_artikel')).",".$martikel."");
    $entrys = cnt(dba::get('artikel'));

    if(_rows($qry))
    {
        $color = 1; $show = '';
        while($get = _fetch($qry))
        {
            $titel = '<a style="display:block" href="?action=show&amp;id='.$get['id'].'">'.$get['titel'].'</a>';
            $class = ($color % 2) ? "contentMainSecond" : "contentMainFirst"; $color++;
            $show .= show($dir."/artikel_show", array("titel" => $titel, "class" => $class, "datum" => date("d.m.Y", $get['datum']), "autor" => autor($get['autor'])));
        } //while end
    }
    else
        $show = show(_no_entrys_yet, array("colspan" => "4"));

    $seiten = nav($entrys,$martikel,"?page");
    $index = show($dir."/artikel", array("show" => $show, "nav" => $seiten));
}