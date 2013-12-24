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
    $qry = db("SELECT id,kat,beschreibung FROM ".dba::get('gallery')." ORDER BY id DESC");
    if(_rows($qry))
    {
        $color = 1; $show = '';
        while($get = _fetch($qry))
        {
            $files = get_files(basePath."/inc/images/uploads/gallery/",false,true,$picformat,"#^".$get['id']."_(.*?)#");
            $cnt = convert::ToString($files ? count($files) : 0);
            $file = $files[rand(0,$cnt-1)];
            $filetime = filemtime(basePath."/inc/images/uploads/gallery/".$file);
            $cntpics = ($cnt == 1 ? _gallery_image : _gallery_images);

            if(check_mod_rewrite())
            {
                $endung = explode(".", $file);
                $endung = strtolower($endung[count($endung)-1]);
                $file = str_replace('.'.$endung,'',$file);
                $filetime = filemtime(basePath."/inc/images/uploads/gallery/".$file.".".$endung);
                $show .= show($dir."/gallery_show_rewrite", array("link" => string::decode($get['kat']),
                                                                  "images" => $cntpics,
                                                                  "image" => 'inc/ajax/thumbgen/uploads/gallery/'.$file,
                                                                  "endung"=>$endung,
                                                                  "id" => $get['id'],
                                                                  "time"=>$filetime,
                                                                  "beschreibung" => bbcode::parse_html($get['beschreibung']),
                                                                  "cnt" => $cnt));
            }
            else
            {
                $show .= show($dir."/gallery_show", array("link" => string::decode($get['kat']),
                                                          "images" => $cntpics,
                                                          "image" => 'inc/ajax.php?loader=thumbgen&file=uploads/gallery/'.$file,
                                                          "id" => $get['id'],
                                                          "time"=>$filetime,
                                                          "beschreibung" => bbcode::parse_html($get['beschreibung']),
                                                          "cnt" => $cnt));
            }
        }
    }
    else
        $show = show(_no_entrys_yet, array("colspan" => "10"));

    $index = show($dir."/gallery",array("show" => $show));
}
