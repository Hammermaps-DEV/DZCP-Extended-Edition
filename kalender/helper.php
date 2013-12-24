<?php
/**
 * <DZCP-Extended Edition>
 * @package: DZCP-Extended Edition
 * @author: DZCP Developer Team || Hammermaps.de Developer Team
 * @link: http://www.dzcp.de || http://www.hammermaps.de
 */

/**
* Stellt eine Liste der Events für den Kalender zusammen
* Clanwars,Geburtstage,Kalender Events, etc.
*
* @return array
*/
function kalender_show_events($i=0,$monat=0,$jahr=0,$datum=0)
{
    $bdays = ""; $cws = ""; $event = ""; $i = cal(convert::ToInt($i));

    //Geburtstage
    $qry = db("SELECT id,bday,nick FROM ".dba::get('users')." WHERE bday LIKE '".$i.".".$monat.".____"."'");
    if(($bday_count=_rows($qry)))
    {
        $infoBday = ''; $i_bday = 1;
        while($get = _fetch($qry))
        {
            $p_tag = ($bday_count >= 2 && $i_bday != $bday_count ? '<p>' : '');
            $infoBday .= jsconvert(_kal_birthday.rawautor($get['id']).$p_tag); $i_bday++;
        }

        $info = ' onmouseover="DZCP.showInfo(\''.$infoBday.'\')" onmouseout="DZCP.hideInfo()"';
        $bdays = '<a href="?index=user&amp;action=userlist&amp;show=bday&amp;time='.$datum.'"'.$info.'><img src="inc/images/bday.gif" alt="" /></a>';
        unset($qry,$bday_count,$get,$p_tag,$infoBday,$i_bday,$info);
    }

    //Clanwars
    $qry = db("SELECT datum,gegner,squad_id FROM ".dba::get('cw')." WHERE DATE_FORMAT(FROM_UNIXTIME(datum), '%d.%m.%Y') = '".$i.".".$monat.".".$jahr."'");
    if(($cw_count=_rows($qry)))
    {
        $infoCW = ''; $i_cw = 1;
        while($get = _fetch($qry))
        {
            $get_squad_icon = db("SELECT icon FROM `".dba::get('squads')."` WHERE `id` = '".$get['squad_id']."' LIMIT 1",false,true);
            $p_tag = ($cw_count >= 2 && $i_cw != $cw_count ? '<p>' : '');
            $test = '<img align="absmiddle" src="inc/images/gameicons/'.string::decode($get_squad_icon['icon']).'" alt="" /> ';
            $infoCW .= jsconvert($test._kal_cw.string::decode($get['gegner']).$p_tag); $i_cw++;
        }

        $info = ' onmouseover="DZCP.showInfo(\''.$infoCW.'\')" onmouseout="DZCP.hideInfo()"';
        $cws = '<a href="?index=clanwars&amp;action=kalender&amp;time='.$datum.'"'.$info.'><img src="inc/images/cw.gif" alt="" /></a>';
        unset($qry,$cw_count,$get,$p_tag,$infoCW,$i_cw,$info);
    }

    //Events
    $qry = db("SELECT datum,title FROM ".dba::get('events')." WHERE DATE_FORMAT(FROM_UNIXTIME(datum), '%d.%m.%Y') = '".$i.".".$monat.".".$jahr."'");
    if(($event_count=_rows($qry)))
    {
        $infoEvent = ''; $i_event = 1;
        while($get = _fetch($qry))
        {
            $p_tag = ($event_count >= 2 && $i_event != $event_count ? '<p>' : '');
            $infoEvent .= jsconvert(_kal_event.string::decode($get['title']).$p_tag);  $i_event++;
        }

        $info = ' onmouseover="DZCP.showInfo(\''.$infoEvent.'\')" onmouseout="DZCP.hideInfo()"';
        $event = '<a href="?index=kalender&amp;action=show&amp;time='.$datum.'"'.$info.'><img src="inc/images/event.png" alt="" /></a>';
        unset($qry,$event_count,$get,$p_tag,$infoEvent,$i_event,$info);
    }

    return $bdays." ".$cws." ".$event;
}