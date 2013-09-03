<?php
/**
 * <DZCP-Extended Edition>
 * @package: DZCP-Extended Edition
 * @author: DZCP Developer Team || Hammermaps.de Developer Team
 * @link: http://www.dzcp.de || http://www.hammermaps.de
 */

if(_adminMenu != 'true') exit();

if(isset($_POST))
{
    if(convert::ToString($_POST['cache_engine']) != $cache_engine)
        $cache_cleanup = true;

        //UPDATE
        if(settings::changed(($key='upicsize'),($var=convert::ToInt($_POST['m_upicsize'])))) settings::set($key,$var);
        if(settings::changed(($key='m_gallery'),($var=convert::ToInt($_POST['m_gallery'])))) settings::set($key,$var);
        if(settings::changed(($key='m_gallerypics'),($var=convert::ToInt($_POST['m_gallerypics'])))) settings::set($key,$var);
        if(settings::changed(($key='m_usergb'),($var=convert::ToInt($_POST['m_usergb'])))) settings::set($key,$var);
        if(settings::changed(($key='m_artikel'),($var=convert::ToInt($_POST['m_artikel'])))) settings::set($key,$var);
        if(settings::changed(($key='m_adminartikel'),($var=convert::ToInt($_POST['m_adminartikel'])))) settings::set($key,$var);
        if(settings::changed(($key='m_clanwars'),($var=convert::ToInt($_POST['m_clanwars'])))) settings::set($key,$var);
        if(settings::changed(($key='m_awards'),($var=convert::ToInt($_POST['m_awards'])))) settings::set($key,$var);
        if(settings::changed(($key='allowhover'),($var=convert::ToInt($_POST['ahover'])))) settings::set($key,$var);
        if(settings::changed(($key='securelogin'),($var=convert::ToInt($_POST['securelogin'])))) settings::set($key,$var);
        if(settings::changed(($key='m_clankasse'),($var=convert::ToInt($_POST['m_clankasse'])))) settings::set($key,$var);
        if(settings::changed(($key='m_userlist'),($var=convert::ToInt($_POST['m_userlist'])))) settings::set($key,$var);
        if(settings::changed(($key='m_adminnews'),($var=convert::ToInt($_POST['m_adminnews'])))) settings::set($key,$var);
        if(settings::changed(($key='l_servernavi'),($var=convert::ToInt($_POST['l_servernavi'])))) settings::set($key,$var);
        if(settings::changed(($key='l_shoutnick'),($var=convert::ToInt($_POST['l_shoutnick'])))) settings::set($key,$var);
        if(settings::changed(($key='m_gb'),($var=convert::ToInt($_POST['m_gb'])))) settings::set($key,$var);
        if(settings::changed(($key='m_fthreads'),($var=convert::ToInt($_POST['m_fthreads'])))) settings::set($key,$var);
        if(settings::changed(($key='m_fposts'),($var=convert::ToInt($_POST['m_fposts'])))) settings::set($key,$var);
        if(settings::changed(($key='gallery'),($var=convert::ToInt($_POST['m_gallery_user'])))) settings::set($key,$var);
        if(settings::changed(($key='m_news'),($var=convert::ToInt($_POST['m_news'])))) settings::set($key,$var);
        if(settings::changed(($key='m_shout'),($var=convert::ToInt($_POST['m_shout'])))) settings::set($key,$var);
        if(settings::changed(($key='m_comments'),($var=convert::ToInt($_POST['m_comments'])))) settings::set($key,$var);
        if(settings::changed(($key='m_archivnews'),($var=convert::ToInt($_POST['m_archivnews'])))) settings::set($key,$var);
        if(settings::changed(($key='maxwidth'),($var=convert::ToInt($_POST['maxwidth'])))) settings::set($key,$var);
        if(settings::changed(($key='f_forum'),($var=convert::ToInt($_POST['f_forum'])))) settings::set($key,$var);
        if(settings::changed(($key='f_cwcom'),($var=convert::ToInt($_POST['f_cwcom'])))) settings::set($key,$var);
        if(settings::changed(($key='f_gb'),($var=convert::ToInt($_POST['f_gb'])))) settings::set($key,$var);
        if(settings::changed(($key='f_artikelcom'),($var=convert::ToInt($_POST['f_artikelcom'])))) settings::set($key,$var);
        if(settings::changed(($key='f_membergb'),($var=convert::ToInt($_POST['f_membergb'])))) settings::set($key,$var);
        if(settings::changed(($key='f_shout'),($var=convert::ToInt($_POST['f_shout'])))) settings::set($key,$var);
        if(settings::changed(($key='f_newscom'),($var=convert::ToInt($_POST['f_newscom'])))) settings::set($key,$var);
        if(settings::changed(($key='f_downloadcom'),($var=convert::ToInt($_POST['f_downloadcom'])))) settings::set($key,$var);
        if(settings::changed(($key='l_newsadmin'),($var=convert::ToInt($_POST['l_newsadmin'])))) settings::set($key,$var);
        if(settings::changed(($key='l_shouttext'),($var=convert::ToInt($_POST['l_shouttext'])))) settings::set($key,$var);
        if(settings::changed(($key='l_newsarchiv'),($var=convert::ToInt($_POST['l_newsarchiv'])))) settings::set($key,$var);
        if(settings::changed(($key='l_forumtopic'),($var=convert::ToInt($_POST['l_forumtopic'])))) settings::set($key,$var);
        if(settings::changed(($key='l_forumsubtopic'),($var=convert::ToInt($_POST['l_forumsubtopic'])))) settings::set($key,$var);
        if(settings::changed(($key='l_clanwars'),($var=convert::ToInt($_POST['l_clanwars'])))) settings::set($key,$var);
        if(settings::changed(($key='m_lnews'),($var=convert::ToInt($_POST['m_lnews'])))) settings::set($key,$var);
        if(settings::changed(($key='m_lartikel'),($var=convert::ToInt($_POST['m_lartikel'])))) settings::set($key,$var);
        if(settings::changed(($key='m_events'),($var=convert::ToInt($_POST['m_events'])))) settings::set($key,$var);
        if(settings::changed(($key='m_topdl'),($var=convert::ToInt($_POST['m_topdl'])))) settings::set($key,$var);
        if(settings::changed(($key='m_ftopics'),($var=convert::ToInt($_POST['m_ftopics'])))) settings::set($key,$var);
        if(settings::changed(($key='m_cwcomments'),($var=convert::ToInt($_POST['m_cwcomments'])))) settings::set($key,$var);
        if(settings::changed(($key='m_lwars'),($var=convert::ToInt($_POST['m_lwars'])))) settings::set($key,$var);
        if(settings::changed(($key='m_lreg'),($var=convert::ToInt($_POST['m_lreg'])))) settings::set($key,$var);
        if(settings::changed(($key='m_nwars'),($var=convert::ToInt($_POST['m_nwars'])))) settings::set($key,$var);
        if(settings::changed(($key='l_topdl'),($var=convert::ToInt($_POST['l_topdl'])))) settings::set($key,$var);
        if(settings::changed(($key='l_ftopics'),($var=convert::ToInt($_POST['l_ftopics'])))) settings::set($key,$var);
        if(settings::changed(($key='l_lreg'),($var=convert::ToInt($_POST['l_lreg'])))) settings::set($key,$var);
        if(settings::changed(($key='l_lnews'),($var=convert::ToInt($_POST['l_lnews'])))) settings::set($key,$var);
        if(settings::changed(($key='l_lartikel'),($var=convert::ToInt($_POST['l_lartikel'])))) settings::set($key,$var);
        if(settings::changed(($key='l_lwars'),($var=convert::ToInt($_POST['l_lwars'])))) settings::set($key,$var);
        if(settings::changed(($key='teamrow'),($var=convert::ToInt($_POST['teamrow'])))) settings::set($key,$var);
        if(settings::changed(($key='shout_max_zeichen'),($var=convert::ToInt($_POST['zeichen'])))) settings::set($key,$var);
        if(settings::changed(($key='maxshoutarchiv'),($var=convert::ToInt($_POST['m_shouta'])))) settings::set($key,$var);
        if(settings::changed(($key='m_away'),($var=convert::ToInt($_POST['m_away'])))) settings::set($key,$var);
        if(settings::changed(($key='direct_refresh'),($var=convert::ToInt($_POST['direct_refresh'])))) settings::set($key,$var);
        if(settings::changed(($key='cache_teamspeak'),($var=convert::ToInt($_POST['cache_teamspeak'])))) settings::set($key,$var);
        if(settings::changed(($key='cache_server'),($var=convert::ToInt($_POST['cache_server'])))) settings::set($key,$var);
        if(settings::changed(($key='cache_news'),($var=convert::ToInt($_POST['cache_news'])))) settings::set($key,$var);
        if(settings::changed(($key='cache_engine'),($var=string::encode($_POST['cache_engine'])))) settings::set($key,$var);
        if(settings::changed(($key='l_nwars'),($var=convert::ToInt($_POST['l_nwars'])))) settings::set($key,$var);
        if(settings::changed(($key='news_feed'),($var=convert::ToInt($_POST['feed'])))) settings::set($key,$var);
        if(settings::changed(($key='use_akl'),($var=convert::ToInt($_POST['akl'])))) settings::set($key,$var);
        if(settings::changed(($key='clanname'),($var=string::encode($_POST['clanname'])))) settings::set($key,$var);
        if(settings::changed(($key='pagetitel'),($var=string::encode($_POST['pagetitel'])))) settings::set($key,$var);
        if(settings::changed(($key='badwords'),($var=string::encode($_POST['badwords'])))) settings::set($key,$var);
        if(settings::changed(($key='gmaps_who'),($var=convert::ToInt($_POST['gmaps_who'])))) settings::set($key,$var);
        if(settings::changed(($key='language'),($var=string::encode($_POST['language'])))) settings::set($key,$var);
        if(settings::changed(($key='regcode'),($var=convert::ToInt($_POST['regcode'])))) settings::set($key,$var);
        if(settings::changed(($key='forum_vote'),($var=convert::ToInt($_POST['forum_vote'])))) settings::set($key,$var);
        if(settings::changed(($key='reg_forum'),($var=convert::ToInt($_POST['reg_forum'])))) settings::set($key,$var);
        if(settings::changed(($key='reg_artikel'),($var=convert::ToInt($_POST['reg_artikel'])))) settings::set($key,$var);
        if(settings::changed(($key='reg_shout'),($var=convert::ToInt($_POST['reg_shout'])))) settings::set($key,$var);
        if(settings::changed(($key='reg_cwcomments'),($var=convert::ToInt($_POST['reg_cwcomments'])))) settings::set($key,$var);
        if(settings::changed(($key='reg_newscomments'),($var=convert::ToInt($_POST['reg_nc'])))) settings::set($key,$var);
        if(settings::changed(($key='reg_dl'),($var=convert::ToInt($_POST['reg_dl'])))) settings::set($key,$var);
        if(settings::changed(($key='reg_dlcomments'),($var=convert::ToInt($_POST['reg_dlcomments'])))) settings::set($key,$var);
        if(settings::changed(($key='eml_reg_subj'),($var=string::encode($_POST['eml_reg_subj'])))) settings::set($key,$var);
        if(settings::changed(($key='eml_pwd_subj'),($var=string::encode($_POST['eml_pwd_subj'])))) settings::set($key,$var);
        if(settings::changed(($key='eml_nletter_subj'),($var=string::encode($_POST['eml_nletter_subj'])))) settings::set($key,$var);
        if(settings::changed(($key='eml_pn_subj'),($var=string::encode($_POST['eml_pn_subj'])))) settings::set($key,$var);
        if(settings::changed(($key='double_post'),($var=convert::ToInt($_POST['double_post'])))) settings::set($key,$var);
        if(settings::changed(($key='gb_activ'),($var=convert::ToInt($_POST['gb_activ'])))) settings::set($key,$var);
        if(settings::changed(($key='eml_fabo_npost_subj'),($var=string::encode($_POST['eml_fabo_npost_subj'])))) settings::set($key,$var);
        if(settings::changed(($key='eml_fabo_tedit_subj'),($var=string::encode($_POST['eml_fabo_tedit_subj'])))) settings::set($key,$var);
        if(settings::changed(($key='eml_fabo_pedit_subj'),($var=string::encode($_POST['eml_fabo_pedit_subj'])))) settings::set($key,$var);
        if(settings::changed(($key='eml_akl_register_subj'),($var=string::encode($_POST['eml_akl_regist_subj'])))) settings::set($key,$var);
        if(settings::changed(($key='eml_reg'),($var=string::encode($_POST['eml_reg'])))) settings::set($key,$var);
        if(settings::changed(($key='eml_pwd'),($var=string::encode($_POST['eml_pwd'])))) settings::set($key,$var);
        if(settings::changed(($key='eml_nletter'),($var=string::encode($_POST['eml_nletter'])))) settings::set($key,$var);
        if(settings::changed(($key='eml_pn'),($var=string::encode($_POST['eml_pwd'])))) settings::set($key,$var);
        if(settings::changed(($key='eml_fabo_npost'),($var=string::encode($_POST['eml_fabo_npost'])))) settings::set($key,$var);
        if(settings::changed(($key='eml_fabo_tedit'),($var=string::encode($_POST['eml_fabo_tedit'])))) settings::set($key,$var);
        if(settings::changed(($key='eml_fabo_pedit'),($var=string::encode($_POST['eml_fabo_pedit'])))) settings::set($key,$var);
        if(settings::changed(($key='eml_akl_register'),($var=string::encode($_POST['eml_akl_regist'])))) settings::set($key,$var);
        if(settings::changed(($key='mailfrom'),($var=string::encode($_POST['mailfrom'])))) settings::set($key,$var);
        if(settings::changed(($key='tmpdir'),($var=string::encode($_POST['tmpdir'])))) settings::set($key,$var);
        if(settings::changed(($key='persinfo'),($var=convert::ToInt($_POST['persinfo'])))) settings::set($key,$var);
        if(settings::changed(($key='wmodus'),($var=convert::ToInt($_POST['wmodus'])))) settings::set($key,$var);
        if(settings::changed(($key='mail_extension'),($var=string::encode($_POST['mail_extension'])))) settings::set($key,$var);
        if(settings::changed(($key='smtp_password'),($var=encryptData($_POST['smtp_pass'])))) settings::set($key,$var);
        if(settings::changed(($key='smtp_port'),($var=convert::ToInt($_POST['smtp_port'])))) settings::set($key,$var);
        if(settings::changed(($key='smtp_hostname'),($var=string::encode($_POST['smtp_host'])))) settings::set($key,$var);
        if(settings::changed(($key='smtp_username'),($var=string::encode($_POST['smtp_username'])))) settings::set($key,$var);
        if(settings::changed(($key='smtp_tls_ssl'),($var=convert::ToInt($_POST['smtp_tls_ssl'])))) settings::set($key,$var);
        if(settings::changed(($key='sendmail_path'),($var=string::encode($_POST['sendmail_path'])))) settings::set($key,$var);
        if(settings::changed(($key='memcache_host'),($var=string::encode($_POST['memcache_host'])))) settings::set($key,$var);
        if(settings::changed(($key='memcache_port'),($var=convert::ToInt($_POST['memcache_port'])))) settings::set($key,$var);
        if(settings::changed(($key='urls_linked'),($var=string::encode($_POST['urls_linked'])))) settings::set($key,$var);

        $show = info(_config_set, "?admin=config", 6);
}