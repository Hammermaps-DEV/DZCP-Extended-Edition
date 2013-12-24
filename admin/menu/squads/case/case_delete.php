<?php
/**
 * <DZCP-Extended Edition>
 * @package: DZCP-Extended Edition
 * @author: DZCP Developer Team || Hammermaps.de Developer Team
 * @link: http://www.dzcp.de || http://www.hammermaps.de
 */

if(_adminMenu != 'true') exit();

db("DELETE FROM ".dba::get('squads')." WHERE id = '".convert::ToInt($_GET['id'])."'");
db("DELETE FROM ".dba::get('squaduser')." WHERE squad = '".convert::ToInt($_GET['id'])."'");
db("DELETE FROM ".dba::get('navi')." WHERE url = '?index=squads&amp;action=shows&amp;id=".convert::ToInt($_GET['id'])."'");
$show = info(_admin_squad_deleted, "?index=admin&amp;admin=squads");