<?php
/**
 * <DZCP-Extended Edition>
 * @package: DZCP-Extended Edition
 * @author: DZCP Developer Team || Hammermaps.de Developer Team
 * @link: http://www.dzcp.de || http://www.hammermaps.de
 */

if(_adminMenu != 'true') exit();

$get = db("SELECT id,enable FROM ".dba::get('ipban')." WHERE `id` = ".convert::ToInt($_GET['id']),false,true);
db("UPDATE ".dba::get('ipban')." SET `enable` = '".($get['enable'] == '1' ? '0' : '1')."' WHERE `id` = ".$get['id'].";");
$show = header("Location: ?index=admin&admin=ipban&sfs_side=".(isset($_GET['sfs_side']) ? $_GET['sfs_side'] : 1)."&sys_side=".(isset($_GET['sys_side']) ? $_GET['sys_side'] : 1)."&ub_side=".(isset($_GET['ub_side']) ? $_GET['ub_side'] : 1));