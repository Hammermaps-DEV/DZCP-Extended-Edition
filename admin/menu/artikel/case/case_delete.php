<?php
/**
 * <DZCP-Extended Edition>
 * @package: DZCP-Extended Edition
 * @author: DZCP Developer Team || Hammermaps.de Developer Team
 * @link: http://www.dzcp.de || http://www.hammermaps.de
 */

#####################
## Admin Menu-File ##
#####################
if(_adminMenu != 'true')
    exit();

db("DELETE FROM ".dba::get('artikel')." WHERE id = '".convert::ToInt($_GET['id'])."'");
$show = info(_artikel_deleted, "?admin=artikel");