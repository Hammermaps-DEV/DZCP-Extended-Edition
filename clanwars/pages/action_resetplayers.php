<?php
/**
 * <DZCP-Extended Edition>
 * @package: DZCP-Extended Edition
 * @author: DZCP Developer Team || Hammermaps.de Developer Team
 * @link: http://www.dzcp.de || http://www.hammermaps.de
 */

if (!defined('IS_DZCP')) exit();
if (_version < '1.0')
    $index = _version_for_page_outofdate;
else
{
    if(permission("clanwars"))
        db("DELETE FROM ".dba::get('cw_player')." WHERE `cwid` = '".convert::ToInt($_GET['id'])."'");

    $index = info(_cw_players_reset, '?index=clanwars&amp;action=details&id='.convert::ToInt($_GET['id']));
}