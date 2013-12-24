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
    if(permission('news') || permission('artikel'))
    {
        $infos = show(_upload_usergallery_info, array("userpicsize" => settings('upicsize')));

        if(isset($_GET['edit'])) $action = "?index=upload&amp;action=newskats&amp;do=upload&edit=".$_GET['edit']."";
        else $action = "?index=upload&amp;action=newskats&amp;do=upload";

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
            } elseif($size > settings('upicsize')."000") {
                $index = error(_upload_wrong_size);
            } else {
                copy($tmpname, basePath."/inc/images/uploads/newskat/".$_FILES['file']['name']."");
                @unlink($_FILES['file']['tmp_name']);

                if(isset($_GET['edit'])) $index = info(_info_upload_success, "?index=admin&amp;admin=news&amp;do=edit&amp;id=".$_GET['edit']."");
                else $index = info(_info_upload_success, "?index=admin&amp;admin=news&amp;do=add");
            }
        }
    } else {
        $index = error(_error_wrong_permissions);
    }
}