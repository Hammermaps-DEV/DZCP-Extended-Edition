<?php
function uotm()
{
    global $picformat,$allowHover;
    $menu_xml = get_menu_xml('uotm');
    if(!Cache::is_mem() || !$menu_xml['xml'] || Cache::check('nav_uotm'))
    {
        $imgFiles = array(); $uotm = '';
        if(!$folder=get_files(basePath.'/inc/images/uploads/userpics',false,true,$picformat))
            return '';

        //Mische
        foreach($folder AS $file) array_push($imgFiles, $file);
        $userid = convert::ToInt($imgFiles[rand(0, count($imgFiles) - 1)]);
        $sql = db("SELECT id,nick,country,bday FROM ".dba::get('users')." WHERE id = '".convert::ToInt($userid)."'");
        if(_rows($sql))
        {
            $get=_fetch($sql);
            $info = ($allowHover ? 'onmouseover="DZCP.showInfo(\''.fabo_autor($get['id']).'\', \''._age.'\', \''.getAge($get['bday']).'\', \''.hoveruserpic($get['id']).'\')" onmouseout="DZCP.hideInfo()"' : '');
            $uotm = show("menu/uotm", array("uid" => convert::ToInt($userid), "upic" => userpic($get['id'], 130, 161), "info" => $info));

            if(Cache::is_mem() && $menu_xml['xml'] && $menu_xml['config']['update'] != '0') //Only Memory Cache
                Cache::set('nav_uotm',$uotm,$menu_xml['config']['update']);

        }
    }
    else
        $uotm = Cache::get('nav_uotm');

    return empty($uotm) ? '' : '<table class="navContent" cellspacing="0">'.$uotm.'</table>';
}