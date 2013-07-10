<?php
/**
 * <DZCP-Extended Edition>
 * @package: DZCP-Extended Edition
 * @author: DZCP Developer Team || Hammermaps.de Developer Team
 * @link: http://www.dzcp.de || http://www.hammermaps.de
 */

/**
 * Prueft ob ein Ereignis neu ist.
 *
 * @return boolean
 */
function check_is_new($datum = 0)
{
    global $userid;

    if($userid != 0 && !empty($userid) && !empty($datum))
    {
        if(convert::ToInt($datum) >= userstats($userid, 'lastvisit'))
            return true;
    }

    return false;
}

//Funktion fuer die Sprachdefinierung der Profilfelder
function pfields_name($name)
{
    $pattern = array("=_city_=Uis","=_hobbys_=Uis","=_motto_=Uis","=_job_=Uis","=_exclans_=Uis","=_email2_=Uis","=_email3_=Uis","=_autor_=Uis","=_auto_=Uis","=_buch_=Uis",
    "=_drink_=Uis","=_essen_=Uis","=_favoclan_=Uis","=_film_=Uis","=_game_=Uis","=_map_=Uis","=_musik_=Uis","=_person_=Uis","=_song_=Uis","=_spieler_=Uis","=_sportler_=Uis",
    "=_sport_=Uis","=_waffe_=Uis","=_board_=Uis","=_cpu_=Uis","=_graka_=Uis","=_hdd_=Uis","=_headset_=Uis","=_inet_=Uis","=_maus_=Uis","=_mauspad_=Uis","=_monitor_=Uis",
    "=_ram_=Uis","=_system_=Uis");

    $replacement = array(_profil_city,_profil_hobbys,_profil_motto,_profil_job,_profil_exclans,_profil_email2,_profil_email3,_profil_autor,_profil_auto,
    _profil_buch,_profil_drink,_profil_essen,_profil_favoclan,_profil_film,_profil_game,_profil_map,_profil_musik,_profil_person,_profil_song,_profil_spieler,
    _profil_sportler,_profil_sport,_profil_waffe,_profil_board,_profil_cpu,_profil_graka,_profil_hdd,_profil_headset,_profil_inet,_profil_maus,_profil_mauspad,
    _profil_monitor,_profil_ram,_profil_os);

    return preg_replace($pattern, $replacement, $name);
}

//-> Prueft ob ein User schon in der Buddyliste vorhanden ist
function check_buddy($buddy)
{
    global $userid;
    return (db("SELECT buddy FROM ".dba::get('buddys')." WHERE user = '".convert::ToInt($userid)."' AND buddy = '".convert::ToInt($buddy)."'",true) ? false : true);
}

//-> Prueft, ob eine Userid existiert
function exist($tid)
{
    echo 'run<p>';
    return db("SELECT id FROM ".dba::get('users')." WHERE id = '".convert::ToInt($tid)."'",true) ? true : false;
}