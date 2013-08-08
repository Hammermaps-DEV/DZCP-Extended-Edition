<?php
/**
 * <DZCP-Extended Edition>
 * @package: DZCP-Extended Edition
 * @author: DZCP Developer Team || Hammermaps.de Developer Team
 * @link: http://www.dzcp.de || http://www.hammermaps.de
 */

function ftopics()
{
    global $allowHover;
    $menu_xml = get_menu_xml('ftopics');
    $cache_tag = 'nav_ftopics_uid'.userid();
    if(!Cache::is_mem() || !$menu_xml['xml'] || Cache::check($cache_tag))
    {
        $ftopicsconfig = config(array('m_ftopics','l_ftopics','m_fposts')); $f = 0; $ftopics = '';
        $qry = db("SELECT s1.*,s2.id AS subid FROM ".dba::get('f_threads')." s1, ".dba::get('f_skats')." s2, ".dba::get('f_kats')." s3
                   WHERE s1.kid = s2.id AND s2.sid = s3.id ORDER BY s1.lp DESC LIMIT 100");

        if(_rows($qry))
        {
            while($get = _fetch($qry))
            {
                if($f == $ftopicsconfig['m_ftopics'])
                    break;

                if(fintern($get['kid']))
                {
                    $lp = cnt(dba::get('f_posts'), " WHERE sid = '".$get['id']."'");
                    $pagenr = ceil($lp/$ftopicsconfig['m_fposts']);
                    $page = (!$pagenr ? 1 : $pagenr);
                    $info = ($allowHover == 1 ? 'onmouseover="DZCP.showInfo(\''.jsconvert(string::decode($get['topic'])).'\', \''._forum_posts.';'._forum_lpost.'\', \''.$lp.';'.date("d.m.Y H:i", $get['lp'])._uhr.'\')" onmouseout="DZCP.hideInfo()"' : '');
                    $ftopics .= show("menu/forum_topics", array("id" => $get['id'], "pagenr" => $page, "p" => $lp + 1, "titel" => cut(string::decode($get['topic']),$ftopicsconfig['l_ftopics']), "info" => $info, "kid" => $get['kid']));
                    $f++;
                }
            }

            if(Cache::is_mem() && $menu_xml['xml'] && $menu_xml['config']['update'] != '0') //Only Memory Cache
                Cache::set($cache_tag,$ftopics,$menu_xml['config']['update']);
        }
    }
    else
        $ftopics = Cache::get($cache_tag);

    return empty($ftopics) ? '' : '<table class="navContent" cellspacing="0">'.$ftopics.'</table>';
}