<?php
/**
 * <DZCP-Extended Edition>
 * @package: DZCP-Extended Edition
 * @author: DZCP Developer Team || Hammermaps.de Developer Team
 * @link: http://www.dzcp.de || http://www.hammermaps.de
 */

if(_adminMenu != 'true') exit();

if(empty($_POST['kat']))
{
    $show = error(_config_empty_katname);
} else {
    if($_POST['kid'] == "lazy"){
        $kid = "";
    }else{
        $kid = "`kid` = '".convert::ToInt($_POST['kid'])."',";

        if($_POST['kid'] == "1" || "2") $sign = ">= ";
        else  $sign = "> ";
        $posi = db("UPDATE ".dba::get('f_kats')."
                        SET `kid` = kid+1
                        WHERE `kid` ".$sign." '".convert::ToInt($_POST['kid'])."'");
    }


    $qry = db("UPDATE ".dba::get('f_kats')."
                       SET `name`    = '".string::encode($_POST['kat'])."',
                           ".$kid."
                           `intern`  = '".convert::ToInt($_POST['intern'])."'
                       WHERE id = '".convert::ToInt($_POST['id'])."'");


    $insert_id=convert::ToInt($_POST['id']);
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

    $show = info(_config_forum_kat_edited, "?index=admin&amp;admin=forum");
}