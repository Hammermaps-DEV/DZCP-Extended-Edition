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
    if(!permission("editsquads"))
    {
        $index = error(_error_wrong_permissions);
    } else {
        $infos = show(_upload_usergallery_info, array("userpicsize" => config('upicsize')));

        $index = show($dir."/upload", array("uploadhead" => _upload_icons_head,
                "file" => _upload_file,
                "name" => "file",
                "action" => "?action=upload",
                "upload" => _button_value_upload,
                "info" => _upload_info,
                "infos" => $infos));
    }
}