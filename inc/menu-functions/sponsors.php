<?php
function sponsors()
{
    $menu_xml = get_menu_xml('rotationsbanner');
    if(!Cache::is_mem() || !$menu_xml['xml'] || Cache::check('nav_sponsors'))
    {
        $sponsors = '';
        $qry = db("SELECT * FROM ".dba::get('sponsoren')." WHERE box = 1 ORDER BY pos");
        if(_rows($qry))
        {
            while($get = _fetch($qry))
            {
                $banner = show(_sponsors_bannerlink, array("id" => $get['id'], "title" => htmlspecialchars(str_replace('http://', '', re($get['link']))), "banner" => (empty($get['xlink']) ? "../banner/sponsors/box_".$get['id'].".".$get['xend'] : re($get['xlink']))));
                $sponsors .= show("menu/sponsors", array("banner" => $banner));
            }

            if(Cache::is_mem() && $menu_xml['xml'] && $menu_xml['config']['update'] != '0') //Only Memory Cache
                Cache::set('nav_sponsors',$sponsors,$menu_xml['config']['update']);
        }
    }
    else
        $sponsors = Cache::get('nav_sponsors');

    return empty($sponsors) ? '' : '<table class="navContent" cellspacing="0">'.$sponsors.'</table>';
}