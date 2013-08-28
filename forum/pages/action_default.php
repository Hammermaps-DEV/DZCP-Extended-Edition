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
    $qry = db("SELECT * FROM ".dba::get('f_kats')." ORDER BY kid"); $show = '';
    while($get = _fetch($qry))
    {
        $show_kats = ''; $lpost = ''; $color = 1; $lpost = '';
        $qrys = db("SELECT * FROM ".dba::get('f_skats')." WHERE sid = '".$get['id']."' ORDER BY pos");
        while($gets = _fetch($qrys))
        {
            if(!$get['intern'] || ($get['intern'] && fintern($gets['id'])))
            {
                unset($lpost);
                $getlt = db("SELECT t_date,t_nick,t_email,t_reg,lp,first,topic
                     FROM ".dba::get('f_threads')."
                     WHERE kid = '".$gets['id']."'
                     ORDER BY lp DESC",false,true);

                $getlp = db("SELECT s1.date,s1.nick,s1.reg,s1.email,s2.t_date,s2.lp,s2.first
                     FROM ".dba::get('f_posts')." AS s1
                     LEFT JOIN ".dba::get('f_threads')." AS s2
                     ON s2.lp = s1.date
                     WHERE s2.kid = '".$gets['id']."'
                     ORDER BY s1.date DESC",false,true);

                $lpost = "-"; $lpdate = "";
                if($getlt['first'] == "1")
                {
                    $lpost .= show(_forum_thread_lpost, array("nick" => autor($getlt['t_reg'], '', $getlt['t_nick'], $getlt['t_email']), "date" => date("d.m.y H:i", $getlt['t_date'])._uhr));
                    $lpdate = $getlt['t_date'];
                }
                else if($getlt['first'] == "0")
                {
                    $lpost .= show(_forum_thread_lpost, array("nick" => autor($getlp['reg'], '', $getlp['nick'], $getlp['email']), "date" => date("d.m.y H:i", $getlp['date'])._uhr));
                    $lpdate = $getlp['date'];
                }

                $threads = cnt(dba::get('f_threads'), " WHERE kid = '".$gets['id']."'");
                $posts = cnt(dba::get('f_posts'), " WHERE kid = '".$gets['id']."'");
                $class = ($color % 2) ? "contentMainSecond" : "contentMainFirst"; $color++;
                $show_kats .= show($dir."/kats_show", array("topic" => string::decode($gets['kattopic']),
                        "subtopic" => string::decode($gets['subtopic']),
                        "lpost" => $lpost,
                        "new" => (check_is_new($lpdate) ? _newicon : ''),
                        "threads" => $threads,
                        "posts" => $posts+$threads,
                        "class" => $class,
                        "kid" => $gets['sid'],
                        "id" => $gets['id']));
            }
        }

        if(!empty($show_kats))
        {
            $katname = ($get['intern'] ? show(_forum_katname_intern, array("katname" => string::decode($get['name']))) : string::decode($get['name']));
            $show .= show($dir."/kats", array("katname" => $katname, "showt" => $show_kats));
        }
    }

    $threads = show(_forum_cnt_threads, array("threads" => cnt(dba::get('f_threads'))));
    $posts = show(_forum_cnt_posts, array("posts" => cnt(dba::get('f_posts'))+cnt(dba::get('f_threads'))));
    $qrytp = db("SELECT id,user,forumposts FROM ".dba::get('userstats')." ORDER BY forumposts DESC, id LIMIT 5");

    //Top 5 Poster
    $show_top = ''; $color = 1;
    while($gettp = _fetch($qrytp))
    {
        $class = ($color % 2) ? "contentMainSecond" : "contentMainFirst"; $color++;
        $show_top .= show($dir."/top_posts_show", array("nick" => autor($gettp['user']), "posts" => $gettp['forumposts'], "class" => $class));
    }

    $top_posts = show($dir."/top_posts", array("show" => $show_top));

    //TODO: Versteckt
    //Im Forum online
    $qryo = db("SELECT id FROM ".dba::get('users')." WHERE whereami = 'Forum' AND time+'".users_online."'>'".time()."' AND online = '1' AND id != '".userid()."'");
    if(_rows($qryo))
    {
        $check = 1; $nick = ''; $i=0;
        $cnto = cnt(dba::get('users'), " WHERE time+'".users_online."'>'".time()."' AND whereami = 'Forum' AND online = '1' AND id != '".userid()."'");
        while($geto = _fetch($qryo))
        {
            if($i == 5)
            { $end = "<br />"; $i=0; }
            else
                $end = ($cnto == $check ? '' : ', ');

            $nick .= autor($geto['id']).$end;

            $i++;
            $check++;
        }
    }
    else
        $nick = (checkme() == "unlogged" ? "<center>"._forum_nobody_is_online."</center>" : "<center>"._forum_nobody_is_online2."</center>");

    $online = show($dir."/online", array("nick" => $nick));
    $index = show($dir."/forum", array("threads" => $threads, "posts" => $posts, "show" => $show, "online" => $online, "top_posts" => $top_posts));
}