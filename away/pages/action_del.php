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
    if(checkme() == "unlogged" || checkme() < "2")
        $index = error(_error_wrong_permissions);
    else
    {
        db("DELETE FROM ".dba::get('away')." WHERE id = '".convert::ToInt($_GET['id'])."'");
        $index = info(_away_successful_del, "?index=away");
    }
}