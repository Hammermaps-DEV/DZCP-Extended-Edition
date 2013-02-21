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
    $files = get_files(basePath."/inc/images/uploads/gallery/",false,true,$picformat);
    $t = 1; $cnt = 0; $color = 1; $show = ''; $gallery = config('gallery');
    foreach($files as $file)
    {
        if(preg_match("#^".$_GET['id']."_(.*?)#",strtolower($file)) !== false)
        {
            $tr1 = ""; $tr2 = "";

            if($t == 0 || $t == 1)
                $tr1 = "<tr>";

            if($t == $gallery)
            {
                $tr2 = "</tr>";
                $t = 0;
            }

            $del = (permission("gallery") ? show("page/button_delete_gallery", array("action" => "admin=gallery&amp;do=delete&amp;pic=".$file, "title" => _button_title_del, "del" => convSpace(_confirm_del_galpic))) : '');
            $class = ($color % 2) ? "contentMainSecond" : "contentMainFirst"; $color++;
            $show .= show($dir."/show_gallery", array("img" => "<a href=\"../inc/images/uploads/gallery/".$file."\" rel=\"lightbox[gallery_".$cnt."]\"><img src=\"../inc/ajax.php?loader=thumbgen&file=gallery/".$file."&width=160\" alt=\"\" /></a>",
                                                      "tr1" => $tr1,
                                                      "max" => $gallery,
                                                      "width" => convert::ToInt(round(100/$gallery)),
                                                      "del" => $del,
                                                      "tr2" => $tr2));
            $t++; $cnt++;
        }
    }

    $end = '';
    if(is_float($cnt/$gallery))
    {
        $end = "";
        for($e=$t; $e<=$gallery; $e++)
        { $end .= '<td class="contentMainFirst"></td>'; }

        $end = $end."</tr>";
    }

    $get = db("SELECT kat,beschreibung FROM ".$db['gallery']." WHERE id = '".convert::ToInt($_GET['id'])."'",false,true);
    $index = show($dir."/show", array("gallery" => re($get['kat']),
                                      "show" => $show,
                                      "beschreibung" => bbcode($get['beschreibung']),
                                      "end" => $end,
                                      "back" => _gal_back,
                                      "head" => _subgallery_head));
}
?>