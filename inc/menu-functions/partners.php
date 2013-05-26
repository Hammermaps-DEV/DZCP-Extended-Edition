<?php
function partners()
{
    $menu_xml = get_menu_xml('partners');
    if(!Cache::is_mem() || !$menu_xml['xml'] || Cache::check('nav_partners'))
    {
        $partners = '';
        $qry = db("SELECT * FROM ".dba::get('partners')." ORDER BY `textlink` ASC, `id` DESC");

        if(_rows($qry))
        {
            while($get = _fetch($qry))
            {
                $partners .= $get['textlink'] ? show("menu/partners_textlink", array("link" => $get['link'], "name" => re($get['banner']))) : show("menu/partners", array("link" => re($get['link']), "title" => htmlspecialchars(str_replace('http://', '', re($get['link']))), "banner" => re($get['banner'])));
                $table = strstr($partners, '<tr>') ? true : false;
            }

            if(Cache::is_mem() && $menu_xml['xml'] && $menu_xml['config']['update'] != '0') //Only Memory Cache
                Cache::set('nav_partners',array('partners' => $partners, 'table' => $table),$menu_xml['config']['update']);
        }
    }
    else
    {
        $partners = Cache::get('nav_partners');
        $table = $partners['table'];
        $partners = $partners['partners'];
    }

    return empty($partners) ? '' : ($table ? '<table class="navContent" cellspacing="0">'.$partners.'</table>' : $partners);
}