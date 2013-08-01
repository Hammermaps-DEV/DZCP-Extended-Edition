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
    if(checkme() != 'unlogged')
    {
        $infos = show(_upload_userava_info, array("userpicsize" => config('upicsize')));

        $index = show($dir."/upload", array("uploadhead" => _upload_ava_head,
                "file" => _upload_file,
                "name" => "file",
                "action" => "?action=avatar&amp;do=upload",
                "upload" => _button_value_upload,
                "info" => _upload_info,
                "infos" => $infos));
        if($_GET['do'] == "upload")
        {
            $tmpname = $_FILES['file']['tmp_name'];
            $name = $_FILES['file']['name'];
            $type = $_FILES['file']['type'];
            $size = $_FILES['file']['size'];


            $endung = explode(".", $_FILES['file']['name']);
            $endung = strtolower($endung[count($endung)-1]);

            if(!$tmpname)
            {
                $index = error(_upload_no_data);
            } elseif($size > config('upicsize')."000") {
                $index = error(_upload_wrong_size);
            } else {
                foreach($picformat as $tmpendung)
                {
                    if(file_exists(basePath."/inc/images/uploads/useravatare/".userid().".".$tmpendung))
                    {
                        @unlink(basePath."/inc/images/uploads/useravatare/".userid().".".$tmpendung);
                    }
                }
                copy($tmpname, basePath."/inc/images/uploads/useravatare/".userid().".".strtolower($endung));
                @unlink($_FILES['file']['tmp_name']);

                $index = info(_info_upload_success, "../user/?action=editprofile");
            }
        } elseif($_GET['do'] == "delete") {
            foreach($picformat as $tmpendung)
            {
                if(file_exists(basePath."/inc/images/uploads/useravatare/".userid().".".$tmpendung))
                {
                    @unlink(basePath."/inc/images/uploads/useravatare/".userid().".".$tmpendung);
                    $index = info(_delete_pic_successful, "../user/?action=editprofile");
                }
            }
        }
    } else {
        $index = error(_error_wrong_permissions);
    }
}