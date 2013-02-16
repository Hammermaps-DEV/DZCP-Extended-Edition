<?php
function random_gallery()
{
    global $db, $picformat;

    $imgArr = array(); $gallery = '';
    $files = get_files(basePath.'/inc/images/uploads/gallery/',false,true,$picformat);

    if($files && count($files) >= 1)
    {
        $get = db("SELECT id,kat FROM ".$db['gallery']." ORDER BY RAND()",false,true);

        foreach($files as $file)
        {
            if(convert::ToInt($file) == $get['id'])
                array_push($imgArr, $file);
        }

        shuffle($imgArr);
        if(!empty($imgArr[0]))
            $gallery = show("menu/random_gallery", array("image" => $imgArr[0], "id" => $get['id'], "kat" => re($get['kat'])));
    }

    return empty($gallery) ? '' : '<table class="navContent" cellspacing="0">'.$gallery.'</table>';
}
?>