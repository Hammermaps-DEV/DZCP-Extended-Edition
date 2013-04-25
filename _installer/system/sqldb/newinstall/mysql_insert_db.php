<?php
//===============================================================
//Insert DZCP-Database MySQL Installer
//===============================================================

function install_mysql_insert($db_infos)
{
    global $db;

    //===============================================================
    //-> Downloadkategorien =========================================
    //===============================================================
    db("INSERT INTO ".$db['dl_kat']." SET `name` = 'Downloads';",false,false,true);
    db("INSERT INTO ".$db['dl_kat']." SET `name` = 'Demos';",false,false,true);
    db("INSERT INTO ".$db['dl_kat']." SET `name` = 'Stuff';",false,false,true);

    //===============================================================
    //-> Downloads ==================================================
    //===============================================================
    db("INSERT INTO ".$db['downloads']." SET
    `download` = 'Testdownload',
    `url` = 'http://www.url.de/test.zip',
    `beschreibung` = '<p>Das ist ein Testdownload</p>',
    `kat` = '1',
    `date` = '".time()."';",false,false,true);

    //===============================================================
    //-> Forum ======================================================
    //===============================================================
    db("INSERT INTO ".$db['f_kats']." SET `kid` = 1, `name` = 'Hauptforum';",false,false,true);
    db("INSERT INTO ".$db['f_kats']." SET `kid` = 2, `name` = 'OFFtopic';",false,false,true);
    db("INSERT INTO ".$db['f_kats']." SET `kid` = 3, `name` = 'Clanforum', `intern` = '1';",false,false,true);

    //===============================================================
    //-> Newskategorien =============================================
    //===============================================================
    db("INSERT INTO ".$db['newskat']." SET `katimg` = 'hp.jpg', `kategorie` = 'Homepage';",false,false,true);

    //===============================================================
    //-> Event ======================================================
    //===============================================================
    db("INSERT INTO ".$db['events']." SET
    `datum` = ".(time()+90000).",
    `title` = 'Testevent',
    `event` = '<p>Das ist nur ein Testevent! :)</p>';",false,false,true);

    //===============================================================
    //-> Settings ===================================================
    //===============================================================
    db("INSERT INTO `".$db['settings']."` SET
    `clanname`             = '".up($db_infos['clanname'])."',
    `badwords`             = 'arsch,Arsch,arschloch,Arschloch,hure,Hure',
    `pagetitel`            = '".up($db_infos['seitentitel'])."',
    `i_domain`             = '".$_SERVER['SERVER_NAME']."',
    `domain`               = '".$_SERVER['SERVER_ADDR']."',
    `mailfrom`             = '".$db_infos['emailweb']."',
    `ts_ip`                = '80.190.204.164',
    `ts_port`              = '7000',
    `prev`                 = '".strtolower(mkpwd(3,false))."',
    `memcache_host`        = 'localhost',
    `memcache_port`        = '11211',
    `db_version`           = '1600',
    `eml_reg_subj`         = '".emlv('eml_reg_subj')."',
    `eml_pwd_subj`         = '".emlv('eml_pwd_subj')."',
    `eml_reg`              = '".emlv('eml_reg')."',
    `eml_pwd`              = '".emlv('eml_pwd')."',
    `eml_nletter_subj`     = '".emlv('eml_nletter_subj')."',
    `eml_nletter`          = '".emlv('eml_nletter')."',
    `eml_fabo_npost_subj`  = '".emlv('eml_fabo_npost_subj')."',
    `eml_fabo_tedit_subj`  = '".emlv('eml_fabo_tedit_subj')."',
    `eml_fabo_pedit_subj`  = '".emlv('eml_fabo_pedit_subj')."',
    `eml_pn_subj`          = '".emlv('eml_pn_subj')."',
    `eml_fabo_npost`       = '".emlv('eml_fabo_npost')."',
    `eml_fabo_tedit`       = '".emlv('eml_fabo_tedit')."',
    `eml_fabo_pedit`       = '".emlv('eml_fabo_pedit')."',
    `eml_pn`               = '".emlv('eml_pn')."';",false,false,true);

    //===============================================================
    //-> Forum: Kategorien ==========================================
    //===============================================================
    db("INSERT INTO ".$db['f_skats']." SET `sid` = 1, `kattopic` = 'Allgemein', `subtopic` = 'Allgemeines...', `pos` = 2;",false,false,true);
    db("INSERT INTO ".$db['f_skats']." SET `sid` = 1, `kattopic` = 'Homepage', `subtopic` = 'Kritiken/Anregungen/Bugs', `pos` = 3;",false,false,true);
    db("INSERT INTO ".$db['f_skats']." SET `sid` = 1, `kattopic` = 'Server', `subtopic` = 'Serverseitige Themen...', `pos` = 4;",false,false,true);
    db("INSERT INTO ".$db['f_skats']." SET `sid` = 1, `kattopic` = 'Spam', `subtopic` = 'Spamt die Bude voll ;)', `pos` = 5;",false,false,true);
    db("INSERT INTO ".$db['f_skats']." SET `sid` = 2, `kattopic` = 'Sonstiges', `subtopic` = '', `pos` = 6;",false,false,true);
    db("INSERT INTO ".$db['f_skats']." SET `sid` = 2, `kattopic` = 'OFFtopic', `subtopic` = '', `pos` = 7;",false,false,true);
    db("INSERT INTO ".$db['f_skats']." SET `sid` = 3, `kattopic` = 'internes Forum', `subtopic` = 'interne Angelegenheiten', `pos` = 1;",false,false,true);
    db("INSERT INTO ".$db['f_skats']." SET `sid` = 3, `kattopic` = 'Server intern', `subtopic` = 'interne Serverangelegenheiten', `pos` = 8;",false,false,true);
    db("INSERT INTO ".$db['f_skats']." SET `sid` = 3, `kattopic` = 'War Forum', `subtopic` = 'Alles &uuml;ber und rundum Clanwars', `pos` = 9;",false,false,true);

    //===============================================================
    //-> Forum: Threads =============================================
    //===============================================================
    db("INSERT INTO ".$db['f_threads']." SET
    `kid` = 1,
    `t_date` = ".(time() - 9000).",
    `topic` = 'Testeintrag',
    `t_reg` = 1,
    `t_text` = '<p>Testeintrag</p>',
    `first` = 1,
    `lp` = ".time().",
    `ip` = '".visitorIp()."'",false,false,true);


    //===============================================================
    //-> Galerie ====================================================
    //===============================================================
    db("INSERT INTO ".$db['gallery']." SET `datum` = ".time().", `kat` = 'Testgalerie', `beschreibung` = '<p>Das ist die erste Testgalerie.</p>\r\n<p>Hier seht ihr ein paar Bilder die eigentlich nur als Platzhalter dienen :)</p>';",false,false,true);

    //===============================================================
    //-> Links ======================================================
    //===============================================================
    db("INSERT INTO ".$db['links']." SET `url` = 'http://www.dzcp.de', `text` = 'http://www.dzcp.de/banner/dzcp.gif', `banner` = 1, `beschreibung` = 'deV!L`z Clanportal';",false,false,true);
    db("INSERT INTO ".$db['links']." SET `url` = 'http://www.my-starmedia.de', `text` = 'http://www.my-starmedia.de/extern/b3/b3.gif', `banner` = 1, `beschreibung` = '<b>my-STARMEDIA</b><br />my-STARMEDIA.de - DZCP Mods and Coding';",false,false,true);

    //===============================================================
    //-> LinkUs =====================================================
    //===============================================================
    db("INSERT INTO ".$db['linkus']." SET `url` = 'http://www.dzcp.de', `text` = 'http://www.dzcp.de/banner/dzcp.gif', `banner` = 1, `beschreibung` = 'deV!L`z Clanportal';",false,false,true);

    //===============================================================
    //-> Navigation =================================================
    //===============================================================
    db("INSERT INTO ".$db['navi']." SET `pos` = 1,  `kat` = 'nav_main', `name` = '_news_', `url` = '../news/', `type` = 1, `internal` = 0, `wichtig` = 0, `extended_perm` = NULL;",false,false,true);
    db("INSERT INTO ".$db['navi']." SET `pos` = 2,  `kat` = 'nav_main', `name` = '_newsarchiv_', `url` = '../news/?action=archiv', `type` = 1, `internal` = 0, `wichtig` = 0, `extended_perm` = NULL;",false,false,true);
    db("INSERT INTO ".$db['navi']." SET `pos` = 3,  `kat` = 'nav_main', `name` = '_artikel_', `url` = '../artikel/', `type` = 1, `internal` = 0, `wichtig` = 0, `extended_perm` = NULL;",false,false,true);
    db("INSERT INTO ".$db['navi']." SET `pos` = 4,  `kat` = 'nav_main', `name` = '_forum_', `url` = '../forum/', `type` = 1, `internal` = 0, `wichtig` = 0, `extended_perm` = NULL;",false,false,true);
    db("INSERT INTO ".$db['navi']." SET `pos` = 5,  `kat` = 'nav_main', `name` = '_gb_', `url` = '../gb/', `type` = 1, `internal` = 0, `wichtig` = 0, `extended_perm` = NULL;",false,false,true);
    db("INSERT INTO ".$db['navi']." SET `pos` = 6,  `kat` = 'nav_main', `name` = '_kalender_', `url` = '../kalender/', `type` = 1, `internal` = 0, `wichtig` = 0, `extended_perm` = NULL;",false,false,true);
    db("INSERT INTO ".$db['navi']." SET `pos` = 7,  `kat` = 'nav_main', `name` = '_votes_', `url` = '../votes/', `type` = 1, `internal` = 0, `wichtig` = 0, `extended_perm` = NULL;",false,false,true);
    db("INSERT INTO ".$db['navi']." SET `pos` = 8,  `kat` = 'nav_main', `name` = '_links_', `url` = '../links/', `type` = 1, `internal` = 0, `wichtig` = 0, `extended_perm` = NULL;",false,false,true);
    db("INSERT INTO ".$db['navi']." SET `pos` = 9,  `kat` = 'nav_main', `name` = '_sponsoren_', `url` = '../sponsors/', `type` = 1, `internal` = 0, `wichtig` = 0, `extended_perm` = NULL;",false,false,true);
    db("INSERT INTO ".$db['navi']." SET `pos` = 10, `kat` = 'nav_main', `name` = '_downloads_', `url` = '../downloads/', `type` = 1, `internal` = 0, `wichtig` = 0, `extended_perm` = NULL;",false,false,true);
    db("INSERT INTO ".$db['navi']." SET `pos` = 11, `kat` = 'nav_main', `name` = '_userlist_', `url` = '../user/?action=userlist', `type` = 1, `internal` = 0, `wichtig` = 0, `extended_perm` = NULL;",false,false,true);
    db("INSERT INTO ".$db['navi']." SET `pos` = 12, `kat` = 'nav_main', `name` = '_glossar_', `url` = '../glossar/', `type` = 1, `internal` = 0, `wichtig` = 0, `extended_perm` = NULL;",false,false,true);

    db("INSERT INTO ".$db['navi']." SET `pos` = 1, `kat` = 'nav_clan', `name` = '_squads_', `url` = '../squads/', `type` = 1, `internal` = 0, `wichtig` = 0, `extended_perm` = NULL;",false,false,true);
    db("INSERT INTO ".$db['navi']." SET `pos` = 2, `kat` = 'nav_clan', `name` = '_membermap_', `url` = '../membermap/', `type` = 1, `internal` = 0, `wichtig` = 0, `extended_perm` = NULL;",false,false,true);
    db("INSERT INTO ".$db['navi']." SET `pos` = 3, `kat` = 'nav_clan', `name` = '_cw_', `url` = '../clanwars/', `type` = 1, `internal` = 0, `wichtig` = 0, `extended_perm` = NULL;",false,false,true);
    db("INSERT INTO ".$db['navi']." SET `pos` = 4, `kat` = 'nav_clan', `name` = '_awards_', `url` = '../awards/', `type` = 1, `internal` = 0, `wichtig` = 0, `extended_perm` = NULL;",false,false,true);
    db("INSERT INTO ".$db['navi']." SET `pos` = 5, `kat` = 'nav_clan', `name` = '_rankings_', `url` = '../rankings/', `type` = 1, `internal` = 0, `wichtig` = 0, `extended_perm` = NULL;",false,false,true);

    db("INSERT INTO ".$db['navi']." SET `pos` = 1, `kat` = 'nav_server', `name` = '_server_', `url` = '../server/', `type` = 1, `internal` = 0, `wichtig` = 0, `extended_perm` = NULL;",false,false,true);
    db("INSERT INTO ".$db['navi']." SET `pos` = 2, `kat` = 'nav_server', `name` = '_serverlist_', `url` = '../serverliste/', `type` = 1, `internal` = 0, `wichtig` = 0, `extended_perm` = NULL;",false,false,true);
    db("INSERT INTO ".$db['navi']." SET `pos` = 3, `kat` = 'nav_server', `name` = '_ts_', `url` = '../teamspeak/', `type` = 1, `internal` = 0, `wichtig` = 0, `extended_perm` = NULL;",false,false,true);

    db("INSERT INTO ".$db['navi']." SET `pos` = 1, `kat` = 'nav_misc', `name` = '_galerie_', `url` = '../gallery/', `type` = 1, `internal` = 0, `wichtig` = 0, `extended_perm` = NULL;",false,false,true);
    db("INSERT INTO ".$db['navi']." SET `pos` = 2, `kat` = 'nav_misc', `name` = '_kontakt_', `url` = '../contact/', `type` = 1, `internal` = 0, `wichtig` = 0, `extended_perm` = NULL;",false,false,true);
    db("INSERT INTO ".$db['navi']." SET `pos` = 3, `kat` = 'nav_misc', `name` = '_joinus_', `url` = '../contact/?action=joinus', `type` = 1, `internal` = 0, `wichtig` = 0, `extended_perm` = NULL;",false,false,true);
    db("INSERT INTO ".$db['navi']." SET `pos` = 4, `kat` = 'nav_misc', `name` = '_fightus_', `url` = '../contact/?action=fightus', `type` = 1, `internal` = 0, `wichtig` = 0, `extended_perm` = NULL;",false,false,true);
    db("INSERT INTO ".$db['navi']." SET `pos` = 5, `kat` = 'nav_misc', `name` = '_linkus_', `url` = '../linkus/', `type` = 1, `internal` = 0, `wichtig` = 0, `extended_perm` = NULL;",false,false,true);
    db("INSERT INTO ".$db['navi']." SET `pos` = 6, `kat` = 'nav_misc', `name` = '_stats_', `url` = '../stats/', `type` = 1, `internal` = 0, `wichtig` = 0, `extended_perm` = NULL;",false,false,true);
    db("INSERT INTO ".$db['navi']." SET `pos` = 7, `kat` = 'nav_misc', `name` = '_impressum_', `url` = '../impressum/', `type` = 1, `internal` = 0, `wichtig` = 0, `extended_perm` = NULL;",false,false,true);

    db("INSERT INTO ".$db['navi']." SET `pos` = 1, `kat` = 'nav_user', `name` = '_lobby_', `url` = '../user/?action=userlobby', `type` = 1, `internal` = 0, `wichtig` = 0, `extended_perm` = NULL;",false,false,true);
    db("INSERT INTO ".$db['navi']." SET `pos` = 2, `kat` = 'nav_user', `name` = '_nachrichten_', `url` = '../user/?action=msg', `type` = 1, `internal` = 0, `wichtig` = 0, `extended_perm` = NULL;",false,false,true);
    db("INSERT INTO ".$db['navi']." SET `pos` = 3, `kat` = 'nav_user', `name` = '_buddys_', `url` = '../user/?action=buddys', `type` = 1, `internal` = 0, `wichtig` = 0, `extended_perm` = NULL;",false,false,true);
    db("INSERT INTO ".$db['navi']." SET `pos` = 4, `kat` = 'nav_user', `name` = '_edit_profile_', `url` = '../user/?action=editprofile', `type` = 1, `internal` = 0, `wichtig` = 0, `extended_perm` = NULL;",false,false,true);
    db("INSERT INTO ".$db['navi']." SET `pos` = 5, `kat` = 'nav_user', `name` = '_logout_', `url` = '../user/?action=logout', `type` = 1, `internal` = 0, `wichtig` = 1, `extended_perm` = NULL;",false,false,true);

    db("INSERT INTO ".$db['navi']." SET `pos` = 1, `kat` = 'nav_member', `name` = '_clankasse_', `url` = '../clankasse/', `type` = 1, `internal` = 1, `wichtig` = 0, `extended_perm` = 'clankasse';",false,false,true);
    db("INSERT INTO ".$db['navi']." SET `pos` = 2, `kat` = 'nav_member', `name` = '_taktiken_', `url` = '../taktik/', `type` = 1, `internal` = 1, `wichtig` = 0, `extended_perm` = 'edittactics';",false,false,true);

    db("INSERT INTO ".$db['navi']." SET `pos` = 1, `kat` = 'nav_main', `name` = '_news_send_', `url` = '../news/?action=send', `type` = 1, `internal` = 0, `wichtig` = 0, `extended_perm` = NULL;",false,false,true);
    db("INSERT INTO ".$db['navi']." SET `pos` = 1, `kat` = 'nav_trial', `name` = '_awaycal_', `url` = '../away/', `type` = 2, `internal` = 1, `wichtig` = 0, `extended_perm` = NULL;",false,false,true);
    db("INSERT INTO ".$db['navi']." SET `pos` = 1, `kat` = 'nav_admin', `name` = '_admin_', `url` = '../admin/', `type` = 1, `internal` = 1, `wichtig` = 1, `extended_perm` = NULL;",false,false,true);

    //===============================================================
    //-> News =======================================================
    //===============================================================
    db("INSERT INTO ".$db['news']." (`id`, `autor`, `datum`, `kat`, `sticky`, `titel`, `intern`, `text`, `klapplink`, `klapptext`, `link1`, `url1`, `link2`, `url2`, `link3`, `url3`, `viewed`, `public`, `timeshift`) VALUES
    (NULL, '1', '".time()."', 1, 0, 'deV!L`z Clanportal - Extended Edition', 0, '<p>deV!L`z Clanportal - Extended Edition wurde erfolgreich installiert!</p><p>Bei Fragen oder Problemen kannst du gerne das Forum unter <a href=\"http://www.dzcp.de/\" target=\"_blank\">www.dzcp.de</a> kontaktieren.</p><p>Mehr Designtemplates und Modifikationen findest du unter <a href=\"http://www.templatebar.de/\" target=\"_blank\" title=\"Templates, Designs &amp; Modifikationen\">www.templatebar.de</a>.</p><p><br /></p><p>Viel Spass mit dem DZCP w&uuml;nscht dir das Team von www.dzcp.de.</p>', '', '', 'www.dzcp.de', 'http://www.dzcp.de', 'TEMPLATEbar.de', 'http://www.templatebar.de', '', '', 0, 1, 0);",false,false,true);

    //===============================================================
    //-> Artikel ====================================================
    //===============================================================
    db("INSERT INTO ".$db['artikel']." (`id`, `autor`, `datum`, `kat`, `titel`, `text`, `link1`, `url1`, `link2`, `url2`, `link3`, `url3`, `viewed`, `public`) VALUES
    (NULL, '1', '".time()."', 1, 'Testartikel', '<p>Hier k&ouml;nnte dein Artikel stehen!</p>\r\n<p> </p>', '', '', '', '', '', '', 0, 1);",false,false,true);

    //===============================================================
    //-> Profilfelder ===============================================
    //===============================================================
    db("INSERT INTO ".$db['profile']." (`id`, `kid`, `name`, `feldname`, `type`, `shown`) VALUES
    (NULL, 1, '_job_', 'job', 1, 1),
    (NULL, 1, '_hobbys_', 'hobbys', 1, 1),
    (NULL, 1, '_motto_', 'motto', 1, 1),
    (NULL, 2, '_exclans_', 'ex', 1, 1),
    (NULL, 4, '_drink_', 'drink', 1, 1),
    (NULL, 4, '_essen_', 'essen', 1, 1),
    (NULL, 4, '_film_', 'film', 1, 1),
    (NULL, 4, '_musik_', 'musik', 1, 1),
    (NULL, 4, '_song_', 'song', 1, 1),
    (NULL, 4, '_buch_', 'buch', 1, 1),
    (NULL, 4, '_autor_', 'autor', 1, 1),
    (NULL, 4, '_person_', 'person', 1, 1),
    (NULL, 4, '_sport_', 'sport', 1, 1),
    (NULL, 4, '_sportler_', 'sportler', 1, 1),
    (NULL, 4, '_auto_', 'auto', 1, 1),
    (NULL, 4, '_game_', 'game', 1, 1),
    (NULL, 4, '_favoclan_', 'favoclan', 1, 1),
    (NULL, 4, '_spieler_', 'spieler', 1, 1),
    (NULL, 4, '_map_', 'map', 1, 1),
    (NULL, 4, '_waffe_', 'waffe', 1, 1),
    (NULL, 5, '_system_', 'os', 1, 1),
    (NULL, 5, '_board_', 'board', 1, 1),
    (NULL, 5, '_cpu_', 'cpu', 1, 1),
    (NULL, 5, '_ram_', 'ram', 1, 1),
    (NULL, 5, '_graka_', 'graka', 1, 1),
    (NULL, 5, '_hdd_', 'hdd', 1, 1),
    (NULL, 5, '_monitor_', 'monitor', 1, 1),
    (NULL, 5, '_maus_', 'maus', 1, 1),
    (NULL, 5, '_mauspad_', 'mauspad', 1, 1),
    (NULL, 5, '_headset_', 'headset', 1, 1),
    (NULL, 5, '_inet_', 'inet', 1, 1);",false,false,true);

    //===============================================================
    //-> Partnerbuttons =============================================
    //===============================================================
    db("INSERT INTO `".$db['partners']."` (`id`, `link`, `banner`, `textlink`) VALUES
    (NULL, 'http://www.my-starmedia.de', 'my-starmedia.gif', 0),
    (NULL, 'http://www.hogibo.net', 'hogibo.gif', 0),
    (NULL, 'http://www.codeking.eu', 'codeking.gif', 0),
    (NULL, 'http://www.dzcp.de', 'dzcp.gif', 0),
    (NULL, 'http://spenden.dzcp.de', 'spenden.gif', 0),
    (NULL, 'http://www.modsbar.de', 'mb_88x32.png', 0),
    (NULL, 'http://www.templatebar.de', 'tb_88x32.png', 0);",false,false,true);

    //===============================================================
    //-> Rechte =====================================================
    //===============================================================
    db("INSERT INTO `".$db['permissions']."` (`id`, `user`, `pos`, `artikel`, `awards`, `backup`, `clear`, `config`, `contact`, `clanwars`, `clankasse`, `downloads`, `editkalender`, `editserver`, `edittactics`, `editsquads`, `editusers`, `editor`, `forum`, `gallery`, `gb`, `gs_showpw`, `glossar`, `impressum`, `intforum`, `intnews`, `joinus`, `links`, `news`, `newsletter`, `partners`, `profile`, `protocol`, `rankings`, `receivecws`, `serverliste`, `smileys`, `sponsors`, `shoutbox`, `support`, `votes`, `votesadmin`) VALUES
    (NULL, '1', '0', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1');",false,false,true);

    //===============================================================
    //-> Positionen =================================================
    //===============================================================
    db("INSERT INTO ".$db['pos']." (`id`, `pid`, `position`, `nletter`) VALUES
    (NULL, 1, 'Leader', 0),
    (NULL, 2, 'Co-Leader', 0),
    (NULL, 3, 'Webmaster', 0),
    (NULL, 4, 'Member', 0);",false,false,true);

    //===============================================================
    //-> Server =====================================================
    //===============================================================
    db("INSERT INTO `".$db['server']."` (`id`, `status`, `shown`, `navi`, `name`, `ip`, `port`, `pwd`, `game`, `qport`) VALUES
    (NULL, 'bf2', 1, 1, 'Battlefield-Basis.de II von Hogibo.net', '80.190.178.115', 9260, '', 'bf2.gif', '');",false,false,true);

    //===============================================================
    //-> Server List ================================================
    //===============================================================
    db("INSERT INTO ".$db['serverliste']." (`id`, `datum`, `clanname`, `clanurl`, `ip`, `port`, `pwd`, `checked`, `slots`) VALUES
    (NULL, 1298817167, '[-tHu-] teamHanau', 'http://www.thu-clan.de', '82.98.216.10', '27015', '', 1, '17');",false,false,true);

    //===============================================================
    //-> Shoutbox ===================================================
    //===============================================================
    db("INSERT INTO ".$db['shout']." (`id`, `datum`, `nick`, `email`, `text`, `ip`) VALUES (NULL, 1298817167, 'deV!L', 'webmaster@dzcp.de', 'Viel Gl&uuml;ck und Erfolg mit eurem Clan!', '');",false,false,true);

    //===============================================================
    //-> Squads =====================================================
    //===============================================================
    db("INSERT INTO ".$db['squads']." (`id`, `name`, `game`, `icon`, `pos`, `shown`, `navi`, `status`, `beschreibung`, `team_show`) VALUES (NULL, 'Testsquad', 'Counter-Strike', 'cs.gif', 1, 1, 1, 1, NULL, 1);",false,false,true);

    //===============================================================
    //-> Squadusers =================================================
    //===============================================================
    db("INSERT INTO ".$db['squaduser']." (`id`, `user`, `squad`) VALUES (NULL, 1, 1)",false,false,true);

    //===============================================================
    //-> Userstats ==================================================
    //===============================================================
    db("INSERT INTO ".$db['userstats']." (`id`, `user`, `logins`, `writtenmsg`, `lastvisit`, `hits`, `votes`, `profilhits`, `forumposts`, `cws`) VALUES (NULL, 1, 0, 0, 0, 1, 0, 0, 0, 0);",false,false,true);

    //===============================================================
    //-> Users ======================================================
    //===============================================================
    db("INSERT INTO `".$db['users']."` (`id`, `user`, `nick`, `pwd`, `sessid`, `pkey`, `country`, `ip`, `regdatum`, `email`, `icq`, `xfire`, `steamid`, `level`, `rlname`, `city`, `sex`, `bday`, `hobbys`, `motto`, `hp`, `cpu`, `ram`, `monitor`, `maus`, `mauspad`, `headset`, `board`, `os`, `graka`, `hdd`, `inet`, `signatur`, `position`, `status`, `ex`, `job`, `time`, `listck`, `online`, `nletter`, `whereami`, `drink`, `essen`, `film`, `musik`, `song`, `buch`, `autor`, `person`, `sport`, `sportler`, `auto`, `game`, `favoclan`, `spieler`, `map`, `waffe`, `rasse`, `url2`, `url3`, `beschreibung`, `gmaps_koord`, `pnmail`, `profile_access`) VALUES
    (NULL, '".$db_infos['login']."', '".up($db_infos['nick'])."', '".($pwd_hash=pass_hash($db_infos['pwd'],2))."', '', '', 'de', '', 0, '".$db_infos['email']."', '', '', '', '4', '', '', 0, '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', NULL, 1, 1, '', '', ".time().", 0, 0, 1, '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', NULL, '', 1, 0);",false,false,true);

    //Login NOW
    if($db_infos['loginnow'])
    {
        $_SESSION['id']         = ($userid=mysql_insert_id());
        $_SESSION['pwd']        = $pwd_hash;
        $_SESSION['lastvisit']  = 0;
        $_SESSION['ip']         = ($userip=visitorIp());
        db("UPDATE ".$db['userstats']." SET `logins` = logins+1 WHERE user = ".convert::ToInt($userid));
        db("UPDATE ".$db['users']." SET `online` = '1', `sessid` = '".session_id()."', `ip` = '".$userip."' WHERE id = ".convert::ToInt($userid));
        wire_ipcheck("login(".convert::ToInt($userid).")");
    }

    //===============================================================
    //-> Votes ======================================================
    //===============================================================
    db("INSERT INTO ".$db['votes']." (`id`, `datum`, `titel`, `intern`, `menu`, `closed`, `von`, `forum`) VALUES (NULL, ".time().", 'Wie findet ihr unsere Seite?', 0, 1, 0, 1, 0);",false,false,true);

    //===============================================================
    //-> Vote Möglichkeit ===========================================
    //===============================================================
    db("INSERT INTO ".$db['vote_results']." (`id`, `vid`, `what`, `sel`, `stimmen`) VALUES
    (NULL, 1, 'a1', 'Gut', 0), (NULL, 1, 'a2', 'Schlecht', 0);",false,false,true);

    //===============================================================
    //-> Navigation Kategorien ======================================
    //===============================================================
    db("INSERT INTO ".$db['navi_kats']." (`id`, `name`, `placeholder`, `level`) VALUES
    (NULL, 'Clan Navigation', 'nav_clan', 0),
    (NULL, 'Main Navigation', 'nav_main', 0),
    (NULL, 'Server Navigation', 'nav_server', 0),
    (NULL, 'Misc Navigation', 'nav_misc', 0),
    (NULL, 'Trial Navigation', 'nav_trial', 2),
    (NULL, 'Admin Navigation', 'nav_admin', 4),
    (NULL, 'User Navigation', 'nav_user', 1),
    (NULL, 'Member Navigation', 'nav_member', 3);",false,false,true);

    //===============================================================
    //-> Clanwars ===================================================
    //===============================================================
    db("INSERT INTO ".$db['cw']." (`id`, `squad_id`, `gametype`, `gcountry`, `matchadmins`, `lineup`, `glineup`, `datum`, `clantag`, `gegner`, `url`, `xonx`, `liga`, `punkte`, `gpunkte`, `maps`, `serverip`, `servername`, `serverpwd`, `bericht`, `top`) VALUES
    (NULL, 1, '', 'de', '', '', '', ".(time()-90000).", 'DZCP', 'deV!L`z Clanportal', 'http://www.dzcp.de', '5on5', 'DZCP', 0, 21, 'de_dzcp', '', '', '', '', 1);",false,false,true);

    //===============================================================
    //-> Clankassenkategorien =======================================
    //===============================================================
    db("INSERT INTO ".$db['c_kats']." (`id`, `kat`) VALUES
    (NULL, 'Servermiete'),
    (NULL, 'Serverbeitrag');",false,false,true);

    //===============================================================
    //-> Config =====================================================
    //===============================================================
    db("INSERT INTO `".$db['config']."` (`id`, `upicsize`, `gallery`, `m_gallery`, `m_usergb`, `m_clanwars`, `maxshoutarchiv`, `m_clankasse`, `m_awards`, `m_userlist`, `maxwidth`, `shout_max_zeichen`, `l_servernavi`, `m_adminnews`, `m_shout`, `m_comments`, `m_archivnews`, `m_gb`, `m_fthreads`, `m_fposts`, `m_news`, `f_forum`, `l_shoutnick`, `f_gb`, `f_membergb`, `f_shout`, `f_newscom`, `f_cwcom`, `f_artikelcom`, `f_downloadcom`, `l_newsadmin`, `l_shouttext`, `l_newsarchiv`, `l_forumtopic`, `l_forumsubtopic`, `l_clanwars`, `m_gallerypics`, `m_lnews`, `m_topdl`, `m_ftopics`, `m_lwars`, `m_nwars`, `l_topdl`, `l_ftopics`, `l_lnews`, `l_lwars`, `l_nwars`, `l_lreg`, `m_lreg`, `m_artikel`, `m_cwcomments`, `m_adminartikel`, `securelogin`, `allowhover`, `teamrow`, `l_lartikel`, `m_lartikel`, `m_events`, `m_away`, `cache_engine`, `cache_teamspeak`, `cache_server`, `cache_news`, `direct_refresh`, `news_feed`) VALUES
    (1, 100, 4, 36, 10, 10, 20, 20, 15, 40, 400, 100, 22, 20, 10, 10, 30, 10, 20, 10, 5, 20, 20, 20, 20, 20, 20, 20, 20, 20, 20, 22, 20, 20, 20, 30, 5, 6, 5, 6, 6, 6, 20, 28, 22, 12, 12, 12, 5, 15, 10, 15, ".$db_infos['loginsec'].", 1, 3, 18, 5, 5, 10, 'file', 30, 30, 5, 0, 1);",false,false,true);

    //===============================================================
    //-> Sponsoren ==================================================
    //===============================================================
    db("INSERT INTO ".$db['sponsoren']." (`id`, `name`, `link`, `beschreibung`, `site`, `send`, `slink`, `banner`, `bend`, `blink`, `box`, `xend`, `xlink`, `pos`, `hits`) VALUES
    (1, 'DZCP', 'http://www.dzcp.de', '<p>deV!L''z Clanportal, das CMS for Online-Clans!</p>', 0, '', '', 0, '', '', 1, 'gif', '', 7, 0),
    (2, 'DZCP Rotationsbanner', 'http://www.dzcp.de', '<p>deV!L`z Clanportal</p>', 0, '', '', 1, '', 'http://www.dzcp.de/banner/dzcp.gif', 0, '', '', 5, 0);",false,false,true);

    //===============================================================
    //-> Glossar ====================================================
    //===============================================================
    db("INSERT INTO `".$db['glossar']."` (`id`, `word`, `glossar`) VALUES
    (NULL, 'DZCP', '<p>deV!L`z Clanportal - kurz DZCP - ist ein CMS-System speziell f&uuml;r Onlinegaming Clans.</p>\r\n<p>Viele schon in der Grundinstallation vorhandene Module erleichtern die Verwaltung einer Clan-Homepage ungemein.</p>');",false,false,true);
}
?>