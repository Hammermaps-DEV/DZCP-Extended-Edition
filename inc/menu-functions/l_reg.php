<?php
/**
 * <DZCP-Extended Edition>
 * @package: DZCP-Extended Edition
 * @author: DZCP Developer Team || Hammermaps.de Developer Team
 * @link: http://www.dzcp.de || http://www.hammermaps.de
 */

function l_reg()
{
    $menu_xml = get_menu_xml('l_reg');
    if(!Cache::is_mem() || !$menu_xml['xml'] || Cache::check('nav_l_reg'))
    {
        $lregconfig = settings(array('m_lreg','l_lreg')); $lreg = '';
        $qry = db("SELECT id,nick,country,regdatum FROM ".dba::get('users')." ORDER BY regdatum DESC LIMIT ".$lregconfig['m_lreg']."");

        if(_rows($qry))
        {
            while($get = _fetch($qry))
            { $lreg .= show("menu/last_reg", array("nick" => string::decode(cut($get['nick'], $lregconfig['l_lreg'])), "country" => flag($get['country']), "reg" => date("d.m.", $get['regdatum']), "id" => $get['id'])); }

            if(Cache::is_mem() && $menu_xml['xml'] && $menu_xml['config']['update'] != '0') //Only Memory Cache
                Cache::set('nav_l_reg',$lreg,$menu_xml['config']['update']);
        }
    }
    else
        $lreg = Cache::get('nav_l_reg');

    return empty($lreg) ? '' : '<table class="navContent" cellspacing="0">'.$lreg.'</table>';
}