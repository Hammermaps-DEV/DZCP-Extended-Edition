<?php
/**
 * <DZCP-Extended Edition>
 * @package: DZCP-Extended Edition
 * @author: DZCP Developer Team || Hammermaps.de Developer Team
 * @link: http://www.dzcp.de || http://www.hammermaps.de
 */

if(_adminMenu != 'true') exit();

$qry = db("DELETE FROM ".dba::get('dl_kat')."
                   WHERE id = '".convert::ToInt($_GET['id'])."'");

$show = info(_dl_admin_deleted, "?index=admin&amp;admin=dlkat");