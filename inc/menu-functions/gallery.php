<?php
/**
 * <DZCP-Extended Edition>
 * @package: DZCP-Extended Edition
 * @author: DZCP Developer Team || Hammermaps.de Developer Team
 * @link: http://www.dzcp.de || http://www.hammermaps.de
 */

function gallery()
{
    global $picformat;
    $menu_xml = get_menu_xml('gallery');
    if(!Cache::is_mem() || !$menu_xml['xml'] || Cache::check('nav_gallery'))
    {
        $get = db("SELECT id,kat FROM ".dba::get('gallery')." ORDER BY RAND()",false,true); $gallery = '';
        $files = get_files(basePath.'/inc/images/uploads/gallery/',false,true,$picformat,"#^".convert::ToInt($get['id'])."_(.*)#");
        $cnt = count($files);
        if($files != false && count($files) >= 1)
        {
            shuffle($files); $files = limited_array($files,1,4);
            foreach($files as $file)
            {
                $filetime = filemtime(basePath.'/inc/images/uploads/gallery/'.$file);
                if(!empty($file) && check_mod_rewrite())
                {

                    $endung = explode(".", $file);
                    $endung = strtolower($endung[count($endung)-1]);
                    $file = str_replace('.'.$endung,'',$file);
                    $info = 'onmouseover="DZCP.showInfo(\''.jsconvert(string::decode($get['kat'])).'\', \''._gal_pics.'\', \''.$cnt.'\')" onmouseout="DZCP.hideInfo()"';
                    $gallery .= show("menu/gallery_rewrite", array("info" => '<p><b>'.jsconvert(string::decode($get['kat'])).'</b></p><p>'._gal_pics.$cnt.'</p>',
                                                            "image" => $file,
                                                            "endung" => $endung,
                                                            "time" => $filetime,
                                                            "kat" => string::decode($get['kat']),
                                                            "info" => $info,
                                                            "id" => $get['id']));
                }
                else if(!empty($file))
                {
                    $info = 'onmouseover="DZCP.showInfo(\''.jsconvert(string::decode($get['kat'])).'\', \''._gal_pics.'\', \''.$cnt.'\')" onmouseout="DZCP.hideInfo()"';
                    $gallery .= show("menu/gallery", array("info" => '<p><b>'.jsconvert(string::decode($get['kat'])).'</b></p><p>'._gal_pics.$cnt.'</p>',
                                                           "image" => $file,
                                                           "filetime" => $filetime,
                                                           "kat" => string::decode($get['kat']),
                                                           "info" => $info,
                                                           "id" => $get['id']));
                }
            }

            if(Cache::is_mem() && $menu_xml['xml'] && $menu_xml['config']['update'] != '0' && !empty($gallery)) //Only Memory Cache
                Cache::set('nav_gallery',$gallery,$menu_xml['config']['update']);
        }
    }
    else
        $gallery = Cache::get('nav_gallery');

    return empty($gallery) ? '<center>'._gal_npa.'</center>' : $gallery;
}