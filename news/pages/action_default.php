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
    if(!($kat = (isset($_GET['kat']) ? convert::ToInt($_GET['kat']) : false)))
    {
        $navKat = 'lazy';
        $n_kat = '';
        $navWhere = "WHERE public = 1 ".(!permission("intnews") ? "AND `intern` = '0'" : '');
    }
    else
    {
        $n_kat = "AND kat = '".$kat."'";
        $navKat = $kat;
        $navWhere = "WHERE kat = '".$kat."' AND public = 1 ".(!permission("intnews") ? "AND `intern` = '0'" : '');
    }

    //Interne News
    $qry = db("SELECT kat,id,klapptext,viewed,link1,link2,link3,url1,url2,url3,titel,intern,text,datum,autor FROM ".$db['news']."
            WHERE sticky >= ".time()." AND datum <= ".time()." AND public = 1 ".(!permission("intnews") ? "AND `intern` = '0'" : '')." ".$n_kat."
            ORDER BY datum DESC LIMIT ".($page - 1)*$maxnews.",".$maxnews."");

    $show_sticky = "";
    while($get = _fetch($qry))
    {
        if(Cache::check('news_sticky_id_'.$get['id']))
        {
            $getkat = db("SELECT katimg FROM ".$db['newskat']." WHERE id = '".$get['kat']."'",false,true);
            $c = cnt($db['newscomments'], " WHERE news = '".$get['id']."'");
            $comments = ($c == 1 ? show(_news_comment, array("comments" => "1", "id" => $get['id'])) : show(_news_comments, array("comments" => $c, "id" => $get['id'])));
            $klapp = ($get['klapptext'] ? show(_news_klapplink, array("klapplink" => re($get['klapplink']), "which" => "expand", "id" => $get['id'])) : '');
            $links1 = (!empty($get['url1']) ? show(_news_link, array("link" => re($get['link1']), "url" => $get['url1'])) : '');
            $links2 = (!empty($get['url2']) ? show(_news_link, array("link" => re($get['link2']), "url" => $get['url2'])) : '');
            $links3 = (!empty($get['url3']) ? show(_news_link, array("link" => re($get['link3']), "url" => $get['url3'])) : '');
            $links = (!empty($links1) || !empty($links2) || !empty($links3) ? show(_news_links, array("link1" => $links1, "link2" => $links2, "link3" => $links3, "rel" => _related_links)) : '');

            $sticky_news = show($dir."/news_show", array("titel" => re($get['titel']),
                                                          "kat" => re($getkat['katimg']),
                                                          "id" => $get['id'],
                                                          "comments" => $comments,
                                                          "dp" => "none",
                                                          "sticky" => _news_sticky,
                                                          "intern" => ($get['intern'] == 1 ? _votes_intern : ''),
                                                          "klapp" => $klapp,
                                                          "more" => bbcode($get['klapptext']),
                                                          "text" => bbcode($get['text']),
                                                          "datum" => date("d.m.y H:i", $get['datum'])._uhr,
                                                          "links" => $links,
                                                          "autor" => autor($get['autor'])));
            $show_sticky .= $sticky_news;
            Cache::set('news_sticky_id_'.$get['id'], $sticky_news, config('cache_news'));
        }
        else
            $show_sticky .= Cache::get('news_sticky_id_'.$get['id']);
    }

    //Public News
    if(Cache::check('news_page'))
    {
        $qry = db("SELECT id,url1,url2,url3,link1,link2,link3,titel,klapptext,klapplink,text,datum,autor,viewed,intern,kat FROM ".$db['news']."
                        WHERE sticky < ".time()."
                        AND datum <= ".time()."
                        AND public = 1 ".(!permission("intnews") ? "AND `intern` = '0'" : '')." ".$n_kat."
                        ORDER BY datum DESC LIMIT ".($page - 1)*$maxnews.",".$maxnews."");

        $show = "";
        while($get = _fetch($qry))
        {
                $getkat = _fetch(db("SELECT katimg FROM ".$db['newskat']." WHERE id = '".$get['kat']."'"));
                $c = cnt($db['newscomments'], " WHERE news = '".$get['id']."'");
                $comments = ($c == 1 ? show(_news_comment, array("comments" => "1", "id" => $get['id'])) : show(_news_comments, array("comments" => $c, "id" => $get['id'])));
                $klapp = ($get['klapptext'] ? show(_news_klapplink, array("klapplink" => re($get['klapplink']), "which" => "expand", "id" => $get['id'])) : '');
                $viewed = show(_news_viewed, array("viewed" => $get['viewed']));
                $links1 = (!empty($get['url1']) ? show(_news_link, array("link" => re($get['link1']), "url" => $get['url1'])) : '');
                $links2 = (!empty($get['url2']) ? show(_news_link, array("link" => re($get['link2']), "url" => $get['url2'])) : '');
                $links3 = (!empty($get['url3']) ? show(_news_link, array("link" => re($get['link3']), "url" => $get['url3'])) : '');
                $links = (!empty($links1) || !empty($links2) || !empty($links3) ? show(_news_links, array("link1" => $links1, "link2" => $links2, "link3" => $links3, "rel" => _related_links)) : '');

                $show .= show($dir."/news_show", array("titel" => re($get['titel']),
                                                              "kat" => re($getkat['katimg']),
                                                              "id" => $get['id'],
                                                              "comments" => $comments,
                                                              "dp" => "none",
                                                              "sticky" => '',
                                                              "intern" => ($get['intern'] == 1 ? _votes_intern : ''),
                                                              "klapp" => $klapp,
                                                              "more" => bbcode($get['klapptext']),
                                                              "text" => bbcode($get['text']),
                                                              "datum" => date("d.m.y H:i", $get['datum'])._uhr,
                                                              "links" => $links,
                                                              "autor" => autor($get['autor'])));
        }

        Cache::set('news_page', $show, config('cache_news'));
    }
    else
        $show = Cache::get('news_page');

    //Kategorien
    if(Cache::check('news_kat') || isset($_GET['kat']))
    {
        $kategorien = "";
        $qrykat = db("SELECT * FROM ".$db['newskat']."");
        while($getkat = _fetch($qrykat))
        {
            $sel = (isset($_GET['kat']) && $_GET['kat'] == $getkat['id'] ? 'selected' : '');
            $kategorien .= "<option value='".$getkat['id']."' ".$sel.">".$getkat['kategorie']."</option>";
        }

        Cache::set('news_kat', $kategorien, 10);
    }
    else
        $kategorien = Cache::get('news_kat');

    //Output
    $index = show($dir."/news", array('show' => $show, 'show_sticky' => $show_sticky, 'nav' => nav(cnt($db['news'],$navWhere),$maxnews,'?kat='.$navKat,false), 'kategorien' => $kategorien));
}
?>
