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
    if(checkme() == 4)
    {
        if(!permission("partners"))
        {
            $index = error(_error_wrong_permissions);
        } else {
            $infos = show(_upload_partners_info, array("userpicsize" => settings('upicsize')));

            $index = show($dir."/upload", array("uploadhead" => _upload_partners_head,
                    "file" => _upload_file,
                    "name" => "file",
                    "action" => "?action=partners&amp;do=upload",
                    "upload" => _button_value_upload,
                    "info" => _upload_info,
                    "infos" => $infos));
            if($_GET['do'] == "upload")
            {
                $tmpname = $_FILES['file']['tmp_name'];
                $name = $_FILES['file']['name'];
                $type = $_FILES['file']['type'];
                $size = $_FILES['file']['size'];


                if(!$tmpname)
                {
                    $index = error(_upload_no_data);
                } else {
                    copy($tmpname, basePath."/banner/partners/".$_FILES['file']['name']."");
                    @unlink($_FILES['file']['tmp_name']);

                    $index = info(_info_upload_success, "?index=admin&amp;admin=partners&amp;do=add");
                }
            }
        }
    } else {
        $index = error(_error_wrong_permissions);
    }
}