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
    $qry = db("SELECT * FROM ".$db['dl_kat']."
             ORDER BY name");
    $t = 1;
    $cnt = 0;
    while($get = _fetch($qry))
    {
        if(isset($_GET['kat'])) $kid = " WHERE id = '".intval($_GET['kat'])."'";
        else                    $kid = "";

        $qrydl = db("SELECT * FROM ".$db['downloads']."
                 WHERE kat = '".$get['id']."'
                 ORDER BY download");
        $show = "";
        if(_rows($qrydl))
        {
            $display = "none";
            $img = "expand";
            while($getdl = _fetch($qrydl))
            {
                if($_GET['hl'] == $getdl['id'])
                {
                    $display = "";
                    $img = "collapse";
                    $download = highlight(re($getdl['download']));
                } else $download = re($getdl['download']);

                $link = show(_downloads_link, array("id" => $getdl['id'],
                        "download" => $download,
                        "titel" => re($getdl['download']),
                        "target" => $target));

                $class = ($color % 2) ? "contentMainSecond" : "contentMainFirst"; $color++;

                $show .= show($dir."/downloads_show", array("class" => $class,
                        "link" => $link,
                        "kid" => $get['id'],
                        "display" => $display,
                        "beschreibung" => bbcode($getdl['beschreibung']),
                        "hits" => $getdl['hits']));
            }

            $cntKat = cnt($db['downloads'], " WHERE kat = '".$get['id']."'");

            if(cnt($db['downloads'], "WHERE kat = '".$get['id']."'") == 1)  $dltitel = _dl_file;
            else $dltitel = _site_stats_files;


            $kat = show(_dl_titel, array("id" => $get['id'],
                    "icon" => $moreicon,
                    "file" => $dltitel,
                    "cnt" => $cntKat,
                    "name" => re($get['name'])));

            $class = ($color % 2) ? "contentMainSecond" : "contentMainFirst"; $color++;

            $kats .= show($dir."/download_kats", array("kat" => $kat,
                    "class" => $class,
                    "kid" => $get['id'],
                    "img" => $img,
                    "download" => _dl_file,
                    "hits" => _hits,
                    "show" => $show,
                    "display" => $display));


        }
    }

    $index = show($dir."/downloads", array("kats" => $kats,
            "head" => _downloads_head));
}
?>