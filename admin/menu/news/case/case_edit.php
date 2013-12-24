<?php
/**
 * <DZCP-Extended Edition>
 * @package: DZCP-Extended Edition
 * @author: DZCP Developer Team || Hammermaps.de Developer Team
 * @link: http://www.dzcp.de || http://www.hammermaps.de
 */

if(_adminMenu != 'true') exit();

$get = db("SELECT * FROM ".dba::get('newskat')." WHERE id = '".convert::ToInt($_GET['id'])."'",false,true);
$files = get_files(basePath.'/inc/images/uploads/newskat/',false,true); $img = '';
if($files != false)
{
    foreach($files as $file)
    {
        $img .= show(_select_field, array("value" => $file, "sel" => ($get['katimg'] == $file ? 'selected="selected"' : ''), "what" => $file));
    }
}

$upload = show(_config_neskats_katbild_upload_edit, array("id" => $_GET['id']));
$do = show(_config_newskats_editid, array("id" => $_GET['id']));
$show = show($dir."/newskatform", array("head" => _config_newskats_edit_head,
                                        "kat" => string::decode($get['kategorie']),
                                        "value" => _button_value_edit,
                                        "id" => $_GET['id'],
                                        "kat_img" => $get['katimg'],
                                        "nothing" => _nothing,
                                        "do" => $do,
                                        "upload" => $upload,
                                        "img" => $img));