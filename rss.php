<?php
/**
 * <DZCP-Extended Edition>
 * @package: DZCP-Extended Edition
 * @author: DZCP Developer Team || Hammermaps.de Developer Team
 * @link: http://www.dzcp.de || http://www.hammermaps.de
 */

define('basePath', dirname(__FILE__));
require_once(basePath."/inc/debugger.php");
require_once(basePath."/inc/config.php");
require_once(basePath."/inc/common.php");

header("Content-Type: text/xml");
if(isset($_GET['key']) || !empty($_GET['key']))
{
    $qry = db("SELECT id,user FROM `".dba::get('users')."` WHERE `rss_key` = '".htmlentities($_GET['key'])."' LIMIT 1");
    if(!_rows($qry)) exit(); $user = _fetch($qry);
    $rss_uconf = db("SELECT * FROM `".dba::get('rss')."` WHERE `userid` = '".$user['id']."' LIMIT 1",false,true);

    if(Cache::check('private_news_rss_userid_'.$user['id']))
    {
        $host = $_SERVER['HTTP_HOST'];
        $pfad = dirname($_SERVER['PHP_SELF']);
        rss_feed::set_main_config('pagetitle',show(_rss_pagetitle,array('clanname' => $clanname, 'username' => string::decode($user['user']))));

        // Intern News
        if($rss_uconf['show_intern_news'])
        {
            $qry = db("SELECT * FROM ".dba::get('news')." WHERE intern = 1 AND public = 1 ORDER BY datum DESC LIMIT ".$rss_uconf['show_intern_news_max']);
            while($get = _fetch($qry))
            {
                $get_user = db("SELECT nick FROM ".dba::get('users')." WHERE id = '".$get['autor']."'",false,true);
                rss_feed::add_item(string::decode('News-Intern: '.$get['titel']),'http://'.$host.$pfad.'/news/?action=show&id='.$get['id'],string::decode($get['text']),string::decode($get_user['nick']),
                ($get['comments'] ? 'http://'.$host.$pfad.'/news/?action=show&id='.$get['id'] : ''),date("r", $get['datum']));
            }
        }

        // Public News
        if($rss_uconf['show_public_news'])
        {
            $qry = db("SELECT * FROM ".dba::get('news')." WHERE intern = 0 AND public = 1 ORDER BY datum DESC LIMIT ".$rss_uconf['show_public_news_max']);
            while($get = _fetch($qry))
            {
                $get_user = db("SELECT nick FROM ".dba::get('users')." WHERE id = '".$get['autor']."'",false,true);
                rss_feed::add_item('News: '.string::decode($get['titel']),'http://'.$host.$pfad.'/news/?action=show&id='.$get['id'],string::decode($get['text']),string::decode($get_user['nick']),
                ($get['comments'] ? 'http://'.$host.$pfad.'/news/?action=show&id='.$get['id'] : ''),date("r", $get['datum']));
            }
        }

        // Artikel
        if($rss_uconf['show_artikel'])
        {
            $qry = db("SELECT * FROM ".dba::get('artikel')." WHERE public = 1 ORDER BY datum DESC LIMIT ".$rss_uconf['show_artikel_max']);
            while($get = _fetch($qry))
            {
                $get_user = db("SELECT nick FROM ".dba::get('users')." WHERE id = '".$get['autor']."'",false,true);
                rss_feed::add_item('Artikel: '.string::decode($get['titel']),'http://'.$host.$pfad.'/artikel/?action=show&id='.$get['id'],string::decode($get['text']),string::decode($get_user['nick']),
                ($get['comments'] ? 'http://'.$host.$pfad.'/artikel/?action=show&id='.$get['id'] : ''),date("r", $get['datum']));
            }
        }

        // Downloads
        if($rss_uconf['show_downloads'])
        {
            $qry = db("SELECT * FROM ".dba::get('downloads')." ORDER BY date DESC LIMIT ".$rss_uconf['show_downloads_max']);
            while($get = _fetch($qry))
            {
                rss_feed::add_item('Downloads: '.string::decode($get['download']),'http://'.$host.$pfad.'/downloads/?action=download&id='.$get['id'],$get['beschreibung'],'',
                ($get['comments'] ? 'http://'.$host.$pfad.'/downloads/?action=download&id='.$get['id'] : ''),date("r", $get['date']));
            }
        }

        rss_feed::gen_rss();
        $rss = rss_feed::get_rss();
        Cache::set('private_news_rss_userid_'.$user['id'],$rss, rss_cache_private_news);
        exit($rss);
    }
    else
        exit(Cache::get('private_news_rss_userid_'.$user['id']));
}
else
{
    if(settings('news_feed'))
    {
        if(cache::check('public_news_rss'))
        {
            $host = $_SERVER['HTTP_HOST'];
            $pfad = preg_replace("#^(.*?)\/(.*?)#Uis","$1",dirname($_SERVER['PHP_SELF']));

            $qry = db("SELECT * FROM ".dba::get('news')." WHERE intern = 0 AND public = 1 ORDER BY datum DESC LIMIT 16");
            while($get = _fetch($qry))
            {
                $get_user = db("SELECT nick FROM ".dba::get('users')." WHERE id = '".$get['autor']."'",false,true);
                rss_feed::add_item(string::decode($get['titel']),'http://'.$host.$pfad.'/news/?action=show&amp;id='.$get['id'],string::decode($get['text']),string::decode($get_user['nick']),
                ($get['comments'] ? 'http://'.$host.$pfad.'/news/?action=show&amp;id='.$get['id'] : ''),date("r", $get['datum']));
            }

            rss_feed::gen_rss();
            $rss = rss_feed::get_rss();
            cache::set('public_news_rss',$rss, rss_cache_public_news);
            exit($rss);
        }
        else
            exit(cache::get('public_news_rss'));
    }
}