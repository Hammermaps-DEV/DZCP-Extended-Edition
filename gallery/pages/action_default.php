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
    $qry = db("SELECT id,kat,beschreibung FROM ".$db['gallery']." ORDER BY id DESC");
    if(_rows($qry))
    {
        $color = 1;
        while($get = _fetch($qry))
        {
            $files = get_files(basePath."/gallery/images/",false,true,$img_format);
            $cnt = 0;
            for($i=0; $i<count($files); $i++)
            {
            if(preg_match("#^".$get['id']."_(.*?)#",strtolower($files[$i]))!=FALSE)
            {
            $cnt++;
            }
            }

                $cntpics = ($cnt == 1 ? _gallery_image : _gallery_images);
                $class = ($color % 2) ? "contentMainSecond" : "contentMainFirst"; $color++;
                        $show .= show($dir."/gallery_show", array("link" => re($get['kat']),
                                "class" => $class,
                                "images" => $cntpics,
                                "id" => $get['id'],
                                "beschreibung" => bbcode($get['beschreibung']),
                                "cnt" => $cnt));
        }
        }
        else
                    $show = show(_no_entrys_yet, array("colspan" => "10"));

                    $index = show($dir."/gallery",array("show" => $show, "head" => _gallery_head));
}
?>