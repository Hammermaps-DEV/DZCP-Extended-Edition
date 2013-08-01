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
        $show = error(_error_wrong_permissions);
    } else {
        $tmpname = $_FILES['file']['tmp_name'];
        $name = $_FILES['file']['name'];
        $type = $_FILES['file']['type'];
        $size = $_FILES['file']['size'];


        if(!$tmpname)
        {
            $index = error(_upload_no_data, 1);
        } elseif($size > config('upicsize')."000") {
            $index = error(_upload_wrong_size);
        } else {
            copy($tmpname, basePath."/inc/images/gameicons/custom/".$_FILES['file']['name']);
            @unlink($_FILES['file']['tmp_name']);

            $index = info(_info_upload_success, "../admin/?admin=squads&amp;do=add");
        }
    }
}