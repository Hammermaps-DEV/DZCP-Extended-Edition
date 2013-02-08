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
    if($chkMe != 'unlogged')
    {
        $infos = show(_upload_usergallery_info, array("userpicsize" => $upicsize));

        $index = show($dir."/usergallery", array("uploadhead" => _upload_head_usergallery,
                "file" => _upload_file,
                "name" => "file",
                "upload" => _button_value_upload,
                "beschreibung" => _upload_beschreibung,
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
            } elseif($size > $upicsize."000") {
                $index = error(_upload_wrong_size, 1);
            } elseif(cnt($db['usergallery'], " WHERE user = ".$userid) == $maxgallerypics) {
                $index = error(_upload_over_limit, 2);
            } elseif(file_exists(basePath."/inc/images/uploads/usergallery/".$userid."_".$_FILES['file']['name'])) {
                $index = error(_upload_file_exists, 1);
            } else {
                copy($tmpname, basePath."/inc/images/uploads/usergallery/".$userid."_".$_FILES['file']['name']);
                @unlink($_FILES['file']['tmp_name']);

                $qry = db("INSERT INTO ".$db['usergallery']."
                   SET `user`         = '".((int)$userid)."',
                       `beschreibung` = '".up($_POST['beschreibung'],1)."',
                       `pic`          = '".up($_FILES['file']['name'])."'");

                $index = info(_info_upload_success, "../user/?action=editprofile&show=gallery");
            }
        } elseif($_GET['do'] == "edit") {
            $qry = db("SELECT * FROM ".$db['usergallery']."
                 WHERE id = '".intval($_GET['gid'])."'");
            $get = _fetch($qry);

            if($get['user'] == $userid)
            {
                $infos = show(_upload_usergallery_info, array("userpicsize" => $upicsize));

                $index = show($dir."/usergallery_edit", array("uploadhead" => _upload_head_usergallery,
                        "file" => _upload_file,
                        "showpic" => img_size("inc/images/uploads/usergallery/".$get['user']."_".$get['pic']),
                        "id" => $_GET['gid'],
                        "showbeschreibung" => re($get['beschreibung']),
                        "name" => "file",
                        "upload" => _button_value_edit,
                        "beschreibung" => _upload_beschreibung,
                        "info" => _upload_info,
                        "infos" => $infos));
            } else {
                $index = error(_error_wrong_permissions, 1);
            }
        } elseif($_GET['do'] == "editfile") {
            $tmpname = $_FILES['file']['tmp_name'];
            $name = $_FILES['file']['name'];
            $type = $_FILES['file']['type'];
            $size = $_FILES['file']['size'];

            $endung = explode(".", $_FILES['file']['name']);
            $endung = strtolower($endung[count($endung)-1]);

            $qry = db("SELECT pic FROM ".$db['usergallery']."
                 WHERE id = '".intval($_POST['id'])."'");
            $get = _fetch($qry);

            if(!empty($_FILES['file']['size']))
            {
                $unlinkgallery = show(_gallery_edit_unlink, array("img" => $get['pic'],
                        "user" => $userid));
                @unlink($unlinkgallery);

                copy($tmpname, basePath."/inc/images/uploads/usergallery/".$userid."_".$_FILES['file']['name']);
                @unlink($_FILES['file']['tmp_name']);

                $pic = "`pic` = '".$_FILES['file']['name']."',";
            }

            $qry = db("UPDATE ".$db['usergallery']."
                 SET ".$pic."
                     `beschreibung` = '".up($_POST['beschreibung'],1)."'
                 WHERE id = '".intval($_POST['id'])."'
                 AND `user` = '".((int)$userid)."'");

            $index = info(_edit_gallery_done, "../user/?action=editprofile&show=gallery");
        }
    } else {
        $index = error(_error_wrong_permissions, 1);
    }
}
?>