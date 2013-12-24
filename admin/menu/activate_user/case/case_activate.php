<?php
/**
 * <DZCP-Extended Edition>
 * @package: DZCP-Extended Edition
 * @author: DZCP Developer Team || Hammermaps.de Developer Team
 * @link: http://www.dzcp.de || http://www.hammermaps.de
 */

if(_adminMenu != 'true') exit();

db_stmt("UPDATE `".dba::get('users')."` SET `level` = 1, `status` = 1, `actkey` = '' WHERE `id` = ?",array('i', $_GET['id']));
$show = info(_actived, "?index=admin&amp;admin=activate_user", 2);