<?php
/**
 * <DZCP-Extended Edition>
 * @package: DZCP-Extended Edition
 * @author: DZCP Developer Team || Hammermaps.de Developer Team
 * @link: http://www.dzcp.de || http://www.hammermaps.de
 */

if(_adminMenu != 'true') exit();

$get = db("SELECT ip,port,game,name FROM ".dba::get('server')." WHERE id = '".convert::ToInt($_GET['id'])."'",false,true);
$cache_hash = md5($get['ip'].':'.$get['port'].'_'.$get['game']);
Cache::delete('server_'.$cache_hash);

db("DELETE FROM ".dba::get('server')." WHERE id = '".convert::ToInt($_GET['id'])."'");
$show = info(show(_server_admin_deleted,array('host' => $get['name'])), "?admin=server");