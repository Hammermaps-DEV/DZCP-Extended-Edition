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
    $files = get_files(basePath."/gallery/images/",false,true,$img_format);
    $t = 1; $cnt = 0; $color = 1;
    for($i=0; $i<count($files); $i++)
    {
    if(preg_match("#^".$_GET['id']."_(.*?)#",strtolower($files[$i]))!=FALSE)
    {
    $tr1 = ""; $tr2 = ""; $del = "";

    if($t == 0 || $t == 1)
    $tr1 = "<tr>";

    if($t == $gallery)
    {
    $tr2 = "</tr>";
            $t = 0;
    }

    if(permission("gallery"))
    {
    $del = show("page/button_delete_gallery", array("id" => "",
    "action" => "admin=gallery&amp;do=delete&amp;pic=".$files[$i],
    "title" => _button_title_del,
    "del" => convSpace(_confirm_del_galpic)));
    }


    $class = ($color % 2) ? "contentMainSecond" : "contentMainFirst"; $color++;
    $show .= show($dir."/show_gallery", array("img" => gallery_size($files[$i]),
            "tr1" => $tr1,
            "max" => $gallery,
            "width" => intval(round(100/$gallery)),
            "del" => $del,
            "tr2" => $tr2));
            $t++;
            $cnt++;
    }
    }

    if(is_float($cnt/$gallery))
    {
    $end = "";
        for($e=$t; $e<=$gallery; $e++)
            {
            $end .= '<td class="contentMainFirst"></td>';
    }

    $end = $end."</tr>";
    }

            $qry = db("SELECT kat,beschreibung FROM ".$db['gallery']." WHERE id = '".intval($_GET['id'])."'");
            $get = _fetch($qry);

            $index = show($dir."/show", array("gallery" => re($get['kat']),
                    "show" => $show,
                    "beschreibung" => bbcode($get['beschreibung']),
                    "end" => $end,
                    "back" => _gal_back,
                    "head" => _subgallery_head));
}
?>