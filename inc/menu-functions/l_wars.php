<?php
/**
 * <DZCP-Extended Edition>
 * @package: DZCP-Extended Edition
 * @author: DZCP Developer Team || Hammermaps.de Developer Team
 * @link: http://www.dzcp.de || http://www.hammermaps.de
 */

#####################
##### Menu-File #####
#####################

function l_wars()
{
    global $allowHover;
    $menu_xml = get_menu_xml('l_wars');
    if(!Cache::is_mem() || Cache::check('nav_l_wars'))
    {
        $lwarsconfig = config(array('m_lwars','l_lwars')); $lwars = '';
        $qry = db("SELECT s1.datum,s1.gegner,s1.id,s1.bericht,s1.xonx,s1.clantag,s1.punkte,s1.gpunkte,s1.squad_id,s2.icon,s2.name FROM ".dba::get('cw')." AS s1
                 LEFT JOIN ".dba::get('squads')." AS s2 ON s1.squad_id = s2.id
                 WHERE datum < ".time()."
                 ORDER BY datum DESC
                 LIMIT ".$lwarsconfig['m_lwars']."");

        if(_rows($qry))
        {
            while($get = _fetch($qry))
            {
                if($allowHover == 1 || $allowHover == 2)
                    $info = 'onmouseover="DZCP.showInfo(\''.jsconvert(string::decode($get['name'])).' vs. '.jsconvert(string::decode($get['gegner'])).'\', \''._played_at.';'._cw_xonx.';'._result.';'._comments_head.'\', \''.date("d.m.Y H:i", $get['datum'])._uhr.';'.jsconvert(string::decode($get['xonx'])).';'.cw_result_nopic_nocolor($get['punkte'],$get['gpunkte']).';'.cnt(dba::get('cw_comments'), "WHERE cw = '".$get['id']."'").'\')" onmouseout="DZCP.hideInfo()"';

                $lwars .= show("menu/last_wars", array("id" => $get['id'],"clantag" => string::decode(cut($get['clantag'],$lwarsconfig['l_lwars'])),"icon" => string::decode($get['icon']),"info" => $info,"result" => cw_result_pic($get['punkte'],$get['gpunkte'])));
            }

            if(Cache::is_mem() && $menu_xml['xml'] && $menu_xml['config']['update'] != '0') //Only Memory Cache
                Cache::set('nav_l_wars',$lwars,$menu_xml['config']['update']);
        }
    }
    else
        $lwars = Cache::get('nav_l_wars');

    return empty($lwars) ? '' : '<table class="navContent" cellspacing="0">'.$lwars.'</table>';
}