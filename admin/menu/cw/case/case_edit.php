<?php
/**
 * <DZCP-Extended Edition>
 * @package: DZCP-Extended Edition
 * @author: DZCP Developer Team || Hammermaps.de Developer Team
 * @link: http://www.dzcp.de || http://www.hammermaps.de
 */

if(_adminMenu != 'true') exit();


        $qry = db("SELECT * FROM ".dba::get('cw')."
WHERE id = '".convert::ToInt($_GET['id'])."'");
        $get = _fetch($qry);

        list($xonx1,$xonx2) = explode('on', $get['xonx']);
   $qrym = db("SELECT * FROM ".dba::get('squads')."
WHERE status = '1'
ORDER BY game");
        while($gets = _fetch($qrym))
        {
            $squads .= show(_cw_edit_select_field_squads, array("id" => $gets['id'],
                                                                "name" => string::decode($gets['name']),
                                                                "game" => string::decode($gets['game']),
                                                                "sel" => ($get['squad_id'] == $gets['id'] ? 'selected="selected"' : ''),
                                                                "icon" => $gets['icon']));
   }

        $dropdown_date = show(_dropdown_date, array("day" => dropdown("day",date("d",$get['datum'])),
   "month" => dropdown("month",date("m",$get['datum'])),
                                             "year" => dropdown("year",date("Y",$get['datum']))));

        $dropdown_time = show(_dropdown_time, array("hour" => dropdown("hour",date("H",$get['datum'])),
                                             "minute" => dropdown("minute",date("i",$get['datum'])),
                                                    "uhr" => _uhr));

        $show = show($dir."/form_cw", array("head" => _cw_admin_head_edit,
                                           "datum" => _datum,
                                           "gegner" => _cw_head_gegner,
                                           "xonx" => _cw_head_xonx,
                                           "preview" => _preview,
                                           "nothing" => _cw_nothing,
                                           "screenshot1" => _cw_new." "._cw_screenshot." 1",
                                           "screenshot2" => _cw_new." "._cw_screenshot." 2",
                                           "screenshot3" => _cw_new." "._cw_screenshot." 3",
                                           "screenshot4" => _cw_new." "._cw_screenshot." 4",
                                           "screens" => _cw_screens,
                                           "liga" => _cw_head_liga,
                                           "screen_info" => _cw_screens_info,
   "gametype" => _cw_head_gametype,
                                           "url" => _url,
                                           "clantag" => _cw_admin_clantag,
                                           "bericht" => _cw_bericht,
                                           "result" => _cw_head_result,
                                           "info" => _cw_admin_info,
                                           "gegnerstuff" => _cw_admin_gegnerstuff,
                                           "warstuff" => _cw_admin_warstuff,
                                           "maps" => _cw_admin_maps,
   "match_admins" => _cw_head_admin,
   "lineup" => _cw_head_lineup,
   "glineup" => _cw_head_glineup,
                                           "serverip" => _cw_admin_serverip,
                                           "lineup_info" => _cw_admin_lineup_info,
                                           "servername" => _server_name,
                                           "serverpwd" => _server_password,
                                           "do" => "editcw&amp;id=".$_GET['id']."",
                                           "what" => _button_value_edit,
                                           "cw_clantag" => string::decode($get['clantag']),
                                           "cw_gegner" => string::decode($get['gegner']),
                                           "cw_url" => $get['url'],
                                           "cw_xonx1" => $xonx1,
                                           "logo" => _cw_logo,
                                           "cw_xonx2" => $xonx2,
                                           "cw_maps" => string::decode($get['maps']),
   "cw_matchadmins" => string::decode($get['matchadmins']),
     "cw_lineup" => string::decode($get['lineup']),
   "cw_glineup" => string::decode($get['glineup']),
                                           "cw_servername" => string::decode($get['servername']),
                                           "cw_serverip" => $get['serverip'],
                                           "cw_serverpwd" => string::decode($get['serverpwd']),
                                           "cw_punkte" => $get['punkte'],
                                           "cw_gpunkte" => $get['gpunkte'],
                                           "cw_bericht" => string::decode($get['bericht']),
                                           "day" => date("d", $get['datum']),
   "dropdown_date" => $dropdown_date,
   "dropdown_time" => $dropdown_time,
                                           "month" => date("m", $get['datum']),
                                           "year" => date("Y", $get['datum']),
                                           "hour" => date("H", $get['datum']),
                                           "minute" => date("i", $get['datum']),
                                           "name" => _member_admin_squad,
   "countrys" => show_countrys($get['gcountry']),
   "squad_info" => _cw_admin_head_squads,
                                           "game" => _member_admin_game,
                                           "squads" => $squads,
                                           "cw_liga" => string::decode($get['liga']),
   "country" => _cw_admin_head_country,
   "cw_gametype" => string::decode($get['gametype'])));