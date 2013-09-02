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

	$show = info(_config_forum_kat_added, "?admin=forum");
} else {
	$show = error(_config_empty_katname);
}