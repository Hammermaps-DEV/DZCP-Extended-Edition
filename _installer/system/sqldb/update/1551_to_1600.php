<?php
$versions[3] = array('update_id' => 3, 3 => '1.5.5.x', "version_list" => 'v1.5.5.x', 'call' => '155x_1600', 'dbv' => false); //Update Info

//Update von V1.5.5.x auf V1.6.0.0 DZCP-Extended Edition
function install_155x_1600_update()
{
    db("ALTER TABLE `".dba::get('f_threads')."` CHANGE `edited` `edited` TEXT CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL",false,false,true);
    db("ALTER TABLE `".dba::get('users')."` CHANGE `whereami` `whereami` TEXT CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL",false,false,true);
    db("ALTER TABLE `".dba::get('users')."` CHANGE `hlswid` `xfire` VARCHAR( 100 ) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT ''",false,false,true);
    db("ALTER TABLE `".dba::get('downloads')."` ADD `last_dl` INT( 20 ) NOT NULL DEFAULT '0' AFTER `date`",false,false,true);
    db("ALTER TABLE `".dba::get('settings')."` CHANGE `i_autor` `i_autor` TEXT CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL",false,false,true);
    db("ALTER TABLE `".dba::get('gb')."` CHANGE `hp` `hp` VARCHAR(130) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL",false,false,true);
    db("ALTER TABLE `".dba::get('settings')."` ADD `urls_linked` INT(1) NOT NULL DEFAULT '1', ADD `ts_customicon` INT(1) NOT NULL DEFAULT '1' AFTER `ts_version`, ADD `ts_showchannel` INT(1) NOT NULL DEFAULT '0' AFTER `ts_customicon`",false,false,true);
    db("ALTER TABLE `".dba::get('msg')."` CHANGE `see_u` `see_u` INT( 1 ) NOT NULL DEFAULT '0'",false,false,true);
    db("ALTER TABLE `".dba::get('msg')."` CHANGE `page` `page` INT( 11 ) NOT NULL DEFAULT '0'",false,false,true);
    db("ALTER TABLE `".dba::get('away')."` CHANGE `lastedit` `lastedit` TEXT CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL",false,false,true);
    db("ALTER TABLE `".dba::get('settings')."` DROP `pfad`",false,false,true);
    db("ALTER TABLE `".dba::get('newskat')."` CHANGE `katimg` `katimg` VARCHAR( 100 ) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT ''",false,false,true);
    db("ALTER TABLE `".dba::get('newskat')."` CHANGE `kategorie` `kategorie` VARCHAR( 60 ) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT ''",false,false,true);
    db("ALTER TABLE `".dba::get('server')."` CHANGE `name` `name` TEXT CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL",false,false,true);
    db("ALTER TABLE `".dba::get('server')."` DROP `bl_file`, DROP `bl_path`, DROP `ftp_pwd`, DROP `ftp_login`, DROP `ftp_host`;",false,false,true);
    db("ALTER TABLE `".dba::get('serverliste')."` CHANGE `clanname` `clanname` VARCHAR( 50 ) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT ''",false,false,true);
    db("ALTER TABLE `".dba::get('serverliste')."` CHANGE `datum` `datum` INT( 11 ) NOT NULL DEFAULT '0'",false,false,true);
    db("ALTER TABLE `".dba::get('settings')."` ADD `db_version` VARCHAR( 5 ) NOT NULL DEFAULT '00000'");
    db("ALTER TABLE `".dba::get('config')."` ADD `cache_engine` varchar(50) NOT NULL DEFAULT 'file'",false,false,true);
    db("ALTER TABLE `".dba::get('settings')."` ADD `memcache_host` VARCHAR( 50 ) NOT NULL DEFAULT '';",false,false,true);
    db("ALTER TABLE `".dba::get('settings')."` ADD `memcache_port` INT( 11 ) NOT NULL DEFAULT '11211';",false,false,true);
    db("ALTER TABLE `".dba::get('config')."` ADD `cache_news` INT( 10 ) NOT NULL DEFAULT '5' AFTER `cache_server`;",false,false,true);
    db("ALTER TABLE `".dba::get('config')."` ADD `news_feed` INT( 1 ) NOT NULL DEFAULT '1'",false,false,true);
    db("ALTER TABLE `".dba::get('settings')."` DROP `gmaps_key`",false,false,true);
    db("ALTER TABLE `".dba::get('users')."` ADD `pkey` VARCHAR( 50 ) NOT NULL DEFAULT '' AFTER `sessid`;",false,false,true);
    db("ALTER TABLE `".dba::get('navi')."` ADD `extended_perm` varchar(50) DEFAULT NULL AFTER `editor`;",false,false,true);
    db("TABLE `".dba::get('settings')."` DROP `gametiger`,`squadtmpl`,`balken_vote`,`balken_vote_menu`,`balken_cw`;",false,false,true);
    db("ALTER TABLE `".dba::get('newscomments')."` CHANGE `editby` `editby` TEXT CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL",false,false,true);
    db("ALTER TABLE `".dba::get('acomments')."` CHANGE `editby` `editby` TEXT CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL",false,false,true);
    db("ALTER TABLE `".dba::get('cw_comments')."` CHANGE `editby` `editby` TEXT CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL",false,false,true);
    db("ALTER TABLE `".dba::get('gb')."` CHANGE `editby` `editby` TEXT CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL",false,false,true);
    db("ALTER TABLE `".dba::get('usergb')."` CHANGE `editby` `editby` TEXT CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL",false,false,true);
    db("ALTER TABLE `".dba::get('usergb')."` ADD INDEX ( `user` );",false,false,true);
    db("ALTER TABLE `".dba::get('users')."` CHANGE `gmaps_koord` `gmaps_koord` VARCHAR( 255 ) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT ''",false,false,true);
    db("ALTER TABLE `".dba::get('users')."` ADD `pwd_encoder` INT( 1 ) NOT NULL DEFAULT '0' AFTER `pwd`;",false,false,true);
    db("ALTER TABLE `".dba::get('settings')."` ADD `default_pwd_encoder` INT( 1 ) NOT NULL DEFAULT '2' AFTER `urls_linked`;",false,false,true);
    db("ALTER TABLE `".dba::get('artikel')."` ADD `viewed` INT( 11 ) NOT NULL DEFAULT '0' AFTER `url3`;",false,false,true);
    db("ALTER TABLE `".dba::get('config')."` ADD `f_downloadcom` INT( 5 ) NOT NULL DEFAULT '20' AFTER `f_artikelcom`;",false,false,true);
    db("ALTER TABLE `".dba::get('settings')."` ADD `reg_dlcomments` INT( 1 ) NOT NULL DEFAULT '1' AFTER `reg_newscomments`;",false,false,true);
    db("ALTER TABLE `".dba::get('downloads')."` ADD `comments` INT( 1 ) NOT NULL DEFAULT '0' AFTER `last_dl`;",false,false,true);
    db("ALTER TABLE `".dba::get('f_posts')."` CHANGE `edited` `edited` TEXT CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL",false,false,true);
    db("ALTER TABLE `".dba::get('rankings')."` CHANGE `lastranking` `lastranking` INT( 10 ) NOT NULL DEFAULT '0'",false,false,true);
    db("ALTER TABLE `".dba::get('users')."` ADD `profile_access` INT( 1 ) NOT NULL DEFAULT '0' AFTER `pnmail`;",false,false,true);
    db("ALTER TABLE `".dba::get('config')."` ADD `m_gallery` INT( 11 ) NOT NULL DEFAULT '36' AFTER `gallery`;",false,false,true);
    db("ALTER TABLE `".dba::get('news')."` ADD `comments` INT( 1 ) NOT NULL DEFAULT '1' AFTER `timeshift`;",false,false,true);
    db("ALTER TABLE `".dba::get('users')."` ADD `rss_key` VARCHAR( 50 ) NOT NULL DEFAULT '' AFTER `profile_access`;",false,false,true);
    db("ALTER TABLE `".dba::get('artikel')."` ADD `comments` INT( 1 ) NOT NULL DEFAULT '1' AFTER `public`;",false,false,true);
    db("ALTER TABLE `".dba::get('config')."` DROP `m_banned`;",false,false,true);
    db("ALTER TABLE `".dba::get('config')."` DROP `l_team`;",false,false,true);
    db("ALTER TABLE `".dba::get('server')."` ADD `custom_icon` VARCHAR( 30 ) NOT NULL DEFAULT '' AFTER `qport`;",false,false,true);
    db("ALTER TABLE `".dba::get('users')."` ADD `language` VARCHAR( 15 ) NOT NULL DEFAULT 'default' AFTER `country`;",false,false,true);

    // Add UNIQUE INDEX
    if(db("SELECT id FROM `".dba::get('config')."`",true) >= 2)
    {
        $get_old = db("SELECT * FROM `".dba::get('config')."` LIMIT 0 , 1",false,true);
        db("TRUNCATE TABLE `".dba::get('config')."`",false,false,true);
        db("ALTER TABLE `".dba::get('config')."` ADD UNIQUE (`id`)",false,false,true);
        $count = count($get_old); $i = 1; $set = '';
        foreach ($get_old as $key => $var)
        {
            $i++;
            if($i <= $count)
                $set .= $key." = '".$var."', ";
            else
                $set .= $key." = '".$var."';";
        }

        db("INSERT INTO `".dba::get('config')."` SET ".$set,false,false,true);
    }
    else
        db("ALTER TABLE `".dba::get('config')."` ADD UNIQUE (`id`)",false,false,true);

    // Add UNIQUE INDEX
    if(db("SELECT id FROM `".dba::get('settings')."`",true) >= 2)
    {
        $get_old = db("SELECT * FROM `".dba::get('settings')."` LIMIT 0 , 1",false,true);
        db("TRUNCATE TABLE `".dba::get('settings')."`",false,false,true);
        db("ALTER TABLE `".dba::get('settings')."` ADD UNIQUE (`id`)",false,false,true);
        $count = count($get_old); $i = 1; $set = '';
        foreach ($get_old as $key => $var)
        {
            $i++;
            if($i <= $count)
                $set .= $key." = '".$var."', ";
            else
                $set .= $key." = '".$var."';";
        }

        db("INSERT INTO `".dba::get('settings')."` SET ".$set,false,false,true);
    }
    else
        db("ALTER TABLE `".dba::get('settings')."` ADD UNIQUE (`id`)",false,false,true);

    // Schreibe DB Version in Datenbank
    db("UPDATE ".dba::get('settings')." SET `db_version` = '1600' WHERE id = 1",false,false,true);

    // Lösche dzcp_banned Tabelle
    dba::set('banned','banned'); //Tempadd
    db("DROP TABLE `".dba::get('banned')."`",false,false,true);

    // Forum Sortieren
    db("ALTER TABLE ".dba::get('f_skats')." ADD `pos` int(5) NOT NULL",false,false,true);

    // Forum Sortieren funktion: schreibe id von spalte in pos feld um konflikte zu vermeiden!
    $qry = db("SELECT id FROM `".dba::get('f_skats')."`");
    if(_rows($qry) >= 1)
    {  while($get = _fetch($qry)) { db("UPDATE ".dba::get('f_skats')." SET `pos` = '".$get['id']."' WHERE `id` = '".$get['id']."';",false,false,true); } }

    // Update News einsenden Link * wenn vorhanden
    $qry = db("SELECT id,url FROM `".dba::get('navi')."` WHERE `name` = '_news_send_'");
    if(_rows($qry) >= 1)
    {  while($get = _fetch($qry)) { if($get['url'] == '../news/send.php') db("UPDATE ".dba::get('navi')." SET `url` = '../news/?action=send' WHERE `id` = '".$get['id']."';",false,false,true); } }

    // Update setze MD5 Encoder für alte User & Gen. Private RSS Key
    $qry = db("SELECT id FROM `".dba::get('users')."`");
    if(_rows($qry) >= 1)
    {  while($get = _fetch($qry)) { db("UPDATE ".dba::get('users')." SET `pwd_encoder` = 0, `rss_key`  = '".md5(mkpwd())."' WHERE `id` = '".$get['id']."';",false,false,true); } }

    //===============================================================
    //-> Cache ======================================================
    //===============================================================
    db("DROP TABLE IF EXISTS `".dba::get('cache')."`;",false,false,true);
    db("CREATE TABLE IF NOT EXISTS `".dba::get('cache')."` (
      `qry` varchar(32) NOT NULL DEFAULT '',
      `data` longblob,
      `timestamp` varchar(16) DEFAULT NULL,
      `cacheTime` varchar(16) DEFAULT NULL,
      `array` varchar(1) NOT NULL DEFAULT '0',
      `stream_hash` varchar(60) NOT NULL DEFAULT '',
      `original_file` varchar(255) NOT NULL DEFAULT '',
      PRIMARY KEY (`qry`)
    ) ".get_db_engine($_SESSION['mysql_dbengine'])." DEFAULT CHARSET=latin1;",false,false,true);

    //===============================================================
    //-> Click IP Counter ===========================================
    //===============================================================
    db("DROP TABLE IF EXISTS `".dba::get('clicks_ips')."`;");
    db("CREATE TABLE IF NOT EXISTS `".dba::get('clicks_ips')."` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `ip` varchar(15) NOT NULL DEFAULT '000.000.000.000',
    `uid` int(11) NOT NULL DEFAULT '0',
    `ids` int(11) NOT NULL DEFAULT '0',
    `side` varchar(30) NOT NULL DEFAULT '',
    `time` int(20) NOT NULL DEFAULT '0',
    PRIMARY KEY (`id`),
    KEY `ip` (`ip`)
    ) ".get_db_engine($_SESSION['mysql_dbengine'])." DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;",false,false,true);

    // Ersetze Permissions Tabelle
    $qry = db("SELECT * FROM `".dba::get('permissions')."`");
    if(_rows($qry) >= 1)
    {
        $cache_array_sql = array();
        while($get = _fetch($qry))
        {
            $cache_array_sql[] = "INSERT INTO `".dba::get('permissions')."` SET
            `user` = ".$get['user'].",
            `pos` = ".$get['pos'].",
            `intforum` = ".$get['intforum'].",
            `clankasse` = ".$get['clankasse'].",
            `clanwars` = ".$get['clanwars'].",
            `shoutbox` = ".$get['shoutbox'].",
            `serverliste` = ".$get['serverliste'].",
            `editusers` = ".$get['editusers'].",
            `edittactics` = ".$get['edittactics'].",
            `editsquads` = ".$get['editsquads'].",
            `editserver` = ".$get['editserver'].",
            `editkalender` = ".$get['editkalender'].",
            `news` = ".$get['news'].",
            `gb` = ".$get['gb'].",
            `forum` = ".$get['forum'].",
            `votes` = ".$get['votes'].",
            `gallery` = ".$get['gallery'].",
            `votesadmin` = ".$get['votesadmin'].",
            `links` = ".$get['links'].",
            `downloads` = ".$get['downloads'].",
            `newsletter` = ".$get['newsletter'].",
            `intnews` = ".$get['intnews'].",
            `rankings` = ".$get['rankings'].",
            `contact` = ".$get['contact'].",
            `joinus` = ".$get['joinus'].",
            `awards` = ".$get['awards'].",
            `artikel` = ".$get['artikel'].",
            `receivecws` = ".$get['receivecws'].",
            `editor` = ".$get['editor'].",
            `glossar` = ".$get['glossar'].",
            `gs_showpw` = ".$get['gs_showpw'].";";
        }

        unset($qry,$get);
    }

    //===============================================================
    //-> Rechte =====================================================
    //===============================================================
    db("DROP TABLE IF EXISTS `".dba::get('permissions')."`;",false,false,true);
    db("CREATE TABLE IF NOT EXISTS `".dba::get('permissions')."` (
      `id` int(11) NOT NULL AUTO_INCREMENT,
      `user` int(11) NOT NULL DEFAULT '0',
      `pos` int(1) NOT NULL DEFAULT '0',
      `artikel` int(1) NOT NULL DEFAULT '0',
      `awards` int(1) NOT NULL DEFAULT '0',
      `backup` int(1) NOT NULL DEFAULT '0',
      `clear` int(1) NOT NULL DEFAULT '0',
      `config` int(1) NOT NULL DEFAULT '0',
      `contact` int(1) NOT NULL DEFAULT '0',
      `clanwars` int(1) NOT NULL DEFAULT '0',
      `clankasse` int(1) NOT NULL DEFAULT '0',
      `downloads` int(1) NOT NULL DEFAULT '0',
      `editkalender` int(1) NOT NULL DEFAULT '0',
      `editserver` int(1) NOT NULL DEFAULT '0',
      `editteamspeak` int(1) NOT NULL DEFAULT '0',
      `edittactics` int(1) NOT NULL DEFAULT '0',
      `editsquads` int(1) NOT NULL DEFAULT '0',
      `editusers` int(1) NOT NULL DEFAULT '0',
      `editor` int(1) NOT NULL DEFAULT '0',
      `forum` int(1) NOT NULL DEFAULT '0',
      `gallery` int(1) NOT NULL DEFAULT '0',
      `gb` int(1) NOT NULL DEFAULT '0',
      `gs_showpw` int(1) NOT NULL DEFAULT '0',
      `glossar` int(1) NOT NULL DEFAULT '0',
      `impressum` int(1) NOT NULL DEFAULT '0',
      `intforum` int(1) NOT NULL DEFAULT '0',
      `intnews` int(1) NOT NULL DEFAULT '0',
      `joinus` int(1) NOT NULL DEFAULT '0',
      `links` int(1) NOT NULL DEFAULT '0',
      `news` int(1) NOT NULL DEFAULT '0',
      `newsletter` int(1) NOT NULL DEFAULT '0',
      `partners` int(1) NOT NULL DEFAULT '0',
      `profile` int(1) NOT NULL DEFAULT '0',
      `protocol` int(1) NOT NULL DEFAULT '0',
      `rankings` int(1) NOT NULL DEFAULT '0',
      `receivecws` int(1) NOT NULL DEFAULT '0',
      `serverliste` int(1) NOT NULL DEFAULT '0',
      `smileys` int(1) NOT NULL DEFAULT '0',
      `sponsors` int(1) NOT NULL DEFAULT '0',
      `shoutbox` int(1) NOT NULL DEFAULT '0',
      `support` int(1) NOT NULL DEFAULT '0',
      `votes` int(1) NOT NULL DEFAULT '0',
      `votesadmin` int(1) NOT NULL DEFAULT '0',
      PRIMARY KEY (`id`)
    ) ".get_db_engine($_SESSION['mysql_dbengine'])." DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;",false,false,true);

    // Permissions Datensatz einspielen
    if(count($cache_array_sql) >= 1)
    {
        foreach ($cache_array_sql as $sql)
        { db($sql); }
    }
    unset($cache_array_sql);

    //Permissions Tabelle prüfen
    $qry = db("SELECT id FROM `".dba::get('users')."`");
    if(_rows($qry))
    {
        while($get = _fetch($qry))
        { if(!db("SELECT id FROM `".dba::get('permissions')."` WHERE `user` = ".$get['id'],true)) db("INSERT INTO ".dba::get('permissions')." SET `user` = ".$get['id']); }
    }

    // Ersetze Forum Access Tabelle
    $qry = db("SELECT * FROM `".dba::get('f_access')."`");
    if(_rows($qry) >= 1)
    {
        $cache_array_sql = array();
        while($get = _fetch($qry))
        { $cache_array_sql[] = "INSERT INTO `".dba::get('f_access')."` SET `user` = ".$get['user']." , `pos` =  ".(empty($get['pos']) ? '0' : $get['pos']).", `forum` = ".$get['forum']; }

        unset($qry,$get);
    }

    //===============================================================
    //-> Forum: Access ==============================================
    //===============================================================
    db("DROP TABLE IF EXISTS `".dba::get('f_access')."`;",false,false,true);
    db("CREATE TABLE IF NOT EXISTS `".dba::get('f_access')."` (
      `id` int(11) NOT NULL AUTO_INCREMENT,
      `user` int(11) NOT NULL DEFAULT '0',
      `pos` int(5) NOT NULL DEFAULT '0',
      `forum` int(11) NOT NULL DEFAULT '0',
      PRIMARY KEY (`id`),
      UNIQUE KEY `id` (`id`),
      KEY `user` (`user`)
    ) ".get_db_engine($_SESSION['mysql_dbengine'])." DEFAULT CHARSET=latin1;",false,false,true);

    // Furm Access Datensatz einspielen
    if(count($cache_array_sql) >= 1)
    {
        foreach ($cache_array_sql as $sql)
        { db($sql); }
    }
    unset($cache_array_sql);

    // Navigation aktualisieren
    $qry = db("SELECT id FROM `".dba::get('navi')."` WHERE `name` = '_taktiken_'");
    if(_rows($qry))
    {
        while($get = _fetch($qry))
        { db("UPDATE `".dba::get('navi')."` SET `extended_perm` = 'edittactics' WHERE `id` = ".$get['id'].";"); }
    }

    $qry = db("SELECT id FROM `".dba::get('navi')."` WHERE `name` = '_clankasse_'");
    if(_rows($qry))
    {
        while($get = _fetch($qry))
        { db("UPDATE `".dba::get('navi')."` SET `extended_perm` = 'clankasse' WHERE `id` = ".$get['id'].";"); }
    }

    //===============================================================
    //-> Downloadkommentare =========================================
    //===============================================================
    db("DROP TABLE IF EXISTS `".dba::get('dl_comments')."`;",false,false,true);
    db("CREATE TABLE IF NOT EXISTS `".dba::get('dl_comments')."` (
      `id` int(10) NOT NULL AUTO_INCREMENT,
      `download` int(10) NOT NULL DEFAULT '0',
      `nick` varchar(50) NOT NULL DEFAULT '',
      `datum` int(20) NOT NULL DEFAULT '0',
      `email` varchar(130) NOT NULL DEFAULT '',
      `hp` varchar(50) NOT NULL DEFAULT '',
      `reg` int(5) NOT NULL DEFAULT '0',
      `comment` text NOT NULL,
      `ip` varchar(50) NOT NULL DEFAULT '',
      `editby` text,
      PRIMARY KEY (`id`)
    ) ".get_db_engine($_SESSION['mysql_dbengine'])." DEFAULT CHARSET=latin1;",false,false,true);

    //===============================================================
    //-> Gästebuchkommentare ========================================
    //===============================================================
    db("DROP TABLE IF EXISTS `".dba::get('gb_comments')."`;",false,false,true);
    db("CREATE TABLE IF NOT EXISTS `".dba::get('gb_comments')."` (
      `id` int(10) NOT NULL AUTO_INCREMENT,
      `gbe` int(10) NOT NULL DEFAULT '0',
      `nick` varchar(50) NOT NULL DEFAULT '',
      `datum` int(20) NOT NULL DEFAULT '0',
      `email` varchar(130) NOT NULL DEFAULT '',
      `hp` varchar(50) NOT NULL DEFAULT '',
      `reg` int(5) NOT NULL DEFAULT '0',
      `comment` text NOT NULL,
      `ip` varchar(50) NOT NULL DEFAULT '',
      `editby` text,
      PRIMARY KEY (`id`)
    ) ".get_db_engine($_SESSION['mysql_dbengine'])." DEFAULT CHARSET=latin1;",false,false,true);

    // Gallery Bilder verschieben
    $files = get_files(basePath."/gallery/images/",false,true);
    foreach($files as $file)
    {
        if(copy(basePath."/gallery/images/".$file ,basePath."/inc/images/uploads/gallery/".$file))
            if(file_exists(basePath."/inc/images/uploads/gallery/".$file))
                unlink(basePath."/gallery/images/".$file);
    }

    // Alten Gallery Bilder Ordner löschen
    if(is_dir(basePath."/gallery/images"))
        @rmdir(basePath."/gallery/images");

    // Squads Bilder verschieben
    $files = get_files(basePath."/inc/images/squads/",false,true);
    foreach($files as $file)
    {
        if(copy(basePath."/inc/images/squads/".$file ,basePath."/inc/images/uploads/squads/".$file))
            if(file_exists(basePath."/inc/images/uploads/squads/".$file))
            unlink(basePath."/inc/images/squads/".$file);
    }

    // Alten Squads Bilder Ordner löschen
    if(is_dir(basePath."/inc/images/squads"))
        @rmdir(basePath."/inc/images/squads");

    // Clanwars Bilder verschieben
    $files = get_files(basePath."/inc/images/clanwars/",false,true);
    foreach($files as $file)
    {
        if(copy(basePath."/inc/images/clanwars/".$file ,basePath."/inc/images/uploads/clanwars/".$file))
            if(file_exists(basePath."/inc/images/uploads/clanwars/".$file))
            unlink(basePath."/inc/images/clanwars/".$file);
    }

    // Alten Clanwars Bilder Ordner löschen
    if(is_dir(basePath."/inc/images/clanwars"))
        @rmdir(basePath."/inc/images/clanwars");

    //===============================================================
    //-> RSS Feeds ==================================================
    //===============================================================
    db("DROP TABLE IF EXISTS `".dba::get('rss')."`;",false,false,true);
    db("CREATE TABLE IF NOT EXISTS `".dba::get('rss')."` (
      `id` int(11) NOT NULL AUTO_INCREMENT,
      `userid` int(11) NOT NULL,
      `show_public_news` int(1) NOT NULL DEFAULT '1',
      `show_public_news_max` int(11) NOT NULL DEFAULT '4',
      `show_intern_news` int(1) NOT NULL DEFAULT '1',
      `show_intern_news_max` int(11) NOT NULL DEFAULT '4',
      `show_artikel` int(1) NOT NULL DEFAULT '1',
      `show_artikel_max` int(11) NOT NULL DEFAULT '4',
      `show_downloads` int(1) NOT NULL DEFAULT '1',
      `show_downloads_max` int(11) NOT NULL DEFAULT '2',
      PRIMARY KEY (`id`)
    ) ".get_db_engine($_SESSION['mysql_dbengine'])." DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;",false,false,true);

    //RSS Feed Config anlegen
    $qry = db("SELECT id FROM `".dba::get('users')."`");
    if(_rows($qry))
    {
        while($get = _fetch($qry))
        { db("INSERT INTO `".dba::get('rss')."` SET userid = ".$get['id'].";",false,false,true); }
    }

    //===============================================================
    //-> Teamspeak ==================================================
    //===============================================================
    db("DROP TABLE IF EXISTS `".dba::get('ts')."`;",false,false,true);
    db("CREATE TABLE IF NOT EXISTS `".dba::get('ts')."` (
      `id` int(11) NOT NULL AUTO_INCREMENT,
      `host_ip_dns` varchar(200) NOT NULL DEFAULT '',
      `server_port` int(8) NOT NULL DEFAULT '9987',
      `query_port` int(8) NOT NULL DEFAULT '10011',
      `file_port` int(8) NOT NULL DEFAULT '30033',
      `username` varchar(100) NOT NULL DEFAULT '',
      `passwort` varchar(100) NOT NULL DEFAULT '',
      `customicon` int(1) NOT NULL DEFAULT '1',
      `showchannel` int(1) NOT NULL DEFAULT '0',
      `default_server` int(1) NOT NULL DEFAULT '0',
      `show_navi` int(1) NOT NULL DEFAULT '0',
      PRIMARY KEY (`id`)
    ) ".get_db_engine($_SESSION['mysql_dbengine'])." DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;",false,false,true);

    //===============================================================
    //-> Teamspeak Update ===========================================
    //===============================================================
    $ts_settings = settings(array('ts_ip','ts_port','ts_sport','ts_version','ts_customicon','ts_showchannel'));
    if($ts_settings['ts_version'] == '3')
        db("INSERT INTO `".dba::get('ts')."` SET `host_ip_dns` = '".$ts_settings['ts_ip']."', `server_port` = ".$ts_settings['ts_port'].", `query_port` = ".$ts_settings['ts_sport'].", `customicon` = ".$ts_settings['ts_customicon'].", `showchannel` = ".$ts_settings['ts_showchannel'].", `default_server` = 1, `show_navi` = 1;",false,false,true);

    unset($ts_settings);
    db("ALTER TABLE `".dba::get('settings')."` DROP `ts_ip`, DROP `ts_port`, DROP `ts_sport`, DROP `ts_version`, DROP `ts_customicon`, DROP `ts_showchannel`, DROP `ts_width`;",false,false,true);

    //===============================================================
    //-> IP-Ban & Spam Blocker ======================================
    //===============================================================
    db("DROP TABLE IF EXISTS `".dba::get('ipban')."`;",false,false,true);
    db("CREATE TABLE IF NOT EXISTS `".dba::get('ipban')."` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `ip` varchar(15) NOT NULL DEFAULT '255.255.255.255',
        `time` int(11) NOT NULL DEFAULT '0',
        `data` text,
        `typ` int(1) NOT NULL DEFAULT '0',
        `enable` int(1) NOT NULL DEFAULT '1',
        PRIMARY KEY (`id`),
        UNIQUE KEY `id` (`id`),
        KEY `ip` (`ip`)
    ) ".get_db_engine($_SESSION['mysql_dbengine'])." DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;",false,false,true);

    return true;
}