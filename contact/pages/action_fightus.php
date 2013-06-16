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
    $qry = db("SELECT id,name,game FROM ".dba::get('squads')."
             WHERE status = 1
             ORDER BY name");
    while($get = _fetch($qry))
    {
        $squads .= show(_select_field_fightus, array("id" => $get['id'],
                "squad" => re($get['name']),
                "game" => re($get['game'])));
    }

    $dropdown_date = show(_dropdown_date, array("day" => dropdown("day",date("d",time())),
            "month" => dropdown("month",date("m",time())),
            "year" => dropdown("year",date("Y",time()))));

    $dropdown_time = show(_dropdown_time, array("hour" => dropdown("hour",date("H",time())),
            "minute" => dropdown("minute",date("i",time())),
            "uhr" => _uhr));

    $index = show($dir."/fightus", array("head" => _site_fightus,
            "nachricht" => _contact_fightus,
            "partner" => _contact_fightus_partner,
            "clandaten" => _contact_fightus_clandata,
            "nick" => _nick,
            "datum" => $dropdown_date,
            "squad" => _fightus_squad,
            "squads" => $squads,
            "zeit" => $dropdown_time,
            "security" => _register_confirm,
            "clan" => _contact_fightus_clanname,
            "date" => _datum,
            "value" => _button_value_send,
            "year" => date("Y", time()),
            "maps" => _fightus_maps,
            "vs" => _cw_xonx,
            "game" => _game,
            "hp" => _hp,
            "pflicht" => _contact_pflichtfeld,
            "email" => _email,
            "icq" => _icq));
}