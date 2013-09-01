<?php
/**
 * <DZCP-Extended Edition>
 * @package: DZCP-Extended Edition
 * @author: DZCP Developer Team || Hammermaps.de Developer Team
 * @link: http://www.dzcp.de || http://www.hammermaps.de
 */

if(_adminMenu != 'true') exit();

db("DELETE FROM ".dba::get('ipban')." WHERE id = '".convert::ToInt($_GET['id'])."'");
$show = info(_ipban_admin_deleted, "?admin=ipban");