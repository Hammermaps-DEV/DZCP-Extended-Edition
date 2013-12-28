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
        $ftopicsconfig = settings(array('m_ftopics','l_ftopics','m_fposts')); $f = 0; $ftopics = '';
        $qry = db("SELECT s1.*,s2.id AS subid, s3.name AS e_kat, s2.kattopic AS e_subkat FROM ".dba::get('f_threads')." s1, ".dba::get('f_skats')." s2, ".dba::get('f_kats')." s3
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
                    $f_posts = db('SELECT reg,text,nick FROM '.dba::get('f_posts').' WHERE kid = '.$get['kid'].' AND sid = '.$get['id'].' ORDER BY date DESC LIMIT 1');
                    if(_rows($f_posts) >= 1) $get_info = _fetch($f_posts); else $get_info['reg'] = $get['t_reg'];
                    $autor = !$get_info['reg'] ? $get_info['nick'] : rawautor($get_info['reg']);
                    $info = ($allowHover == 1 ? 'onmouseover="DZCP.showInfo(\'<tr><td colspan=2 align=center padding=3 class=infoTop>' .
                            jsconvert(string::decode($get['topic'])).'</td></tr><tr><td><b>'._forum_posts.':</b></td><td>'.$lp.'</td></tr><tr><td><b>'._forum_lpost . ':</b></td><td>'.
                            date("d.m.Y H:i", $get['lp']) . _uhr.'</td></tr><tr><td><b>'._ftopics_autor.':</b></td><td>'.jsconvert(string::decode($autor)) . '</td></tr><tr><td><b>'._ftopics_kat.':</b></td><td>'.
                            jsconvert(string::decode($get['e_kat']).' / '.string::decode($get['e_subkat'])).'</td></tr>\')" onmouseout="DZCP.hideInfo()"' : '');

                    $ftopics .= show("menu/forum_topics", array("id" => $get['id'], "pagenr" => $page, "p" => $lp + 1, "titel" => cut(string::decode($get['topic']),$ftopicsconfig['l_ftopics']), "info" => $info, "date" => date("d.m H:i", $get['lp']), "kid" => $get['kid']));
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