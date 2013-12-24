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
        $infos = show(_upload_usergallery_info, array("userpicsize" => settings('upicsize')));

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
                $index = error(_upload_no_data);
            } elseif($size > settings('upicsize')."000") {
                $index = error(_upload_wrong_size);
            } elseif(cnt(dba::get('usergallery'), " WHERE user = ".userid()) == settings('m_gallerypics')) {
                $index = error(_upload_over_limit, '2');
            } elseif(file_exists(basePath."/inc/images/uploads/usergallery/".userid()."_".$_FILES['file']['name'])) {
                $index = error(_upload_file_exists);
            } else {
                copy($tmpname, basePath."/inc/images/uploads/usergallery/".userid()."_".$_FILES['file']['name']);
                @unlink($_FILES['file']['tmp_name']);

                $qry = db("INSERT INTO ".dba::get('usergallery')."
                   SET `user`         = '".userid()."',
                       `beschreibung` = '".string::encode($_POST['beschreibung'])."',
                       `pic`          = '".string::encode($_FILES['file']['name'])."'");

                $index = info(_info_upload_success, "?index=user&amp;action=editprofile&show=gallery");
            }
        } elseif($_GET['do'] == "edit") {
            $qry = db("SELECT * FROM ".dba::get('usergallery')."
                 WHERE id = '".convert::ToInt($_GET['gid'])."'");
            $get = _fetch($qry);

            if($get['user'] == userid())
            {
                $infos = show(_upload_usergallery_info, array("userpicsize" => settings('upicsize')));

                $index = show($dir."/usergallery_edit", array("uploadhead" => _upload_head_usergallery,
                        "file" => _upload_file,
                        "showpic" => img_size("inc/images/uploads/usergallery/".$get['user']."_".$get['pic']),
                        "id" => $_GET['gid'],
                        "showbeschreibung" => string::decode($get['beschreibung']),
                        "name" => "file",
                        "upload" => _button_value_edit,
                        "beschreibung" => _upload_beschreibung,
                        "info" => _upload_info,
                        "infos" => $infos));
            } else {
                $index = error(_error_wrong_permissions);
            }
        } elseif($_GET['do'] == "editfile") {
            $tmpname = $_FILES['file']['tmp_name'];
            $name = $_FILES['file']['name'];
            $type = $_FILES['file']['type'];
            $size = $_FILES['file']['size'];

            $endung = explode(".", $_FILES['file']['name']);
            $endung = strtolower($endung[count($endung)-1]);

            $qry = db("SELECT pic FROM ".dba::get('usergallery')."
                 WHERE id = '".convert::ToInt($_POST['id'])."'");
            $get = _fetch($qry);

            if(!empty($_FILES['file']['size']))
            {
                $unlinkgallery = show(_gallery_edit_unlink, array("img" => $get['pic'],
                        "user" => userid()));
                @unlink($unlinkgallery);

                copy($tmpname, basePath."/inc/images/uploads/usergallery/".userid()."_".$_FILES['file']['name']);
                @unlink($_FILES['file']['tmp_name']);

                $pic = "`pic` = '".$_FILES['file']['name']."',";
            }

            $qry = db("UPDATE ".dba::get('usergallery')."
                 SET ".$pic."
                     `beschreibung` = '".string::encode($_POST['beschreibung'])."'
                 WHERE id = '".convert::ToInt($_POST['id'])."'
                 AND `user` = '".userid()."'");

            $index = info(_edit_gallery_done, "?index=user&amp;action=editprofile&show=gallery");
        }
    } else {
        $index = error(_error_wrong_permissions);
    }
}