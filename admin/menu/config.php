<?php
/**
 * <DZCP-Extended Edition>
 * @package: DZCP-Extended Edition
 * @author: DZCP Developer Team || Hammermaps.de Developer Team
 * @link: http://www.dzcp.de || http://www.hammermaps.de
 */

#####################
## Admin Menu-File ##
#####################
if(_adminMenu != 'true')
    exit();

$where = $where.': '._config_global_head; $show = '';
switch (isset($_GET['do']) ? $_GET['do'] : '')
{
    case 'update':
        if(isset($_POST))
        {
            if(convert::ToString($_POST['cache_engine']) != $cache_engine)
                $cache_cleanup = true;

            db("UPDATE ".$db['config']." SET
                `upicsize`           = '".convert::ToInt($_POST['m_upicsize'])."',
                `m_gallerypics`      = '".convert::ToInt($_POST['m_gallerypics'])."',
                `m_usergb`           = '".convert::ToInt($_POST['m_usergb'])."',
                `m_artikel`          = '".convert::ToInt($_POST['m_artikel'])."',
                `m_adminartikel`     = '".convert::ToInt($_POST['m_adminartikel'])."',
                `m_clanwars`         = '".convert::ToInt($_POST['m_clanwars'])."',
                `m_awards`           = '".convert::ToInt($_POST['m_awards'])."',
                `allowhover`         = '".convert::ToInt($_POST['ahover'])."',
                `securelogin`        = '".convert::ToInt($_POST['securelogin'])."',
                `m_clankasse`        = '".convert::ToInt($_POST['m_clankasse'])."',
                `m_userlist`         = '".convert::ToInt($_POST['m_userlist'])."',
                `m_banned`           = '".convert::ToInt($_POST['m_banned'])."',
                `m_adminnews`        = '".convert::ToInt($_POST['m_adminnews'])."',
                `l_servernavi`       = '".convert::ToInt($_POST['l_servernavi'])."',
                `l_shoutnick`        = '".convert::ToInt($_POST['l_shoutnick'])."',
                `m_gb`               = '".convert::ToInt($_POST['m_gb'])."',
                `m_fthreads`         = '".convert::ToInt($_POST['m_fthreads'])."',
                `m_fposts`           = '".convert::ToInt($_POST['m_fposts'])."',
                `gallery`            = '".convert::ToInt($_POST['m_gallery'])."',
                `m_news`             = '".convert::ToInt($_POST['m_news'])."',
                `m_shout`            = '".convert::ToInt($_POST['m_shout'])."',
                `m_comments`         = '".convert::ToInt($_POST['m_comments'])."',
                `m_archivnews`       = '".convert::ToInt($_POST['m_archivnews'])."',
                `maxwidth`           = '".convert::ToInt($_POST['maxwidth'])."',
                `f_forum`            = '".convert::ToInt($_POST['f_forum'])."',
                `f_cwcom`            = '".convert::ToInt($_POST['f_cwcom'])."',
                `f_gb`               = '".convert::ToInt($_POST['f_gb'])."',
                `f_artikelcom`       = '".convert::ToInt($_POST['f_artikelcom'])."',
                `f_membergb`         = '".convert::ToInt($_POST['f_membergb'])."',
                `f_shout`            = '".convert::ToInt($_POST['f_shout'])."',
                `f_newscom`          = '".convert::ToInt($_POST['f_newscom'])."',
                `f_downloadcom`      = '".convert::ToInt($_POST['f_downloadcom'])."',
                `l_newsadmin`        = '".convert::ToInt($_POST['l_newsadmin'])."',
                `l_shouttext`        = '".convert::ToInt($_POST['l_shouttext'])."',
                `l_newsarchiv`       = '".convert::ToInt($_POST['l_newsarchiv'])."',
                `l_forumtopic`       = '".convert::ToInt($_POST['l_forumtopic'])."',
                `l_forumsubtopic`    = '".convert::ToInt($_POST['l_forumsubtopic'])."',
                `l_clanwars`         = '".convert::ToInt($_POST['l_clanwars'])."',
                `m_lnews`            = '".convert::ToInt($_POST['m_lnews'])."',
                `m_lartikel`         = '".convert::ToInt($_POST['m_lartikel'])."',
                `m_events`           = '".convert::ToInt($_POST['m_events'])."',
                `m_topdl`            = '".convert::ToInt($_POST['m_topdl'])."',
                `m_ftopics`          = '".convert::ToInt($_POST['m_ftopics'])."',
                `m_cwcomments`       = '".convert::ToInt($_POST['m_cwcomments'])."',
                `m_lwars`            = '".convert::ToInt($_POST['m_lwars'])."',
                `m_lreg`             = '".convert::ToInt($_POST['m_lreg'])."',
                `m_nwars`            = '".convert::ToInt($_POST['m_nwars'])."',
                `l_topdl`            = '".convert::ToInt($_POST['l_topdl'])."',
                `l_ftopics`          = '".convert::ToInt($_POST['l_ftopics'])."',
                `l_lreg`             = '".convert::ToInt($_POST['l_lreg'])."',
                `l_lnews`            = '".convert::ToInt($_POST['l_lnews'])."',
                `l_lartikel`         = '".convert::ToInt($_POST['l_lartikel'])."',
                `l_lwars`            = '".convert::ToInt($_POST['l_lwars'])."',
                `teamrow`            = '".convert::ToInt($_POST['teamrow'])."',
                `shout_max_zeichen`  = '".convert::ToInt($_POST['zeichen'])."',
                `maxshoutarchiv`     = '".convert::ToInt($_POST['m_shouta'])."',
                `m_away`             = '".convert::ToInt($_POST['m_away'])."',
                `direct_refresh`     = '".convert::ToInt($_POST['direct_refresh'])."',
                `cache_teamspeak`    = '".convert::ToInt($_POST['cache_teamspeak'])."',
                `cache_server`       = '".convert::ToInt($_POST['cache_server'])."',
                `cache_news`         = '".convert::ToInt($_POST['cache_news'])."',
                `cache_engine`       = '".convert::ToString($_POST['cache_engine'])."',
                `l_nwars`            = '".convert::ToInt($_POST['l_nwars'])."',
                `news_feed`   	     = '".convert::ToInt($_POST['feed'])."'
            WHERE id = 1");

            db("UPDATE ".$db['settings']." SET
                `clanname`            = '".up($_POST['clanname'])."',
                `pagetitel`           = '".up($_POST['pagetitel'])."',
                `badwords`            = '".up($_POST['badwords'])."',
                `gmaps_who`           = '".convert::ToInt($_POST['gmaps_who'])."',
                `language`            = '".$_POST['language']."',
                `regcode`             = '".convert::ToInt($_POST['regcode'])."',
                `forum_vote`          = '".convert::ToInt($_POST['forum_vote'])."',
                `reg_forum`           = '".convert::ToInt($_POST['reg_forum'])."',
                `reg_artikel`         = '".convert::ToInt($_POST['reg_artikel'])."',
                `reg_shout`           = '".convert::ToInt($_POST['reg_shout'])."',
                `reg_cwcomments`      = '".convert::ToInt($_POST['reg_cwcomments'])."',
                `counter_start`       = '".convert::ToInt($_POST['counter_start'])."',
                `reg_newscomments`    = '".convert::ToInt($_POST['reg_nc'])."',
                `reg_dl`              = '".convert::ToInt($_POST['reg_dl'])."',
                `reg_dlcomments`      = '".convert::ToInt($_POST['reg_dlcomments'])."',
                `eml_reg_subj`        = '".up($_POST['eml_reg_subj'])."',
                `eml_pwd_subj`        = '".up($_POST['eml_pwd_subj'])."',
                `eml_nletter_subj`    = '".up($_POST['eml_nletter_subj'])."',
                `eml_pn_subj`	      = '".up($_POST['eml_pn_subj'])."',
                `double_post`	      = '".convert::ToInt($_POST['double_post'])."',
                `gb_activ`	      	  = '".convert::ToInt($_POST['gb_activ'])."',
                `eml_fabo_npost_subj` = '".up($_POST['eml_fabo_npost_subj'])."',
                `eml_fabo_tedit_subj` = '".up($_POST['eml_fabo_tedit_subj'])."',
                `eml_fabo_pedit_subj` = '".up($_POST['eml_fabo_pedit_subj'])."',
                `eml_reg`             = '".up($_POST['eml_reg'])."',
                `eml_pwd`             = '".up($_POST['eml_pwd'])."',
                `eml_nletter`         = '".up($_POST['eml_nletter'])."',
                `eml_pn`        	  = '".up($_POST['eml_pn'])."',
                `eml_fabo_npost`      = '".up($_POST['eml_fabo_npost'])."',
                `eml_fabo_tedit`      = '".up($_POST['eml_fabo_tedit'])."',
                `eml_fabo_pedit`      = '".up($_POST['eml_fabo_pedit'])."',
                `mailfrom`            = '".up($_POST['mailfrom'])."',
                `tmpdir`              = '".up($_POST['tmpdir'])."',
                `persinfo`            = '".convert::ToInt($_POST['persinfo'])."',
                `wmodus`              = '".convert::ToInt($_POST['wmodus'])."',
                `memcache_host`       = '".$_POST['memcache_host']."',
                `memcache_port`       = '".convert::ToInt($_POST['memcache_port'])."',
                `urls_linked`   	  = '".up($_POST['urls_linked'])."'
            WHERE id = 1");

            $show = info(_config_set, "?admin=config", 10);
        }
    break;
}

if(empty($show))
{
    $get = db("SELECT * FROM ".$db['config'],false,true);
    $gets = db("SELECT * FROM ".$db['settings'],false,true);

    $files = get_files(basePath.'/inc/lang/languages/',false,true,array('php')); $lang = '';
    foreach($files as $file)
    {
        $sel = ($gets['language'] == $file ? 'selected="selected"' : '');
        $lng = preg_replace("#.php#", "",$file);
        $lang .= show(_select_field, array("value" => $lng, "what" => $lng, "sel" => $sel));
    }

    unset($files,$file,$lng,$sel);

    $tmps = get_files(basePath.'/inc/_templates_/',true); $tmpldir = '';
    foreach($tmps as $tmp)
    {
        $selt = ($gets['tmpdir'] == $tmp ? 'selected="selected"' : '');
        $tmpldir .= show(_select_field, array("value" => $tmp, "what" => $tmp, "sel" => $selt));
    }

    unset($tmps,$tmp,$selt);

    $pwde_options = show('<option '.(!$gets['default_pwd_encoder'] ? 'selected="selected"' : '').' value="0">MD5 [lang_pwd_encoder_algorithm]</option>
    <option '.($gets['default_pwd_encoder'] == 1 ? 'selected="selected"' : '').' value="1">SHA1 [lang_pwd_encoder_algorithm]</option>
    <option '.($gets['default_pwd_encoder'] == 2 ? 'selected="selected"' : '').' value="2">SHA256 [lang_pwd_encoder_algorithm]</option>
    <option '.($gets['default_pwd_encoder'] == 3 ? 'selected="selected"' : '').' value="3">SHA512 [lang_pwd_encoder_algorithm]</option>');

    $selyes = ($gets['regcode'] ? 'selected="selected"' : '');
    $selno = (!$gets['regcode'] ? 'selected="selected"' : '');
    $selr_forum = ($gets['reg_forum'] ? 'selected="selected"' : '');
    $selr_nc = ($gets['reg_newscomments'] ? 'selected="selected"' : '');
    $selr_dl = ($gets['reg_dl'] ? 'selected="selected"' : '');
    $selr_dc = ($gets['reg_dlcomments'] ? 'selected="selected"' : '');
    $selr_artikel = ($gets['reg_artikel'] ? 'selected="selected"' : '');
    $selr_cwc = ($gets['reg_cwcomments'] ? 'selected="selected"' : '');
    $selr_shout = ($gets['reg_shout'] ? 'selected="selected"' : '');
    $selwm = ($gets['wmodus'] ? 'selected="selected"' : '');
    $selr_pi = (!$gets['persinfo'] ? 'selected="selected"' : '');
    $sel_sl = ($get['securelogin'] ? 'selected="selected"' : '');
    $selh_all = ($get['allowhover'] == 1 ? 'selected="selected"' : '');
    $selh_cw = ($get['allowhover'] == 2 ? 'selected="selected"' : '');
    $sel_gm = ($gets['gmaps_who'] ? 'selected="selected"' : '');
    $sel_dp = ($gets['double_post'] ? 'selected="selected"' : '');
    $sel_fv = ($gets['forum_vote'] ? 'selected="selected"' : '');
    $sel_gba = ($gets['gb_activ'] ? 'selected="selected"' : '');
    $sel_url = ($gets['urls_linked'] ? 'selected="selected"' : '');
    $sel_feed = ($get['news_feed'] ? 'selected="selected"' : '');

    $wysiwyg = '_word';
    $show_ = show($dir."/form_config", array("cache_select" => Cache::GetConfigMenu(),
                                             "main_info" => _main_info,
                                             "cache_info" => _config_cache_info,
                                             "badword_info" => _admin_config_badword_info,
                                             "eml_info" => _admin_eml_info,
                                             "reg_info" => _admin_reg_info,
                                             "c_limits_what" => _config_c_limits_what,
                                             "c_floods_what" => _config_c_floods_what,
                                             "c_length_what" => _config_c_length_what,
                                             "sel_refresh" => ($get['direct_refresh'] == 1 ? ' selected="selected"' : ''),
                                             "sel_gm" => $sel_gm,
                                             "cache_teamspeak" => $get['cache_teamspeak'],
                                             "cache_server" => $get['cache_server'],
                                             "cache_news" => $get['cache_news'],
                                             "c_eml_reg_subj" => $gets['eml_reg_subj'],
                                             "c_eml_pwd_subj" => $gets['eml_pwd_subj'],
                                             "c_eml_nletter_subj" => $gets['eml_nletter_subj'],
                                             "c_eml_pn_subj" => $gets['eml_pn_subj'],
                                             "c_eml_fabo_npost_subj" => $gets['eml_fabo_npost_subj'],
                                             "c_eml_fabo_tedit_subj" => $gets['eml_fabo_tedit_subj'],
                                             "c_eml_fabo_pedit_subj" => $gets['eml_fabo_pedit_subj'],
                                             "c_eml_reg" => txtArea($gets['eml_reg']),
                                             "c_eml_pwd" => txtArea($gets['eml_pwd']),
                                             "c_eml_nletter" => txtArea($gets['eml_nletter']),
                                             "c_eml_pn" => txtArea($gets['eml_pn']),
                                             "c_eml_fabo_tedit" => txtArea($gets['eml_fabo_tedit']),
                                             "c_eml_fabo_pedit" => txtArea($gets['eml_fabo_pedit']),
                                             "c_eml_fabo_nposr" => txtArea($gets['eml_fabo_npost']),
                                             "memcache_host" => $gets['memcache_host'],
                                             "memcache_port" => $gets['memcache_port'],
                                             "tmpdir" => $tmpldir,
                                             "maxwidth" => $get['maxwidth'],
                                             "l_servernavi" => $get['l_servernavi'],
                                             "mailfrom" => re($gets['mailfrom']),
                                             "selpi" => $selr_pi,
                                             "l_lreg" => $get['l_lreg'],
                                             "m_lreg" => $get['m_lreg'],
                                             "selr_shout" => $selr_shout,
                                             "badwords" => re($gets['badwords']),
                                             "l_shoutnick" => $get['l_shoutnick'],
                                             "m_awards" => $get['m_awards'],
                                             "selr_cwc" => $selr_cwc,
                                             "f_cwcom" => $get['f_cwcom'],
                                             "selyes" => $selyes,
                                             "selno" => $selno,
                                             "selfeed" => $sel_feed,
                                             "regcode" => $gets['regcode'],
                                             "m_gallery" => $get['gallery'],
                                             "m_lnews" => $get['m_lnews'],
                                             "m_lartikel" => $get['m_lartikel'],
                                             "m_ftopics" => $get['m_ftopics'],
                                             "m_lwars" => $get['m_lwars'],
                                             "m_nwars" => $get['m_nwars'],
                                             "m_events" => $get['m_events'],
                                             "m_topdl" => $get['m_topdl'],
                                             "m_usergb" => $get['m_usergb'],
                                             "m_clankasse" => $get['m_clankasse'],
                                             "m_userlist" => $get['m_userlist'],
                                             "m_banned" => $get['m_banned'],
                                             "m_adminnews" => $get['m_adminnews'],
                                             "m_shout" => $get['m_shout'],
                                             "m_shouta" => $get['maxshoutarchiv'],
                                             "zeichen" => $get['shout_max_zeichen'],
                                             "m_comments" => $get['m_comments'],
                                             "m_cwcomments" => $get['m_cwcomments'],
                                             "m_archivnews" => $get['m_archivnews'],
                                             "m_gb" => $get['m_gb'],
                                             "m_fthreads" => $get['m_fthreads'],
                                             "m_fposts" => $get['m_fposts'],
                                             "m_clanwars" => $get['m_clanwars'],
                                             "m_news" => $get['m_news'],
                                             "m_gallerypics" => $get['m_gallerypics'],
                                             "m_upicsize" => $get['upicsize'],
                                             "counter_start" => _counter_start,
                                             "c_start" => $gets['counter_start'],
                                             "f_forum" => $get['f_forum'],
                                             "f_gb" => $get['f_gb'],
                                             "f_membergb" => $get['f_membergb'],
                                             "f_shout" => $get['f_shout'],
                                             "f_newscom" => $get['f_newscom'],
                                             "f_downloadcom" => $get['f_downloadcom'],
                                             "m_artikel" => $get['m_artikel'],
                                             "m_adminartikel" => $get['m_adminartikel'],
                                             "m_away" => $get['m_away'],
                                             "c_wmodus" => $gets['wmodus'],
                                             "selwm" => $selwm,
                                             "l_clanwars" => $get['l_clanwars'],
                                             "l_newsadmin" => $get['l_newsadmin'],
                                             "l_shouttext" => $get['l_shouttext'],
                                             "l_newsarchiv" => $get['l_newsarchiv'],
                                             "l_forumtopic" => $get['l_forumtopic'],
                                             "l_forumsubtopic" => $get['l_forumsubtopic'],
                                             "l_topdl" => $get['l_topdl'],
                                             "l_ftopics" => $get['l_ftopics'],
                                             "l_lnews" => $get['l_lnews'],
                                             "l_lartikel" => $get['l_lartikel'],
                                             "l_lwars" => $get['l_lwars'],
                                             "l_nwars" => $get['l_nwars'],
                                             "clanname" => re($gets['clanname']),
                                             "pagetitel" => re($gets['pagetitel']),
                                             "lang" => $lang,
                                             "sel_fv" => $sel_fv,
                                             "sel_sl" => $sel_sl,
                                             "sel_dp" => $sel_dp,
                                             "sel_gba" => $sel_gba,
                                             "selh_all" => $selh_all,
                                             "selh_cw" => $selh_cw,
                                             "selr_nc" => $selr_nc,
                                             "selr_forum" => $selr_forum,
                                             "selr_dl" => $selr_dl,
                                             "selr_artikel" => $selr_artikel,
                                             "selr_dc" => $selr_dc,
                                             "c_teamrow" => $get['teamrow'],
                                             "f_artikelcom" => $get['f_artikelcom'],
                                             "sel_url" => $sel_url,
                                             "pwde_options" => $pwde_options));

    $show = show($dir."/form", array("head" => _config_global_head, "what" => "config", "value" => _button_value_config, "show" => $show_));
}
?>
