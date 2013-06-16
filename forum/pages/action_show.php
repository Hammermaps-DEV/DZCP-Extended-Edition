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
    global $userid;

    if($userid != 0 && !empty($userid))
    {
        $get = db("SELECT lastvisit FROM ".dba::get('userstats')." WHERE user = '".convert::ToInt($userid)."'",false,true);
        if($datum >= $get['lastvisit'] || $datum2 >= $get['lastvisit'])
        { if(empty($new)) return _newicon; }
    }

    return '';
}

if (_version < '1.0') //Mindest Version pruefen
    $index = _version_for_page_outofdate;
else
{
    $check = db("SELECT s2.id,s1.intern FROM ".dba::get('f_kats')." AS s1
               LEFT JOIN ".dba::get('f_skats')." AS s2
               ON s2.sid = s1.id
               WHERE s2.id = '".convert::ToInt($_GET['id'])."'");
    $checks = _fetch($check);

    if($checks['intern'] == 1 && (!permission("intforum") && !fintern($checks['id'])))
    {
        $index = error(_error_no_access);
    } else {
        if(isset($_GET['page']))  $page = $_GET['page'];
        else $page = 1;

        if(empty($_POST['suche']))
        {
            $qry = db("SELECT * FROM ".dba::get('f_threads')."
                 WHERE kid ='".convert::ToInt($_GET['id'])."'
                 OR global = 1
                 ORDER BY global DESC, sticky DESC, lp DESC, t_date DESC
                 LIMIT ".(($page - 1)*$maxfthreads=config('m_fthreads')).",".$maxfthreads."");
        } else {
            $qry = db("SELECT s1.global,s1.topic,s1.subtopic,s1.t_text,s1.t_email,s1.hits,s1.t_reg,s1.t_date,s1.closed,s1.sticky,s1.id
                 FROM ".dba::get('f_threads')." AS s1
                 WHERE s1.topic LIKE '%".$_POST['suche']."%'
                 AND s1.kid = '".convert::ToInt($_GET['id'])."'
                 OR s1.subtopic LIKE '%".$_POST['suche']."%'
                 AND s1.kid = '".convert::ToInt($_GET['id'])."'
                 OR s1.t_text LIKE '%".$_POST['suche']."%'
                 AND s1.kid = '".convert::ToInt($_GET['id'])."'
                 ORDER BY s1.global DESC, s1.sticky DESC, s1.lp DESC, s1.t_date DESC
                 LIMIT ".($page - 1)*($maxfthreads=config('m_fthreads')).",".$maxfthreads."");
        }

        $entrys = cnt(dba::get('f_threads'), " WHERE kid = ".convert::ToInt($_GET['id']));
        $i = 2;

        while($get = _fetch($qry))
        {
            if($get['sticky'] == "1") $sticky = _forum_sticky;
            else $sticky = "";

            if($get['global'] == "1") $global = _forum_global;
            else $global = "";

            if($get['closed'] == "1") $closed = show("page/button_closed");
            else $closed = "";

            $cntpage = cnt(dba::get('f_posts'), " WHERE sid = ".$get['id']);

            if($cntpage == "0") $pagenr = "1";
            else $pagenr = ceil($cntpage/$maxfposts);

            if(empty($_POST['suche']))
            {
                $qrys = db("SELECT id FROM ".dba::get('f_skats')."
                    WHERE id = '".convert::ToInt($_GET['id'])."'");
                $gets = _fetch($qrys);

                $threadlink = show(_forum_thread_link, array("topic" => re(cut($get['topic'],config('l_forumtopic'))),
                        "id" => $get['id'],
                        "kid" => $gets['id'],
                        "sticky" => $sticky,
                        "global" => $global,
                        "closed" => $closed,
                        "lpid" => $cntpage+1,
                        "page" => $pagenr));
            } else {
                $threadlink = show(_forum_thread_search_link, array("topic" => re(cut($get['topic'],config('l_forumtopic'))),
                        "id" => $get['id'],
                        "sticky" => $sticky,
                        "hl" => $_POST['suche'],
                        "closed" => $closed,
                        "lpid" => $cntpage+1,
                        "page" => $pagenr));
            }

            $qrylp = db("SELECT date,nick,reg,email FROM ".dba::get('f_posts')."
                   WHERE sid = '".$get['id']."'
                   ORDER BY date DESC");
            if(_rows($qrylp))
            {
                $getlp = _fetch($qrylp);
                $lpost = show(_forum_thread_lpost, array("nick" => autor($getlp['reg'], '', $getlp['nick'], $getlp['email']),
                        "date" => date("d.m.y H:i", $getlp['date'])._uhr));
                $lpdate = $getlp['date'];
            } else {
                $lpost = "-";
                $lpdate = "";
            }

            $class = ($color % 2) ? "contentMainSecond" : "contentMainFirst"; $color++;
            $threads .= show($dir."/forum_show_threads", array("new" => check_new_old($get['lp']),
                    "topic" => $threadlink,
                    "subtopic" => re(cut($get['subtopic'],config('l_forumsubtopic'))),
                    "hits" => $get['hits'],
                    "replys" => cnt(dba::get('f_posts'), " WHERE sid = '".$get['id']."'"),
                    "class" => $class,
                    "lpost" => $lpost,
                    "autor" => autor($get['t_reg'], '', $get['t_nick'], $get['t_email'])));
            $i--;
        }

        $qrys = db("SELECT id,kattopic FROM ".dba::get('f_skats')."
                WHERE id = '".convert::ToInt($_GET['id'])."'");
        $gets = _fetch($qrys);

        $search = show($dir."/forum_skat_search", array("head_search" => _forum_head_skat_search,
                "id" => $_GET['id'],
                "suchwort" => re($_POST['suche'])));
        $nav = nav($entrys,$maxfthreads,"?action=show&amp;id=".$_GET['id']."");

        if(!empty($_POST['suche']))
        {
            $what = show($dir."/search", array("head" => _forum_search_head,
                    "thread" => _forum_thread,
                    "autor" => _autor,
                    "lpost" => _forum_lpost,
                    "hits" => _hits,
                    "replys" => _forum_replys,
                    "threads" => $threads,
                    "nav" => $nav));
        } else {
            $new = show(_forum_new_thread, array("id" => $_GET['id']));
            $what = show($dir."/forum_show_thread", array("head_threads" => _forum_head_threads,
                    "thread" => _forum_thread,
                    "autor" => _autor,
                    "lpost" => _forum_lpost,
                    "hits" => _hits,
                    "replys" => _forum_replys,
                    "nav" => $nav,
                    "threads" => $threads,
                    "new" => $new,));
        }

        $qrysub = db("SELECT sid FROM ".dba::get('f_skats')."
                  WHERE id = '".convert::ToInt($_GET['id'])."'");
        $subkat = _fetch($qrysub);

        $qryk = db("SELECT name FROM ".dba::get('f_kats')."
                WHERE id = '".$subkat['sid']."'");
        $kat = _fetch($qryk);

        $wheres = show(_forum_subkat_where, array("where" => re($gets['kattopic']),
                "id" => $gets['id']));

        $index = show($dir."/forum_show", array("head" => _forum_head,
                "where" => $wheres,
                "mainkat" => re($kat['name']),
                "what" => $what,
                "search" => $search));
    }
}