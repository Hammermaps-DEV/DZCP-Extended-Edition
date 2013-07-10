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

function counter()
{
    global $where;
    $menu_xml = get_menu_xml('counter');
    if(!isSpider())
    {
        if(!Cache::is_mem() || !$menu_xml['xml'] || Cache::check('nav_counter'))
        {
            $v_today = 0;
            $qry2day = db("SELECT visitors FROM ".dba::get('counter')." WHERE today = '".date("j.n.Y")."'");
            if(_rows($qry2day))
            {
                $get2day = _fetch($qry2day);
                $v_today = $get2day['visitors'];
            }

            $gestern = time() - 86400;
            $tag   = date("j", $gestern);
            $monat = date("n", $gestern);
            $jahr  = date("Y", $gestern);
            $yesterday = $tag.".".$monat.".".$jahr;

            $yDay = 0;
            $qryyday = db("SELECT visitors FROM ".dba::get('counter')." WHERE today = '".$yesterday."'");
            if(_rows($qryyday))
            {
                $getyday = _fetch($qryyday);
                $yDay = $getyday['visitors'];
            }

            $getstats = db("SELECT SUM(visitors) AS allvisitors, MAX(visitors) AS maxvisitors, MAX(maxonline) AS maxonline,
            AVG(visitors) AS avgvisitors, SUM(visitors) AS allvisitors FROM ".dba::get('counter')."",false,true);

            $info = '';
            if(abs($online_reg=cnt(dba::get('users'), " WHERE time+'".users_online."'>'".time()."' AND online = '1'")) != 0)
            {
                $qryo = db("SELECT id FROM ".dba::get('users')." WHERE time+'".users_online."'>'".time()."' AND online = 1 ORDER BY nick");

                $kats = ''; $text = '';
                while($geto = _fetch($qryo))
                {
                    $kats .= fabo_autor($geto['id']).';';
                    $text .= jsconvert(getrank($geto['id'])).';';
                }

                $info = 'onmouseover="DZCP.showInfo(\''._online_head.'\', \''.$kats.'\', \''.$text.'\')" onmouseout="DZCP.hideInfo()"';
            }

            $counter = show("menu/counter", array("v_today" => $v_today,
                                                  "v_yesterday" => $yDay,
                                                  "v_all" => $getstats['allvisitors'],
                                                  "v_perday" => round($getstats['avgvisitors'], 2),
                                                  "v_max" => $getstats['maxvisitors'],
                                                  "g_online" => abs(online_guests($where)-$online_reg),
                                                  "u_online" => abs($online_reg),
                                                  "info" => $info,
                                                  "v_online" => $getstats['maxonline']));

            if(Cache::is_mem() && $menu_xml['xml'] && $menu_xml['config']['update'] != '0') //Only Memory Cache
                Cache::set('nav_counter',$counter,$menu_xml['config']['update']);
        }
        else
            $counter = Cache::get('nav_counter');

        return '<table class="navContent" cellspacing="0">'.$counter.'</table>';
    }
}