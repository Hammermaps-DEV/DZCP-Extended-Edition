<?php
/**
 * <DZCP-Extended Edition>
 * @package: DZCP-Extended Edition
 * @author: DZCP Developer Team || Hammermaps.de Developer Team
 * @link: http://www.dzcp.de || http://www.hammermaps.de
 */

if(_adminMenu != 'true') exit();

$get = db("SELECT public,id FROM ".dba::get('artikel')." WHERE id = '".convert::ToInt($_GET['id'])."'",false,true);
db("UPDATE ".dba::get('artikel')." SET `public` = '".($get['public'] ? '0' : '1')."', `datum`  = '".($get['public'] ? '0' : time())."' WHERE id = '".$get['id']."'");
header("Location: ?index=admin&admin=artikel");