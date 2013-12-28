<?php
/**
 * <DZCP-Extended Edition>
 * @package: DZCP-Extended Edition
 * @author: DZCP Developer Team || Hammermaps.de Developer Team
 * @link: http://www.dzcp.de || http://www.hammermaps.de
 */

if(_adminMenu != 'true') exit();

if($_GET['what'] == 'set')
    db("UPDATE ".dba::get('profile')." SET `shown` = '1' WHERE id = '".convert::ToInt($_GET['id'])."'");
else
    db("UPDATE ".dba::get('profile')." SET `shown` = '0' WHERE id = '".convert::ToInt($_GET['id'])."'");

header("Location: ?index=admin&admin=profile");