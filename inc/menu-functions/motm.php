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

function motm()
{
    global $allowHover;
    $menu_xml = get_menu_xml('motm');
    if(!Cache::is_mem() || !$menu_xml['xml'] || Cache::check('nav_motm'))
    {
        if(!$userpics=get_files(basePath.'/inc/images/uploads/userpics/',false,true))
            return '';

        $qry = db("SELECT id FROM ".dba::get('users')." WHERE level >= 2");
        while($rs = _fetch($qry))
        {
            foreach($userpics AS $userpic)
            {
                $tmpId = convert::ToInt($userpic);
                if($tmpId == $rs['id'])
                {
                    $temparr[] = $rs['id'];
                    break;
                }
            }
        }

        $arrayID = rand(0, count($temparr) - 1);
        $uid = $temparr[$arrayID]; $member = '';

        $sql = db("SELECT id,status,level FROM ".dba::get('users')." WHERE id = '".$uid."'");
        if(!_rows($sql) && !empty($temparr))
        {
            $get=_fetch($sql);
            $status = ($get['status'] == 1 || $get['level'] == 1) ? "aktiv" : "inaktiv";
            $info = ($allowHover ? 'onmouseover="DZCP.showInfo(\''.fabo_autor($get['id']).'\', \''._posi.';'._status.';'._age.'\', \''.getrank($get['id']).';'.$status.';'.getAge($get['bday']).'\', \''.hoveruserpic($get['id']).'\')" onmouseout="DZCP.hideInfo()"' : '');
            $member = show("menu/motm", array("uid" => $get['id'], "upic" => userpic($get['id'], 130, 161), "info" => $info));

            if(Cache::is_mem() && $menu_xml['xml'] && $menu_xml['config']['update'] != '0') //Only Memory Cache
                Cache::set('nav_motm',$member,$menu_xml['config']['update']);
        }
    }
    else
        $member = Cache::get('nav_motm');

    return empty($member) ? '' : '<table class="navContent" cellspacing="0">'.$member.'</table>';
}