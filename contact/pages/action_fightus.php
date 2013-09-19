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
    $qry = db("SELECT id,name,game FROM ".dba::get('squads')." WHERE status = 1 ORDER BY name"); $squads = '';
    while($get = _fetch($qry))
    {
        $squads .= show(_select_field_fightus, array("id" => $get['id'], "squad" => string::decode($get['name']), "game" => string::decode($get['game'])));
    }

    $dropdown_date = show(_dropdown_date, array("day" => dropdown("day",date("d",time())), "month" => dropdown("month",date("m",time())), "year" => dropdown("year",date("Y",time()))));
    $dropdown_time = show(_dropdown_time, array("hour" => dropdown("hour",date("H",time())), "minute" => dropdown("minute",date("i",time())), "uhr" => _uhr));
    $index = show($dir."/fightus", array("datum" => $dropdown_date, "squads" => $squads, "zeit" => $dropdown_time, "year" => date("Y", time())));
}