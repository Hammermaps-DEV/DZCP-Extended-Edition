<?php
/**
 * <DZCP-Extended Edition>
 * @package: DZCP-Extended Edition
 * @author: DZCP Developer Team || Hammermaps.de Developer Team
 * @link: http://www.dzcp.de || http://www.hammermaps.de
 */

if (!defined('IS_DZCP')) exit();
if (_version < '1.0')
    $index = _version_for_page_outofdate;
else
{
    $qry = db("SELECT * FROM ".dba::get('dl_kat')." ORDER BY name"); $color = 1; $kats = '';
    while($get = _fetch($qry))
    {
        $qrydl = db("SELECT * FROM ".dba::get('downloads')." WHERE kat = '".$get['id']."' ORDER BY download");
        if(_rows($qrydl))
        {
            $color_ = 1; $show = "";
            while($getdl = _fetch($qrydl))
            {
                if((isset($_GET['hl']) ? convert::ToInt($_GET['hl']) : 0) == $getdl['id'])
                {
                    $download = string::decode($getdl['download']);
                    $download = str_ireplace($download,'<span class="fontRed">'.$download.'</span>',$download);
                }
                else
                    $download = string::decode($getdl['download']);

                $link = show(_downloads_link, array("id" => $getdl['id'], "download" => $download, "titel" => string::decode($getdl['download'])));
                $class = ($color_ % 2) ? "contentMainSecond" : "contentMainFirst"; $color_++;
                $show .= show($dir."/downloads_show", array("class" => $class, "link" => $link, "hits" => $getdl['hits']));
            }

            $cntKat = cnt(dba::get('downloads'), " WHERE kat = '".$get['id']."'");
            $dltitel = ($cntKat == 1 ? _dl_file : _site_stats_files);
            $kat = show(_dl_titel, array("file" => $dltitel, "cnt" => $cntKat, "name" => string::decode($get['name'])));
            $kats .= show($dir."/download_kats", array("kat" => $kat, "kid" => $get['id'], "show" => $show));
        }
    }

    if(empty($kats))
        $kats = show(_no_entrys_yet_all, array("colspan" => "0"));

    $index = show($dir."/downloads", array("kats" => $kats));
}