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
    header("Content-type: text/html; charset=utf-8");

    $qrykat = db("SELECT katimg FROM ".$db['newskat']."
                  WHERE id = '".intval($_POST['kat'])."'");
    $getkat = _fetch($qrykat);

    if($_POST['url1'])
    {
        $rel = _related_links;
        $links1 = show(_artikel_link, array("link" => re($_POST['link1']),
                "url" => links($_POST['url1'])));
    } else {
        $links1 = "";
    }
    if($_POST['url2'])
    {
        $rel = _related_links;
        $links2 = show(_artikel_link, array("link" => re($_POST['link2']),
                "url" => links($_POST['url2'])));
    } else {
        $links2 = "";
    }
    if($_POST['url3'])
    {
        $rel = _related_links;
        $links3 = show(_artikel_link, array("link" => re($_POST['link3']),
                "url" => links($_POST['url3'])));
    } else {
        $links3 = "";
    }

    if(!empty($links1) || !empty($links2) || !empty($links3))
    {
        $links = show(_artikel_links, array("link1" => $links1,
                "link2" => $links2,
                "link3" => $links3,
                "rel" => $rel));
    } else {
        $links = "";
    }

    $index = show($dir."/show_more", array("titel" => re($_POST['titel']),
            "id" => $get['id'],
            "comments" => "",
            "display" => "inline",
            "nautor" => _autor,
            "dir" => $designpath,
            "kat" => re($getkat['katimg']),
            "ndatum" => _datum,
            "showmore" => $showmore,
            "icq" => "",
            "text" => bbcode($_POST['artikel'],1),
            "datum" => date("j.m.y H:i")._uhr,
            "links" => $links,
            "autor" => autor($userid)));

    update_user_status_preview();
    exit('<table class="mainContent" cellspacing="1">'.$index.'</table>');
}
?>