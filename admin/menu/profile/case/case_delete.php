<?php
/**
 * <DZCP-Extended Edition>
 * @package: DZCP-Extended Edition
 * @author: DZCP Developer Team || Hammermaps.de Developer Team
 * @link: http://www.dzcp.de || http://www.hammermaps.de
 */

if(_adminMenu != 'true') exit();

$get = db("SELECT feldname FROM ".dba::get('profile')." WHERE id = '".convert::ToInt($_GET['id'])."'",false,true);
db("ALTER TABLE ".dba::get('users')." DROP `".$get['feldname']."`");
db("DELETE FROM ".dba::get('profile')." WHERE id = '".convert::ToInt($_GET['id'])."'");
$show = info(_profil_deleted, "?index=admin&amp;admin=profile");