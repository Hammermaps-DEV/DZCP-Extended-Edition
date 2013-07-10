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

function top_dl()
{
    global $allowHover;

    $menu_xml = get_menu_xml('top_dl');
    if(!Cache::is_mem() || !$menu_xml['xml'] || Cache::check('nav_top_dl'))
    {
        $top_dl = '';
        $qry = db("SELECT id,download,hits,date,kat FROM ".dba::get('downloads')." ORDER BY hits DESC LIMIT ".config('m_topdl')."");
        if(_rows($qry))
        {
            while($get = _fetch($qry))
            {
                if($allowHover == 1)
                {
                    $getkat = db("SELECT name FROM ".dba::get('dl_kat')." WHERE id = '".$get['kat']."'",false,true);
                    $info = 'onmouseover="DZCP.showInfo(\''.jsconvert(string::decode($get['download'])).'\', \''._datum.';'._dl_dlkat.';'._hits.'\', \''.date("d.m.Y H:i", $get['date'])._uhr.';'.jsconvert(string::decode($getkat['name'])).';'.$get['hits'].'\')" onmouseout="DZCP.hideInfo()"';
                }

                $top_dl .= show("menu/top_dl", array("id" => $get['id'], "titel" => cut(string::decode($get['download']),config('l_topdl')), "info" => $info, "hits" => $get['hits']));
            }

            if(Cache::is_mem() && $menu_xml['xml'] && $menu_xml['config']['update'] != '0') //Only Memory Cache
                Cache::set('nav_top_dl',$top_dl,$menu_xml['config']['update']);
        }
    }
    else
        $top_dl = Cache::get('nav_top_dl');

    return empty($top_dl) ? '' : '<table class="navContent" cellspacing="0">'.$top_dl.'</table>';
}