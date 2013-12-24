<?php
/**
 * <DZCP-Extended Edition>
 * @package: DZCP-Extended Edition
 * @author: DZCP Developer Team || Hammermaps.de Developer Team
 * @link: http://www.dzcp.de || http://www.hammermaps.de
 */

if(_adminMenu != 'true') exit();

if(($id = isset($_GET['id']) ? $_GET['id'] : false) != false)
{
    db("DELETE FROM ".dba::get('users')." WHERE id = ".convert::ToInt($id));
    db("DELETE FROM ".dba::get('permissions')." WHERE user = ".convert::ToInt($id));
    db("DELETE FROM ".dba::get('userstats')." WHERE user = ".convert::ToInt($id));
    db("DELETE FROM ".dba::get('rss')." WHERE userid = ".convert::ToInt($id));
    $show = info(_user_deleted, "?index=admin&amp;admin=activate_user", 4);
}