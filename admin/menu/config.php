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
wysiwyg::set('advanced');
switch ($do)
{
    case 'update':
        if(isset($_POST))
        {
            if(convert::ToString($_POST['cache_engine']) != $cache_engine)
                $cache_cleanup = true;

            db("UPDATE ".dba::get('config')." SET
                `upicsize`           = '".convert::ToInt($_POST['m_upicsize'])."',
                `m_gallery`          = '".convert::ToInt($_POST['m_gallery'])."',
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
                `m_adminnews`        = '".convert::ToInt($_POST['m_adminnews'])."',
                `l_servernavi`       = '".convert::ToInt($_POST['l_servernavi'])."',
                `l_shoutnick`        = '".convert::ToInt($_POST['l_shoutnick'])."',
                `m_gb`               = '".convert::ToInt($_POST['m_gb'])."',
                `m_fthreads`         = '".convert::ToInt($_POST['m_fthreads'])."',
                `m_fposts`           = '".convert::ToInt($_POST['m_fposts'])."',
                `gallery`            = '".convert::ToInt($_POST['m_gallery_user'])."',
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
                `cache_engine`       = '".string::encode($_POST['cache_engine'])."',
                `l_nwars`            = '".convert::ToInt($_POST['l_nwars'])."',
                `news_feed`   	     = '".convert::ToInt($_POST['feed'])."',
                `use_akl`   	     = '".convert::ToInt($_POST['akl'])."'
            WHERE id = 1");

            db("UPDATE ".dba::get('settings')." SET
                `clanname`              = '".string::encode($_POST['clanname'])."',
                `pagetitel`             = '".string::encode($_POST['pagetitel'])."',
                `badwords`              = '".string::encode($_POST['badwords'])."',
                `gmaps_who`             = '".convert::ToInt($_POST['gmaps_who'])."',
                `language`              = '".string::encode($_POST['language'])."',
                `regcode`               = '".convert::ToInt($_POST['regcode'])."',
                `forum_vote`            = '".convert::ToInt($_POST['forum_vote'])."',
                `reg_forum`             = '".convert::ToInt($_POST['reg_forum'])."',
                `reg_artikel`           = '".convert::ToInt($_POST['reg_artikel'])."',
                `reg_shout`             = '".convert::ToInt($_POST['reg_shout'])."',
                `reg_cwcomments`        = '".convert::ToInt($_POST['reg_cwcomments'])."',
                `reg_newscomments`      = '".convert::ToInt($_POST['reg_nc'])."',
                `reg_dl`                = '".convert::ToInt($_POST['reg_dl'])."',
                `reg_dlcomments`        = '".convert::ToInt($_POST['reg_dlcomments'])."',
                `eml_reg_subj`          = '".string::encode($_POST['eml_reg_subj'])."',
                `eml_pwd_subj`          = '".string::encode($_POST['eml_pwd_subj'])."',
                `eml_nletter_subj`      = '".string::encode($_POST['eml_nletter_subj'])."',
                `eml_pn_subj`           = '".string::encode($_POST['eml_pn_subj'])."',
                `double_post`           = '".convert::ToInt($_POST['double_post'])."',
                `gb_activ`              = '".convert::ToInt($_POST['gb_activ'])."',
                `eml_fabo_npost_subj`   = '".string::encode($_POST['eml_fabo_npost_subj'])."',
                `eml_fabo_tedit_subj`   = '".string::encode($_POST['eml_fabo_tedit_subj'])."',
                `eml_fabo_pedit_subj`   = '".string::encode($_POST['eml_fabo_pedit_subj'])."',
                `eml_akl_register_subj` = '".string::encode($_POST['eml_akl_regist_subj'])."',
                `eml_reg`               = '".string::encode($_POST['eml_reg'])."',
                `eml_pwd`               = '".string::encode($_POST['eml_pwd'])."',
                `eml_nletter`           = '".string::encode($_POST['eml_nletter'])."',
                `eml_pn`        	    = '".string::encode($_POST['eml_pn'])."',
                `eml_fabo_npost`        = '".string::encode($_POST['eml_fabo_npost'])."',
                `eml_fabo_tedit`        = '".string::encode($_POST['eml_fabo_tedit'])."',
                `eml_fabo_pedit`        = '".string::encode($_POST['eml_fabo_pedit'])."',
                `eml_akl_register`      = '".string::encode($_POST['eml_akl_regist'])."',
                `mailfrom`              = '".string::encode($_POST['mailfrom'])."',
                `tmpdir`                = '".string::encode($_POST['tmpdir'])."',
                `persinfo`              = '".convert::ToInt($_POST['persinfo'])."',
                `wmodus`                = '".convert::ToInt($_POST['wmodus'])."',
                `mail_extension`        = '".string::encode($_POST['mail_extension'])."',
                `smtp_password`         = '".encryptData($_POST['smtp_pass'])."',
                `smtp_port`             = '".convert::ToInt($_POST['smtp_port'])."',
                `smtp_hostname`         = '".string::encode($_POST['smtp_host'])."',
                `smtp_username`         = '".string::encode($_POST['smtp_username'])."',
                `smtp_tls_ssl`          = '".convert::ToInt($_POST['smtp_tls_ssl'])."',
                `sendmail_path`         = '".string::encode($_POST['sendmail_path'])."',
                `memcache_host`         = '".string::encode($_POST['memcache_host'])."',
                `memcache_port`         = '".convert::ToInt($_POST['memcache_port'])."',
                `urls_linked`   	    = '".string::encode($_POST['urls_linked'])."'
            WHERE id = 1");

            $show = info(_config_set, "?admin=config", 10);
        }
    break;
}

if(empty($show))
{
    $get_config = db("SELECT * FROM ".dba::get('config'),false,true);
    $get_settings = db("SELECT * FROM ".dba::get('settings'),false,true);

    $files = get_files(basePath.'/inc/lang/languages/',false,true,array('php')); $lang = '';
    foreach($files as $file)
    {
        $lng = preg_replace("#.php#", "",$file);
        $sel = (string::decode($get_settings['language']) == $lng ? 'selected="selected"' : '');
        $lang .= show(_select_field, array("value" => $lng, "what" => $lng, "sel" => $sel));
    }
    unset($files,$file,$lng,$sel);

    $tmps = get_files(basePath.'/inc/_templates_/',true); $tmplsel = '';
    foreach($tmps as $tmp)
    {
        $selt = (string::decode($get_settings['tmpdir']) == $tmp ? 'selected="selected"' : '');
        $tmplsel .= show(_select_field, array("value" => $tmp, "what" => $tmp, "sel" => $selt));
    }
    unset($tmps,$tmp,$selt);

    $pwde_options = show('<option '.(!$get_settings['default_pwd_encoder'] ? 'selected="selected"' : '').' value="0">MD5 [lang_pwd_encoder_algorithm]</option>
    <option '.($get_settings['default_pwd_encoder'] == 1 ? 'selected="selected"' : '').' value="1">SHA1 [lang_pwd_encoder_algorithm]</option>
    <option '.($get_settings['default_pwd_encoder'] == 2 ? 'selected="selected"' : '').' value="2">SHA256 [lang_pwd_encoder_algorithm]</option>
    <option '.($get_settings['default_pwd_encoder'] == 3 ? 'selected="selected"' : '').' value="3">SHA512 [lang_pwd_encoder_algorithm]</option>');

    $smtp_secure_options = show('<option '.(!$get_settings['smtp_tls_ssl'] ? 'selected="selected"' : '').' value="0">[lang_default]</option>
    <option '.($get_settings['smtp_tls_ssl'] == 1 ? 'selected="selected"' : '').' value="1">TLS</option>
    <option '.($get_settings['smtp_tls_ssl'] == 2 ? 'selected="selected"' : '').' value="2">SSL</option>');

    $show = show($dir."/form_config", array( "cache_select"          => Cache::GetConfigMenu(),
                                             "main_info"             => _main_info,
                                             "cache_info"            => _config_cache_info,
                                             "badword_info"          => _admin_config_badword_info,
                                             "eml_info"              => _admin_eml_info,
                                             "reg_info"              => _admin_reg_info,
                                             "c_limits_what"         => _config_c_limits_what,
                                             "c_floods_what"         => _config_c_floods_what,
                                             "c_length_what"         => _config_c_length_what,
                                             "cache_teamspeak"       => convert::ToInt($get_config['cache_teamspeak']),
                                             "cache_server"          => convert::ToInt($get_config['cache_server']),
                                             "cache_news"            => convert::ToInt($get_config['cache_news']),
                                             "c_eml_reg_subj"        => string::decode($get_settings['eml_reg_subj']),
                                             "c_eml_pwd_subj"        => string::decode($get_settings['eml_pwd_subj']),
                                             "c_eml_nletter_subj"    => string::decode($get_settings['eml_nletter_subj']),
                                             "c_eml_pn_subj"         => string::decode($get_settings['eml_pn_subj']),
                                             "c_eml_fabo_npost_subj" => string::decode($get_settings['eml_fabo_npost_subj']),
                                             "c_eml_fabo_tedit_subj" => string::decode($get_settings['eml_fabo_tedit_subj']),
                                             "c_eml_fabo_pedit_subj" => string::decode($get_settings['eml_fabo_pedit_subj']),
                                             "c_eml_akl_regist_subj" => string::decode($get_settings['eml_akl_register_subj']),
                                             "c_eml_reg"             => string::decode($get_settings['eml_reg']),
                                             "c_eml_pwd"             => string::decode($get_settings['eml_pwd']),
                                             "c_eml_nletter"         => string::decode($get_settings['eml_nletter']),
                                             "c_eml_pn"              => string::decode($get_settings['eml_pn']),
                                             "c_eml_fabo_tedit"      => string::decode($get_settings['eml_fabo_tedit']),
                                             "c_eml_fabo_pedit"      => string::decode($get_settings['eml_fabo_pedit']),
                                             "c_eml_fabo_nposr"      => string::decode($get_settings['eml_fabo_npost']),
                                             "c_eml_akl_regist"      => string::decode($get_settings['eml_akl_register']),
                                             "memcache_host"         => string::decode($get_settings['memcache_host']),
                                             "memcache_port"         => convert::ToInt($get_settings['memcache_port']),
                                             "tmplsel"               => $tmplsel,
                                             "maxwidth"              => convert::ToInt($get_config['maxwidth']),
                                             "l_servernavi"          => convert::ToInt($get_config['l_servernavi']),
                                             "mailfrom"              => string::decode($get_settings['mailfrom']),
                                             "l_lreg"                => convert::ToInt($get_config['l_lreg']),
                                             "m_lreg"                => convert::ToInt($get_config['m_lreg']),
                                             "badwords"              => string::decode($get_settings['badwords']),
                                             "l_shoutnick"           => convert::ToInt($get_config['l_shoutnick']),
                                             "m_awards"              => convert::ToInt($get_config['m_awards']),
                                             "f_cwcom"               => convert::ToInt($get_config['f_cwcom']),
                                             "regcode"               => convert::ToInt($get_settings['regcode']),
                                             "m_gallery_user"        => convert::ToInt($get_config['gallery']),
                                             "m_gallery"             => convert::ToInt($get_config['m_gallery']),
                                             "m_lnews"               => convert::ToInt($get_config['m_lnews']),
                                             "m_lartikel"            => convert::ToInt($get_config['m_lartikel']),
                                             "m_ftopics"             => convert::ToInt($get_config['m_ftopics']),
                                             "m_lwars"               => convert::ToInt($get_config['m_lwars']),
                                             "m_nwars"               => convert::ToInt($get_config['m_nwars']),
                                             "m_events"              => convert::ToInt($get_config['m_events']),
                                             "m_topdl"               => convert::ToInt($get_config['m_topdl']),
                                             "m_usergb"              => convert::ToInt($get_config['m_usergb']),
                                             "m_clankasse"           => convert::ToInt($get_config['m_clankasse']),
                                             "m_userlist"            => convert::ToInt($get_config['m_userlist']),
                                             "m_adminnews"           => convert::ToInt($get_config['m_adminnews']),
                                             "m_shout"               => convert::ToInt($get_config['m_shout']),
                                             "m_shouta"              => convert::ToInt($get_config['maxshoutarchiv']),
                                             "zeichen"               => convert::ToInt($get_config['shout_max_zeichen']),
                                             "m_comments"            => convert::ToInt($get_config['m_comments']),
                                             "m_cwcomments"          => convert::ToInt($get_config['m_cwcomments']),
                                             "m_archivnews"          => convert::ToInt($get_config['m_archivnews']),
                                             "m_gb"                  => convert::ToInt($get_config['m_gb']),
                                             "m_fthreads"            => convert::ToInt($get_config['m_fthreads']),
                                             "m_fposts"              => convert::ToInt($get_config['m_fposts']),
                                             "m_clanwars"            => convert::ToInt($get_config['m_clanwars']),
                                             "m_news"                => convert::ToInt($get_config['m_news']),
                                             "m_gallerypics"         => convert::ToInt($get_config['m_gallerypics']),
                                             "m_upicsize"            => convert::ToInt($get_config['upicsize']),
                                             "f_forum"               => convert::ToInt($get_config['f_forum']),
                                             "f_gb"                  => convert::ToInt($get_config['f_gb']),
                                             "f_membergb"            => convert::ToInt($get_config['f_membergb']),
                                             "f_shout"               => convert::ToInt($get_config['f_shout']),
                                             "f_newscom"             => convert::ToInt($get_config['f_newscom']),
                                             "f_downloadcom"         => convert::ToInt($get_config['f_downloadcom']),
                                             "m_artikel"             => convert::ToInt($get_config['m_artikel']),
                                             "m_adminartikel"        => convert::ToInt($get_config['m_adminartikel']),
                                             "m_away"                => convert::ToInt($get_config['m_away']),
                                             "c_wmodus"              => convert::ToInt($get_settings['wmodus']),
                                             "l_clanwars"            => convert::ToInt($get_config['l_clanwars']),
                                             "l_newsadmin"           => convert::ToInt($get_config['l_newsadmin']),
                                             "l_shouttext"           => convert::ToInt($get_config['l_shouttext']),
                                             "l_newsarchiv"          => convert::ToInt($get_config['l_newsarchiv']),
                                             "l_forumtopic"          => convert::ToInt($get_config['l_forumtopic']),
                                             "l_forumsubtopic"       => convert::ToInt($get_config['l_forumsubtopic']),
                                             "l_topdl"               => convert::ToInt($get_config['l_topdl']),
                                             "l_ftopics"             => convert::ToInt($get_config['l_ftopics']),
                                             "l_lnews"               => convert::ToInt($get_config['l_lnews']),
                                             "l_lartikel"            => convert::ToInt($get_config['l_lartikel']),
                                             "l_lwars"               => convert::ToInt($get_config['l_lwars']),
                                             "l_nwars"               => convert::ToInt($get_config['l_nwars']),
                                             "c_teamrow"             => convert::ToInt($get_config['teamrow']),
                                             "f_artikelcom"          => convert::ToInt($get_config['f_artikelcom']),
                                             "clanname"              => string::decode($get_settings['clanname']),
                                             "pagetitel"             => string::decode($get_settings['pagetitel']),
                                             "smtp_host"             => string::decode($get_settings['smtp_hostname']),
                                             "smtp_username"         => string::decode($get_settings['smtp_username']),
                                             "smtp_pass"             => decryptData($get_settings['smtp_password']),
                                             "smtp_port"             => convert::ToInt($get_settings['smtp_port']),
                                             "sendmail_path"         => string::decode($get_settings['sendmail_path']),
                                             "smtp_tls_ssl"          => $smtp_secure_options,
                                             "lang"                  => $lang,
                                             "mail_ext_select"        => mailmgr::get_menu($get_settings['mail_extension']),
                                             "selyes"                => ($get_settings['regcode'] ? 'selected="selected"' : ''),
                                             "selno"                 => (!$get_settings['regcode'] ? 'selected="selected"' : ''),
                                             "selwm"                 => ($get_settings['wmodus'] ? 'selected="selected"' : ''),
                                             "sel_fv"                => ($get_settings['forum_vote'] ? 'selected="selected"' : ''),
                                             "sel_sl"                => ($get_config['securelogin'] ? 'selected="selected"' : ''),
                                             "sel_dp"                => ($get_settings['double_post'] ? 'selected="selected"' : ''),
                                             "sel_gba"               => ($get_settings['gb_activ'] ? 'selected="selected"' : ''),
                                             "selh_all"              => ($get_config['allowhover'] == 1 ? 'selected="selected"' : ''),
                                             "selh_cw"               => ($get_config['allowhover'] == 2 ? 'selected="selected"' : ''),
                                             "selr_nc"               => ($get_settings['reg_newscomments'] ? 'selected="selected"' : ''),
                                             "selr_forum"            => ($get_settings['reg_forum'] ? 'selected="selected"' : ''),
                                             "selr_dl"               => ($get_settings['reg_dl'] ? 'selected="selected"' : ''),
                                             "selr_artikel"          => ($get_settings['reg_artikel'] ? 'selected="selected"' : ''),
                                             "selr_dc"               => ($get_settings['reg_dlcomments'] ? 'selected="selected"' : ''),
                                             "sel_url"               => ($get_settings['urls_linked'] ? 'selected="selected"' : ''),
                                             "sel_akl"               => (!$get_config['use_akl'] ? 'selected="selected"' : ''),
                                             "selr_shout"            => ($get_settings['reg_shout'] ? 'selected="selected"' : ''),
                                             "selpi"                 => (!$get_settings['persinfo'] ? 'selected="selected"' : ''),
                                             "selfeed"               => ($get_config['news_feed'] ? 'selected="selected"' : ''),
                                             "selr_cwc"              => ($get_settings['reg_cwcomments'] ? 'selected="selected"' : ''),
                                             "sel_refresh"           => ($get_config['direct_refresh'] ? ' selected="selected"' : ''),
                                             "sel_gm"                => ($get_settings['gmaps_who'] ? 'selected="selected"' : ''),
                                             "pwde_options"          => $pwde_options));
    unset($get_settings,$get_config);
    $show = show($dir."/form", array("head" => _config_global_head, "what" => "config", "value" => _button_value_config, "show" => $show));
}