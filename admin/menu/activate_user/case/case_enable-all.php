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
        db_stmt("UPDATE `".dba::get('users')."` SET `level` = 1, `status` = 1, `actkey` = '' WHERE `id` = ?",array('i', $id));
    }

    $show = info(_actived_all, "?index=admin&amp;admin=activate_user", 3);
}