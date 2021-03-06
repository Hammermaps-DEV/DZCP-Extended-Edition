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
    $t = 1; $cnt = 0; $color = 1; $show = ''; $galleryconfig = settings(array('gallery','m_gallery'));
    $files = get_files(basePath."/inc/images/uploads/gallery/",false,true,$picformat,"#^".convert::ToInt($_GET['id'])."_(.*)#");
    $start_on = ($page >= 2 ? ($page - 1)*$galleryconfig['m_gallery']+1 : 1); ksort($files);
    $files_foreach = limited_array($files,$start_on,$galleryconfig['m_gallery']);
    foreach($files_foreach as $file)
    {
        $tr1 = ($t == 0 || $t == 1 ? '<tr>' : '');
        $tr2 = ($t == $galleryconfig['gallery'] ? '</tr>' : '');
        $t = ($t == $galleryconfig['gallery'] ? 0 : $t);
        $filetime=filemtime(basePath."/inc/images/uploads/gallery/".$file);
        $del = (permission("gallery") ? show("page/button_delete_gallery", array("action" => "index=admin&amp;admin=gallery&amp;do=delete&amp;pic=".$file, "title" => _button_title_del, "del" => _confirm_del_galpic)) : '');
        $class = ($color % 2) ? "contentMainSecond" : "contentMainFirst"; $color++;
        if(check_mod_rewrite())
        {
            $file_original = $file;
            $endung = explode(".", $file);
            $endung = strtolower($endung[count($endung)-1]);
            $file = str_replace('.'.$endung,'',$file);
            $show .= show($dir."/show_gallery_rewrite", array("imgorg" => $file_original,
                                                              "img" => $file,
                                                              "cnt" => $cnt,
                                                              "tr1" => $tr1,
                                                              "endung" => $endung,
                                                              "timestamp" => $filetime,
                                                              "max" => $galleryconfig['gallery'],
                                                              "width" => convert::ToInt(round(100/$galleryconfig['gallery'])),
                                                              "del" => $del,
                                                              "tr2" => $tr2));
        }
        else
        {
            $show .= show($dir."/show_gallery", array("img" => $file,
                                                      "cnt" => $cnt,
                                                      "tr1" => $tr1,
                                                      "timestamp" => $filetime,
                                                      "max" => $galleryconfig['gallery'],
                                                      "width" => convert::ToInt(round(100/$galleryconfig['gallery'])),
                                                      "del" => $del,
                                                      "tr2" => $tr2));
        }

        $t++; $cnt++;
    }

    $end = '';
    if(is_float($cnt/$galleryconfig['gallery']))
    {
        for($e=$t; $e<=$galleryconfig['gallery']; $e++)
        { $end .= '<td class="contentMainFirst"></td>'; }

        $end = $end."</tr>";
    }

    $seiten = nav(count($files),$galleryconfig['m_gallery'],"?index=gallery&amp;action=show&amp;id=".convert::ToString($_GET['id']).""); unset($files_foreach,$files);
    $get = db("SELECT kat,beschreibung FROM ".dba::get('gallery')." WHERE id = '".convert::ToInt($_GET['id'])."'",false,true);
    $where = $where.': '.string::decode($get['kat']);
    $index = show($dir."/show", array("gallery" => string::decode($get['kat']), "show" => $show, "beschreibung" => bbcode::parse_html($get['beschreibung']), "end" => $end, "seiten" => $seiten));
}
