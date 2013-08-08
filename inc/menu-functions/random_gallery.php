<?php
/**
 * <DZCP-Extended Edition>
 * @package: DZCP-Extended Edition
 * @author: DZCP Developer Team || Hammermaps.de Developer Team
 * @link: http://www.dzcp.de || http://www.hammermaps.de
 */

function random_gallery()
{
    global $picformat;
    $menu_xml = get_menu_xml('random_gallery');
    if(!Cache::is_mem() || !$menu_xml['xml'] || Cache::check('nav_random_gallery'))
    {
        $imgArr = array(); $gallery = '';
        $files = get_files(basePath.'/inc/images/uploads/gallery/',false,true,$picformat);

        if($files && count($files) >= 1)
        {
            $get = db("SELECT id,kat FROM ".dba::get('gallery')." ORDER BY RAND()",false,true);

            foreach($files as $file)
            {
                if(convert::ToInt($file) == $get['id'])
                    array_push($imgArr, $file);
            }

            shuffle($imgArr);
            if(!empty($imgArr[0]))
            {
                $gallery = show("menu/random_gallery", array("image" => $imgArr[0], "id" => $get['id'], "kat" => string::decode($get['kat'])));

                if(Cache::is_mem() && $menu_xml['xml'] && $menu_xml['config']['update'] != '0') //Only Memory Cache
                    Cache::set('nav_random_gallery',$gallery,$menu_xml['config']['update']);
            }
        }
    }
    else
        $gallery = Cache::get('nav_random_gallery');

    return empty($gallery) ? '' : '<table class="navContent" cellspacing="0">'.$gallery.'</table>';
}