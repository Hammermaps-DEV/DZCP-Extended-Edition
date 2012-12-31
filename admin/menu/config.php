<?php
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
            if(((int)$_POST['cache_engine']) != $cache_engine)
                $cache_cleanup = true;

            db("UPDATE ".$db['config']." SET
                  `upicsize`           = '".((int)$_POST['m_upicsize'])."',
                `m_gallerypics`      = '".((int)$_POST['m_gallerypics'])."',
                `m_usergb`           = '".((int)$_POST['m_usergb'])."',
                `m_artikel`          = '".((int)$_POST['m_artikel'])."',
                `m_adminartikel`     = '".((int)$_POST['m_adminartikel'])."',
                `m_clanwars`         = '".((int)$_POST['m_clanwars'])."',
                `m_awards`           = '".((int)$_POST['m_awards'])."',
                `allowhover`         = '".((int)$_POST['ahover'])."',
                `securelogin`        = '".((int)$_POST['securelogin'])."',
                `m_clankasse`        = '".((int)$_POST['m_clankasse'])."',
                `m_userlist`         = '".((int)$_POST['m_userlist'])."',
                `m_banned`           = '".((int)$_POST['m_banned'])."',
                `m_adminnews`        = '".((int)$_POST['m_adminnews'])."',
                `l_servernavi`       = '".((int)$_POST['l_servernavi'])."',
                `l_shoutnick`        = '".((int)$_POST['l_shoutnick'])."',
                `m_gb`               = '".((int)$_POST['m_gb'])."',
                `m_fthreads`         = '".((int)$_POST['m_fthreads'])."',
                `m_fposts`           = '".((int)$_POST['m_fposts'])."',
                `gallery`            = '".((int)$_POST['m_gallery'])."',
                `m_news`             = '".((int)$_POST['m_news'])."',
                `m_shout`            = '".((int)$_POST['m_shout'])."',
                   `m_comments`         = '".((int)$_POST['m_comments'])."',
                `m_archivnews`       = '".((int)$_POST['m_archivnews'])."',
                `maxwidth`           = '".((int)$_POST['maxwidth'])."',
                `f_forum`            = '".((int)$_POST['f_forum'])."',
                `f_cwcom`            = '".((int)$_POST['f_cwcom'])."',
                `f_gb`               = '".((int)$_POST['f_gb'])."',
                `f_artikelcom`       = '".((int)$_POST['f_artikelcom'])."',
                `f_membergb`         = '".((int)$_POST['f_membergb'])."',
                `f_shout`            = '".((int)$_POST['f_shout'])."',
                `f_newscom`          = '".((int)$_POST['f_newscom'])."',
                `l_newsadmin`        = '".((int)$_POST['l_newsadmin'])."',
                `l_shouttext`        = '".((int)$_POST['l_shouttext'])."',
                `l_newsarchiv`       = '".((int)$_POST['l_newsarchiv'])."',
                `l_forumtopic`       = '".((int)$_POST['l_forumtopic'])."',
                `l_forumsubtopic`    = '".((int)$_POST['l_forumsubtopic'])."',
                `l_clanwars`         = '".((int)$_POST['l_clanwars'])."',
                `m_lnews`            = '".((int)$_POST['m_lnews'])."',
                `m_lartikel`         = '".((int)$_POST['m_lartikel'])."',
                `m_events`           = '".((int)$_POST['m_events'])."',
                `m_topdl`            = '".((int)$_POST['m_topdl'])."',
                `m_ftopics`          = '".((int)$_POST['m_ftopics'])."',
                `m_cwcomments`       = '".((int)$_POST['m_cwcomments'])."',
                `m_lwars`            = '".((int)$_POST['m_lwars'])."',
                `m_lreg`             = '".((int)$_POST['m_lreg'])."',
                `m_nwars`            = '".((int)$_POST['m_nwars'])."',
                `l_topdl`            = '".((int)$_POST['l_topdl'])."',
                `l_ftopics`          = '".((int)$_POST['l_ftopics'])."',
                `l_lreg`             = '".((int)$_POST['l_lreg'])."',
                `l_lnews`            = '".((int)$_POST['l_lnews'])."',
                `l_lartikel`         = '".((int)$_POST['l_lartikel'])."',
                `l_lwars`            = '".((int)$_POST['l_lwars'])."',
                `teamrow`            = '".((int)$_POST['teamrow'])."',
                `shout_max_zeichen`  = '".((int)$_POST['zeichen'])."',
                `maxshoutarchiv`     = '".((int)$_POST['m_shouta'])."',
                `m_away`             = '".((int)$_POST['m_away'])."',
                `direct_refresh`     = '".((int)$_POST['direct_refresh'])."',
                `cache_teamspeak`    = '".((int)$_POST['cache_teamspeak'])."',
                `cache_server`       = '".((int)$_POST['cache_server'])."',
                `cache_news`         = '".((int)$_POST['cache_news'])."',
                  `cache_engine`       = '".((int)$_POST['cache_engine'])."',
                `l_nwars`            = '".((int)$_POST['l_nwars'])."',
                `news_feed`   	     = '".((int)$_POST['feed'])."'
            WHERE id = 1");

            db("UPDATE ".$db['settings']." SET
                `clanname`            = '".up($_POST['clanname'])."',
                `pagetitel`           = '".up($_POST['pagetitel'])."',
                `badwords`            = '".up($_POST['badwords'])."',
                `gmaps_who`           = '".((int)$_POST['gmaps_who'])."',
                `language`            = '".$_POST['language']."',
                `gametiger`           = '".$_POST['gametiger']."',
                `regcode`             = '".((int)$_POST['regcode'])."',
                `forum_vote`          = '".((int)$_POST['forum_vote'])."',
                `reg_forum`           = '".((int)$_POST['reg_forum'])."',
                `reg_artikel`         = '".((int)$_POST['reg_artikel'])."',
                `reg_shout`           = '".((int)$_POST['reg_shout'])."',
                `reg_cwcomments`      = '".((int)$_POST['reg_cwcomments'])."',
                `squadtmpl`           = '".((int)$_POST['squadtmpl'])."',
                `counter_start`       = '".((int)$_POST['counter_start'])."',
                `reg_newscomments`    = '".((int)$_POST['reg_nc'])."',
                `reg_dl`              = '".((int)$_POST['reg_dl'])."',
                `eml_reg_subj`        = '".up($_POST['eml_reg_subj'])."',
                `eml_pwd_subj`        = '".up($_POST['eml_pwd_subj'])."',
                `eml_nletter_subj`    = '".up($_POST['eml_nletter_subj'])."',
                `eml_pn_subj`	      = '".up($_POST['eml_pn_subj'])."',
                `double_post`	      = '".((int)$_POST['double_post'])."',
                `gb_activ`	      	  = '".((int)$_POST['gb_activ'])."',
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
                `persinfo`            = '".((int)$_POST['persinfo'])."',
                `wmodus`              = '".((int)$_POST['wmodus'])."',
                `balken_cw`           = '".up($_POST['balken_cw'])."',
                `balken_vote`         = '".up($_POST['balken_vote'])."',
                `balken_vote_menu`    = '".up($_POST['balken_vote_menu'])."',
                `memcache_host`       = '".$_POST['memcache_host']."',
                  `memcache_port`       = '".((int)$_POST['memcache_port'])."',
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

    $files = get_files('../inc/lang/languages/',false,true,array('php')); $lang = '';
    for($i=0; $i<count($files); $i++)
    {
        $sel = ($gets['language'] == $files[$i] ? 'selected="selected"' : '');
        $lng = preg_replace("#.php#", "",$files[$i]);
        $lang .= show(_select_field, array("value" => $lng, "what" => $lng, "sel" => $sel));
    }

    unset($files,$lng,$sel);

    $tmps = get_files('../inc/_templates_/',true); $tmpldir = '';
    for($i=0; $i<count($tmps); $i++)
    {
        $selt = ($gets['tmpdir'] == $tmps[$i] ? 'selected="selected"' : '');
        $tmpldir .= show(_select_field, array("value" => $tmps[$i], "what" => $tmps[$i], "sel" => $selt));
    }

    unset($tmps,$selt);

       $cache = array(0 => 'Keinen', 1 => 'File', 2 => 'MySQL', 3 => 'Memcache'); $cache_select = '';
    foreach ($cache as $key => $value)
    { $cache_select .= show(_select_field, array("value" => $key, "what" => $value, "sel" => ($cache_engine == $key ? 'selected="selected"' : ''))); }

    $selyes = ($gets['regcode'] ? 'selected="selected"' : '');
    $selno = (!$gets['regcode'] ? 'selected="selected"' : '');
    $selr_forum = ($gets['reg_forum'] ? 'selected="selected"' : '');
    $selr_nc = ($gets['reg_newscomments'] ? 'selected="selected"' : '');
    $selr_dl = ($gets['reg_dl'] ? 'selected="selected"' : '');
    $selr_artikel = ($gets['reg_artikel'] ? 'selected="selected"' : '');
    $selr_cwc = ($gets['reg_cwcomments'] ? 'selected="selected"' : '');
    $selr_shout = ($gets['reg_shout'] ? 'selected="selected"' : '');
    $selwm = ($gets['wmodus'] ? 'selected="selected"' : '');
    $selsq = ($gets['squadtmpl'] ? 'selected="selected"' : '');
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
    $show_ = show($dir."/form_config", array("cache_select" => $cache_select,
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
                                             "selsq" => $selsq,
                                             "f_forum" => $get['f_forum'],
                                             "f_gb" => $get['f_gb'],
                                             "f_membergb" => $get['f_membergb'],
                                             "f_shout" => $get['f_shout'],
                                             "f_newscom" => $get['f_newscom'],
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
                                             "c_teamrow" => $get['teamrow'],
                                             "f_artikelcom" => $get['f_artikelcom'],
                                             "sel_url" => $sel_url));

    $show = show($dir."/form", array("head" => _config_global_head, "what" => "config", "value" => _button_value_config, "show" => $show_));
}
?>
