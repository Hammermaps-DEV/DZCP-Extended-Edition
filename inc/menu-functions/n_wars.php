<?php
function n_wars()
{
    global $allowHover;
    $menu_xml = get_menu_xml('n_wars');
    if(!Cache::is_mem() || !$menu_xml['xml'] || Cache::check('nav_n_wars'))
    {
        $nwarsconfig = config(array('m_nwars','l_nwars')); $nwars = '';
        $qry = db("SELECT s1.id,s1.datum,s1.clantag,s1.maps,s1.gegner,s1.squad_id,s2.icon,s1.xonx,s2.name FROM ".dba::get('cw')." AS s1 LEFT JOIN ".dba::get('squads')." AS s2 ON s1.squad_id = s2.id
        WHERE s1.datum > ".time()." ORDER BY s1.datum LIMIT ".$nwarsconfig['m_nwars']."");

        if(_rows($qry))
        {
            while($get = _fetch($qry))
            {
                if($allowHover == 1 || $allowHover == 2)
                    $info = 'onmouseover="DZCP.showInfo(\''.jsconvert(re($get['name'])).' vs. '.jsconvert(re($get['gegner'])).'\', \''._datum.';'._cw_xonx.';'._cw_maps.';'._comments_head.'\', \''.date("d.m.Y H:i", $get['datum'])._uhr.';'.jsconvert(re($get['xonx'])).';'.jsconvert(re($get['maps'])).';'.cnt(dba::get('cw_comments'),"WHERE cw = '".$get['id']."'").'\')" onmouseout="DZCP.hideInfo()"';

                $nwars .= show("menu/next_wars", array("id" => $get['id'], "clantag" => re(cut($get['clantag'],$nwarsconfig['l_nwars'])), "icon" => re($get['icon']), "info" => $info, "datum" => date("d.m.:", $get['datum'])));
            }

            if(Cache::is_mem() && $menu_xml['xml'] && $menu_xml['config']['update'] != '0') //Only Memory Cache
                Cache::set('nav_n_wars',$nwars,$menu_xml['config']['update']);
        }
        else
            $nwars = show(_navi_nnwars_entrys, array("colspan" => "1"));
    }
    else
        $nwars = Cache::get('nav_n_wars');

    return empty($nwars) ? '' : '<table class="navContent" cellspacing="0">'.$nwars.'</table>';
}