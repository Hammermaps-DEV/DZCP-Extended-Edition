<?php
/**
 * <DZCP-Extended Edition>
 * @package: DZCP-Extended Edition
 * @author: DZCP Developer Team || Hammermaps.de Developer Team
 * @link: http://www.dzcp.de || http://www.hammermaps.de
 */

if(_adminMenu != 'true') exit();

db("DELETE FROM ".dba::get('downloads')." WHERE id = '".convert::ToInt($_GET['id'])."'");
db("DELETE FROM ".dba::get('dl_comments')." WHERE download = '".convert::ToInt($_GET['id'])."'");
$show = info(_downloads_deleted, "?index=admin&amp;admin=dladmin");