<?php
/**
 * <DZCP-Extended Edition>
 * @package: DZCP-Extended Edition
 * @author: DZCP Developer Team || Hammermaps.de Developer Team
 * @link: http://www.dzcp.de || http://www.hammermaps.de
 */

####################################
## Wird in einer Index ausgeführt ##
####################################
if (!defined('IS_DZCP'))
    exit();

if (_version < '1.0') //Mindest Version pruefen
    $index = _version_for_page_outofdate;
else
{
    if(permission("clanwars")) {
        db("DELETE FROM ".$db['cw_player']." WHERE `cwid` = '".intval($_GET['id'])."'");
    }

    $index = info(_cw_players_reset, '?action=details&id='.intval($_GET['id']));
}
?>