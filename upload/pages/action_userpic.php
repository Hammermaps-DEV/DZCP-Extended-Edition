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
        $infos = show(_upload_userpic_info, array("userpicsize" => settings('upicsize')));

        $index = show($dir."/upload", array("uploadhead" => _upload_head,
                "file" => _upload_file,
                "name" => "file",
                "action" => "?action=userpic&amp;do=upload",
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
            } elseif($size > settings('upicsize')."000")  {
                $index = error(_upload_wrong_size);
            } else {
                foreach($picformat as $tmpendung)
                {
                    if(file_exists(basePath."/inc/images/uploads/userpics/".userid().".".$tmpendung))
                    {
                        @unlink(basePath."/inc/images/uploads/userpics/".userid().".".$tmpendung);
                    }
                }
                copy($tmpname, basePath."/inc/images/uploads/userpics/".userid().".".strtolower($endung)."");
                @unlink($_FILES['file']['tmp_name']);

                $index = info(_info_upload_success, "?index=user&amp;action=editprofile");
            }
        } elseif($_GET['do'] == "deletepic") {
            foreach($picformat as $tmpendung)
            {
                if(file_exists(basePath."/inc/images/uploads/userpics/".userid().".".$tmpendung))
                {
                    @unlink(basePath."/inc/images/uploads/userpics/".userid().".".$tmpendung);
                    $index = info(_delete_pic_successful, "?index=user&amp;action=editprofile");
                }
            }
        }
    } else {
        $index = error(_error_wrong_permissions);
    }
}