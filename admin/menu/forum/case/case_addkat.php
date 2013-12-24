<?php
/**
 * <DZCP-Extended Edition>
 *
 * @package : DZCP-Extended Edition
 * @author : DZCP Developer Team || Hammermaps.de Developer Team
 * @link
 */

if (_adminMenu != 'true') exit();

if(!empty($_POST['kat']))
{
    if($_POST['kid'] == "1" || "2") $sign = ">= ";
    else  $sign = "> ";

    $posi = db("UPDATE ".dba::get('f_kats')."
                        SET `kid` = kid+1
                        WHERE kid ".$sign." '".convert::ToInt($_POST['kid'])."'");

    $qry = db("INSERT INTO ".dba::get('f_kats')."
                       SET `kid`    = '".convert::ToInt($_POST['kid'])."',
                           `name`   = '".string::encode($_POST['kat'])."',
                           `intern` = '".convert::ToInt($_POST['intern'])."'");

    $insert_id=database::get_insert_id();
    $tmpname = $_FILES['icon']['tmp_name'];
    $name = $_FILES['icon']['name'];
    $type = $_FILES['icon']['type'];
    $size = $_FILES['icon']['size'];
    $imageinfo = @getimagesize($tmpname);

    $endung = explode(".", $_FILES['icon']['name']);
    $endung = strtolower($endung[count($endung)-1]);

    if($tmpname)
    {
        foreach($picformat as $tmpendung)
        {
            if(file_exists(basePath."/inc/images/uploads/forum/mainkat/".$insert_id.".".$tmpendung))
            {
                @unlink(basePath."/inc/images/uploads/forum/mainkat/".$insert_id.".".$tmpendung);
            }
        }
        copy($tmpname, basePath."/inc/images/uploads/forum/mainkat/".$insert_id.".".strtolower($endung)."");
        @unlink($_FILES['icon']['tmp_name']);

    }

    $show = info(_config_forum_kat_added, "?index=admin&amp;admin=forum");
} else {
    $show = error(_config_empty_katname);
}