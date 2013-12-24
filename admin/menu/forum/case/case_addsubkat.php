<?php
/**
 * <DZCP-Extended Edition>
 * @package: DZCP-Extended Edition
 * @author: DZCP Developer Team || Hammermaps.de Developer Team
 * @link: http://www.dzcp.de || http://www.hammermaps.de
 */

if(_adminMenu != 'true') exit();

if(empty($_POST['skat']))
{
    $show = error(_config_forum_empty_skat);
} else {
    if($_POST['order'] == "1" || "2") $sign = ">= ";
    else  $sign = "> ";

    $posi = db("UPDATE ".dba::get('f_skats')."
                        SET `pos` = pos+1
                        WHERE `pos` ".$sign." '".convert::ToInt($_POST['order'])."'");

    $qry = db("INSERT INTO ".dba::get('f_skats')."
                       SET `sid`      = '".convert::ToInt($_GET['id'])."',
                           `pos`    = '".convert::ToInt($_POST['order'])."',
                           `kattopic` = '".string::encode($_POST['skat'])."',
                           `subtopic` = '".string::encode($_POST['stopic'])."'");



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
            if(file_exists(basePath."/inc/images/uploads/forum/subkat/".$insert_id.".".$tmpendung))
            {
                @unlink(basePath."/inc/images/uploads/forum/subkat/".$insert_id.".".$tmpendung);
            }
        }
        copy($tmpname, basePath."/inc/images/uploads/forum/subkat/".$insert_id.".".strtolower($endung)."");
        @unlink($_FILES['icon']['tmp_name']);

    }

    $show = info(_config_forum_skat_added, "?index=admin&amp;admin=forum&amp;expand=".convert::ToInt($_GET['id'])."");
}