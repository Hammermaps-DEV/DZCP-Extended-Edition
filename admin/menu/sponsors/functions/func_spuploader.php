<?php
/**
 * <DZCP-Extended Edition>
 * @package: DZCP-Extended Edition
 * @author: DZCP Developer Team || Hammermaps.de Developer Team
 * @link: http://www.dzcp.de || http://www.hammermaps.de
 */

if(_adminMenu != 'true') exit();

//sdata,bdata,xdata
function sponsoren_uploader($type='sdata',$id)
{
    switch ($type)
    {
        case 'sdata': $sql = 'send'; $file_prefix = 'site_'; break;
        case 'bdata': $sql = 'bend'; $file_prefix = 'banner_'; break;
        case 'xdata': $sql = 'xend'; $file_prefix = 'box_'; break;
    }

    $tmp = $_FILES[$type]['tmp_name'];
    $img_type = $_FILES[$type]['type'];
    $end = explode(".", $_FILES[$type]['name']);
    $end = strtolower($end[count($end)-1]);
    $img = getimagesize($tmp);
    if(!empty($tmp))
    {
        if($img_type == "image/gif" || $img_type == "image/png" || $img_type == "image/jpeg" || !$img[0])
            @move_uploaded_file($tmp, basePath."/banner/sponsors/".$file_prefix.$id.".".strtolower($end));

        db("UPDATE ".dba::get('sponsoren')." SET `".$sql."` = '".$end."' WHERE id = '".convert::ToInt($id)."'");
    }
}