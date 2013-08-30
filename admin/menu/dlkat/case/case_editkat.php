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
	$show = error(_dl_empty_kat);
} else {
	$qry = db("UPDATE ".dba::get('dl_kat')."
                     SET `name` = '".string::encode($_POST['kat'])."'
                     WHERE id = '".convert::ToInt($_GET['id'])."'");

	$show = info(_dl_admin_edited, "?admin=dlkat");
}

