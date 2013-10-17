<?php
/**
 * <DZCP-Extended Edition>
 * @package: DZCP-Extended Edition
 * @author: DZCP Developer Team || Hammermaps.de Developer Team
 * @link: http://www.dzcp.de || http://www.hammermaps.de
 */

if(_adminMenu != 'true') exit();

$get = db("SELECT navi,game,id FROM ".dba::get('server')." WHERE id = '".convert::ToInt($_GET['id'])."'",false,true);
if($get['game'] != 'nope')
{
    db("UPDATE ".dba::get('server')." SET `navi` = '".($get['navi'] ? '0' : '1')."' WHERE id = '".$get['id']."'");
    header("Location: ?admin=server");
}
else
    $show = error(_server_isnt_live);