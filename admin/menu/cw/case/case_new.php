<?php
/**
 * <DZCP-Extended Edition>
 * @package: DZCP-Extended Edition
 * @author: DZCP Developer Team || Hammermaps.de Developer Team
 * @link: http://www.dzcp.de || http://www.hammermaps.de
 */

if(_adminMenu != 'true') exit();

$qry = db("SELECT * FROM ".dba::get('squads')." WHERE status = '1' ORDER BY game ASC");
$squads = '';
while($get = _fetch($qry))
{
    $squads .= show(_cw_add_select_field_squads, array("name" => string::decode($get['name']), "game" => string::decode($get['game']), "id" => $get['id'],"icon" => $get['icon']));
}

$dropdown_date = show(_dropdown_date, array("day" => dropdown("day",date("d",time())), "month" => dropdown("month",date("m",time())), "year" => dropdown("year",date("Y",time()))));
$dropdown_time = show(_dropdown_time, array("hour" => dropdown("hour",date("H",time())), "minute" => dropdown("minute",date("i",time())), "uhr" => _uhr));

$show = show($dir."/form_cw", array("head" => _cw_admin_head,
                                    "nothing" => "",
                                    "screenshot1" => _cw_screenshot." 1",
                                    "screenshot2" => _cw_screenshot." 2",
                                    "screenshot3" => _cw_screenshot." 3",
                                    "screenshot4" => _cw_screenshot." 4",
                                    "do" => "add",
                                    "what" => _button_value_add,
                                    "cw_clantag" => "",
                                    "cw_gegner" => "",
                                    "cw_url" => "",
                                    "cw_xonx1" => "",
                                    "cw_xonx2" => "",
                                    "cw_maps" => "",
                                    "cw_servername" => "",
                                    "cw_serverip" => "",
                                    "cw_serverpwd" => "",
                                    "cw_punkte" => "",
                                    "cw_gpunkte" => "",
                                    "cw_matchadmins" => "",
                                    "cw_lineup" => "",
                                    "cw_glineup" => "",
                                    "cw_bericht" => "",
                                    "dropdown_date" => $dropdown_date,
                                    "dropdown_time" => $dropdown_time,
                                    "hour" => "",
                                    "minute" => "",
                                    "squads" => $squads,
                                    "cw_liga" => "",
                                    "countrys" => show_countrys(),
                                    "cw_gametype" => ""));

unset($squads,$dropdown_date,$dropdown_time,$qry);