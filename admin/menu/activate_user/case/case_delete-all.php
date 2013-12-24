<?php
/**
 * <DZCP-Extended Edition>
 * @package: DZCP-Extended Edition
 * @author: DZCP Developer Team || Hammermaps.de Developer Team
 * @link: http://www.dzcp.de || http://www.hammermaps.de
 */

if(_adminMenu != 'true') exit();

if(isset($_POST['userid']) && count($_POST['userid']) >= 1)
{
    foreach ($_POST['userid'] as $id)
    {
        db_stmt("DELETE FROM `".dba::get('users')."` WHERE `id` = ?",array('i', $id));
        db_stmt("DELETE FROM `".dba::get('permissions')."` WHERE `user` = ?",array('i', $id));
        db_stmt("DELETE FROM `".dba::get('userstats')."` WHERE `user` = ?",array('i', $id));
        db_stmt("DELETE FROM `".dba::get('rss')."` WHERE `userid` = ?",array('i', $id));
    }

    $show = info(_users_deleted, "?index=admin&amp;admin=activate_user", 4);
}
