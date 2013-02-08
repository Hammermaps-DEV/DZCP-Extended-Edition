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
  if(isset($_GET['page'])) $page = $_GET['page'];
  else $page = 1;

  $qry = db("SELECT * FROM ".$db['artikel']."
             WHERE public = 1
             ORDER BY datum DESC
             LIMIT ".($page - 1)*$martikel.",".$martikel."");

  $entrys = cnt($db['artikel']);
  if(_rows($qry))
  {
    while($get = _fetch($qry))
    {
      $titel = '<a style="display:block" href="?action=show&amp;id='.$get['id'].'">'.$get['titel'].'</a>';

      $class = ($color % 2) ? "contentMainSecond" : "contentMainFirst"; $color++;

      $show .= show($dir."/artikel_show", array("titel" => $titel,
                                                "kat" => $kat,
                                                "id" => $get['id'],
                                                "display" => "none",
                                                "nautor" => _autor,
                                                "ndatum" => _datum,
                                                "class" => $class,
                                                "ncomments" => _news_kommentare.":",
                                                "viewed" => $viewed,
                                                "text" => bbcode($get['text']),
                                                "datum" => date("d.m.Y", $get['datum']),
                                                "links" => $links,
                                                "autor" => autor($get['autor'])));
    }
  } else {
    $show = show(_no_entrys_yet, array("colspan" => "4"));
  }

  $seiten = nav($entrys,$martikel,"?page");
  $index = show($dir."/artikel", array("show" => $show,
                                       "stats" => $stats,
                                       "nav" => $seiten,
                                       "artikel" => _artikel,
                                       "datum" => _datum,
                                       "autor" => _autor,
                                       "archiv" => _news_archiv));
}
?>