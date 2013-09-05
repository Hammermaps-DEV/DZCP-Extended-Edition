<?php
/**
 * <DZCP-Extended Edition>
 * @package: DZCP-Extended Edition
 * @author: DZCP Developer Team || Hammermaps.de Developer Team
 * @link: http://www.dzcp.de || http://www.hammermaps.de
 */

function top_match()
{
    global $allowHover,$picformat;

    $menu_xml = get_menu_xml('top_match');
    if(!Cache::is_mem() || !$menu_xml['xml'] || Cache::check('nav_top_match'))
    {
        $qry = db("SELECT s1.datum,s1.gegner,s1.id,s1.bericht,s1.xonx,s1.clantag,s1.punkte,s1.gpunkte,s1.squad_id,s2.icon,s2.name FROM ".dba::get('cw')." AS s1
        LEFT JOIN ".dba::get('squads')." AS s2 ON s1.squad_id = s2.id WHERE `top` = '1' ORDER BY RAND()");

        if($get = _fetch($qry))
        {
            $squad = '_defaultlogo.jpg'; $gegner = '_defaultlogo.jpg';
            foreach($picformat AS $end)
            {
                if(file_exists(basePath.'/inc/images/uploads/clanwars/'.$get['id'].'_logo.'.$end))
                    $gegner = $get['id'].'_logo.'.$end;

                if(file_exists(basePath.'/inc/images/uploads/squads/'.$get['squad_id'].'_logo.'.$end))
                    $squad = $get['squad_id'].'_logo.'.$end;
            }

        	$filetimesquad=filemtime(basePath.'/inc/images/uploads/squads/'.$squad);
        	$filetimegegner=filemtime(basePath.'/inc/images/uploads/squads/'.$gegner);
            if($allowHover == 1 || $allowHover == 2)
                $hover = 'onmouseover="DZCP.showInfo(\''.jsconvert(string::decode($get['name'])).' vs. '.jsconvert(string::decode($get['gegner'])).'\', \''._played_at.';'._cw_xonx.';'._result.';'._comments_head.'\', \''.date("d.m.Y H:i", $get['datum'])._uhr.';'.jsconvert(string::decode($get['xonx'])).';'.cw_result_nopic_nocolor($get['punkte'],$get['gpunkte']).';'.cnt(dba::get('cw_comments'), "WHERE cw = '".$get['id']."'").'\')" onmouseout="DZCP.hideInfo()"';

        	if(check_apache_modul('mod_rewrite')&&use_mod_rewrite)
        	{
        	$endungs = explode(".", $squad);
        	$endungs = strtolower($endungs[count($endungs)-1]);
        	$squad=str_replace('.'.$endungs,'',$squad);

        	$endungg = explode(".", $gegner);
        	$endungg = strtolower($endungg[count($endungg)-1]);
        	$gegner=str_replace('.'.$endungg,'',$gegner);

        	$topmatch = show("menu/top_match_rewrite", array("id" => $get['id'],
									        		"clantag" => string::decode(cut($get['clantag'],($llwars=settings('l_lwars')))),
									        		"team" => string::decode(cut($get['name'],$llwars)),
									        		//"game" => substr(strtoupper(str_replace('.'.string::decode($get['icon']), '', string::decode($get['icon']))), 0, 5), // unused
									        		"id" => $get['id'],
									        		"gegner" => $gegner,
									        		"times"=>$filetimesquad,
									        		"timeg"=>$filetimegegner,
									        		"endungs"=>$endungs,
									        		"endungg"=>$endungg,
									        		"squad" => $squad,
									        		"hover" => $hover,
									        		"info" => ($get['datum'] > time() ? date("d.m.Y", $get['datum']) : cw_result_nopic($get['punkte'],$get['gpunkte']))));
        	}
        	else
        	{
            $topmatch = show("menu/top_match", array("id" => $get['id'],
                                                     "clantag" => string::decode(cut($get['clantag'],($llwars=settings('l_lwars')))),
                                                     "team" => string::decode(cut($get['name'],$llwars)),
                                                     //"game" => substr(strtoupper(str_replace('.'.string::decode($get['icon']), '', string::decode($get['icon']))), 0, 5), // unused
                                                     "id" => $get['id'],
                                                     "gegner" => $gegner,
										            "times"=>$filetimesquad,
										            "timeg"=>$filetimegegner,
                                                     "squad" => $squad,
                                                     "hover" => $hover,
                                                     "info" => ($get['datum'] > time() ? date("d.m.Y", $get['datum']) : cw_result_nopic($get['punkte'],$get['gpunkte']))));

        	}

            if(Cache::is_mem() && $menu_xml['xml'] && $menu_xml['config']['update'] != '0') //Only Memory Cache
                Cache::set('nav_top_match',$topmatch,$menu_xml['config']['update']);
        }
    }
    else
        $topmatch = Cache::get('nav_top_match');

    return empty($topmatch) ? '<center style="margin:3px 0">'._no_top_match.'</center>' : '<table class="navContent" cellspacing="0">'.$topmatch.'</table>';
}