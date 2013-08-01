<?php
/**
 * <DZCP-Extended Edition>
 * @package: DZCP-Extended Edition
 * @author: DZCP Developer Team || Hammermaps.de Developer Team
 * @link: http://www.dzcp.de || http://www.hammermaps.de
 */

if (!defined('IS_DZCP')) exit();
if (_version < '1.0')
    $index = _version_for_page_outofdate;
else
{
    $where = _site_shoutbox;
    $title = $pagetitle." - ".$where."";
    $page = (isset($_GET['page']) ? convert::ToInt($_GET['page']) : 1);
    $entrys = cnt(dba::get('shout'));
    $i = $entrys-($page - 1)*($maxshoutarchiv=config('maxshoutarchiv'));

    $show = ''; $color = 1;
    $qry = db("SELECT * FROM ".dba::get('shout')." ORDER BY datum DESC LIMIT ".($page - 1)*$maxshoutarchiv.",".$maxshoutarchiv."");
    while($get = _fetch($qry))
    {
        $is_num = preg_match("#\d#", $get['email']);
        if($is_num && !check_email($get['email']))
            $nick = autor($get['email']);
        else
            $nick = '<a href="mailto:'.$get['email'].'" title="'.$get['nick'].'">'.cut($get['nick'], config('l_shoutnick')).'</a>';

        $class = ($color % 2) ? "contentMainTop" : "contentMainFirst"; $color++;
        $del = (permission("shoutbox") ? "<a href='../shout/?action=admin&amp;do=delete&amp;id=".$get['id']."'><img src='../inc/images/delete_small.gif' border='0' alt=''></a>" : "");
        $posted_ip = (checkme() == 4 ? $get['ip'] : _logged);

        $show .= show($dir."/shout_part", array("nick" => $nick,
                "datum" => date("j.m.Y H:i", $get['datum'])._uhr,
                "text" => bbcode::parse_html($get['text']),
                "class" => $class,
                "del" => $del,
                "ip" => $posted_ip,
                "id" => $i,
                "email" => string::decode($get['email'])));
        $i--;
    }

    $nav = nav($entrys,$maxshoutarchiv,"?action=archiv");
    $index = show($dir."/shout", array("shout_part" => $show, "head" => _shout_archiv_head, "nav" => $nav));
}