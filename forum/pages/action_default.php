<?php
/**
 * <DZCP-Extended Edition>
 * @package: DZCP-Extended Edition
 * @author: DZCP Developer Team || Hammermaps.de Developer Team
 * @link: http://www.dzcp.de || http://www.hammermaps.de
 */

####################################
## Wird in einer Index ausgeführt ##
####################################
if (!defined('IS_DZCP'))
    exit();

//-> Checkt ob ein Ereignis neu ist
# DEPRECATED #
function check_new_old($datum, $new = "", $datum2 = "") //Out of date!
{
    if(userid() != 0)
    {
        $get = db("SELECT lastvisit FROM ".dba::get('userstats')." WHERE user = '".userid()."'",false,true);
        if($datum >= $get['lastvisit'] || $datum2 >= $get['lastvisit'])
        { if(empty($new)) return _newicon; }
    }

    return '';
}

if (_version < '1.0') //Mindest Version pruefen
    $index = _version_for_page_outofdate;
else
{
    $qry = db("SELECT * FROM ".dba::get('f_kats')." ORDER BY kid"); $show = '';
    while($get = _fetch($qry))
    {
        $showt = ''; $lpost = ''; $color = 1; $lpost = '';
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

                if(cnt(dba::get('f_threads'), " WHERE kid = '".$gets['id']."'") == "0")
                {
                    $lpost = "-";
                    $lpdate = "";
                }
                elseif($getlt['first'] == "1")
                {
                    $lpost .= show(_forum_thread_lpost, array("nick" => autor($getlt['t_reg'], '', $getlt['t_nick'], $getlt['t_email']), "date" => date("d.m.y H:i", $getlt['t_date'])._uhr));

                    $lpdate = $getlt['t_date'];
                }
                elseif($getlt['first'] == "0")
                {
                    $lpost .= show(_forum_thread_lpost, array("nick" => autor($getlp['reg'], '', $getlp['nick'], $getlp['email']),
                            "date" => date("d.m.y H:i", $getlp['date'])._uhr));
                    $lpdate = $getlp['date'];
                }

                $threads = cnt(dba::get('f_threads'), " WHERE kid = '".$gets['id']."'");
                $posts = cnt(dba::get('f_posts'), " WHERE kid = '".$gets['id']."'");
                $class = ($color % 2) ? "contentMainSecond" : "contentMainFirst"; $color++;
                $showt .= show($dir."/kats_show", array("topic" => string::decode($gets['kattopic']),
                        "subtopic" => string::decode($gets['subtopic']),
                        "lpost" => $lpost,
                        "new" => check_new_old($lpdate),
                        "threads" => $threads,
                        "posts" => $posts+$threads,
                        "class" => $class,
                        "kid" => $gets['sid'],
                        "id" => $gets['id']));
            }
        }

        if($get['intern'] == 1) $katname =  show(_forum_katname_intern, array("katname" => string::decode($get['name'])));
        else $katname = string::decode($get['name']);

        if(!empty($showt))
        {
            $show .= show($dir."/kats", array("katname" => $katname,
                    "topic" => _forum_topic,
                    "lpost" => _forum_lpost,
                    "threads" => _forum_threads,
                    "posts" => _forum_posts,
                    "showt" => $showt));
        }
    }

    $threads = show(_forum_cnt_threads, array("threads" => cnt(dba::get('f_threads'))));
    $posts = show(_forum_cnt_posts, array("posts" => cnt(dba::get('f_posts'))+cnt(dba::get('f_threads'))));
    $qrytp = db("SELECT id,user,forumposts FROM ".dba::get('userstats')." ORDER BY forumposts DESC, id LIMIT 5");

    $show_top = ''; $color = 1;
    while($gettp = _fetch($qrytp))
    {
        $class = ($color % 2) ? "contentMainSecond" : "contentMainFirst"; $color++;
        $show_top .= show($dir."/top_posts_show", array("nick" => autor($gettp['user']), "posts" => $gettp['forumposts'], "class" => $class));
    }

    $top_posts = show($dir."/top_posts", array("head" => _forum_top_posts,
            "show" => $show_top,
            "nick" => _nick,
            "posts" => _forum_posts));

    $qryo = db("SELECT id FROM ".dba::get('users')."
              WHERE whereami = 'Forum'
              AND time+'".users_online."'>'".time()."'
              AND id != '".userid()."'");
    if(_rows($qryo))
    {
        $check = 1; $nick = ''; $i=0;
        $cnto = cnt(dba::get('users'), " WHERE time+'".users_online."'>'".time()."' AND whereami = 'Forum' AND id != '".userid()."'");
        while($geto = _fetch($qryo))
        {
            if($i == 5)
            {
                $end = "<br />";
                $i=0;
            } else {
                if($cnto == $check) $end = "";
                else $end = ", ";
            }
            $nick .= autor($geto['id']).$end;

            $i++;
            $check++;
        }
    } else {
        if(checkme() == "unlogged") $nick = "<center>"._forum_nobody_is_online."</center>";
        else                        $nick = "<center>"._forum_nobody_is_online2."</center>";
    }

    $online = show($dir."/online", array("nick" => $nick,
            "head" => _forum_online_head));

    $index = show($dir."/forum", array("head" => _forum_head,
            "threads" => $threads,
            "search" => _forum_searchlink,
            "posts" => $posts,
            "show" => $show,
            "online" => $online,
            "top_posts" => $top_posts));
}