<?php
/**
 * <DZCP-Extended Edition>
 * @package: DZCP-Extended Edition
 * @author: DZCP Developer Team || Hammermaps.de Developer Team
 * @link: http://www.dzcp.de || http://www.hammermaps.de
 */

if(_adminMenu != 'true') exit();
$get = db("SELECT host_ip_dns,server_port FROM ".dba::get('ts')." WHERE `id` = ".convert::ToInt($_GET['id'])." LIMIT 1",false,true);
$ip_port = TS3Renderer::tsdns($get['host_ip_dns']);
$host = ($ip_port != false && is_array($ip_port) ? $ip_port['ip'] : $get['host_ip_dns']);
$port = ($ip_port != false && is_array($ip_port) ? $ip_port['port'] : $get['server_port']);
Cache::delete('teamspeak_'.md5($host.':'.$port));
db("DELETE FROM ".dba::get('ts')." WHERE id = '".convert::ToInt($_GET['id'])."'");
$show = info(show(_server_admin_deleted,array('host'=>$host.':'.$port)), "?admin=teamspeak");