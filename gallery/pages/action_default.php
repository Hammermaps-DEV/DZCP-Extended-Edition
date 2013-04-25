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
        $color = 1; $show = '';
        while($get = _fetch($qry))
        {
            $files = get_files(basePath."/inc/images/uploads/gallery/",false,true,$picformat,"#^".$get['id']."_(.*?)#");
            $cnt = convert::ToString($files ? count($files) : 0);
            $cntpics = ($cnt == 1 ? _gallery_image : _gallery_images);
            $show .= show($dir."/gallery_show", array("link" => re($get['kat']),
                                                      "images" => $cntpics,
                                                      "id" => $get['id'],
                                                      "beschreibung" => bbcode($get['beschreibung']),
                                                      "cnt" => $cnt));
        }
    }
    else
        $show = show(_no_entrys_yet, array("colspan" => "10"));

    $index = show($dir."/gallery",array("show" => $show));
}
?>