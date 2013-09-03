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

	$show = info(_config_forum_skat_added, "?admin=forum&amp;expand=".convert::ToInt($_GET['id'])."");
}