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
    if(!permission("edittactics"))
    {
        $index = error(_error_wrong_permissions, 1);
    } else {
        $infos = show(_upload_usergallery_info, array("userpicsize" => 100));

        $index = show($dir."/upload", array("uploadhead" => _upload_taktiken_head,
                "file" => _upload_file,
                "name" => "file",
                "action" => "?action=taktiken&amp;do=upload",
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
                $index = error(_upload_no_data, 1);
            } elseif($size > 1000000)  {
                $index = error(_upload_wrong_size, 1);
            } else {
                copy($tmpname, basePath."/inc/images/uploads/taktiken/".$_FILES['file']['name']."");
                @unlink($_FILES['file']['tmp_name']);

                $index = info(_info_upload_success, "../taktik/");
            }
        }
    }
}
?>