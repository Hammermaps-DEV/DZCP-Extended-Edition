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
    db("INSERT INTO `".dba::get('settings')."` SET
    `clanname`             = '".string::encode($db_infos['clanname'])."',
    `badwords`             = 'arsch,Arsch,arschloch,Arschloch,hure,Hure',
    `pagetitel`            = '".string::encode($db_infos['seitentitel'])."',
    `i_domain`             = '".$_SERVER['SERVER_NAME']."',
    `domain`               = '".$_SERVER['SERVER_ADDR']."',
    `mailfrom`             = '".$db_infos['emailweb']."',
    `prev`                 = '".string::encode(strtolower(mkpwd(3,false)))."',
    `memcache_host`        = 'localhost',
    `memcache_port`        = '11211',
    `db_version`           = '1600',
    `eml_reg_subj`         = '".string::encode(emlv('eml_reg_subj'))."',
    `eml_pwd_subj`         = '".string::encode(emlv('eml_pwd_subj'))."',
    `eml_reg`              = '".string::encode(emlv('eml_reg'))."',
    `eml_pwd`              = '".string::encode(emlv('eml_pwd'))."',
    `eml_nletter_subj`     = '".string::encode(emlv('eml_nletter_subj'))."',
    `eml_nletter`          = '".string::encode(emlv('eml_nletter'))."',
    `eml_fabo_npost_subj`  = '".string::encode(emlv('eml_fabo_npost_subj'))."',
    `eml_fabo_tedit_subj`  = '".string::encode(emlv('eml_fabo_tedit_subj'))."',
    `eml_fabo_pedit_subj`  = '".string::encode(emlv('eml_fabo_pedit_subj'))."',
    `eml_pn_subj`          = '".string::encode(emlv('eml_pn_subj'))."',
    `eml_fabo_npost`       = '".string::encode(emlv('eml_fabo_npost'))."',
    `eml_fabo_tedit`       = '".string::encode(emlv('eml_fabo_tedit'))."',
    `eml_fabo_pedit`       = '".string::encode(emlv('eml_fabo_pedit'))."',
    `eml_akl_register_subj`= '".string::encode(emlv('eml_akl_register_subj'))."',
    `eml_akl_register`     = '".string::encode(emlv('eml_akl_register'))."',
    `eml_pn`               = '".string::encode(emlv('eml_pn'))."';",false,false,true);

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
    db("INSERT INTO ".dba::get('links')." SET `url` = 'http://www.dzcp.de', `text` = 'http://www.dzcp.de/banner/dzcp.gif', `banner` = 1, `beschreibung` = 'deV!L`z Clanportal';",false,false,true);
    db("INSERT INTO ".dba::get('links')." SET `url` = 'http://www.my-starmedia.de', `text` = 'http://www.my-starmedia.de/extern/b3/b3.gif', `banner` = 1, `beschreibung` = '<b>my-STARMEDIA</b><br />my-STARMEDIA.de - DZCP Mods and Coding';",false,false,true);

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

    //===============================================================
    //-> Rechte =====================================================
    //===============================================================
    db("INSERT INTO `".dba::get('permissions')."` SET `user` = 1, `pos` = 0, `artikel` = 1, `awards` = 1, `activateusers` = 1, `backup` = 1, `clear` = 1, `config` = 1, `contact` = 1, `clanwars` = 1, `clankasse` = 1, `downloads` = 1, `editkalender` = 1, `editserver` = 1, `editteamspeak` = 1, `editsquads` = 1, `editusers` = 1, `editor` = 1, `forum` = 1, `gallery` = 1, `gb` = 1, `gs_showpw` = 1, `glossar` = 1, `impressum` = 1, `intforum` = 1, `intnews` = 1, `joinus` = 1, `links` = 1, `news` = 1, `newsletter` = 1, `partners` = 1, `profile` = 1, `protocol` = 1, `rankings` = 1, `receivecws` = 1, `serverliste` = 1, `smileys` = 1, `sponsors` = 1, `shoutbox` = 1, `support` = 1, `votes` = 1, `votesadmin` = 1;",false,false,true);

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
    `language` = 'default', `regdatum`  = '".time()."', `email` = '".$db_infos['email']."', `level` = 4, `sex` = 0, `position` = 1, `status` = 1, `time` = '".time()."', `pnmail` = 1, `profile_access` = 0, `rss_key` = '".mkpwd(8,false)."';",false,false,true);
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
    //-> Config =====================================================
    //===============================================================
    db("INSERT INTO `".dba::get('config')."` SET `id` = 1, `upicsize` = 100, `gallery` = 4, `m_gallery` = 12, `m_usergb` = 10, `m_clanwars` = 10, `maxshoutarchiv` = 20, `m_clankasse` = 20, `m_awards` = 15, `m_userlist` = 40, `maxwidth` = 400, `shout_max_zeichen` = 100,
     `l_servernavi` = 22, `m_adminnews` = 20, `m_shout` = 10, `m_comments` = 10, `m_archivnews` = 30, `m_gb` = 10, `m_fthreads` = 20, `m_fposts` = 10, `m_news` = 5, `f_forum` = 20, `l_shoutnick` = 20, `f_gb` = 20, `f_membergb` = 20, `f_shout` = 20, `f_newscom` = 20,
     `f_cwcom` = 20, `f_artikelcom` = 20, `f_downloadcom` = 20, `l_newsadmin` = 20, `l_shouttext` = 22, `l_newsarchiv` = 20, `l_forumtopic` = 20, `l_forumsubtopic` = 20, `l_clanwars` = 30, `m_gallerypics` = 5, `m_lnews` = 6, `m_topdl` = 5, `m_ftopics` = 6,
     `m_lwars` = 6, `m_nwars` = 6, `l_topdl` = 20, `l_ftopics` = 28, `l_lnews` = 22, `l_lwars` = 12, `l_nwars` = 12, `l_lreg` = 12, `m_lreg` = 5, `m_artikel` = 15, `m_cwcomments` = 10, `m_adminartikel` = 15, `securelogin` = ".$db_infos['loginsec'].",
     `allowhover` = 1, `teamrow` = 3, `l_lartikel` = 18, `m_lartikel` = 5, `m_events` = 5, `m_away` = 10, `cache_engine` = '".(is_zs() ? 'zenddisk' : 'file')."', `cache_teamspeak` = 30, `cache_server` = 30, `cache_news` = 5, `direct_refresh` = 0, `news_feed` = 1;",false,false,true);

    //===============================================================
    //-> Sponsoren ==================================================
    //===============================================================
    db("INSERT INTO `".dba::get('sponsoren')."` SET `name` = 'DZCP', `link` = 'http://www.dzcp.de', `beschreibung` = '".string::encode("<p>deV!L'z Clanportal, das CMS for Online-Clans!</p>")."', `box` = 1, `pos` = 1;",false,false,true);
    db("INSERT INTO `".dba::get('sponsoren')."` SET `name` = 'DZCP Rotationsbanner', `link` = 'http://www.dzcp.de', `beschreibung` = '".string::encode("<p>deV!L`z Clanportal</p>")."', `banner` = 1, `blink` = 'http://www.dzcp.de/banner/dzcp.gif', `pos` = 2;",false,false,true);

    //===============================================================
    //-> Glossar ====================================================
    //===============================================================
    db("INSERT INTO `".dba::get('glossar')."` SET `word` = 'DZCP', `glossar` = '".string::encode("<p>deV!L'z Clanportal - kurz DZCP - ist ein CMS-System speziell f&uuml;r Onlinegaming Clans.</p>\r\n<p>Viele schon in der Grundinstallation vorhandene Module erleichtern die Verwaltung einer Clan-Homepage ungemein.</p>")."';",false,false,true);
}