<?php
function navi($kat)
{
    global $chkMe,$userid,$designpath;

    $navi=''; $kats_qry = db("SELECT `level` FROM ".dba::get('navi_kats')." WHERE `placeholder` = '".up($kat)."'");
    if(_rows($kats_qry))
    {
        $k = _fetch($kats_qry);
        $intern = ($chkMe >= 2) ? '' : " AND s1.`internal` = '0'";
        $permissions = ($kat == 'nav_admin' && admin_perms(convert::ToInt($userid))) ? "" : $intern." AND ".convert::ToInt($chkMe)." >= '".convert::ToInt($k['level'])."'";

        // Extended permissions
        $extended_perms = '';
        if($chkMe <= 3 && $chkMe != 5)
        {
            $qry = db("SELECT extended_perm FROM `".dba::get('navi')."` WHERE `extended_perm` IS NOT NULL AND `kat` = '".up($kat)."'");
            if(_rows($qry) >= 1)
            while($get = _fetch($qry)) { if(!empty($get['extended_perm'])) $extended_perms .= permission($get['extended_perm']) ? " OR s1.`extended_perm` = '".$get['extended_perm']."'" : ""; }
        }

        $qry = db("SELECT s1.* FROM ".dba::get('navi')." AS s1 LEFT JOIN ".dba::get('navi_kats')." AS s2 ON s1.kat = s2.placeholder WHERE s1.kat = '".up($kat)."' AND s1.`shown` = '1' ".$permissions."".$extended_perms." ORDER BY s1.pos");
        if(_rows($qry) >= 1)
        {
              while($get = _fetch($qry))
              {
                if($get['type'] == 1 || $get['type'] == 2 || $get['type'] == 3)
                {
                    $name = ($get['wichtig'] == 1) ? '<span class="fontWichtig">'.navi_name(re($get['name'])).'</span>' : navi_name(re($get['name']));
                    $target = ($get['target'] == 1) ? '_blank' : '_self';

                    if(file_exists($designpath.'/menu/'.$get['kat'].'.html'))
                        $link = show("menu/".$get['kat']."", array("target" => $target, "href" => re($get['url']), "title" => strip_tags($name), "css" => ucfirst(str_replace('nav_', '', re($get['kat']))), "link" => $name));
                    else
                        $link = show("menu/nav_link", array("target" => $target, "href" => re($get['url']), "title" => strip_tags($name), "css" => ucfirst(str_replace('nav_', '', re($get['kat']))), "link" => $name));

                    $table = strstr($link, '<tr>') ? true : false;
                    $navi .= $link;
                }
            }
        }
    }

    return empty($navi) ? '' : ($table ? '<table class="navContent" cellspacing="0">'.$navi.'</table>' : $navi);
}