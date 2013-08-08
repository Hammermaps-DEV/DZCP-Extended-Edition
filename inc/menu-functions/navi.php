<?php
/**
 * <DZCP-Extended Edition>
 * @package: DZCP-Extended Edition
 * @author: DZCP Developer Team || Hammermaps.de Developer Team
 * @link: http://www.dzcp.de || http://www.hammermaps.de
 */

function navi($kat)
{
    global $designpath;
    $navi=''; $kats_qry = db("SELECT `level` FROM ".dba::get('navi_kats')." WHERE `placeholder` = '".$kat."'");
    if(_rows($kats_qry))
    {
        $k = _fetch($kats_qry);
        $intern = (checkme() >= 2) ? '' : " AND s1.`internal` = '0'";
        $permissions = ($kat == 'nav_admin' && admin_perms(userid())) ? "" : $intern." AND ".convert::ToInt(checkme())." >= '".convert::ToInt($k['level'])."'";

        // Extended permissions
        $extended_perms = '';
        if(checkme() <= 3 && checkme() != 5)
        {
            $qry = db("SELECT extended_perm FROM `".dba::get('navi')."` WHERE `extended_perm` IS NOT NULL AND `kat` = '".$kat."'");
            if(_rows($qry) >= 1)
            while($get = _fetch($qry)) { if(!empty($get['extended_perm'])) $extended_perms .= permission($get['extended_perm']) ? " OR s1.`extended_perm` = '".$get['extended_perm']."'" : ""; }
        }

        $qry = db("SELECT s1.* FROM ".dba::get('navi')." AS s1 LEFT JOIN ".dba::get('navi_kats')." AS s2 ON s1.kat = s2.placeholder WHERE s1.kat = '".$kat."' AND s1.`shown` = '1' ".$permissions."".$extended_perms." ORDER BY s1.pos");
        if(_rows($qry) >= 1)
        {
              while($get = _fetch($qry))
              {
                if($get['type'] == 1 || $get['type'] == 2 || $get['type'] == 3)
                {
                    $name = ($get['wichtig'] == 1) ? '<span class="fontWichtig">'.navi_name(string::decode($get['name'])).'</span>' : navi_name(string::decode($get['name']));
                    $title = (!empty($get['title'])) ? navi_name(string::decode($get['title'])) : navi_name(string::decode($get['name']));
                    $target = ($get['target'] == 1) ? '_blank' : '_self';

                    if(file_exists($designpath.'/menu/'.$get['kat'].'.html'))
                        $link = show("menu/".$get['kat']."", array("target" => $target, "href" => preg_replace('"( |^)(www.[-a-zA-Z0-9@:%_\+.~#?&//=]+)"i', 'http://\2', string::decode($get['url'])), "title" => strip_tags($title), "css" => ucfirst(str_replace('nav_', '', string::decode($get['kat']))), "link" => $name));
                    else
                        $link = show("menu/nav_link", array("target" => $target, "href" => preg_replace('"( |^)(www.[-a-zA-Z0-9@:%_\+.~#?&//=]+)"i', 'http://\2', string::decode($get['url'])), "title" => strip_tags($title), "css" => ucfirst(str_replace('nav_', '', string::decode($get['kat']))), "link" => $name));

                    $table = strstr($link, '<tr>') ? true : false;
                    $navi .= $link;
                }
            }
        }
    }

    return empty($navi) ? '' : ($table ? '<table class="navContent" cellspacing="0">'.$navi.'</table>' : $navi);
}