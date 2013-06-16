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
    if(permission('news') || permission('artikel'))
    {
        $infos = show(_upload_usergallery_info, array("userpicsize" => config('upicsize')));

        if(isset($_GET['edit'])) $action = "?action=newskats&amp;do=upload&edit=".$_GET['edit']."";
        else $action = "?action=newskats&amp;do=upload";

        $index = show($dir."/upload", array("uploadhead" => _upload_newskats_head,
                "file" => _upload_file,
                "name" => "file",
                "action" => $action,
                "upload" => _button_value_upload,
                "info" => _upload_info,
                "infos" => "-"));
        if($_GET['do'] == "upload")
        {
            $tmpname = $_FILES['file']['tmp_name'];
            $name = $_FILES['file']['name'];
            $type = $_FILES['file']['type'];
            $size = $_FILES['file']['size'];
            $imageinfo = @getimagesize($tmpname);

            if(!$tmpname)
            {
                $index = error(_upload_no_data);
            } elseif($size > config('upicsize')."000") {
                $index = error(_upload_wrong_size);
            } else {
                copy($tmpname, basePath."/inc/images/uploads/newskat/".$_FILES['file']['name']."");
                @unlink($_FILES['file']['tmp_name']);

                if(isset($_GET['edit'])) $index = info(_info_upload_success, "../admin/?admin=news&amp;do=edit&amp;id=".$_GET['edit']."");
                else $index = info(_info_upload_success, "../admin/?admin=news&amp;do=add");
            }
        }
    } else {
        $index = error(_error_wrong_permissions);
    }
}