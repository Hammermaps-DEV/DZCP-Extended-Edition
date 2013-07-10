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

function newsticker()
{
    global $allowHover;
    $menu_xml = get_menu_xml('newsticker');
    if(!Cache::is_mem() || !$menu_xml['xml'] || Cache::check('nav_newsticker'))
    {
        $news = '';
        $qry = db("SELECT id,titel,autor,datum,kat FROM ".dba::get('news')." WHERE public = '1'AND datum <= '".time()."' ".(!permission("intnews") ? "AND intern = 0" : "")." ORDER BY id DESC LIMIT 20");
        if(_rows($qry))
        {
            while($get = _fetch($qry))
            {
                if($allowHover == 1)
                {
                    $getkat = _fetch(db("SELECT kategorie FROM ".dba::get('newskat')." WHERE id = '".$get['kat']."'"));
                    $info = 'onmouseover="DZCP.showInfo(\''.jsconvert(string::decode($get['titel'])).'\', \''._datum.';'._autor.';'._news_admin_kat.';'._comments_head.'\', \''.date("d.m.Y H:i", $get['datum'])._uhr.';'.fabo_autor($get['autor']).';'.jsconvert(string::decode($getkat['kategorie'])).';'.cnt(dba::get('newscomments'),"WHERE news = '".$get['id']."'").'\')" onmouseout="DZCP.hideInfo()"';
                }

                $news .= '<a href="../news/?action=show&amp;id='.$get['id'].'" '.$info.'>'.string::decode($get['titel']).'</a> | ';
            }

            if(Cache::is_mem() && $menu_xml['xml'] && $menu_xml['config']['update'] != '0') //Only Memory Cache
                Cache::set('nav_newsticker',$news,$menu_xml['config']['update']);
        }
    }
    else
        $news = Cache::get('nav_newsticker');

    return show("menu/newsticker", array("news" => $news));
}