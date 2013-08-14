<?php
/**
 * <DZCP-Extended Edition>
 * @package: DZCP-Extended Edition
 * @author: DZCP Developer Team || Hammermaps.de Developer Team
 * @link: http://www.dzcp.de || http://www.hammermaps.de
 */

if(_adminMenu != 'true') exit();

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