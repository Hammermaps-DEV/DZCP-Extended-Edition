<?php
//-> Last News
function l_news()
{
    global $db,$allowHover;
    $lnewsconfig = config(array('m_lnews','l_lnews')); $l_news = '';
    $qry = db("SELECT id,titel,autor,datum,kat,public,timeshift FROM ".$db['news']."
               WHERE public = 1 AND datum <= ".time()." ".(!permission("intnews") ? "AND intern = 0" : "")."
               ORDER BY id DESC
               LIMIT ".$lnewsconfig['m_lnews']."");

    while($get = _fetch($qry))
    {
        $getkat = db("SELECT kategorie FROM ".$db['newskat']." WHERE id = '".$get['kat']."'",false,true);
        $info = ($allowHover == 1 ? 'onmouseover="DZCP.showInfo(\''.jsconvert(re($get['titel'])).'\', \''._datum.';'._autor.';'._news_admin_kat.';'._comments_head.'\', \''.date("d.m.Y H:i", $get['datum'])._uhr.';'.fabo_autor($get['autor']).';'.jsconvert(re($getkat['kategorie'])).';'.cnt($db['newscomments'],"WHERE news = '".$get['id']."'").'\')" onmouseout="DZCP.hideInfo()"' : '');
        $l_news .= show("menu/last_news", array("id" => $get['id'],
                                                "titel" => re(cut($get['titel'],$lnewsconfig['l_lnews'])),
                                                "datum" => date("d.m.Y", $get['datum']),
                                                "info" => $info));
    }

    return empty($l_news) ? '' : '<table class="navContent" cellspacing="0">'.$l_news.'</table>';
}
?>