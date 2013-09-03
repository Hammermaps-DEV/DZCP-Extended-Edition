<?php
/**
 * <DZCP-Extended Edition>
 * @package: DZCP-Extended Edition
 * @author: DZCP Developer Team || Hammermaps.de Developer Team
 * @link: http://www.dzcp.de || http://www.hammermaps.de
 */

function l_artikel()
{
    global $allowHover;

    $menu_xml = get_menu_xml('l_artikel');
    if(!Cache::is_mem() || !$menu_xml['xml'] || Cache::check('nav_l_artikel'))
    {
        $lartikelconfig = settings(array('m_lartikel','l_lartikel')); $l_articles = '';
        $qry = db("SELECT id,titel,text,autor,datum,kat,public FROM ".dba::get('artikel')." WHERE public = 1 ORDER BY id DESC LIMIT ".$lartikelconfig['m_lartikel']."");

        if(_rows($qry))
        {
            while($get = _fetch($qry))
            {
                $getkat = db("SELECT kategorie FROM ".dba::get('newskat')." WHERE id = '".$get['kat']."'",false,true);
                $text = strip_tags($get['text']);
                $info = ($allowHover == 1 ? 'onmouseover="DZCP.showInfo(\''.jsconvert(string::decode($get['titel'])).'\', \''._datum.';'._autor.';'._news_admin_kat.';'._comments_head.'\', \''.date("d.m.Y H:i", $get['datum'])._uhr.';'.fabo_autor($get['autor']).';'.jsconvert(string::decode($getkat['kategorie'])).';'.cnt(dba::get('acomments'),"WHERE artikel = '".$get['id']."'").'\')" onmouseout="DZCP.hideInfo()"' : '');
                $l_articles .= show("menu/last_artikel", array("id" => $get['id'], "titel" => string::decode(cut($get['titel'],$lartikelconfig['l_lartikel'])), "text" => cut(bbcode::parse_html($text),260), "datum" => date("d.m.Y", $get['datum']), "info" => $info));
            }

            if(Cache::is_mem() && $menu_xml['xml'] && $menu_xml['config']['update'] != '0') //Only Memory Cache
                Cache::set('nav_l_artikel',$l_articles,$menu_xml['config']['update']);
        }
        else
            $l_articles = show(_navi_nartikel_entrys, array("colspan" => "1"));
    }
    else
        $l_articles = Cache::get('nav_l_artikel');

    return empty($l_articles) ? '' : '<table class="navContent" cellspacing="0">'.$l_articles.'</table>';
}