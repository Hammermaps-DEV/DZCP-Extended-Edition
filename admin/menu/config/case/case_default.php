<?php
/**
 * <DZCP-Extended Edition>
 * @package: DZCP-Extended Edition
 * @author: DZCP Developer Team || Hammermaps.de Developer Team
 * @link: http://www.dzcp.de || http://www.hammermaps.de
 */

if(_adminMenu != 'true') exit();

$files = get_files(basePath.'/inc/lang/languages/',false,true,array('php')); $lang = '';
foreach($files as $file)
{
    $lng = preg_replace("#.php#", "",$file);
    $sel = (string::decode(settings::get('language')) == $lng ? 'selected="selected"' : '');
    $lang .= show(_select_field, array("value" => $lng, "what" => $lng, "sel" => $sel));
}
unset($files,$file,$lng,$sel);

$tmps = get_files(basePath.'/inc/_templates_/',true); $tmplsel = '';
foreach($tmps as $tmp)
{
    $selt = (string::decode(settings::get('tmpdir')) == $tmp ? 'selected="selected"' : '');
    $tmplsel .= show(_select_field, array("value" => $tmp, "what" => $tmp, "sel" => $selt));
}
unset($tmps,$tmp,$selt);

$pwde_options = show('<option '.(!settings::get('default_pwd_encoder') ? 'selected="selected"' : '').' value="0">MD5 [lang_pwd_encoder_algorithm]</option>
<option '.(settings::get('default_pwd_encoder') == 1 ? 'selected="selected"' : '').' value="1">SHA1 [lang_pwd_encoder_algorithm]</option>
<option '.(settings::get('default_pwd_encoder') == 2 ? 'selected="selected"' : '').' value="2">SHA256 [lang_pwd_encoder_algorithm]</option>
<option '.(settings::get('default_pwd_encoder') == 3 ? 'selected="selected"' : '').' value="3">SHA512 [lang_pwd_encoder_algorithm]</option>');

$smtp_secure_options = show('<option '.(!settings::get('smtp_tls_ssl') ? 'selected="selected"' : '').' value="0">[lang_default]</option>
<option '.(settings::get('smtp_tls_ssl') == 1 ? 'selected="selected"' : '').' value="1">TLS</option>
<option '.(settings::get('smtp_tls_ssl') == 2 ? 'selected="selected"' : '').' value="2">SSL</option>');

$show = show($dir."/form_config", array( "cache_select"          => Cache::GetConfigMenu(),
                                         "main_info"             => _main_info,
                                         "cache_info"            => _config_cache_info,
                                         "badword_info"          => _admin_config_badword_info,
                                         "eml_info"              => _admin_eml_info,
                                         "reg_info"              => _admin_reg_info,
                                         "c_limits_what"         => _config_c_limits_what,
                                         "c_floods_what"         => _config_c_floods_what,
                                         "c_length_what"         => _config_c_length_what,
                                         "cache_teamspeak"       => convert::ToInt(settings::get('cache_teamspeak')),
                                         "cache_server"          => convert::ToInt(settings::get('cache_server')),
                                         "cache_news"            => convert::ToInt(settings::get('cache_news')),
                                         "c_eml_reg_subj"        => string::decode(settings::get('eml_reg_subj')),
                                         "c_eml_pwd_subj"        => string::decode(settings::get('eml_pwd_subj')),
                                         "c_eml_nletter_subj"    => string::decode(settings::get('eml_nletter_subj')),
                                         "c_eml_pn_subj"         => string::decode(settings::get('eml_pn_subj')),
                                         "c_eml_fabo_npost_subj" => string::decode(settings::get('eml_fabo_npost_subj')),
                                         "c_eml_fabo_tedit_subj" => string::decode(settings::get('eml_fabo_tedit_subj')),
                                         "c_eml_fabo_pedit_subj" => string::decode(settings::get('eml_fabo_pedit_subj')),
                                         "c_eml_akl_regist_subj" => string::decode(settings::get('eml_akl_register_subj')),
                                         "c_eml_reg"             => string::decode(settings::get('eml_reg')),
                                         "c_eml_pwd"             => string::decode(settings::get('eml_pwd')),
                                         "c_eml_nletter"         => string::decode(settings::get('eml_nletter')),
                                         "c_eml_pn"              => string::decode(settings::get('eml_pn')),
                                         "c_eml_fabo_tedit"      => string::decode(settings::get('eml_fabo_tedit')),
                                         "c_eml_fabo_pedit"      => string::decode(settings::get('eml_fabo_pedit')),
                                         "c_eml_fabo_nposr"      => string::decode(settings::get('eml_fabo_npost')),
                                         "c_eml_akl_regist"      => string::decode(settings::get('eml_akl_register')),
                                         "memcache_host"         => string::decode(settings::get('memcache_host')),
                                         "memcache_port"         => convert::ToInt(settings::get('memcache_port')),
                                         "steam_apikey"          => string::decode(settings::get('steam_api_key')),
                                         "tmplsel"               => $tmplsel,
                                         "maxwidth"              => convert::ToInt(settings::get('maxwidth')),
                                         "l_servernavi"          => convert::ToInt(settings::get('l_servernavi')),
                                         "mailfrom"              => string::decode(settings::get('mailfrom')),
                                         "l_lreg"                => convert::ToInt(settings::get('l_lreg')),
                                         "m_lreg"                => convert::ToInt(settings::get('m_lreg')),
                                         "badwords"              => string::decode(settings::get('badwords')),
                                         "l_shoutnick"           => convert::ToInt(settings::get('l_shoutnick')),
                                         "m_awards"              => convert::ToInt(settings::get('m_awards')),
                                         "f_cwcom"               => convert::ToInt(settings::get('f_cwcom')),
                                         "regcode"               => convert::ToInt(settings::get('regcode')),
                                         "m_gallery_user"        => convert::ToInt(settings::get('gallery')),
                                         "m_gallery"             => convert::ToInt(settings::get('m_gallery')),
                                         "m_lnews"               => convert::ToInt(settings::get('m_lnews')),
                                         "m_lartikel"            => convert::ToInt(settings::get('m_lartikel')),
                                         "m_ftopics"             => convert::ToInt(settings::get('m_ftopics')),
                                         "m_lwars"               => convert::ToInt(settings::get('m_lwars')),
                                         "m_nwars"               => convert::ToInt(settings::get('m_nwars')),
                                         "m_events"              => convert::ToInt(settings::get('m_events')),
                                         "m_topdl"               => convert::ToInt(settings::get('m_topdl')),
                                         "m_usergb"              => convert::ToInt(settings::get('m_usergb')),
                                         "m_clankasse"           => convert::ToInt(settings::get('m_clankasse')),
                                         "m_userlist"            => convert::ToInt(settings::get('m_userlist')),
                                         "m_adminnews"           => convert::ToInt(settings::get('m_adminnews')),
                                         "m_shout"               => convert::ToInt(settings::get('m_shout')),
                                         "m_shouta"              => convert::ToInt(settings::get('maxshoutarchiv')),
                                         "zeichen"               => convert::ToInt(settings::get('shout_max_zeichen')),
                                         "m_comments"            => convert::ToInt(settings::get('m_comments')),
                                         "m_cwcomments"          => convert::ToInt(settings::get('m_cwcomments')),
                                         "m_archivnews"          => convert::ToInt(settings::get('m_archivnews')),
                                         "m_gb"                  => convert::ToInt(settings::get('m_gb')),
                                         "m_fthreads"            => convert::ToInt(settings::get('m_fthreads')),
                                         "m_fposts"              => convert::ToInt(settings::get('m_fposts')),
                                         "m_clanwars"            => convert::ToInt(settings::get('m_clanwars')),
                                         "m_news"                => convert::ToInt(settings::get('m_news')),
                                         "m_gallerypics"         => convert::ToInt(settings::get('m_gallerypics')),
                                         "m_upicsize"            => convert::ToInt(settings::get('upicsize')),
                                         "f_forum"               => convert::ToInt(settings::get('f_forum')),
                                         "f_gb"                  => convert::ToInt(settings::get('f_gb')),
                                         "f_membergb"            => convert::ToInt(settings::get('f_membergb')),
                                         "f_shout"               => convert::ToInt(settings::get('f_shout')),
                                         "f_newscom"             => convert::ToInt(settings::get('f_newscom')),
                                         "f_downloadcom"         => convert::ToInt(settings::get('f_downloadcom')),
                                         "m_artikel"             => convert::ToInt(settings::get('m_artikel')),
                                         "m_adminartikel"        => convert::ToInt(settings::get('m_adminartikel')),
                                         "m_away"                => convert::ToInt(settings::get('m_away')),
                                         "c_wmodus"              => convert::ToInt(settings::get('wmodus')),
                                         "l_clanwars"            => convert::ToInt(settings::get('l_clanwars')),
                                         "l_newsadmin"           => convert::ToInt(settings::get('l_newsadmin')),
                                         "l_shouttext"           => convert::ToInt(settings::get('l_shouttext')),
                                         "l_newsarchiv"          => convert::ToInt(settings::get('l_newsarchiv')),
                                         "l_forumtopic"          => convert::ToInt(settings::get('l_forumtopic')),
                                         "l_forumsubtopic"       => convert::ToInt(settings::get('l_forumsubtopic')),
                                         "l_topdl"               => convert::ToInt(settings::get('l_topdl')),
                                         "l_ftopics"             => convert::ToInt(settings::get('l_ftopics')),
                                         "l_lnews"               => convert::ToInt(settings::get('l_lnews')),
                                         "l_lartikel"            => convert::ToInt(settings::get('l_lartikel')),
                                         "l_lwars"               => convert::ToInt(settings::get('l_lwars')),
                                         "l_nwars"               => convert::ToInt(settings::get('l_nwars')),
                                         "c_teamrow"             => convert::ToInt(settings::get('teamrow')),
                                         "f_artikelcom"          => convert::ToInt(settings::get('f_artikelcom')),
                                         "clanname"              => string::decode(settings::get('clanname')),
                                         "pagetitel"             => string::decode(settings::get('pagetitel')),
                                         "smtp_host"             => string::decode(settings::get('smtp_hostname')),
                                         "smtp_username"         => string::decode(settings::get('smtp_username')),
                                         "smtp_pass"             => decryptData(settings::get('smtp_password')),
                                         "smtp_port"             => convert::ToInt(settings::get('smtp_port')),
                                         "sendmail_path"         => string::decode(settings::get('sendmail_path')),
                                         "smtp_tls_ssl"          => $smtp_secure_options,
                                         "lang"                  => $lang,
                                         "mail_ext_select"        => mailmgr::get_menu(settings::get('mail_extension')),
                                         "selyes"                => (settings::get('regcode') ? 'selected="selected"' : ''),
                                         "selno"                 => (!settings::get('regcode') ? 'selected="selected"' : ''),
                                         "selwm"                 => (settings::get('wmodus') ? 'selected="selected"' : ''),
                                         "sel_fv"                => (settings::get('forum_vote') ? 'selected="selected"' : ''),
                                         "sel_sl"                => (settings::get('securelogin') ? 'selected="selected"' : ''),
                                         "sel_dp"                => (settings::get('double_post') ? 'selected="selected"' : ''),
                                         "sel_gba"               => (settings::get('gb_activ') ? 'selected="selected"' : ''),
                                         "selh_all"              => (settings::get('allowhover') == 1 ? 'selected="selected"' : ''),
                                         "selh_cw"               => (settings::get('allowhover') == 2 ? 'selected="selected"' : ''),
                                         "selr_nc"               => (settings::get('reg_newscomments') ? 'selected="selected"' : ''),
                                         "selr_forum"            => (settings::get('reg_forum') ? 'selected="selected"' : ''),
                                         "selr_dl"               => (settings::get('reg_dl') ? 'selected="selected"' : ''),
                                         "selr_artikel"          => (settings::get('reg_artikel') ? 'selected="selected"' : ''),
                                         "selr_dc"               => (settings::get('reg_dlcomments') ? 'selected="selected"' : ''),
                                         "sel_url"               => (settings::get('urls_linked') ? 'selected="selected"' : ''),
                                         "sel_akl"               => (!settings::get('use_akl') ? 'selected="selected"' : ''),
                                         "selr_shout"            => (settings::get('reg_shout') ? 'selected="selected"' : ''),
                                         "selpi"                 => (!settings::get('persinfo') ? 'selected="selected"' : ''),
                                         "selfeed"               => (settings::get('news_feed') ? 'selected="selected"' : ''),
                                         "selr_cwc"              => (settings::get('reg_cwcomments') ? 'selected="selected"' : ''),
                                         "sel_refresh"           => (settings::get('direct_refresh') ? ' selected="selected"' : ''),
                                         "sel_gm"                => (settings::get('gmaps_who') ? 'selected="selected"' : ''),
                                         "pwde_options"          => $pwde_options));
unset($get_settings,$get_config);
$show = show($dir."/form", array("head" => _config_global_head, "what" => "config", "value" => _button_value_config, "show" => $show));