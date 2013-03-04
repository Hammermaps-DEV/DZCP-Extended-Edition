<?php
//-> Last Articles
function l_artikel()
{
    global $db,$allowHover;
    $lartikelconfig = config(array('m_lartikel','l_lartikel')); $l_articles = '';
    $qry = db("SELECT id,titel,text,autor,datum,kat,public FROM ".$db['artikel']."
               WHERE public = 1
               ORDER BY id DESC
               LIMIT ".$lartikelconfig['m_lartikel']."");

    if(_rows($qry))
    {
        while($get = _fetch($qry))
        {
            $getkat = db("SELECT kategorie FROM ".$db['newskat']." WHERE id = '".$get['kat']."'",false,true);
            $text = strip_tags($get['text']);
            $info = ($allowHover == 1 ? 'onmouseover="DZCP.showInfo(\''.jsconvert(re($get['titel'])).'\', \''._datum.';'._autor.';'._news_admin_kat.';'._comments_head.'\', \''.date("d.m.Y H:i", $get['datum'])._uhr.';'.fabo_autor($get['autor']).';'.jsconvert(re($getkat['kategorie'])).';'.cnt($db['acomments'],"WHERE artikel = '".$get['id']."'").'\')" onmouseout="DZCP.hideInfo()"' : '');
            $l_articles .= show("menu/last_artikel", array("id" => $get['id'],
                                                           "titel" => re(cut($get['titel'],$lartikelconfig['l_lartikel'])),
                                                           "text" => cut(bbcode($text),260),
                                                           "datum" => date("d.m.Y", $get['datum']),
                                                           "info" => $info));
        }
    }

    return empty($l_articles) ? '' : '<table class="navContent" cellspacing="0">'.$l_articles.'</table>';
}
?>