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

function slideshow()
{
    $qry = db("SELECT * FROM ".dba::get('slideshow')." ORDER BY `pos` ASC LIMIT 4");
    if(_rows($qry) >= 1)
    {
        $pic = ''; $tabs = '';
        while($get = _fetch($qry))
        {
            if(empty($get['desc']) && !$get['showbez'])
                $slideroverlay = '';
            else if(!empty($get['desc']) && !$get['showbez'])
                $slideroverlay = '<div class="slideroverlay"><span>'.bbcode::parse_html(wrap(string::decode($get['desc']))).'</span></div>';
            else
                $slideroverlay = '<div class="slideroverlay"><h2>'.bbcode::parse_html(wrap(string::decode($get['bez']))).'</h2><span>'.bbcode::parse_html(wrap(string::decode($get['desc']))).'</span></div>';

            $target = ($get['target'] == "1" ? ",1" : "");
            $pic .= show("menu/slideshowbild", array("image" => "<img src=\"../inc/images/uploads/slideshow/".$get['id'].".jpg\" alt=\"\" />", "link" => "'".$get['url']."'".$target, "text" => $slideroverlay));
            $tabs .= '<a href="#" class="slidertabs" id="slider'.$get['id'].'"></a>';
        }

        return show("menu/slideshow", array("pic" => $pic, "tabs" => $tabs));
    }

    return '';
}