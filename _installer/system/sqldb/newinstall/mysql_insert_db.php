<?php
//===============================================================
//Insert DZCP-Database MySQL Installer
//===============================================================

function install_mysql_insert($db_infos)
{
    //===============================================================
    //-> Downloadkategorien =========================================
    //===============================================================
    db("INSERT INTO ".dba::get('dl_kat')." SET `name` = 'Downloads';",false,false,true);
    db("INSERT INTO ".dba::get('dl_kat')." SET `name` = 'Demos';",false,false,true);
    db("INSERT INTO ".dba::get('dl_kat')." SET `name` = 'Stuff';",false,false,true);

    //===============================================================
    //-> Downloads ==================================================
    //===============================================================
    db("INSERT INTO ".dba::get('downloads')." SET `download` = 'Testdownload', `url` = 'http://www.url.de/test.zip', `beschreibung` = '<p>Das ist ein Testdownload</p>',
    `kat` = '1', `date` = '".time()."';",false,false,true);

    //===============================================================
    //-> Forum ======================================================
    //===============================================================
    db("INSERT INTO ".dba::get('f_kats')." SET `kid` = 1, `name` = 'Hauptforum';",false,false,true);
    db("INSERT INTO ".dba::get('f_kats')." SET `kid` = 2, `name` = 'OFFtopic';",false,false,true);
    db("INSERT INTO ".dba::get('f_kats')." SET `kid` = 3, `name` = 'Clanforum', `intern` = '1';",false,false,true);

    //===============================================================
    //-> Newskategorien =============================================
    //===============================================================
    db("INSERT INTO ".dba::get('newskat')." SET `katimg` = 'hp.jpg', `kategorie` = 'Homepage';",false,false,true);

    //===============================================================
    //-> Event ======================================================
    //===============================================================
    db("INSERT INTO ".dba::get('events')." SET `datum` = ".(time()+90000).", `title` = 'Testevent', `event` = '<p>Das ist nur ein Testevent! :)</p>';",false,false,true);

    //===============================================================
    //-> Settings ===================================================
    //===============================================================

    //E-Mail Templates
    db("INSERT INTO `".dba::get('settings')."` SET `key` = 'eml_akl_register', `value` = '".string::encode(emlv('eml_akl_register'))."', `default` = '".string::encode(emlv('eml_akl_register'))."', `length` = '0', `type` = 'string';",false,false,true);
    db("INSERT INTO `".dba::get('settings')."` SET `key` = 'eml_akl_register_subj', `value` = '".string::encode(emlv('eml_akl_register_subj'))."', `default` = '".string::encode(emlv('eml_akl_register_subj'))."', `length` = '0', `type` = 'string';",false,false,true);
    db("INSERT INTO `".dba::get('settings')."` SET `key` = 'eml_fabo_npost', `value` = '".string::encode(emlv('eml_fabo_npost'))."', `default` = '".string::encode(emlv('eml_fabo_npost'))."', `length` = '0', `type` = 'string';",false,false,true);
    db("INSERT INTO `".dba::get('settings')."` SET `key` = 'eml_fabo_npost_subj', `value` = '".string::encode(emlv('eml_fabo_npost_subj'))."', `default` = '".string::encode(emlv('eml_fabo_npost_subj'))."', `length` = '0', `type` = 'string';",false,false,true);
    db("INSERT INTO `".dba::get('settings')."` SET `key` = 'eml_fabo_pedit', `value` = '".string::encode(emlv('eml_fabo_pedit'))."', `default` = '".string::encode(emlv('eml_fabo_pedit'))."', `length` = '0', `type` = 'string';",false,false,true);
    db("INSERT INTO `".dba::get('settings')."` SET `key` = 'eml_fabo_pedit_subj', `value` = '".string::encode(emlv('eml_fabo_pedit_subj'))."', `default` = '".string::encode(emlv('eml_fabo_pedit_subj'))."', `length` = '0', `type` = 'string';",false,false,true);
    db("INSERT INTO `".dba::get('settings')."` SET `key` = 'eml_fabo_tedit', `value` = '".string::encode(emlv('eml_fabo_tedit'))."', `default` = '".string::encode(emlv('eml_fabo_tedit'))."', `length` = '0', `type` = 'string';",false,false,true);
    db("INSERT INTO `".dba::get('settings')."` SET `key` = 'eml_fabo_tedit_subj', `value` = '".string::encode(emlv('eml_fabo_tedit_subj'))."', `default` = '".string::encode(emlv('eml_fabo_tedit_subj'))."', `length` = '0', `type` = 'string';",false,false,true);
    db("INSERT INTO `".dba::get('settings')."` SET `key` = 'eml_nletter', `value` = '".string::encode(emlv('eml_nletter'))."', `default` = '".string::encode(emlv('eml_nletter'))."', `length` = '0', `type` = 'string';",false,false,true);
    db("INSERT INTO `".dba::get('settings')."` SET `key` = 'eml_nletter_subj', `value` = '".string::encode(emlv('eml_nletter_subj'))."', `default` = '".string::encode(emlv('eml_nletter_subj'))."', `length` = '0', `type` = 'string';",false,false,true);
    db("INSERT INTO `".dba::get('settings')."` SET `key` = 'eml_pn', `value` = '".string::encode(emlv('eml_pn'))."', `default` = '".string::encode(emlv('eml_pn'))."', `length` = '0', `type` = 'string';",false,false,true);
    db("INSERT INTO `".dba::get('settings')."` SET `key` = 'eml_pn_subj', `value` = '".string::encode(emlv('eml_pn_subj'))."', `default` = '".string::encode(emlv('eml_pn_subj'))."', `length` = '0', `type` = 'string';",false,false,true);
    db("INSERT INTO `".dba::get('settings')."` SET `key` = 'eml_pwd', `value` = '".string::encode(emlv('eml_pwd'))."', `default` = '".string::encode(emlv('eml_pwd'))."', `length` = '0', `type` = 'string';",false,false,true);
    db("INSERT INTO `".dba::get('settings')."` SET `key` = 'eml_pwd_subj', `value` = '".string::encode(emlv('eml_pwd_subj'))."', `default` = '".string::encode(emlv('eml_pwd_subj'))."', `length` = '0', `type` = 'string';",false,false,true);
    db("INSERT INTO `".dba::get('settings')."` SET `key` = 'eml_reg', `value` = '".string::encode(emlv('eml_reg'))."', `default` = '".string::encode(emlv('eml_reg'))."', `length` = '0', `type` = 'string';",false,false,true);
    db("INSERT INTO `".dba::get('settings')."` SET `key` = 'eml_reg_subj', `value` = '".string::encode(emlv('eml_reg_subj'))."', `default` = '".string::encode(emlv('eml_reg_subj'))."', `length` = '0', `type` = 'string';",false,false,true);

    //FTP Zugang
    db("INSERT INTO `".dba::get('settings')."` SET `key` = 'ftp_hostname', `value` = '".string::encode($_SESSION['ftp_host'])."', `default` = 'localhost', `length` = '100', `type` = 'string';",false,false,true);
    db("INSERT INTO `".dba::get('settings')."` SET `key` = 'ftp_password', `value` = '".(!empty($_SESSION['ftp_pwd']) ? encryptData($_SESSION['ftp_pwd']) : '')."', `default` = '', `length` = '100', `type` = 'string';",false,false,true);
    db("INSERT INTO `".dba::get('settings')."` SET `key` = 'ftp_path', `value` = '".string::encode($_SESSION['ftp_pfad'])."', `default` = '/', `length` = '200', `type` = 'string';",false,false,true);
    db("INSERT INTO `".dba::get('settings')."` SET `key` = 'ftp_username', `value` = '".string::encode($_SESSION['ftp_user'])."', `default` = '".string::encode($_SESSION['ftp_user'])."', `length` = '100', `type` = 'string';",false,false,true);

    //Config
    $set_cache = 'file'; //File * Standard *
    if(function_exists('zend_shm_cache_store')) $set_cache = 'shm'; //ZEND Server - Shared Memory Cache
    else if(function_exists('apc_store')) $set_cache = 'apc'; //Alternative PHP Cache * APC *
    else if(function_exists('zend_disk_cache_store')) $set_cache = 'zenddisk'; //ZEND Server - Disk Cache
    db("INSERT INTO `".dba::get('settings')."` SET `key` = 'cache_engine', `value` = '".$set_cache."', `default` = 'file', `length` = '20', `type` = 'string';",false,false,true);
    unset($set_cache);

    db("INSERT INTO `".dba::get('settings')."` SET `key` = 'securelogin', `value` = '".$db_infos['loginsec']."', `default` = '1', `length` = '1', `type` = 'int';",false,false,true);
    db("INSERT INTO `".dba::get('settings')."` SET `key` = 'allowhover', `value` = '1', `default` = '1', `length` = '1', `type` = 'int';",false,false,true);
    db("INSERT INTO `".dba::get('settings')."` SET `key` = 'badwords', `value` = 'arsch,Arsch,arschloch,Arschloch,hure,Hure', `default` = '', `length` = '0', `type` = 'string';",false,false,true);
    db("INSERT INTO `".dba::get('settings')."` SET `key` = 'bic', `value` = '', `default` = '', `length` = '100', `type` = 'string';",false,false,true);
    db("INSERT INTO `".dba::get('settings')."` SET `key` = 'cache_news', `value` = '5', `default` = '5', `length` = '5', `type` = 'int';",false,false,true);
    db("INSERT INTO `".dba::get('settings')."` SET `key` = 'cache_server', `value` = '30', `default` = '30', `length` = '5', `type` = 'int';",false,false,true);
    db("INSERT INTO `".dba::get('settings')."` SET `key` = 'cache_teamspeak', `value` = '30', `default` = '30', `length` = '5', `type` = 'int';",false,false,true);
    db("INSERT INTO `".dba::get('settings')."` SET `key` = 'clanname', `value` = '".string::encode($db_infos['clanname'])."', `default` = 'Dein Clanname hier!', `length` = '50', `type` = 'string';",false,false,true);
    db("INSERT INTO `".dba::get('settings')."` SET `key` = 'db_version', `value` = '1600', `default` = '1600', `length` = '8', `type` = 'int';",false,false,true);
    db("INSERT INTO `".dba::get('settings')."` SET `key` = 'default_pwd_encoder', `value` = '2', `default` = '2', `length` = '1', `type` = 'int';",false,false,true);
    db("INSERT INTO `".dba::get('settings')."` SET `key` = 'direct_refresh', `value` = '0', `default` = '0', `length` = '1', `type` = 'int';",false,false,true);
    db("INSERT INTO `".dba::get('settings')."` SET `key` = 'domain', `value` = '".$_SERVER['SERVER_ADDR']."', `default` = '127.0.0.1', `length` = '150', `type` = 'string';",false,false,true);
    db("INSERT INTO `".dba::get('settings')."` SET `key` = 'double_post', `value` = '1', `default` = '1', `length` = '1', `type` = 'int';",false,false,true);
    db("INSERT INTO `".dba::get('settings')."` SET `key` = 'forum_vote', `value` = '1', `default` = '1', `length` = '1', `type` = 'int';",false,false,true);
    db("INSERT INTO `".dba::get('settings')."` SET `key` = 'f_artikelcom', `value` = '20', `default` = '20', `length` = '5', `type` = 'int';",false,false,true);
    db("INSERT INTO `".dba::get('settings')."` SET `key` = 'f_cwcom', `value` = '20', `default` = '20', `length` = '5', `type` = 'int';",false,false,true);
    db("INSERT INTO `".dba::get('settings')."` SET `key` = 'f_downloadcom', `value` = '20', `default` = '20', `length` = '5', `type` = 'int';",false,false,true);
    db("INSERT INTO `".dba::get('settings')."` SET `key` = 'f_forum', `value` = '20', `default` = '20', `length` = '5', `type` = 'int';",false,false,true);
    db("INSERT INTO `".dba::get('settings')."` SET `key` = 'f_gb', `value` = '20', `default` = '20', `length` = '5', `type` = 'int';",false,false,true);
    db("INSERT INTO `".dba::get('settings')."` SET `key` = 'f_membergb', `value` = '20', `default` = '20', `length` = '5', `type` = 'int';",false,false,true);
    db("INSERT INTO `".dba::get('settings')."` SET `key` = 'f_newscom', `value` = '20', `default` = '20', `length` = '5', `type` = 'int';",false,false,true);
    db("INSERT INTO `".dba::get('settings')."` SET `key` = 'f_shout', `value` = '20', `default` = '20', `length` = '5', `type` = 'int';",false,false,true);
    db("INSERT INTO `".dba::get('settings')."` SET `key` = 'gallery', `value` = '4', `default` = '4', `length` = '5', `type` = 'int';",false,false,true);
    db("INSERT INTO `".dba::get('settings')."` SET `key` = 'gb_activ', `value` = '1', `default` = '1', `length` = '1', `type` = 'int';",false,false,true);
    db("INSERT INTO `".dba::get('settings')."` SET `key` = 'gmaps_who', `value` = '1', `default` = '1', `length` = '1', `type` = 'int';",false,false,true);
    db("INSERT INTO `".dba::get('settings')."` SET `key` = 'iban', `value` = '', `default` = '', `length` = '100', `type` = 'string';",false,false,true);
    db("INSERT INTO `".dba::get('settings')."` SET `key` = 'i_autor', `value` = '', `default` = '', `length` = '0', `type` = 'string';",false,false,true);
    db("INSERT INTO `".dba::get('settings')."` SET `key` = 'i_domain', `value` = '".$_SERVER['SERVER_NAME']."', `default` = 'www.deineUrl.de', `length` = '80', `type` = 'string';",false,false,true);
    db("INSERT INTO `".dba::get('settings')."` SET `key` = 'k_bank', `value` = 'Musterbank', `default` = 'Musterbank', `length` = '200', `type` = 'string';",false,false,true);
    db("INSERT INTO `".dba::get('settings')."` SET `key` = 'k_blz', `value` = '123456789', `default` = '123456789', `length` = '100', `type` = 'string';",false,false,true);
    db("INSERT INTO `".dba::get('settings')."` SET `key` = 'k_inhaber', `value` = 'Max Mustermann', `default` = 'Max Mustermann', `length` = '50', `type` = 'string';",false,false,true);
    db("INSERT INTO `".dba::get('settings')."` SET `key` = 'k_nr', `value` = '123456789', `default` = '123456789', `length` = '100', `type` = 'string';",false,false,true);
    db("INSERT INTO `".dba::get('settings')."` SET `key` = 'k_vwz', `value` = '', `default` = '', `length` = '200', `type` = 'string';",false,false,true);
    db("INSERT INTO `".dba::get('settings')."` SET `key` = 'k_waehrung', `value` = '&euro;', `default` = '&euro;', `length` = '15', `type` = 'string';",false,false,true);
    db("INSERT INTO `".dba::get('settings')."` SET `key` = 'language', `value` = 'deutsch', `default` = 'deutsch', `length` = '50', `type` = 'string';",false,false,true);
    db("INSERT INTO `".dba::get('settings')."` SET `key` = 'last_backup', `value` = '0', `default` = '0', `length` = '1', `type` = 'int';",false,false,true);
    db("INSERT INTO `".dba::get('settings')."` SET `key` = 'l_clanwars', `value` = '30', `default` = '30', `length` = '5', `type` = 'int';",false,false,true);
    db("INSERT INTO `".dba::get('settings')."` SET `key` = 'l_forumsubtopic', `value` = '20', `default` = '20', `length` = '5', `type` = 'int';",false,false,true);
    db("INSERT INTO `".dba::get('settings')."` SET `key` = 'l_forumtopic', `value` = '20', `default` = '20', `length` = '5', `type` = 'int';",false,false,true);
    db("INSERT INTO `".dba::get('settings')."` SET `key` = 'l_ftopics', `value` = '28', `default` = '28', `length` = '5', `type` = 'int';",false,false,true);
    db("INSERT INTO `".dba::get('settings')."` SET `key` = 'l_lartikel', `value` = '18', `default` = '18', `length` = '5', `type` = 'int';",false,false,true);
    db("INSERT INTO `".dba::get('settings')."` SET `key` = 'l_lnews', `value` = '22', `default` = '22', `length` = '5', `type` = 'int';",false,false,true);
    db("INSERT INTO `".dba::get('settings')."` SET `key` = 'l_lreg', `value` = '12', `default` = '12', `length` = '5', `type` = 'int';",false,false,true);
    db("INSERT INTO `".dba::get('settings')."` SET `key` = 'l_lwars', `value` = '12', `default` = '12', `length` = '5', `type` = 'int';",false,false,true);
    db("INSERT INTO `".dba::get('settings')."` SET `key` = 'l_newsadmin', `value` = '20', `default` = '20', `length` = '5', `type` = 'int';",false,false,true);
    db("INSERT INTO `".dba::get('settings')."` SET `key` = 'l_newsarchiv', `value` = '20', `default` = '20', `length` = '5', `type` = 'int';",false,false,true);
    db("INSERT INTO `".dba::get('settings')."` SET `key` = 'l_nwars', `value` = '12', `default` = '12', `length` = '5', `type` = 'int';",false,false,true);
    db("INSERT INTO `".dba::get('settings')."` SET `key` = 'l_servernavi', `value` = '22', `default` = '22', `length` = '5', `type` = 'int';",false,false,true);
    db("INSERT INTO `".dba::get('settings')."` SET `key` = 'l_shoutnick', `value` = '20', `default` = '20', `length` = '5', `type` = 'int';",false,false,true);
    db("INSERT INTO `".dba::get('settings')."` SET `key` = 'l_shouttext', `value` = '22', `default` = '22', `length` = '5', `type` = 'int';",false,false,true);
    db("INSERT INTO `".dba::get('settings')."` SET `key` = 'l_topdl', `value` = '20', `default` = '20', `length` = '5', `type` = 'int';",false,false,true);
    db("INSERT INTO `".dba::get('settings')."` SET `key` = 'mailfrom', `value` = '".$db_infos['emailweb']."', `default` = 'info@127.0.0.1', `length` = '100', `type` = 'string';",false,false,true);
    db("INSERT INTO `".dba::get('settings')."` SET `key` = 'mail_extension', `value` = 'mail', `default` = 'mail', `length` = '20', `type` = 'string';",false,false,true);
    db("INSERT INTO `".dba::get('settings')."` SET `key` = 'maxshoutarchiv', `value` = '20', `default` = '20', `length` = '5', `type` = 'int';",false,false,true);
    db("INSERT INTO `".dba::get('settings')."` SET `key` = 'maxwidth', `value` = '400', `default` = '400', `length` = '5', `type` = 'int';",false,false,true);
    db("INSERT INTO `".dba::get('settings')."` SET `key` = 'memcache_host', `value` = 'localhost', `default` = 'localhost', `length` = '100', `type` = 'string';",false,false,true);
    db("INSERT INTO `".dba::get('settings')."` SET `key` = 'memcache_port', `value` = '11211', `default` = '11211', `length` = '11', `type` = 'int';",false,false,true);
    db("INSERT INTO `".dba::get('settings')."` SET `key` = 'm_adminartikel', `value` = '15', `default` = '15', `length` = '5', `type` = 'int';",false,false,true);
    db("INSERT INTO `".dba::get('settings')."` SET `key` = 'm_adminnews', `value` = '20', `default` = '20', `length` = '5', `type` = 'int';",false,false,true);
    db("INSERT INTO `".dba::get('settings')."` SET `key` = 'm_archivnews', `value` = '30', `default` = '30', `length` = '5', `type` = 'int';",false,false,true);
    db("INSERT INTO `".dba::get('settings')."` SET `key` = 'm_artikel', `value` = '15', `default` = '15', `length` = '5', `type` = 'int';",false,false,true);
    db("INSERT INTO `".dba::get('settings')."` SET `key` = 'm_awards', `value` = '15', `default` = '15', `length` = '5', `type` = 'int';",false,false,true);
    db("INSERT INTO `".dba::get('settings')."` SET `key` = 'm_away', `value` = '10', `default` = '10', `length` = '5', `type` = 'int';",false,false,true);
    db("INSERT INTO `".dba::get('settings')."` SET `key` = 'm_clankasse', `value` = '20', `default` = '20', `length` = 5'', `type` = 'int';",false,false,true);
    db("INSERT INTO `".dba::get('settings')."` SET `key` = 'm_clanwars', `value` = '10', `default` = '10', `length` = '5', `type` = 'int';",false,false,true);
    db("INSERT INTO `".dba::get('settings')."` SET `key` = 'm_comments', `value` = '10', `default` = '10', `length` = '5', `type` = 'int';",false,false,true);
    db("INSERT INTO `".dba::get('settings')."` SET `key` = 'm_cwcomments', `value` = '10', `default` = '10', `length` = '5', `type` = 'int';",false,false,true);
    db("INSERT INTO `".dba::get('settings')."` SET `key` = 'm_events', `value` = '5', `default` = '5', `length` = '5', `type` = 'int';",false,false,true);
    db("INSERT INTO `".dba::get('settings')."` SET `key` = 'm_fposts', `value` = '10', `default` = '10', `length` = '5', `type` = 'int';",false,false,true);
    db("INSERT INTO `".dba::get('settings')."` SET `key` = 'm_fthreads', `value` = '20', `default` = '20', `length` = '5', `type` = 'int';",false,false,true);
    db("INSERT INTO `".dba::get('settings')."` SET `key` = 'm_ftopics', `value` = '6', `default` = '6', `length` = '5', `type` = 'int';",false,false,true);
    db("INSERT INTO `".dba::get('settings')."` SET `key` = 'm_gallery', `value` = '36', `default` = '36', `length` = '5', `type` = 'int';",false,false,true);
    db("INSERT INTO `".dba::get('settings')."` SET `key` = 'm_gallerypics', `value` = '5', `default` = '5', `length` = '5', `type` = 'int';",false,false,true);
    db("INSERT INTO `".dba::get('settings')."` SET `key` = 'm_gb', `value` = '10', `default` = '10', `length` = '5', `type` = 'int';",false,false,true);
    db("INSERT INTO `".dba::get('settings')."` SET `key` = 'm_lartikel', `value` = '5', `default` = '5', `length` = '5', `type` = 'int';",false,false,true);
    db("INSERT INTO `".dba::get('settings')."` SET `key` = 'm_lnews', `value` = '6', `default` = '6', `length` = '5', `type` = 'int';",false,false,true);
    db("INSERT INTO `".dba::get('settings')."` SET `key` = 'm_lreg', `value` = '5', `default` = '5', `length` = '5', `type` = 'int';",false,false,true);
    db("INSERT INTO `".dba::get('settings')."` SET `key` = 'm_lwars', `value` = '6', `default` = '6', `length` = '5', `type` = 'int';",false,false,true);
    db("INSERT INTO `".dba::get('settings')."` SET `key` = 'm_news', `value` = '5', `default` = '5', `length` = '5', `type` = 'int';",false,false,true);
    db("INSERT INTO `".dba::get('settings')."` SET `key` = 'm_nwars', `value` = '6', `default` = '6', `length` = '5', `type` = 'int';",false,false,true);
    db("INSERT INTO `".dba::get('settings')."` SET `key` = 'm_shout', `value` = '10', `default` = '10', `length` = '5', `type` = 'int';",false,false,true);
    db("INSERT INTO `".dba::get('settings')."` SET `key` = 'm_topdl', `value` = '5', `default` = '5', `length` = '5', `type` = 'int';",false,false,true);
    db("INSERT INTO `".dba::get('settings')."` SET `key` = 'm_usergb', `value` = '10', `default` = '10', `length` = '5', `type` = 'int';",false,false,true);
    db("INSERT INTO `".dba::get('settings')."` SET `key` = 'm_userlist', `value` = '40', `default` = '40', `length` = '5', `type` = 'int';",false,false,true);
    db("INSERT INTO `".dba::get('settings')."` SET `key` = 'news_feed', `value` = '2', `default` = '1', `length` = '1', `type` = 'int';",false,false,true);
    db("INSERT INTO `".dba::get('settings')."` SET `key` = 'pagetitel', `value` = '".string::encode($db_infos['seitentitel'])."', `default` = 'Dein Seitentitel hier!', `length` = '50', `type` = 'string';",false,false,true);
    db("INSERT INTO `".dba::get('settings')."` SET `key` = 'persinfo', `value` = '1', `default` = '1', `length` = '1', `type` = 'int';",false,false,true);
    db("INSERT INTO `".dba::get('settings')."` SET `key` = 'prev', `value` = '".string::encode(strtolower(mkpwd(3,false)))."', `default` = '".string::encode(strtolower(mkpwd(3,false)))."', `length` = '3', `type` = 'string';",false,false,true);
    db("INSERT INTO `".dba::get('settings')."` SET `key` = '', `value` = '', `default` = '', `length` = '', `type` = 'int';",false,false,true);
    db("INSERT INTO `".dba::get('settings')."` SET `key` = 'regcode', `value` = '1', `default` = '1', `length` = '1', `type` = 'int';",false,false,true);
    db("INSERT INTO `".dba::get('settings')."` SET `key` = 'reg_artikel', `value` = '1', `default` = '1', `length` = '1', `type` = 'int';",false,false,true);
    db("INSERT INTO `".dba::get('settings')."` SET `key` = 'reg_cwcomments', `value` = '1', `default` = '1', `length` = '1', `type` = 'int';",false,false,true);
    db("INSERT INTO `".dba::get('settings')."` SET `key` = 'reg_dl', `value` = '1', `default` = '1', `length` = '1', `type` = 'int';",false,false,true);
    db("INSERT INTO `".dba::get('settings')."` SET `key` = 'reg_dlcomments', `value` = '1', `default` = '1', `length` = '1', `type` = 'int';",false,false,true);
    db("INSERT INTO `".dba::get('settings')."` SET `key` = 'reg_forum', `value` = '1', `default` = '1', `length` = '1', `type` = 'int';",false,false,true);
    db("INSERT INTO `".dba::get('settings')."` SET `key` = 'reg_newscomments', `value` = '1', `default` = '1', `length` = '1', `type` = 'int';",false,false,true);
    db("INSERT INTO `".dba::get('settings')."` SET `key` = 'reg_shout', `value` = '1', `default` = '1', `length` = '1', `type` = 'int';",false,false,true);
    db("INSERT INTO `".dba::get('settings')."` SET `key` = 'sendmail_path', `value` = '/usr/sbin/sendmail', `default` = '/usr/sbin/sendmail', `length` = '150', `type` = 'string';",false,false,true);
    db("INSERT INTO `".dba::get('settings')."` SET `key` = 'shout_max_zeichen', `value` = '100', `default` = '100', `length` = '5', `type` = 'int';",false,false,true);
    db("INSERT INTO `".dba::get('settings')."` SET `key` = 'smtp_hostname', `value` = 'localhost', `default` = 'localhost', `length` = '100', `type` = 'string';",false,false,true);
    db("INSERT INTO `".dba::get('settings')."` SET `key` = 'smtp_password', `value` = '', `default` = '', `length` = '0', `type` = 'string';",false,false,true);
    db("INSERT INTO `".dba::get('settings')."` SET `key` = 'smtp_port', `value` = '25', `default` = '25', `length` = '11', `type` = 'int';",false,false,true);
    db("INSERT INTO `".dba::get('settings')."` SET `key` = 'smtp_tls_ssl', `value` = '0', `default` = '0', `length` = '1', `type` = 'int';",false,false,true);
    db("INSERT INTO `".dba::get('settings')."` SET `key` = 'smtp_username', `value` = '', `default` = '', `length` = '150', `type` = 'string';",false,false,true);
    db("INSERT INTO `".dba::get('settings')."` SET `key` = 'teamrow', `value` = '3', `default` = '3', `length` = '1', `type` = 'int';",false,false,true);
    db("INSERT INTO `".dba::get('settings')."` SET `key` = 'tmpdir', `value` = 'version1.6', `default` = 'version1.6', `length` = '50', `type` = 'string';",false,false,true);
    db("INSERT INTO `".dba::get('settings')."` SET `key` = 'upicsize', `value` = '100', `default` = '100', `length` = '5', `type` = 'int';",false,false,true);
    db("INSERT INTO `".dba::get('settings')."` SET `key` = 'urls_linked', `value` = '1', `default` = '1', `length` = '1', `type` = 'int';",false,false,true);
    db("INSERT INTO `".dba::get('settings')."` SET `key` = 'use_akl', `value` = '1', `default` = '1', `length` = '1', `type` = 'int';",false,false,true);
    db("INSERT INTO `".dba::get('settings')."` SET `key` = 'wmodus', `value` = '0', `default` = '0', `length` = '1', `type` = 'int';",false,false,true);

    //===============================================================
    //-> Teamspeak ==================================================
    //===============================================================
    db("INSERT INTO ".dba::get('ts')." SET `host_ip_dns` = 'ts.revoplay.de', `server_port` = 9987, `query_port` = 10011, `customicon` = 1, `showchannel` = 0, `default_server` = 0, `show_navi` = 0;",false,false,true);
    db("INSERT INTO ".dba::get('ts')." SET `host_ip_dns` = 'ts.hammermaps.de', `server_port` = 9987, `query_port` = 10011, `customicon` = 1, `showchannel` = 0, `default_server` = 1, `show_navi` = 1;",false,false,true);

    //===============================================================
    //-> Forum: Kategorien ==========================================
    //===============================================================
    db("INSERT INTO ".dba::get('f_skats')." SET `sid` = 1, `kattopic` = 'Allgemein', `subtopic` = 'Allgemeines...', `pos` = 2;",false,false,true);
    db("INSERT INTO ".dba::get('f_skats')." SET `sid` = 1, `kattopic` = 'Homepage', `subtopic` = 'Kritiken/Anregungen/Bugs', `pos` = 3;",false,false,true);
    db("INSERT INTO ".dba::get('f_skats')." SET `sid` = 1, `kattopic` = 'Server', `subtopic` = 'Serverseitige Themen...', `pos` = 4;",false,false,true);
    db("INSERT INTO ".dba::get('f_skats')." SET `sid` = 1, `kattopic` = 'Spam', `subtopic` = 'Spamt die Bude voll ;)', `pos` = 5;",false,false,true);
    db("INSERT INTO ".dba::get('f_skats')." SET `sid` = 2, `kattopic` = 'Sonstiges', `subtopic` = '', `pos` = 6;",false,false,true);
    db("INSERT INTO ".dba::get('f_skats')." SET `sid` = 2, `kattopic` = 'OFFtopic', `subtopic` = '', `pos` = 7;",false,false,true);
    db("INSERT INTO ".dba::get('f_skats')." SET `sid` = 3, `kattopic` = 'internes Forum', `subtopic` = 'interne Angelegenheiten', `pos` = 1;",false,false,true);
    db("INSERT INTO ".dba::get('f_skats')." SET `sid` = 3, `kattopic` = 'Server intern', `subtopic` = 'interne Serverangelegenheiten', `pos` = 8;",false,false,true);
    db("INSERT INTO ".dba::get('f_skats')." SET `sid` = 3, `kattopic` = 'War Forum', `subtopic` = 'Alles &uuml;ber und rundum Clanwars', `pos` = 9;",false,false,true);

    //===============================================================
    //-> Forum: Threads =============================================
    //===============================================================
    db("INSERT INTO ".dba::get('f_threads')." SET `kid` = 1, `t_date` = ".(time() - 9000).", `topic` = 'Testeintrag', `t_reg` = 1, `t_text` = '<p>Testeintrag</p>', `first` = 1, `lp` = ".time().", `ip` = '".visitorIp()."'",false,false,true);

    //===============================================================
    //-> Galerie ====================================================
    //===============================================================
    db("INSERT INTO ".dba::get('gallery')." SET `datum` = ".time().", `kat` = 'Testgalerie', `beschreibung` = '<p>Das ist die erste Testgalerie.</p>\r\n<p>Hier seht ihr ein paar Bilder die eigentlich nur als Platzhalter dienen :)</p>';",false,false,true);

    //===============================================================
    //-> Links ======================================================
    //===============================================================
    db("INSERT INTO ".dba::get('links')." SET `url` = 'http://www.dzcp.de', `blink` = 'http://www.dzcp.de/banner/dzcp.gif', `banner` = 1, `beschreibung` = 'deV!L`z Clanportal';",false,false,true);
    db("INSERT INTO ".dba::get('links')." SET `url` = 'http://www.my-starmedia.de', `blink` = 'http://www.my-starmedia.de/extern/b3/b3.gif', `banner` = 1, `beschreibung` = '<b>my-STARMEDIA</b><br />my-STARMEDIA.de - DZCP Mods and Coding';",false,false,true);

    //===============================================================
    //-> LinkUs =====================================================
    //===============================================================
    db("INSERT INTO ".dba::get('linkus')." SET `url` = 'http://www.dzcp.de', `text` = 'http://www.dzcp.de/banner/dzcp.gif', `banner` = 1, `beschreibung` = 'deV!L`z Clanportal';",false,false,true);

    //===============================================================
    //-> Navigation =================================================
    //===============================================================
    db("INSERT INTO ".dba::get('navi')." SET `pos` = 1,  `kat` = 'nav_main', `name` = '_news_', `url` = '../news/', `type` = 1, `internal` = 0, `wichtig` = 0, `extended_perm` = NULL;",false,false,true);
    db("INSERT INTO ".dba::get('navi')." SET `pos` = 2,  `kat` = 'nav_main', `name` = '_newsarchiv_', `url` = '../news/?action=archiv', `type` = 1, `internal` = 0, `wichtig` = 0, `extended_perm` = NULL;",false,false,true);
    db("INSERT INTO ".dba::get('navi')." SET `pos` = 3,  `kat` = 'nav_main', `name` = '_artikel_', `url` = '../artikel/', `type` = 1, `internal` = 0, `wichtig` = 0, `extended_perm` = NULL;",false,false,true);
    db("INSERT INTO ".dba::get('navi')." SET `pos` = 4,  `kat` = 'nav_main', `name` = '_forum_', `url` = '../forum/', `type` = 1, `internal` = 0, `wichtig` = 0, `extended_perm` = NULL;",false,false,true);
    db("INSERT INTO ".dba::get('navi')." SET `pos` = 5,  `kat` = 'nav_main', `name` = '_gb_', `url` = '../gb/', `type` = 1, `internal` = 0, `wichtig` = 0, `extended_perm` = NULL;",false,false,true);
    db("INSERT INTO ".dba::get('navi')." SET `pos` = 6,  `kat` = 'nav_main', `name` = '_kalender_', `url` = '../kalender/', `type` = 1, `internal` = 0, `wichtig` = 0, `extended_perm` = NULL;",false,false,true);
    db("INSERT INTO ".dba::get('navi')." SET `pos` = 7,  `kat` = 'nav_main', `name` = '_votes_', `url` = '../votes/', `type` = 1, `internal` = 0, `wichtig` = 0, `extended_perm` = NULL;",false,false,true);
    db("INSERT INTO ".dba::get('navi')." SET `pos` = 8,  `kat` = 'nav_main', `name` = '_links_', `url` = '../links/', `type` = 1, `internal` = 0, `wichtig` = 0, `extended_perm` = NULL;",false,false,true);
    db("INSERT INTO ".dba::get('navi')." SET `pos` = 9,  `kat` = 'nav_main', `name` = '_sponsoren_', `url` = '../sponsors/', `type` = 1, `internal` = 0, `wichtig` = 0, `extended_perm` = NULL;",false,false,true);
    db("INSERT INTO ".dba::get('navi')." SET `pos` = 10, `kat` = 'nav_main', `name` = '_downloads_', `url` = '../downloads/', `type` = 1, `internal` = 0, `wichtig` = 0, `extended_perm` = NULL;",false,false,true);
    db("INSERT INTO ".dba::get('navi')." SET `pos` = 11, `kat` = 'nav_main', `name` = '_userlist_', `url` = '../user/?action=userlist', `type` = 1, `internal` = 0, `wichtig` = 0, `extended_perm` = NULL;",false,false,true);
    db("INSERT INTO ".dba::get('navi')." SET `pos` = 12, `kat` = 'nav_main', `name` = '_glossar_', `url` = '../glossar/', `type` = 1, `internal` = 0, `wichtig` = 0, `extended_perm` = NULL;",false,false,true);

    db("INSERT INTO ".dba::get('navi')." SET `pos` = 1, `kat` = 'nav_clan', `name` = '_squads_', `url` = '../squads/', `type` = 1, `internal` = 0, `wichtig` = 0, `extended_perm` = NULL;",false,false,true);
    db("INSERT INTO ".dba::get('navi')." SET `pos` = 2, `kat` = 'nav_clan', `name` = '_membermap_', `url` = '../membermap/', `type` = 1, `internal` = 0, `wichtig` = 0, `extended_perm` = NULL;",false,false,true);
    db("INSERT INTO ".dba::get('navi')." SET `pos` = 3, `kat` = 'nav_clan', `name` = '_cw_', `url` = '../clanwars/', `type` = 1, `internal` = 0, `wichtig` = 0, `extended_perm` = NULL;",false,false,true);
    db("INSERT INTO ".dba::get('navi')." SET `pos` = 4, `kat` = 'nav_clan', `name` = '_awards_', `url` = '../awards/', `type` = 1, `internal` = 0, `wichtig` = 0, `extended_perm` = NULL;",false,false,true);
    db("INSERT INTO ".dba::get('navi')." SET `pos` = 5, `kat` = 'nav_clan', `name` = '_rankings_', `url` = '../rankings/', `type` = 1, `internal` = 0, `wichtig` = 0, `extended_perm` = NULL;",false,false,true);

    db("INSERT INTO ".dba::get('navi')." SET `pos` = 1, `kat` = 'nav_server', `name` = '_server_', `url` = '../server/', `type` = 1, `internal` = 0, `wichtig` = 0, `extended_perm` = NULL;",false,false,true);
    db("INSERT INTO ".dba::get('navi')." SET `pos` = 2, `kat` = 'nav_server', `name` = '_serverlist_', `url` = '../serverliste/', `type` = 1, `internal` = 0, `wichtig` = 0, `extended_perm` = NULL;",false,false,true);
    db("INSERT INTO ".dba::get('navi')." SET `pos` = 3, `kat` = 'nav_server', `name` = '_ts_', `url` = '../teamspeak/', `type` = 1, `internal` = 0, `wichtig` = 0, `extended_perm` = NULL;",false,false,true);

    db("INSERT INTO ".dba::get('navi')." SET `pos` = 1, `kat` = 'nav_misc', `name` = '_galerie_', `url` = '../gallery/', `type` = 1, `internal` = 0, `wichtig` = 0, `extended_perm` = NULL;",false,false,true);
    db("INSERT INTO ".dba::get('navi')." SET `pos` = 2, `kat` = 'nav_misc', `name` = '_kontakt_', `url` = '../contact/', `type` = 1, `internal` = 0, `wichtig` = 0, `extended_perm` = NULL;",false,false,true);
    db("INSERT INTO ".dba::get('navi')." SET `pos` = 3, `kat` = 'nav_misc', `name` = '_joinus_', `url` = '../contact/?action=joinus', `type` = 1, `internal` = 0, `wichtig` = 0, `extended_perm` = NULL;",false,false,true);
    db("INSERT INTO ".dba::get('navi')." SET `pos` = 4, `kat` = 'nav_misc', `name` = '_fightus_', `url` = '../contact/?action=fightus', `type` = 1, `internal` = 0, `wichtig` = 0, `extended_perm` = NULL;",false,false,true);
    db("INSERT INTO ".dba::get('navi')." SET `pos` = 5, `kat` = 'nav_misc', `name` = '_linkus_', `url` = '../linkus/', `type` = 1, `internal` = 0, `wichtig` = 0, `extended_perm` = NULL;",false,false,true);
    db("INSERT INTO ".dba::get('navi')." SET `pos` = 6, `kat` = 'nav_misc', `name` = '_stats_', `url` = '../stats/', `type` = 1, `internal` = 0, `wichtig` = 0, `extended_perm` = NULL;",false,false,true);
    db("INSERT INTO ".dba::get('navi')." SET `pos` = 7, `kat` = 'nav_misc', `name` = '_impressum_', `url` = '../impressum/', `type` = 1, `internal` = 0, `wichtig` = 0, `extended_perm` = NULL;",false,false,true);

    db("INSERT INTO ".dba::get('navi')." SET `pos` = 1, `kat` = 'nav_user', `name` = '_lobby_', `url` = '../user/?action=userlobby', `type` = 1, `internal` = 0, `wichtig` = 0, `extended_perm` = NULL;",false,false,true);
    db("INSERT INTO ".dba::get('navi')." SET `pos` = 2, `kat` = 'nav_user', `name` = '_nachrichten_', `url` = '../user/?action=msg', `type` = 1, `internal` = 0, `wichtig` = 0, `extended_perm` = NULL;",false,false,true);
    db("INSERT INTO ".dba::get('navi')." SET `pos` = 3, `kat` = 'nav_user', `name` = '_buddys_', `url` = '../user/?action=buddys', `type` = 1, `internal` = 0, `wichtig` = 0, `extended_perm` = NULL;",false,false,true);
    db("INSERT INTO ".dba::get('navi')." SET `pos` = 4, `kat` = 'nav_user', `name` = '_edit_profile_', `url` = '../user/?action=editprofile', `type` = 1, `internal` = 0, `wichtig` = 0, `extended_perm` = NULL;",false,false,true);
    db("INSERT INTO ".dba::get('navi')." SET `pos` = 5, `kat` = 'nav_user', `name` = '_logout_', `url` = '../user/?action=logout', `type` = 1, `internal` = 0, `wichtig` = 1, `extended_perm` = NULL;",false,false,true);

    db("INSERT INTO ".dba::get('navi')." SET `pos` = 1, `kat` = 'nav_member', `name` = '_clankasse_', `url` = '../clankasse/', `type` = 1, `internal` = 1, `wichtig` = 0, `extended_perm` = 'clankasse';",false,false,true);

    db("INSERT INTO ".dba::get('navi')." SET `pos` = 1, `kat` = 'nav_main', `name` = '_news_send_', `url` = '../news/?action=send', `type` = 1, `internal` = 0, `wichtig` = 0, `extended_perm` = NULL;",false,false,true);
    db("INSERT INTO ".dba::get('navi')." SET `pos` = 1, `kat` = 'nav_trial', `name` = '_awaycal_', `url` = '../away/', `type` = 2, `internal` = 1, `wichtig` = 0, `extended_perm` = NULL;",false,false,true);
    db("INSERT INTO ".dba::get('navi')." SET `pos` = 1, `kat` = 'nav_admin', `name` = '_admin_', `url` = '../admin/', `type` = 1, `internal` = 1, `wichtig` = 1, `extended_perm` = NULL;",false,false,true);

    //===============================================================
    //-> News =======================================================
    //===============================================================
    db("INSERT INTO ".dba::get('news')." SET `autor` = '1', `datum` = '".time()."', `kat` = 1, `sticky` = 0, `titel` = 'deV!L`z Clanportal - Extended Edition', `intern` = 0, `text` = '<p>deV!L`z Clanportal - Extended Edition wurde erfolgreich installiert!</p><p>Bei Fragen oder Problemen kannst du gerne das Forum unter <a href=\"http://www.dzcp.de/\" target=\"_blank\">www.dzcp.de</a> kontaktieren.</p><p>Mehr Designtemplates und Modifikationen findest du unter <a href=\"http://www.templatebar.de/\" target=\"_blank\" title=\"Templates, Designs &amp; Modifikationen\">www.templatebar.de</a>.</p><p><br /></p><p>Viel Spass mit dem DZCP w&uuml;nscht dir das Team von www.dzcp.de.</p>',
    `klapplink` = '', `klapptext` = '', `link1` = 'www.dzcp.de', `url1` = 'http://www.dzcp.de', `link2` = 'TEMPLATEbar.de', `url2` = 'http://www.templatebar.de', `link3` = '', `url3` = '', `viewed` = 0, `public` = 1, `timeshift` = 0, `comments` = 1;",false,false,true);

    //===============================================================
    //-> Artikel ====================================================
    //===============================================================
    db("INSERT INTO ".dba::get('artikel')." SET `autor` = '1', `datum` = '".time()."', `kat` = 1, `titel` = 'Testartikel', `text` = '<p>Hier k&ouml;nnte dein Artikel stehen!</p>\r\n<p> </p>', `link1` = '',
    `url1` = '', `link2` = '', `url2` = '', `link3` = '', `url3` = '', `viewed` = 0, `public` = 1, `comments` = 1;",false,false,true);

    //===============================================================
    //-> Profilfelder ===============================================
    //===============================================================
    db("INSERT INTO `".dba::get('profile')."` SET `kid` = 1, `name` = '_job_', `feldname` = 'job', `type` = 1, `shown` = 1;",false,false,true);
    db("INSERT INTO `".dba::get('profile')."` SET `kid` = 1, `name` = '_hobbys_', `feldname` = 'hobbys', `type` = 1, `shown` = 1;",false,false,true);
    db("INSERT INTO `".dba::get('profile')."` SET `kid` = 1, `name` = '_motto_', `feldname` = 'motto', `type` = 1, `shown` = 1;",false,false,true);
    db("INSERT INTO `".dba::get('profile')."` SET `kid` = 2, `name` = '_exclans_', `feldname` = 'ex', `type` = 1, `shown` = 1;",false,false,true);

    db("INSERT INTO `".dba::get('profile')."` SET `kid` = 4, `name` = '_drink_', `feldname` = 'drink', `type` = 1, `shown` = 1;",false,false,true);
    db("INSERT INTO `".dba::get('profile')."` SET `kid` = 4, `name` = '_essen_', `feldname` = 'essen', `type` = 1, `shown` = 1;",false,false,true);
    db("INSERT INTO `".dba::get('profile')."` SET `kid` = 4, `name` = '_film_', `feldname` = 'film', `type` = 1, `shown` = 1;",false,false,true);
    db("INSERT INTO `".dba::get('profile')."` SET `kid` = 4, `name` = '_musik_', `feldname` = 'musik', `type` = 1, `shown` = 1;",false,false,true);
    db("INSERT INTO `".dba::get('profile')."` SET `kid` = 4, `name` = '_song_', `feldname` = 'song', `type` = 1, `shown` = 1;",false,false,true);
    db("INSERT INTO `".dba::get('profile')."` SET `kid` = 4, `name` = '_buch_', `feldname` = 'buch', `type` = 1, `shown` = 1;",false,false,true);
    db("INSERT INTO `".dba::get('profile')."` SET `kid` = 4, `name` = '_autor_', `feldname` = 'autor', `type` = 1, `shown` = 1;",false,false,true);
    db("INSERT INTO `".dba::get('profile')."` SET `kid` = 4, `name` = '_person_', `feldname` = 'person', `type` = 1, `shown` = 1;",false,false,true);
    db("INSERT INTO `".dba::get('profile')."` SET `kid` = 4, `name` = '_sport_', `feldname` = 'sport', `type` = 1, `shown` = 1;",false,false,true);
    db("INSERT INTO `".dba::get('profile')."` SET `kid` = 4, `name` = '_sportler_', `feldname` = 'sportler', `type` = 1, `shown` = 1;",false,false,true);
    db("INSERT INTO `".dba::get('profile')."` SET `kid` = 4, `name` = '_auto_', `feldname` = 'auto', `type` = 1, `shown` = 1;",false,false,true);
    db("INSERT INTO `".dba::get('profile')."` SET `kid` = 4, `name` = '_game_', `feldname` = 'game', `type` = 1, `shown` = 1;",false,false,true);
    db("INSERT INTO `".dba::get('profile')."` SET `kid` = 4, `name` = '_favoclan_', `feldname` = 'favoclan', `type` = 1, `shown` = 1;",false,false,true);
    db("INSERT INTO `".dba::get('profile')."` SET `kid` = 4, `name` = '_spieler_', `feldname` = 'spieler', `type` = 1, `shown` = 1;",false,false,true);
    db("INSERT INTO `".dba::get('profile')."` SET `kid` = 4, `name` = '_map_', `feldname` = 'map', `type` = 1, `shown` = 1;",false,false,true);
    db("INSERT INTO `".dba::get('profile')."` SET `kid` = 4, `name` = '_waffe_', `feldname` = 'waffe', `type` = 1, `shown` = 1;",false,false,true);

    db("INSERT INTO `".dba::get('profile')." SET `kid` = 5, `name` = '_system_', `feldname` = 'os', `type` = 1, `shown` = 1;",false,false,true);
    db("INSERT INTO `".dba::get('profile')." SET `kid` = 5, `name` = '_board_', `feldname` = 'board', `type` = 1, `shown` = 1;",false,false,true);
    db("INSERT INTO `".dba::get('profile')." SET `kid` = 5, `name` = '_cpu_', `feldname` = 'cpu', `type` = 1, `shown` = 1;",false,false,true);
    db("INSERT INTO `".dba::get('profile')." SET `kid` = 5, `name` = '_ram_', `feldname` = 'ram', `type` = 1, `shown` = 1;",false,false,true);
    db("INSERT INTO `".dba::get('profile')." SET `kid` = 5, `name` = '_graka_', `feldname` = 'graka', `type` = 1, `shown` = 1;",false,false,true);
    db("INSERT INTO `".dba::get('profile')." SET `kid` = 5, `name` = '_hdd_', `feldname` = 'hdd', `type` = 1, `shown` = 1;",false,false,true);
    db("INSERT INTO `".dba::get('profile')." SET `kid` = 5, `name` = '_monitor_', `feldname` = 'monitor', `type` = 1, `shown` = 1;",false,false,true);
    db("INSERT INTO `".dba::get('profile')." SET `kid` = 5, `name` = '_maus_', `feldname` = 'maus', `type` = 1, `shown` = 1;",false,false,true);
    db("INSERT INTO `".dba::get('profile')." SET `kid` = 5, `name` = '_mauspad_', `feldname` = 'mauspad', `type` = 1, `shown` = 1;",false,false,true);
    db("INSERT INTO `".dba::get('profile')." SET `kid` = 5, `name` = '_headset_', `feldname` = 'headset', `type` = 1, `shown` = 1;",false,false,true);
    db("INSERT INTO `".dba::get('profile')." SET `kid` = 5, `name` = '_inet_', `feldname` = 'inet', `type` = 1, `shown` = 1;",false,false,true);

    //===============================================================
    //-> Partnerbuttons =============================================
    //===============================================================
    db("INSERT INTO `".dba::get('partners')."` SET `link` = 'http://www.my-starmedia.de', `banner` = 'my-starmedia.gif', `textlink` = 0;",false,false,true);
    db("INSERT INTO `".dba::get('partners')."` SET `link` = 'http://www.hogibo.net', `banner` = 'hogibo.gif', `textlink` = 0;",false,false,true);
    db("INSERT INTO `".dba::get('partners')."` SET `link` = 'http://www.codeking.eu', `banner` = 'codeking.gif', `textlink` = 0;",false,false,true);
    db("INSERT INTO `".dba::get('partners')."` SET `link` = 'http://www.dzcp.de', `banner` = 'dzcp.gif', `textlink` = 0;",false,false,true);
    db("INSERT INTO `".dba::get('partners')."` SET `link` = 'http://spenden.dzcp.de', `banner` = 'spenden.gif', `textlink` = 0;",false,false,true);
    db("INSERT INTO `".dba::get('partners')."` SET `link` = 'http://www.modsbar.de', `banner` = 'mb_88x32.png', `textlink` = 0;",false,false,true);
    db("INSERT INTO `".dba::get('partners')."` SET `link` = 'http://www.templatebar.de', `banner` = 'tb_88x32.png', `textlink` = 0;",false,false,true);
    db("INSERT INTO `".dba::get('partners')."` SET `link` = 'http://www.hammermaps.de', `banner` = 'hm_team.gif', `textlink` = 0;",false,false,true);

    //===============================================================
    //-> Rechte =====================================================
    //===============================================================
    db("INSERT INTO `".dba::get('permissions')."` SET `user` = 1, `pos` = 0, `artikel` = 1, `awards` = 1, `activateusers` = 1, `startpage` = 1, `backup` = 1, `clear` = 1, `config` = 1, `contact` = 1, `clanwars` = 1, `clankasse` = 1, `downloads` = 1, `editkalender` = 1, `editserver` = 1, `editteamspeak` = 1, `editsquads` = 1, `editusers` = 1, `editor` = 1, `forum` = 1, `gallery` = 1, `gb` = 1, `gs_showpw` = 1, `glossar` = 1, `impressum` = 1, `intforum` = 1, `intnews` = 1, `joinus` = 1, `links` = 1, `news` = 1, `newsletter` = 1, `partners` = 1, `profile` = 1, `protocol` = 1, `rankings` = 1, `receivecws` = 1, `serverliste` = 1, `smileys` = 1, `sponsors` = 1, `shoutbox` = 1, `support` = 1, `votes` = 1, `votesadmin` = 1, `slideshow` = 1;",false,false,true);

    //===============================================================
    //-> Positionen =================================================
    //===============================================================
    db("INSERT INTO `".dba::get('pos')."` SET `pid` = '1', `position` = 'Leader', `nletter` = 0;",false,false,true);
    db("INSERT INTO `".dba::get('pos')."` SET `pid` = '2', `position` = 'Co-Leader', `nletter` = 0;",false,false,true);
    db("INSERT INTO `".dba::get('pos')."` SET `pid` = '3', `position` = 'Webmaster', `nletter` = 0;",false,false,true);
    db("INSERT INTO `".dba::get('pos')."` SET `pid` = '4', `position` = 'Member', `nletter` = 0;",false,false,true);

    //===============================================================
    //-> Server =====================================================
    //===============================================================
    db("INSERT INTO `".dba::get('server')."` SET `game` = 'ns2', `shown` = 1, `navi` = 1, `name` = 'Hammermaps.de Community Server#1', `ip` = '176.9.114.124', `port` = 1100, `pwd` = '', `qport` = 1101, `custom_icon` = '';",false,false,true);
    db("INSERT INTO `".dba::get('server')."` SET `game` = 'css', `shown` = 1, `navi` = 1, `name` = 'KpyTou css-server #1.NoSteam[RUS][FastAWP][HLstatsX][v1765266]', `ip` = '77.108.242.105', `port` = 27015, `pwd` = '', `qport` = 27015, `custom_icon` = '';",false,false,true);

    //===============================================================
    //-> Server List ================================================
    //===============================================================
    db("INSERT INTO `".dba::get('serverliste')."` SET `datum` = 1298817167, `clanname` = '[-tHu-] teamHanau', `clanurl` = 'http://www.thu-clan.de', `ip` = '82.98.216.10', `port` = '27015', `pwd` = 0, `checked` = 1, `slots` = 17;",false,false,true);

    //===============================================================
    //-> Startseite =================================================
    //===============================================================
    db("INSERT INTO `".dba::get('startpage')."` SET `name` = 'News', `url` = 'news/', `level` = 1;",false,false,true);
    db("INSERT INTO `".dba::get('startpage')."` SET `name` = 'Forum', `url` = 'forum/', `level` = 1;",false,false,true);

    //===============================================================
    //-> Shoutbox ===================================================
    //===============================================================
    db("INSERT INTO `".dba::get('shout')."` SET `datum` = '".time()."', `nick` = 'deV!L', `email` = 'webmaster@dzcp.de', `text` = 'Viel Gl&uuml;ck und Erfolg mit eurem Clan!', `ip` = '';",false,false,true);

    //===============================================================
    //-> Squads =====================================================
    //===============================================================
    db("INSERT INTO `".dba::get('squads')."` SET `name` = 'Testsquad', `game` = 'Counter-Strike', `icon` = 'cs.gif', `pos` = 1, `shown` = 1, `navi` = 1, `status` = 1, `beschreibung` = 'Unser Counter-Strike 1.6 Team', `team_show` = 1;",false,false,true);

    //===============================================================
    //-> Squadusers =================================================
    //===============================================================
    db("INSERT INTO `".dba::get('squaduser')."` SET `user` = 1, `squad` = 1;",false,false,true);

    //===============================================================
    //-> Userstats ==================================================
    //===============================================================
    db("INSERT INTO ".dba::get('userstats')." SET `user` = 1, `logins` = 0, `writtenmsg` = 0, `lastvisit` = 0, `hits` = 0, `votes` = 0, `profilhits` = 0, `forumposts` = 0, `cws` = 0;",false,false,true);

    //===============================================================
    //-> Users ======================================================
    //===============================================================
    db("INSERT INTO `".dba::get('users')."` SET `user` = '".$db_infos['login']."', `nick` = '".string::encode($db_infos['nick'])."', `pwd` = '".($pwd_hash=pass_hash($db_infos['pwd'],2))."', `country` = 'de',
    `language` = 'default', `regdatum`  = '".time()."', `email` = '".$db_infos['email']."', `startpage` = 0, `level` = 4, `sex` = 0, `position` = 1, `status` = 1, `time` = '".time()."', `pnmail` = 1, `profile_access` = 0, `rss_key` = '".mkpwd(8,false)."';",false,false,true);
    $userid=database::get_insert_id();

    //Login NOW
    if($db_infos['loginnow'])
    {
        $_SESSION['id']         = $userid;
        $_SESSION['pwd']        = $pwd_hash;
        $_SESSION['lastvisit']  = 0;
        $_SESSION['ip']         = ($userip=visitorIp());
        db("UPDATE ".dba::get('userstats')." SET `logins` = logins+1 WHERE user = ".convert::ToInt($userid));
        db("UPDATE ".dba::get('users')." SET `online` = '1', `sessid` = '".session_id()."', `ip` = '".$userip."' WHERE id = ".convert::ToInt($userid));
        wire_ipcheck("login(".convert::ToInt($userid).")");
    }

    //===============================================================
    //-> RSS Feeds ==================================================
    //===============================================================
    db("INSERT INTO `".dba::get('rss')."` SET `userid` = ".$userid.", `show_public_news` = 1, `show_public_news_max` = 6, `show_intern_news` = 1, `show_intern_news_max` = 6, `show_artikel` = 1, `show_artikel_max` = 2, `show_downloads` = 1, `show_downloads_max` = 2;",false,false,true);

    //===============================================================
    //-> Votes ======================================================
    //===============================================================
    db("INSERT INTO `".dba::get('votes')."` SET `datum` = ".time().", `titel` = 'Wie findet ihr unsere Seite?', `intern` = 0, `menu` = 1, `closed` = 0, `von` = 1, `forum` = 0;",false,false,true);

    //===============================================================
    //-> Vote Möglichkeit ===========================================
    //===============================================================
    db("INSERT INTO `".dba::get('vote_results')."` SET `vid` = 1, `what` = 'a1', `sel` = 'Gut', `stimmen` = 0;",false,false,true);
    db("INSERT INTO `".dba::get('vote_results')."` SET `vid` = 1, `what` = 'a2', `sel` = 'Schlecht', `stimmen` = 0;",false,false,true);

    //===============================================================
    //-> Navigation Kategorien ======================================
    //===============================================================
    db("INSERT INTO `".dba::get('navi_kats')."` SET `name` = 'Clan Navigation', `placeholder` = 'nav_clan', `level` = 0;",false,false,true);
    db("INSERT INTO `".dba::get('navi_kats')."` SET `name` = 'Main Navigation', `placeholder` = 'nav_main', `level` = 0;",false,false,true);
    db("INSERT INTO `".dba::get('navi_kats')."` SET `name` = 'Server Navigation', `placeholder` = 'nav_server', `level` = 0;",false,false,true);
    db("INSERT INTO `".dba::get('navi_kats')."` SET `name` = 'Misc Navigation', `placeholder` = 'nav_misc', `level` = 0;",false,false,true);
    db("INSERT INTO `".dba::get('navi_kats')."` SET `name` = 'Trial Navigation', `placeholder` = 'nav_trial', `level` = 2;",false,false,true);
    db("INSERT INTO `".dba::get('navi_kats')."` SET `name` = 'Admin Navigation', `placeholder` = 'nav_admin', `level` = 4;",false,false,true);
    db("INSERT INTO `".dba::get('navi_kats')."` SET `name` = 'User Navigation', `placeholder` = 'nav_user', `level` = 1;",false,false,true);
    db("INSERT INTO `".dba::get('navi_kats')."` SET `name` = 'Member Navigation', `placeholder` = 'nav_member', `level` = 3;",false,false,true);

    //===============================================================
    //-> Clanwars ===================================================
    //===============================================================
    db("INSERT INTO ".dba::get('cw')." SET `squad_id` = 1, `gcountry` = 'de', `datum` = ".(time()-90000).", `clantag` = 'DZCP', `gegner` = '".string::encode("deV!L'z Clanportal")."', `url` = 'http://www.dzcp.de', `xonx` = '5on5', `liga` = 'DZCP', `punkte` = 0, `gpunkte` = 21, `maps` = 'de_dzcp', `top` = 1;",false,false,true);

    //===============================================================
    //-> Clankassenkategorien =======================================
    //===============================================================
    db("INSERT INTO `".dba::get('c_kats')."` SET `kat` = 'Servermiete';",false,false,true);
    db("INSERT INTO `".dba::get('c_kats')."` SET `kat` = 'Serverbeitrag';",false,false,true);

    //===============================================================
    //-> Sponsoren ==================================================
    //===============================================================
    db("INSERT INTO `".dba::get('sponsoren')."` SET `name` = 'DZCP', `link` = 'http://www.dzcp.de', `beschreibung` = '".string::encode("<p>deV!L'z Clanportal, das CMS for Online-Clans!</p>")."', `box` = 1, `pos` = 2;",false,false,true);
    db("INSERT INTO `".dba::get('sponsoren')."` SET `name` = 'DZCP Rotationsbanner', `link` = 'http://www.dzcp.de', `beschreibung` = '".string::encode("<p>deV!L`z Clanportal</p>")."', `banner` = 1, `blink` = 'http://www.dzcp.de/banner/dzcp.gif', `pos` = 3;",false,false,true);
    db("INSERT INTO `".dba::get('sponsoren')."` SET `name` = 'DZCP - Extended Edition', `link` = 'http://www.hammermaps.de', `beschreibung` = '".string::encode("<p>deV!L`z Clanportal - Extended Edition</p>")."', `blink` = 'http://www.dzcp.de/banner/dzcp.gif', `box` = 1, `pos` = 1;",false,false,true);

    //===============================================================
    //-> Glossar ====================================================
    //===============================================================
    db("INSERT INTO `".dba::get('glossar')."` SET `word` = 'DZCP', `glossar` = '&lt;p&gt;deV!L&apostroph;z Clanportal - kurz DZCP - ist ein CMS-System speziell f&uuml;r Onlinegaming Clans.&lt;/p&gt;&lt;p&gt;Viele schon in der Grundinstallation vorhandene Module erleichtern die Verwaltung einer Clan-Homepage ungemein.&lt;/p&gt;';",false,false,true);
}