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

$host = $_SERVER['HTTP_HOST'];
$pfad = preg_replace("#^(.*?)\/(.*?)#Uis","$1",dirname($_SERVER['PHP_SELF']));

if(isset($_GET['key']) || !empty($_GET['key']))
{
    $qry = db("SELECT id,user FROM `".dba::get('users')."` WHERE `rss_key` = '".htmlentities($_GET['key'])."' LIMIT 1");
    if(!_rows($qry)) exit(); $user = _fetch($qry);
    $rss_uconf = db("SELECT * FROM `".dba::get('rss')."` WHERE `userid` = '".$user['id']."' LIMIT 1",false,true);

    if(Cache::check('private_news_rss_userid_'.$user['id']))
    {
        rss_feed::set_main_config('pagetitle',show(_rss_pagetitle,array('clanname' => $clanname, 'username' => string::decode($user['user']))));
        
        //MYSQL-Abfrage als leer definieren...
        $myi = '';
        
        // Intern News
        if($rss_uconf['show_intern_news'])
        {
            $ine = "SELECT  datum as pubDate,id,'News' AS category,'http://".$host.$pfad."/news/?action=show&id=' as link,titel as title,text as description,autor as author
                    FROM ".dba::get('news')." WHERE intern = 1 AND public = 1 AND datum > 0";
            $myi .= ( !empty($myi) ? ' UNION '.$ine : $ine );               
        }

        // Public News
        if($rss_uconf['show_public_news'])
        {
            $pne = "\nSELECT  datum as pubDate,id,'News' AS category, 'http://".$host.$pfad."/news/?action=show&id=' as link,titel as title,text as description,autor as author
                    FROM ".dba::get('news')." WHERE intern = 0 AND public = 1 AND datum > 0";
            $myi .= ( !empty($myi) ? ' UNION '.$pne : $pne ); 
        }

        // Artikel
        if($rss_uconf['show_artikel'])
        {
            $par = "\nSELECT  datum as pubDate,id,'Artikel' AS category, 'http://".$host.$pfad."/artikel/?action=show&id=' as link,titel as title,text as description,autor as author
                     FROM ".dba::get('artikel')." WHERE  public = 1";
            $myi .= ( !empty($myi) ? ' UNION '.$par : $par ); 
        }
        
        // Forumthreads
        if($rss_uconf['show_forum'])
        {
            $pth = "\nSELECT  t_date as pubDate,kid,'Thread' AS category, 'http://".$host.$pfad."/?index=forum&action=showthread&kid=' as link,topic as title,t_text as description,t_reg as author
                     FROM ".dba::get('f_threads')." ";
            $myi .= ( !empty($myi) ? ' UNION '.$pth : $pth ); 
        }

        // Downloads
        if($rss_uconf['show_downloads'])
        {
            $pdl = "SELECT  date as pubDate,id,'Downloads' AS category,'http://".$host.$pfad."/downloads/?action=download&id=' as link,download as title,beschreibung as description,1 as author
                    FROM ".dba::get('downloads');
            $myi .= ( !empty($myi) ? ' UNION '.$pdl : $pdl );                                                 
        }
        
        $qry = db($myi." ORDER BY pubDate DESC LIMIT 100"); 
        while($get = _fetch($qry))
        {
            //Feedimage auslesen, ansonsten Standart nehmen
            $files = get_files(basePath."/inc/images/uploads/rss/",false,true,$picformat,"#".strtolower($get['category'])."_".convert::ToInt($get['id'])."#");
            if ( empty($files['0']) ) $files['0'] = strtolower($get['category'])."_no_image.png";
            $image = '<img height="100px" align="left" vspace="10px" hspace="25px" alt="" src="http://'.$host.$pfad.'/inc/images/uploads/rss/'.$files['0'].'">';
            //Nicknamen aus Datenbank lesen
            $get_user = db("SELECT nick FROM ".dba::get('users')." WHERE id = '".$get['author']."'",false,true);
            $lastbuild = $get['pubDate'];
            rss_feed::add_item(  /* Titel  */   string::decode($get['category'].': '.$get['title']),
                                 /* Link   */   $get['link'].$get['id'],
                                 /* Text   */   $image.cut(convert::STRIP_HTML($get['description']),250),
                                 /* Author */   string::decode($get_user['nick']),
                                 /* Comment*/   ($get['comments'] ? $get['link'].$get['id'] : ''),
                                 /* Datum  */   date("r", $get['pubDate']),
                                 /* Category */ $get['category']
                               );     
        }

        //Last-Builddate ausgeben
        $get = _fetch(db($myi." ORDER BY pubDate DESC LIMIT 1"));
        rss_feed::gen_rss($get['pubDate']);
        $rss = rss_feed::get_rss();
        Cache::set('private_news_rss_userid_'.$user['id'],$rss, rss_cache_private_news);
        header("Content-Type: text/xml");
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
        // Public News
        $myi = "\nSELECT  datum as pubDate,id,'News' AS category, 'http://".$host.$pfad."/news/?action=show&id=' as link,titel as title,text as description,autor as author
                FROM ".dba::get('news')." WHERE intern = 0 AND public = 1 AND datum > 0";
                
        $qry = db($myi." ORDER BY pubDate DESC LIMIT 100"); 
        while($get = _fetch($qry))
        {
            //Feedimage auslesen, ansonsten Standart nehmen
            $files = get_files(basePath."/inc/images/uploads/rss/",false,true,$picformat,"#".strtolower($get['category'])."_".convert::ToInt($get['id'])."#");
            if ( empty($files['0']) ) $files['0'] = strtolower($get['category'])."_no_image.png";
            $image = '<img height="100px" align="left" vspace="10px" hspace="25px" alt="" src="http://'.$host.$pfad.'/inc/images/uploads/rss/'.$files['0'].'">';
            //Nicknamen aus Datenbank lesen
            $get_user = db("SELECT nick FROM ".dba::get('users')." WHERE id = '".$get['author']."'",false,true);
            $lastbuild = $get['pubDate'];
            rss_feed::add_item(  /* Titel  */   string::decode($get['category'].': '.$get['title']),
                                 /* Link   */   $get['link'].$get['id'],
                                 /* Text   */   $image.cut(convert::STRIP_HTML($get['description']),250),
                                 /* Author */   string::decode($get_user['nick']),
                                 /* Comment*/   ($get['comments'] ? $get['link'].$get['id'] : ''),
                                 /* Datum  */   date("r", $get['pubDate']),
                                 /* Category */ $get['category']
                               );     
        }
            //Last-Builddate ausgeben
            $get = _fetch(db($myi." ORDER BY pubDate DESC LIMIT 1"));
            rss_feed::gen_rss($get['pubDate']);
            $rss = rss_feed::get_rss();
            cache::set('public_news_rss',$rss, rss_cache_public_news);
            header("Content-Type: text/xml");
            exit($rss);
        }
        else
            exit(cache::get('public_news_rss'));
    }
}