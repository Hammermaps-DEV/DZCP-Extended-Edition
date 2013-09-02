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
                       WHERE id = '".convert::ToInt($_GET['id'])."'");

	$show = info(_config_forum_kat_edited, "?admin=forum");
}