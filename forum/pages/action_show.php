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
    $checks = db_stmt("SELECT s2.id,s1.intern FROM ".dba::get('f_kats')." AS s1 LEFT JOIN ".dba::get('f_skats')." AS s2 ON s2.sid = s1.id WHERE s2.id = ?'",array('i',convert::ToInt(getArgs('id','0','get'))),false,true);
    if($checks['intern'] == 1 && (!permission("intforum") && !fintern($checks['id'])))
        $index = error(_error_no_access);
    else
    {
        if(empty($_POST['suche']))
        {
            $qry = db("SELECT * FROM ".dba::get('f_threads')."
                 WHERE kid ='".convert::ToInt($_GET['id'])."'
                 OR global = 1
                 ORDER BY global DESC, sticky DESC, lp DESC, t_date DESC
                 LIMIT ".(($page - 1)*$maxfthreads=settings('m_fthreads')).",".$maxfthreads."");
        }
        else
        {
            $qry = db("SELECT s1.global,s1.topic,s1.subtopic,s1.t_text,s1.t_email,s1.hits,s1.t_reg,s1.t_date,s1.closed,s1.sticky,s1.id
                 FROM ".dba::get('f_threads')." AS s1
                 WHERE s1.topic LIKE '%".$_POST['suche']."%'
                 AND s1.kid = '".convert::ToInt($_GET['id'])."'
                 OR s1.subtopic LIKE '%".$_POST['suche']."%'
                 AND s1.kid = '".convert::ToInt($_GET['id'])."'
                 OR s1.t_text LIKE '%".$_POST['suche']."%'
                 AND s1.kid = '".convert::ToInt($_GET['id'])."'
                 ORDER BY s1.global DESC, s1.sticky DESC, s1.lp DESC, s1.t_date DESC
                 LIMIT ".($page - 1)*($maxfthreads=settings('m_fthreads')).",".$maxfthreads."");
        }

        $entrys = cnt(dba::get('f_threads'), " WHERE kid = ".convert::ToInt($_GET['id']));
        $color = 0; $threads = ''; $i = 2;
        while($get = _fetch($qry))
        {
            $sticky = $get['sticky'] ? _forum_sticky : '';
            $global = $get['global'] ? _forum_global : '';
            $cntpage = cnt(dba::get('f_posts'), " WHERE sid = ".$get['id']);
            $pagenr = (!$cntpage ? '1' : ceil($cntpage/settings('m_fposts')));

            if(empty($_POST['suche']))
            {
                $gets = db_stmt("SELECT id FROM ".dba::get('f_skats')." WHERE id = ?",array('i',convert::ToInt(getArgs('id','0','get'))),false,true);
                $threadlink = show(_forum_thread_link, array("topic" => string::decode(cut($get['topic'],settings('l_forumtopic'))),
                        "id" => $get['id'],
                        "kid" => $gets['id'],
                        "sticky" => $sticky,
                        "global" => $global,
                        "lpid" => $cntpage+1,
                        "page" => $pagenr));
            }
            else
            {
                $threadlink = show(_forum_thread_search_link, array("topic" => string::decode(cut($get['topic'],settings('l_forumtopic'))),
                        "id" => $get['id'],
                        "sticky" => $sticky,
                        "hl" => $_POST['suche'],
                        "lpid" => $cntpage+1,
                        "page" => $pagenr));
            }

            $qrylp = db("SELECT date,nick,reg,email FROM ".dba::get('f_posts')." WHERE sid = '".$get['id']."' ORDER BY date DESC");
            if(_rows($qrylp))
            {
                $getlp = _fetch($qrylp);
                $lpost = show(_forum_thread_lpost, array("nick" => autor($getlp['reg'], '', $getlp['nick'], $getlp['email']), "date" => date("d.m.Y, H:i", $getlp['date'])));
                $lpdate = $getlp['date'];
            }
            else
            {
                $lpost = "-";
                $lpdate = "";
            }

            $beginner_autor = show(_forum_thread_beginner_autor, array("nick" => autor($get['t_reg'], '', $get['t_nick'], $get['t_email']), "date" => date("d.m.Y, H:i", $get['t_date'])));
            $class = ($color % 2) ? "contentMainSecond" : "contentMainFirst"; $color++;
            $threads .= show($dir."/forum_show_threads", array("new" => check_is_new($get['lp']) ? ($get['closed'] ? _newicon_lock : _newicon) : ($get['closed'] ? _readedicon_lock : _readedicon),
                                                               "topic" => $threadlink,
                                                               "subtopic" => string::decode(cut($get['subtopic'],settings('l_forumsubtopic'))),
                                                               "hits" => $get['hits'],
                                                               "posts" => cnt(dba::get('f_posts'), " WHERE sid = '".$get['id']."'"),
                                                               "class" => $class,
                                                               "lpost" => $lpost,
                                                               "autor" => $beginner_autor));
            $i--;
        }

        $gets = db("SELECT id,kattopic FROM ".dba::get('f_skats')." WHERE id = '".convert::ToInt($_GET['id'])."'",false,true);
        $search = show($dir."/forum_skat_search", array("head_search" => _forum_head_skat_search, "id" => $_GET['id'], "suchwort" => isset($_POST['suche']) ? $_POST['suche'] : ''));
        $nav = nav($entrys,$maxfthreads,"?index=forum&amp;action=show&amp;id=".$_GET['id']."");

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
        }
        else
        {
            $new = show(_forum_new_thread, array("id" => $_GET['id']));
            $what = show($dir."/forum_show_thread", array("nav" => $nav, "threads" => $threads, "new" => $new));
        }

        $subkat = db("SELECT sid FROM ".dba::get('f_skats')." WHERE id = '".convert::ToInt($_GET['id'])."'",false,true);
        $kat = db("SELECT name FROM ".dba::get('f_kats')." WHERE id = '".$subkat['sid']."'",false,true);
        $wheres = show(_forum_subkat_where, array("where" => string::decode($gets['kattopic']), "id" => $gets['id']));
        $index = show($dir."/forum_show", array("where" => $wheres, "mainkat" => string::decode($kat['name']), "what" => $what, "search" => $search));
    }
}