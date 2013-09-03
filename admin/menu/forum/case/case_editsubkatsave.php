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

	if($_POST['order'] == "lazy"){
		$order = "";
	}else{
		$order = "`pos` = '".convert::ToInt($_POST['order'])."',";

		if($_POST['order'] == "1" || "2") $sign = ">= ";
		else  $sign = "> ";
		$posi = db("UPDATE ".dba::get('f_skats')."
                        SET `pos` = pos+1
                        WHERE `pos` ".$sign." '".convert::ToInt($_POST['order'])."'");
	}

	$qry = db("UPDATE ".dba::get('f_skats')."
                       SET `kattopic` = '".string::encode($_POST['skat'])."',
                           ".$order."
                           `subtopic` = '".string::encode($_POST['stopic'])."'
                       WHERE id = '".convert::ToInt($_GET['id'])."'");

	$insert_id=convert::ToInt($_GET['id']);
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
	$show = info(_config_forum_skat_edited, "?admin=forum&amp;expand=".convert::ToInt($_POST['sid'])."");
}