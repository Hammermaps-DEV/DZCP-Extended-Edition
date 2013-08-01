<?php
/**
 * <DZCP-Extended Edition>
 * @package: DZCP-Extended Edition
 * @author: DZCP Developer Team || Hammermaps.de Developer Team
 * @link: http://www.dzcp.de || http://www.hammermaps.de
 */

#####################
##### Menu-File #####
#####################

function l_news()
{
    global $allowHover;
    header('Content-Type: text/html; charset=iso-8859-1');
    $menu_xml = get_menu_xml('l_news');
    if(!Cache::is_mem() || !$menu_xml['xml'] || Cache::check('nav_l_news'))
    {
        $lnewsconfig = config(array('m_lnews','l_lnews')); $l_news = '';
        $qry = db("SELECT id,titel,autor,datum,kat,public,timeshift FROM ".dba::get('news')." WHERE public = 1 AND datum <= ".time()." ".(!permission("intnews") ? "AND intern = 0" : "")." ORDER BY id DESC LIMIT ".$lnewsconfig['m_lnews']."");

        if(_rows($qry))
        {
            while($get = _fetch($qry))
            {
                $getkat = db("SELECT kategorie FROM ".dba::get('newskat')." WHERE id = '".$get['kat']."'",false,true);
                $info = ($allowHover == 1 ? 'onmouseover="DZCP.showInfo(\''.jsconvert(string::decode($get['titel'])).'\', \''._datum.';'._autor.';'._news_admin_kat.';'._comments_head.'\', \''.date("d.m.Y H:i", $get['datum'])._uhr.';'.fabo_autor($get['autor']).';'.jsconvert(string::decode($getkat['kategorie'])).';'.cnt(dba::get('newscomments'),"WHERE news = '".$get['id']."'").'\')" onmouseout="DZCP.hideInfo()"' : '');
                $l_news .= show("menu/last_news", array("id" => $get['id'], "titel" => string::decode(cut($get['titel'],$lnewsconfig['l_lnews'])), "datum" => date("d.m.Y", $get['datum']), "info" => $info));
            }

            if(Cache::is_mem() && $menu_xml['xml'] && $menu_xml['config']['update'] != '0') //Only Memory Cache
                Cache::set('nav_l_news',$l_news,$menu_xml['config']['update']);
        }
    }
    else
        $l_news = Cache::get('nav_l_news');

    return empty($l_news) ? '' : '<table class="navContent" cellspacing="0">'.$l_news.'</table>';
}