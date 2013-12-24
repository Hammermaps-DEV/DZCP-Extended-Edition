<?php
//=======================================
//Create DZCP Database MySQL Installer
//=======================================

//Neuinstallation
function install_mysql_create()
{
    @ignore_user_abort(true);

    //===============================================================
    //-> Artikelkommentare ==========================================
    //===============================================================
    db("DROP TABLE IF EXISTS `".dba::get('acomments')."`;",false,false,true);
    db("CREATE TABLE IF NOT EXISTS `".dba::get('acomments')."` (
      `id` int(10) NOT NULL AUTO_INCREMENT,
      `artikel` int(10) NOT NULL DEFAULT '0',
      `nick` varchar(50) NOT NULL DEFAULT '',
      `datum` int(20) NOT NULL DEFAULT '0',
      `email` varchar(130) NOT NULL DEFAULT '',
      `hp` varchar(50) NOT NULL DEFAULT '',
      `reg` int(5) NOT NULL DEFAULT '0',
      `comment` text NOT NULL,
      `ip` varchar(50) NOT NULL DEFAULT '',
      `editby` text,
      PRIMARY KEY (`id`)
    ) ".dba::get_db_engine($_SESSION['mysql_dbengine'])." DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;",false,false,true);

    //===============================================================
    //-> Addons =====================================================
    //===============================================================
    db("DROP TABLE IF EXISTS `".dba::get('addons')."`;",false,false,true);
    db("CREATE TABLE IF NOT EXISTS `".dba::get('addons')."` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `dir` varchar(200) NOT NULL DEFAULT '',
    `installed` int(1) NOT NULL DEFAULT '0',
    `enable` int(1) NOT NULL DEFAULT '0',
    PRIMARY KEY (`id`)
    ) ".dba::get_db_engine($_SESSION['mysql_dbengine'])." DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;",false,false,true);

    //===============================================================
    //-> Artikel ====================================================
    //===============================================================
    db("DROP TABLE IF EXISTS `".dba::get('artikel')."`;",false,false,true);
    db("CREATE TABLE IF NOT EXISTS `".dba::get('artikel')."` (
      `id` int(10) NOT NULL AUTO_INCREMENT,
      `autor` varchar(5) NOT NULL DEFAULT '',
      `datum` varchar(20) NOT NULL DEFAULT '',
      `kat` int(2) NOT NULL DEFAULT '0',
      `titel` varchar(249) NOT NULL DEFAULT '',
      `text` text NOT NULL,
      `link1` varchar(100) NOT NULL DEFAULT '',
      `url1` varchar(200) NOT NULL DEFAULT '',
      `link2` varchar(100) NOT NULL DEFAULT '',
      `url2` varchar(200) NOT NULL DEFAULT '',
      `link3` varchar(100) NOT NULL DEFAULT '',
      `url3` varchar(200) NOT NULL DEFAULT '',
      `viewed` int(11) NOT NULL DEFAULT '0',
      `public` int(1) NOT NULL DEFAULT '0',
      `comments` int(1) NOT NULL DEFAULT '1',
      `custom_image` int(1) NOT NULL DEFAULT '0',
      PRIMARY KEY (`id`)
    ) ".dba::get_db_engine($_SESSION['mysql_dbengine'])." DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;",false,false,true);

    //===============================================================
    //-> Awards =====================================================
    //===============================================================
    db("DROP TABLE IF EXISTS `".dba::get('awards')."`;",false,false,true);
    db("CREATE TABLE IF NOT EXISTS `".dba::get('awards')."` (
      `id` int(5) NOT NULL AUTO_INCREMENT,
      `squad` int(10) NOT NULL,
      `date` varchar(20) NOT NULL DEFAULT '',
      `postdate` varchar(20) NOT NULL DEFAULT '',
      `event` varchar(50) NOT NULL DEFAULT '',
      `place` varchar(5) NOT NULL DEFAULT '',
      `prize` text NOT NULL,
      `url` text NOT NULL,
      PRIMARY KEY (`id`)
    ) ".dba::get_db_engine($_SESSION['mysql_dbengine'])." DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;",false,false,true);

    //===============================================================
    //-> Away =======================================================
    //===============================================================
    db("DROP TABLE IF EXISTS `".dba::get('away')."`;",false,false,true);
    db("CREATE TABLE IF NOT EXISTS `".dba::get('away')."` (
      `id` int(5) NOT NULL AUTO_INCREMENT,
      `userid` int(14) NOT NULL DEFAULT '0',
      `titel` varchar(30) NOT NULL,
      `reason` longtext NOT NULL,
      `start` int(20) NOT NULL DEFAULT '0',
      `end` int(20) NOT NULL DEFAULT '0',
      `date` text NOT NULL,
      `lastedit` text,
      PRIMARY KEY (`id`)
    ) ".dba::get_db_engine($_SESSION['mysql_dbengine'])." DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;",false,false,true);

    //===============================================================
    //-> Buddys =====================================================
    //===============================================================
    db("DROP TABLE IF EXISTS `".dba::get('buddys')."`;",false,false,true);
    db("CREATE TABLE IF NOT EXISTS `".dba::get('buddys')."` (
      `id` int(10) NOT NULL AUTO_INCREMENT,
      `user` int(5) NOT NULL DEFAULT '0',
      `buddy` int(5) NOT NULL DEFAULT '0',
      PRIMARY KEY (`id`)
    ) ".dba::get_db_engine($_SESSION['mysql_dbengine'])." DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;",false,false,true);

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
    ) ".dba::get_db_engine($_SESSION['mysql_dbengine'])." DEFAULT CHARSET=latin1;",false,false,true);

    //===============================================================
    //-> Captcha ====================================================
    //===============================================================
    db("DROP TABLE IF EXISTS `".dba::get('captcha')."`;",false,false,true);
    db("CREATE TABLE IF NOT EXISTS `".dba::get('captcha')."` (
       `id` varchar(40) NOT NULL,
       `namespace` varchar(32) NOT NULL,
       `code` varchar(32) NOT NULL,
       `code_display` varchar(32) NOT NULL,
       `created` int(11) NOT NULL,
       PRIMARY KEY (`id`,`namespace`),
       KEY `created` (`created`)
    ) ".dba::get_db_engine($_SESSION['mysql_dbengine'])." DEFAULT CHARSET=latin1;",false,false,true);

    //===============================================================
    //-> Clankasse ==================================================
    //===============================================================
    db("DROP TABLE IF EXISTS `".dba::get('clankasse')."`;",false,false,true);
    db("CREATE TABLE IF NOT EXISTS `".dba::get('clankasse')."` (
      `id` int(20) NOT NULL AUTO_INCREMENT,
      `datum` varchar(20) NOT NULL DEFAULT '',
      `member` varchar(50) NOT NULL DEFAULT '0',
      `transaktion` varchar(249) NOT NULL DEFAULT '',
      `pm` int(1) NOT NULL DEFAULT '0',
      `betrag` varchar(10) NOT NULL DEFAULT '0',
      PRIMARY KEY (`id`)
    ) ".dba::get_db_engine($_SESSION['mysql_dbengine'])." DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;",false,false,true);

    //===============================================================
    //-> Clankassenkategorien =======================================
    //===============================================================
    db("DROP TABLE IF EXISTS `".dba::get('c_kats')."`;",false,false,true);
    db("CREATE TABLE IF NOT EXISTS `".dba::get('c_kats')."` (
      `id` int(5) NOT NULL AUTO_INCREMENT,
      `kat` varchar(30) NOT NULL DEFAULT '',
      PRIMARY KEY (`id`)
    ) ".dba::get_db_engine($_SESSION['mysql_dbengine'])." DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;",false,false,true);

    //===============================================================
    //-> Clankassenzahlungen ========================================
    //===============================================================
    db("DROP TABLE IF EXISTS `".dba::get('c_payed')."`;",false,false,true);
    db("CREATE TABLE IF NOT EXISTS `".dba::get('c_payed')."` (
      `id` int(5) NOT NULL AUTO_INCREMENT,
      `user` int(5) NOT NULL DEFAULT '0',
      `payed` varchar(20) NOT NULL DEFAULT '0',
      PRIMARY KEY (`id`)
    ) ".dba::get_db_engine($_SESSION['mysql_dbengine'])." DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;",false,false,true);

    //===============================================================
    //-> Clanwars ===================================================
    //===============================================================
    db("DROP TABLE IF EXISTS `".dba::get('cw')."`;",false,false,true);
    db("CREATE TABLE IF NOT EXISTS `".dba::get('cw')."` (
      `id` int(5) NOT NULL AUTO_INCREMENT,
      `squad_id` int(19) NOT NULL,
      `gametype` varchar(249) NOT NULL DEFAULT '',
      `gcountry` varchar(20) NOT NULL DEFAULT 'de',
      `matchadmins` varchar(249) NOT NULL DEFAULT '',
      `lineup` varchar(249) NOT NULL DEFAULT '',
      `glineup` varchar(249) NOT NULL DEFAULT '',
      `datum` int(20) NOT NULL DEFAULT '0',
      `clantag` varchar(20) NOT NULL DEFAULT '',
      `gegner` varchar(100) NOT NULL DEFAULT '',
      `url` varchar(249) NOT NULL DEFAULT '',
      `xonx` varchar(10) NOT NULL DEFAULT '',
      `liga` varchar(30) NOT NULL DEFAULT '',
      `punkte` int(5) NOT NULL DEFAULT '0',
      `gpunkte` int(5) NOT NULL DEFAULT '0',
      `maps` varchar(30) NOT NULL DEFAULT '',
      `serverip` varchar(50) NOT NULL DEFAULT '',
      `servername` varchar(249) NOT NULL DEFAULT '',
      `serverpwd` varchar(20) NOT NULL DEFAULT '',
      `bericht` text,
      `top` int(1) NOT NULL DEFAULT '0',
      PRIMARY KEY (`id`)
    ) ".dba::get_db_engine($_SESSION['mysql_dbengine'])." DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;",false,false,true);

    //===============================================================
    //-> Clanwarplayers =============================================
    //===============================================================
    db("DROP TABLE IF EXISTS `".dba::get('cw_player')."`",false,false,true);
    db("CREATE TABLE IF NOT EXISTS `".dba::get('cw_player')."` (
      `cwid` int(5) NOT NULL DEFAULT '0',
      `member` int(5) NOT NULL DEFAULT '0',
      `status` int(5) NOT NULL DEFAULT '0',
      KEY `cwid` (`cwid`)
    ) ".dba::get_db_engine($_SESSION['mysql_dbengine'])." DEFAULT CHARSET=latin1;",false,false,true);

    //===============================================================
    //-> Click IP Counter ===========================================
    //===============================================================
    db("DROP TABLE IF EXISTS `".dba::get('clicks_ips')."`;",false,false,true);
    db("CREATE TABLE IF NOT EXISTS `".dba::get('clicks_ips')."` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `ip` varchar(15) NOT NULL DEFAULT '000.000.000.000',
    `uid` int(11) NOT NULL DEFAULT '0',
    `ids` int(11) NOT NULL DEFAULT '0',
    `side` varchar(30) NOT NULL DEFAULT '',
    `time` int(20) NOT NULL DEFAULT '0',
    PRIMARY KEY (`id`),
    KEY `ip` (`ip`)
    ) ".dba::get_db_engine($_SESSION['mysql_dbengine'])." DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;",false,false,true);

    //===============================================================
    //-> Clanwarkommentare ==========================================
    //===============================================================
    db("DROP TABLE IF EXISTS `".dba::get('cw_comments')."`;",false,false,true);
    db("CREATE TABLE IF NOT EXISTS `".dba::get('cw_comments')."` (
      `id` int(10) NOT NULL AUTO_INCREMENT,
      `cw` int(10) NOT NULL DEFAULT '0',
      `nick` varchar(50) NOT NULL DEFAULT '',
      `datum` int(20) NOT NULL DEFAULT '0',
      `email` varchar(130) NOT NULL DEFAULT '',
      `hp` varchar(50) NOT NULL DEFAULT '',
      `reg` int(5) NOT NULL DEFAULT '0',
      `comment` text NOT NULL,
      `ip` varchar(50) NOT NULL DEFAULT '',
      `editby` text,
      PRIMARY KEY (`id`)
    ) ".dba::get_db_engine($_SESSION['mysql_dbengine'])." DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;",false,false,true);

    //===============================================================
    //-> Counter ====================================================
    //===============================================================
    db("DROP TABLE IF EXISTS `".dba::get('counter')."`;",false,false,true);
    db("CREATE TABLE IF NOT EXISTS `".dba::get('counter')."` (
      `id` int(5) NOT NULL AUTO_INCREMENT,
      `visitors` int(20) NOT NULL DEFAULT '0',
      `today` varchar(50) NOT NULL DEFAULT '0',
      `maxonline` int(5) NOT NULL DEFAULT '0',
      PRIMARY KEY (`id`)
    ) ".dba::get_db_engine($_SESSION['mysql_dbengine'])." DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;",false,false,true);

    //===============================================================
    //-> Counter IPs ================================================
    //===============================================================
    db("DROP TABLE IF EXISTS `".dba::get('c_ips')."`;",false,false,true);
    db("CREATE TABLE IF NOT EXISTS `".dba::get('c_ips')."` (
      `id` int(10) NOT NULL AUTO_INCREMENT,
      `ip` varchar(30) NOT NULL DEFAULT '0',
      `datum` int(20) NOT NULL DEFAULT '0',
      PRIMARY KEY (`id`)
    ) ".dba::get_db_engine($_SESSION['mysql_dbengine'])." DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;",false,false,true);

    //===============================================================
    //-> Counter whoison ============================================
    //===============================================================
    db("DROP TABLE IF EXISTS `".dba::get('c_who')."`;",false,false,true);
    db("CREATE TABLE IF NOT EXISTS `".dba::get('c_who')."` (
      `id` int(50) NOT NULL AUTO_INCREMENT,
      `ip` char(50) NOT NULL DEFAULT '',
      `online` int(20) NOT NULL DEFAULT '0',
      `whereami` text NOT NULL,
      `login` int(1) NOT NULL DEFAULT '0',
      PRIMARY KEY (`id`),
      UNIQUE KEY `ip` (`ip`)
    ) ".dba::get_db_engine($_SESSION['mysql_dbengine'])." DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;",false,false,true);

    //===============================================================
    //-> Downloads ==================================================
    //===============================================================
    db("DROP TABLE IF EXISTS `".dba::get('downloads')."`;",false,false,true);
    db("CREATE TABLE IF NOT EXISTS `".dba::get('downloads')."` (
      `id` int(11) NOT NULL AUTO_INCREMENT,
      `download` varchar(249) NOT NULL DEFAULT '',
      `url` varchar(249) NOT NULL DEFAULT '',
      `beschreibung` varchar(249) DEFAULT NULL,
      `hits` int(50) NOT NULL DEFAULT '0',
      `kat` int(5) NOT NULL DEFAULT '0',
      `date` int(20) NOT NULL DEFAULT '0',
      `last_dl` int(20) NOT NULL DEFAULT '0',
      `comments` int(1) NOT NULL DEFAULT '1',
      PRIMARY KEY (`id`)
    ) ".dba::get_db_engine($_SESSION['mysql_dbengine'])." DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;",false,false,true);

    //===============================================================
    //-> Downloadkategorien =========================================
    //===============================================================
    db("DROP TABLE IF EXISTS `".dba::get('dl_kat')."`;",false,false,true);
    db("CREATE TABLE IF NOT EXISTS `".dba::get('dl_kat')."` (
      `id` int(11) NOT NULL AUTO_INCREMENT,
      `name` varchar(249) NOT NULL DEFAULT '',
      PRIMARY KEY (`id`)
    ) ".dba::get_db_engine($_SESSION['mysql_dbengine'])." DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;",false,false,true);

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
    ) ".dba::get_db_engine($_SESSION['mysql_dbengine'])." DEFAULT CHARSET=latin1;",false,false,true);

    //===============================================================
    //-> Events (Kalender) ==========================================
    //===============================================================
    db("DROP TABLE IF EXISTS `".dba::get('events')."`;",false,false,true);
    db("CREATE TABLE IF NOT EXISTS `".dba::get('events')."` (
      `id` int(5) NOT NULL AUTO_INCREMENT,
      `datum` int(20) NOT NULL DEFAULT '0',
      `title` varchar(30) NOT NULL DEFAULT '',
      `event` text NOT NULL,
      PRIMARY KEY (`id`)
    ) ".dba::get_db_engine($_SESSION['mysql_dbengine'])." DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;",false,false,true);

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
    ) ".dba::get_db_engine($_SESSION['mysql_dbengine'])." DEFAULT CHARSET=latin1;",false,false,true);

    //===============================================================
    //-> Forum: Kategorien ==========================================
    //===============================================================
    db("DROP TABLE IF EXISTS `".dba::get('f_kats')."`;",false,false,true);
    db("CREATE TABLE IF NOT EXISTS `".dba::get('f_kats')."` (
      `id` int(10) NOT NULL AUTO_INCREMENT,
      `kid` int(10) NOT NULL DEFAULT '0',
      `name` varchar(50) NOT NULL DEFAULT '',
      `intern` int(1) NOT NULL DEFAULT '0',
      PRIMARY KEY (`id`)
    ) ".dba::get_db_engine($_SESSION['mysql_dbengine'])." DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;",false,false,true);

    //===============================================================
    //-> Forumposts =================================================
    //===============================================================
    db("DROP TABLE IF EXISTS `".dba::get('f_posts')."`;",false,false,true);
    db("CREATE TABLE IF NOT EXISTS `".dba::get('f_posts')."` (
      `id` int(10) NOT NULL AUTO_INCREMENT,
      `kid` int(2) NOT NULL DEFAULT '0',
      `sid` int(2) NOT NULL DEFAULT '0',
      `date` int(20) NOT NULL DEFAULT '0',
      `nick` varchar(50) NOT NULL DEFAULT '',
      `reg` int(1) NOT NULL DEFAULT '0',
      `email` varchar(130) NOT NULL DEFAULT '',
      `text` text NOT NULL,
      `edited` text,
      `ip` varchar(50) NOT NULL DEFAULT '',
      `hp` varchar(249) NOT NULL DEFAULT '',
      PRIMARY KEY (`id`),
      KEY `sid` (`sid`),
      KEY `date` (`date`)
    ) ".dba::get_db_engine($_SESSION['mysql_dbengine'])." DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;",false,false,true);

    //===============================================================
    //-> Forumthreads ===============================================
    //===============================================================
    db("DROP TABLE IF EXISTS `".dba::get('f_threads')."`;",false,false,true);
    db("CREATE TABLE IF NOT EXISTS `".dba::get('f_threads')."` (
      `id` int(10) NOT NULL AUTO_INCREMENT,
      `kid` int(10) NOT NULL DEFAULT '0',
      `t_date` int(20) NOT NULL DEFAULT '0',
      `topic` varchar(249) NOT NULL DEFAULT '',
      `subtopic` varchar(100) NOT NULL DEFAULT '',
      `t_nick` varchar(30) NOT NULL DEFAULT '',
      `t_reg` int(1) NOT NULL DEFAULT '0',
      `t_email` varchar(130) NOT NULL DEFAULT '',
      `t_text` text NOT NULL,
      `hits` int(10) NOT NULL DEFAULT '0',
      `first` int(1) NOT NULL DEFAULT '0',
      `lp` int(20) NOT NULL DEFAULT '0',
      `sticky` int(1) NOT NULL DEFAULT '0',
      `closed` int(1) NOT NULL DEFAULT '0',
      `global` int(1) NOT NULL DEFAULT '0',
      `edited` text,
      `ip` varchar(50) NOT NULL DEFAULT '',
      `t_hp` varchar(249) NOT NULL DEFAULT '',
      `vote` varchar(10) NOT NULL DEFAULT '0',
      PRIMARY KEY (`id`),
      KEY `kid` (`kid`),
      KEY `lp` (`lp`),
      KEY `topic` (`topic`),
      KEY `first` (`first`)
    ) ".dba::get_db_engine($_SESSION['mysql_dbengine'])." DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;",false,false,true);

    //===============================================================
    //-> Forum Unterkategorien ======================================
    //===============================================================
    db("DROP TABLE IF EXISTS `".dba::get('f_skats')."`;",false,false,true);
    db("CREATE TABLE IF NOT EXISTS `".dba::get('f_skats')."` (
      `id` int(10) NOT NULL AUTO_INCREMENT,
      `sid` int(10) NOT NULL DEFAULT '0',
      `kattopic` varchar(150) NOT NULL DEFAULT '',
      `subtopic` varchar(150) NOT NULL DEFAULT '',
      `pos` int(5) NOT NULL DEFAULT 1,
      PRIMARY KEY (`id`)
    ) ".dba::get_db_engine($_SESSION['mysql_dbengine'])." DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;",false,false,true);

    //===============================================================
    //-> Forum ABO ==================================================
    //===============================================================
    db("DROP TABLE IF EXISTS `".dba::get('f_abo')."`;",false,false,true);
    db("CREATE TABLE IF NOT EXISTS `".dba::get('f_abo')."` (
      `id` int(10) NOT NULL AUTO_INCREMENT,
      `fid` int(10) NOT NULL,
      `datum` int(20) NOT NULL,
      `user` int(5) NOT NULL,
      PRIMARY KEY (`id`)
    ) ".dba::get_db_engine($_SESSION['mysql_dbengine'])." DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;",false,false,true);

    //===============================================================
    //-> Galerie ====================================================
    //===============================================================
    db("DROP TABLE IF EXISTS `".dba::get('gallery')."`;",false,false,true);
    db("CREATE TABLE IF NOT EXISTS `".dba::get('gallery')."` (
      `id` int(5) NOT NULL AUTO_INCREMENT,
      `datum` int(20) NOT NULL DEFAULT '0',
      `kat` varchar(200) NOT NULL DEFAULT '',
      `beschreibung` text,
      PRIMARY KEY (`id`)
    ) ".dba::get_db_engine($_SESSION['mysql_dbengine'])." DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;",false,false,true);

    //===============================================================
    //-> Gaestebuch =================================================
    //===============================================================
    db("DROP TABLE IF EXISTS `".dba::get('gb')."`;",false,false,true);
    db("CREATE TABLE IF NOT EXISTS `".dba::get('gb')."` (
      `id` int(5) NOT NULL AUTO_INCREMENT,
      `datum` int(20) NOT NULL DEFAULT '0',
      `nick` varchar(50) NOT NULL DEFAULT '',
      `email` varchar(130) NOT NULL DEFAULT '',
      `hp` varchar(130) DEFAULT NULL,
      `reg` int(1) NOT NULL DEFAULT '0',
      `nachricht` text NOT NULL,
      `ip` varchar(50) NOT NULL DEFAULT '',
      `editby` text,
      `public` int(1) NOT NULL,
      PRIMARY KEY (`id`)
    ) ".dba::get_db_engine($_SESSION['mysql_dbengine'])." DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;",false,false,true);

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
    ) ".dba::get_db_engine($_SESSION['mysql_dbengine'])." DEFAULT CHARSET=latin1;",false,false,true);

    //===============================================================
    //-> Glossar ====================================================
    //===============================================================
    db("DROP TABLE IF EXISTS `".dba::get('glossar')."`;",false,false,true);
    db("CREATE TABLE IF NOT EXISTS `".dba::get('glossar')."` (
      `id` int(11) NOT NULL AUTO_INCREMENT,
      `word` varchar(200) NOT NULL,
      `glossar` text NOT NULL,
      PRIMARY KEY (`id`)
    ) ".dba::get_db_engine($_SESSION['mysql_dbengine'])." DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;",false,false,true);

    //===============================================================
    //-> Ipcheck & Admin Log ========================================
    //===============================================================
    db("DROP TABLE IF EXISTS `".dba::get('ipcheck')."`;",false,false,true);
    db("CREATE TABLE IF NOT EXISTS `".dba::get('ipcheck')."` (
      `id` int(11) NOT NULL AUTO_INCREMENT,
      `ip` varchar(100) NOT NULL DEFAULT '',
      `what` varchar(40) NOT NULL DEFAULT '',
      `time` int(20) NOT NULL DEFAULT '0',
      PRIMARY KEY (`id`)
    ) ".dba::get_db_engine($_SESSION['mysql_dbengine'])." DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;",false,false,true);

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
    ) ".dba::get_db_engine($_SESSION['mysql_dbengine'])." DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;",false,false,true);

    //===============================================================
    //-> Links ======================================================
    //===============================================================
    db("DROP TABLE IF EXISTS `".dba::get('links')."`;",false,false,true);
    db("CREATE TABLE IF NOT EXISTS `".dba::get('links')."` (
      `id` int(5) NOT NULL AUTO_INCREMENT,
      `url` varchar(249) NOT NULL DEFAULT '',
      `blink` varchar(249) NOT NULL DEFAULT '',
      `banner` int(1) NOT NULL DEFAULT '0',
      `beschreibung` text,
      `hits` int(50) NOT NULL DEFAULT '0',
      PRIMARY KEY (`id`)
    ) ".dba::get_db_engine($_SESSION['mysql_dbengine'])." DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;",false,false,true);

    //===============================================================
    //-> LinkUs =====================================================
    //===============================================================
    db("DROP TABLE IF EXISTS `".dba::get('linkus')."`;",false,false,true);
    db("CREATE TABLE IF NOT EXISTS `".dba::get('linkus')."` (
      `id` int(5) NOT NULL AUTO_INCREMENT,
      `url` varchar(249) NOT NULL DEFAULT '',
      `text` varchar(249) NOT NULL DEFAULT '',
      `banner` int(1) NOT NULL DEFAULT '0',
      `beschreibung` varchar(249) DEFAULT NULL,
      PRIMARY KEY (`id`)
    ) ".dba::get_db_engine($_SESSION['mysql_dbengine'])." DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;",false,false,true);

    //===============================================================
    //-> Nachrichten ================================================
    //===============================================================
    db("DROP TABLE IF EXISTS `".dba::get('msg')."`;",false,false,true);
    db("CREATE TABLE IF NOT EXISTS `".dba::get('msg')."` (
      `id` int(5) NOT NULL AUTO_INCREMENT,
      `datum` int(20) NOT NULL DEFAULT '0',
      `von` int(5) NOT NULL DEFAULT '0',
      `an` int(5) NOT NULL DEFAULT '0',
      `see_u` int(1) NOT NULL DEFAULT '0',
      `page` int(11) NOT NULL DEFAULT '0',
      `titel` varchar(80) NOT NULL DEFAULT '',
      `nachricht` text NOT NULL,
      `see` int(1) NOT NULL DEFAULT '0',
      `readed` int(1) NOT NULL DEFAULT '0',
      `sendmail` int(1) DEFAULT '0',
      `sendnews` int(1) NOT NULL DEFAULT '0',
      `senduser` varchar(255) NOT NULL DEFAULT '',
      `sendnewsuser` int(5) NOT NULL DEFAULT '0',
      PRIMARY KEY (`id`)
    ) ".dba::get_db_engine($_SESSION['mysql_dbengine'])." DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;",false,false,true);

    //===============================================================
    //-> Navigation =================================================
    //===============================================================
     db("DROP TABLE IF EXISTS `".dba::get('navi')."`;",false,false,true);
     db("CREATE TABLE IF NOT EXISTS `".dba::get('navi')."` (
      `id` int(11) NOT NULL AUTO_INCREMENT,
      `pos` int(20) NOT NULL DEFAULT '0',
      `kat` varchar(20) DEFAULT '',
      `shown` int(1) NOT NULL DEFAULT '1',
      `name` varchar(249) DEFAULT '',
      `title` varchar(249) NOT NULL DEFAULT '',
      `url` varchar(249) DEFAULT '',
      `target` int(1) NOT NULL DEFAULT '0',
      `type` int(1) NOT NULL DEFAULT '0',
      `internal` int(1) NOT NULL DEFAULT '0',
      `wichtig` int(1) NOT NULL DEFAULT '0',
      `editor` int(10) NOT NULL DEFAULT '0',
      `extended_perm` varchar(50) DEFAULT NULL,
      PRIMARY KEY (`id`)
    ) ".dba::get_db_engine($_SESSION['mysql_dbengine'])." DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;",false,false,true);

    //===============================================================
    //-> Navigation Kategorien ======================================
    //===============================================================
    db("DROP TABLE IF EXISTS `".dba::get('navi_kats')."`;",false,false,true);
    db("CREATE TABLE IF NOT EXISTS `".dba::get('navi_kats')."` (
      `id` int(10) NOT NULL AUTO_INCREMENT,
      `name` varchar(200) NOT NULL,
      `placeholder` varchar(200) NOT NULL,
      `level` int(2) NOT NULL,
      PRIMARY KEY (`id`)
    ) ".dba::get_db_engine($_SESSION['mysql_dbengine'])." DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;",false,false,true);

    //===============================================================
    //-> News =======================================================
    //===============================================================
    db("DROP TABLE IF EXISTS `".dba::get('news')."`;",false,false,true);
    db("CREATE TABLE IF NOT EXISTS `".dba::get('news')."` (
      `id` int(10) NOT NULL AUTO_INCREMENT,
      `autor` varchar(11) NOT NULL DEFAULT '',
      `datum` varchar(20) NOT NULL DEFAULT '',
      `kat` int(2) NOT NULL DEFAULT '0',
      `sticky` int(20) NOT NULL DEFAULT '0',
      `titel` varchar(249) NOT NULL DEFAULT '',
      `intern` int(1) NOT NULL DEFAULT '0',
      `text` text NOT NULL,
      `klapplink` varchar(20) NOT NULL DEFAULT '',
      `klapptext` text NOT NULL,
      `link1` varchar(100) NOT NULL DEFAULT '',
      `url1` varchar(200) NOT NULL DEFAULT '',
      `link2` varchar(100) NOT NULL DEFAULT '',
      `url2` varchar(200) NOT NULL DEFAULT '',
      `link3` varchar(100) NOT NULL DEFAULT '',
      `url3` varchar(200) NOT NULL DEFAULT '',
      `viewed` int(10) NOT NULL DEFAULT '0',
      `public` int(1) NOT NULL DEFAULT '0',
      `timeshift` int(14) NOT NULL DEFAULT '0',
      `comments` int(1) NOT NULL DEFAULT '1',
      `custom_image` int(1) NOT NULL DEFAULT '0',
      PRIMARY KEY (`id`)
    ) ".dba::get_db_engine($_SESSION['mysql_dbengine'])." DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;",false,false,true);

    //===============================================================
    //-> Newskategorien =============================================
    //===============================================================
    db("DROP TABLE IF EXISTS `".dba::get('newskat')."`;",false,false,true);
    db("CREATE TABLE IF NOT EXISTS `".dba::get('newskat')."` (
      `id` int(5) NOT NULL AUTO_INCREMENT,
      `katimg` varchar(100) NOT NULL DEFAULT '',
      `kategorie` varchar(60) NOT NULL DEFAULT '',
      PRIMARY KEY (`id`)
    ) ".dba::get_db_engine($_SESSION['mysql_dbengine'])." DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;",false,false,true);

    //===============================================================
    //-> Newskommentare =============================================
    //===============================================================
    db("DROP TABLE IF EXISTS `".dba::get('newscomments')."`;",false,false,true);
    db("CREATE TABLE IF NOT EXISTS `".dba::get('newscomments')."` (
      `id` int(10) NOT NULL AUTO_INCREMENT,
      `news` int(10) NOT NULL DEFAULT '0',
      `nick` varchar(50) NOT NULL DEFAULT '',
      `datum` int(20) NOT NULL DEFAULT '0',
      `email` varchar(130) NOT NULL DEFAULT '',
      `hp` varchar(50) NOT NULL DEFAULT '',
      `reg` int(5) NOT NULL DEFAULT '0',
      `comment` text NOT NULL,
      `ip` varchar(50) NOT NULL DEFAULT '',
      `editby` text,
      PRIMARY KEY (`id`)
    ) ".dba::get_db_engine($_SESSION['mysql_dbengine'])." DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;",false,false,true);

    //===============================================================
    //-> Partnerbuttons =============================================
    //===============================================================
    db("DROP TABLE IF EXISTS `".dba::get('partners')."`;",false,false,true);
    db("CREATE TABLE IF NOT EXISTS `".dba::get('partners')."` (
      `id` int(5) NOT NULL AUTO_INCREMENT,
      `link` varchar(100) NOT NULL DEFAULT '',
      `banner` varchar(100) NOT NULL DEFAULT '',
      `textlink` int(1) NOT NULL DEFAULT '0',
      PRIMARY KEY (`id`)
    ) ".dba::get_db_engine($_SESSION['mysql_dbengine'])." DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;",false,false,true);

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
      `activateusers` int(1) NOT NULL DEFAULT '0',
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
      `slideshow` int(1) NOT NULL DEFAULT '0',
      `smileys` int(1) NOT NULL DEFAULT '0',
      `sponsors` int(1) NOT NULL DEFAULT '0',
      `shoutbox` int(1) NOT NULL DEFAULT '0',
      `startpage` int(1) NOT NULL DEFAULT '0',
      `support` int(1) NOT NULL DEFAULT '0',
      `votes` int(1) NOT NULL DEFAULT '0',
      `votesadmin` int(1) NOT NULL DEFAULT '0',
      PRIMARY KEY (`id`),
      KEY `user` (`user`)
    ) ".dba::get_db_engine($_SESSION['mysql_dbengine'])." DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;",false,false,true);

    //===============================================================
    //-> Positionen =================================================
    //===============================================================
    db("DROP TABLE IF EXISTS `".dba::get('pos')."`;",false,false,true);
    db("CREATE TABLE IF NOT EXISTS `".dba::get('pos')."` (
      `id` int(2) NOT NULL AUTO_INCREMENT,
      `pid` int(2) NOT NULL DEFAULT '0',
      `position` varchar(30) NOT NULL DEFAULT '',
      `nletter` int(1) NOT NULL DEFAULT '0',
      PRIMARY KEY (`id`)
    ) ".dba::get_db_engine($_SESSION['mysql_dbengine'])." DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;",false,false,true);

    //===============================================================
    //-> Profilfelder ===============================================
    //===============================================================
    db("DROP TABLE IF EXISTS `".dba::get('profile')."`;",false,false,true);
    db("CREATE TABLE IF NOT EXISTS `".dba::get('profile')."` (
      `id` int(5) unsigned NOT NULL AUTO_INCREMENT,
      `kid` int(11) NOT NULL DEFAULT '0',
      `name` varchar(200) NOT NULL,
      `feldname` varchar(20) NOT NULL DEFAULT '',
      `type` int(5) NOT NULL DEFAULT '1',
      `shown` int(5) NOT NULL DEFAULT '1',
      PRIMARY KEY (`id`)
    ) ".dba::get_db_engine($_SESSION['mysql_dbengine'])." DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;",false,false,true);

    //===============================================================
    //-> Rankings ===================================================
    //===============================================================
    db("DROP TABLE IF EXISTS `".dba::get('rankings')."`;",false,false,true);
    db("CREATE TABLE IF NOT EXISTS `".dba::get('rankings')."` (
      `id` int(5) NOT NULL AUTO_INCREMENT,
      `league` varchar(50) NOT NULL,
      `lastranking` int(10) NOT NULL DEFAULT '0',
      `rank` int(10) NOT NULL DEFAULT '0',
      `squad` varchar(5) NOT NULL,
      `url` varchar(249) NOT NULL DEFAULT '',
      `postdate` int(20) NOT NULL,
      PRIMARY KEY (`id`)
    ) ".dba::get_db_engine($_SESSION['mysql_dbengine'])." DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;",false,false,true);

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
    ) ".dba::get_db_engine($_SESSION['mysql_dbengine'])." DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;",false,false,true);

    //===============================================================
    //-> Server =====================================================
    //===============================================================
    db("DROP TABLE IF EXISTS `".dba::get('server')."`;",false,false,true);
    db("CREATE TABLE IF NOT EXISTS `".dba::get('server')."` (
    `id` int(5) NOT NULL AUTO_INCREMENT,
    `game` varchar(100) NOT NULL DEFAULT '',
    `shown` int(1) NOT NULL DEFAULT '1',
    `navi` int(1) NOT NULL DEFAULT '0',
    `name` text,
    `ip` varchar(50) NOT NULL DEFAULT '0',
    `port` int(10) NOT NULL DEFAULT '0',
    `pwd` varchar(20) NOT NULL DEFAULT '',
    `qport` varchar(10) NOT NULL DEFAULT '',
    `custom_icon` varchar(30) NOT NULL DEFAULT '',
    PRIMARY KEY (`id`)
    ) ".dba::get_db_engine($_SESSION['mysql_dbengine'])." DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;",false,false,true);

    //===============================================================
    //-> Settings ===================================================
    //===============================================================
    db("DROP TABLE IF EXISTS `".dba::get('settings')."`;",false,false,true);
    db("CREATE TABLE IF NOT EXISTS `".dba::get('settings')."` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `key` varchar(200) NOT NULL DEFAULT '',
        `value` text,
        `default` text,
        `length` int(11) NOT NULL DEFAULT '1',
        `type` varchar(20) NOT NULL DEFAULT 'int' COMMENT 'int/string',
        PRIMARY KEY (`id`),
        UNIQUE KEY `key` (`key`)
    ) ".dba::get_db_engine($_SESSION['mysql_dbengine'])." DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;",false,false,true);

    //===============================================================
    //-> Sessions ===================================================
    //===============================================================
    db("DROP TABLE IF EXISTS `".dba::get('sessions')."`;",false,false,true);
    db("CREATE TABLE IF NOT EXISTS `".dba::get('sessions')."` (
        `id` char(128) NOT NULL,
        `set_time` char(10) NOT NULL,
        `data` text NOT NULL,
        `session_key` char(128) NOT NULL,
        PRIMARY KEY (`id`)
    ) ".dba::get_db_engine($_SESSION['mysql_dbengine'])." DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;",false,false,true);

    //===============================================================
    //-> Shoutbox ===================================================
    //===============================================================
    db("DROP TABLE IF EXISTS `".dba::get('shout')."`;",false,false,true);
    db("CREATE TABLE IF NOT EXISTS `".dba::get('shout')."` (
      `id` int(11) NOT NULL AUTO_INCREMENT,
      `datum` int(30) NOT NULL DEFAULT '0',
      `nick` varchar(50) NOT NULL DEFAULT '',
      `email` varchar(130) NOT NULL DEFAULT '',
      `text` text NOT NULL,
      `ip` varchar(50) NOT NULL DEFAULT '',
      PRIMARY KEY (`id`)
    ) ".dba::get_db_engine($_SESSION['mysql_dbengine'])." DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;",false,false,true);

    //===============================================================
    //-> Seiten =====================================================
    //===============================================================
    db("DROP TABLE IF EXISTS `".dba::get('sites')."`;",false,false,true);
    db("CREATE TABLE IF NOT EXISTS `".dba::get('sites')."` (
      `id` int(5) NOT NULL AUTO_INCREMENT,
      `titel` varchar(50) NOT NULL DEFAULT '',
      `text` text NOT NULL,
      `html` int(1) NOT NULL,
      PRIMARY KEY (`id`)
    ) ".dba::get_db_engine($_SESSION['mysql_dbengine'])." DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;",false,false,true);

    //===============================================================
    //-> Slideshow ==================================================
    //===============================================================
    db("DROP TABLE IF EXISTS `".dba::get('slideshow')."`;",false,false,true);
    db("CREATE TABLE IF NOT EXISTS `".dba::get('slideshow')."` (
    `id` int(5) NOT NULL AUTO_INCREMENT,
    `pos` int(5) NOT NULL DEFAULT '0',
    `bez` varchar(200) NOT NULL DEFAULT '',
    `showbez` int(1) NOT NULL default '1',
    `desc` varchar(249) NOT NULL DEFAULT '',
    `url` varchar(200) NOT NULL DEFAULT '',
    `target` int(1) NOT NULL DEFAULT '0',
    PRIMARY KEY (`id`)
    ) ".dba::get_db_engine($_SESSION['mysql_dbengine'])." DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;",false,false,true);

    //===============================================================
    //-> Sponsoren ==================================================
    //===============================================================
    db("DROP TABLE IF EXISTS `".dba::get('sponsoren')."`;",false,false,true);
    db("CREATE TABLE IF NOT EXISTS `".dba::get('sponsoren')."` (
      `id` int(5) NOT NULL AUTO_INCREMENT,
      `name` varchar(249) NOT NULL DEFAULT '',
      `link` varchar(249) NOT NULL DEFAULT '',
      `beschreibung` text,
      `site` int(1) NOT NULL DEFAULT '0',
      `send` varchar(5) NOT NULL DEFAULT '',
      `slink` varchar(249) NOT NULL DEFAULT '',
      `banner` int(1) NOT NULL DEFAULT '0',
      `bend` varchar(5) NOT NULL DEFAULT '',
      `blink` varchar(249) NOT NULL DEFAULT '',
      `box` int(1) NOT NULL DEFAULT '0',
      `xend` varchar(5) NOT NULL DEFAULT 'gif',
      `xlink` varchar(255) NOT NULL DEFAULT '',
      `pos` int(5) NOT NULL,
      `hits` int(50) NOT NULL DEFAULT '0',
      PRIMARY KEY (`id`)
    ) ".dba::get_db_engine($_SESSION['mysql_dbengine'])." DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;",false,false,true);

    //===============================================================
    //-> Startseite =================================================
    //===============================================================
    db("DROP TABLE IF EXISTS `".dba::get('startpage')."`;",false,false,true);
    db("CREATE TABLE IF NOT EXISTS `".dba::get('startpage')."` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `name` varchar(200) NOT NULL,
        `url` varchar(200) NOT NULL,
        `level` int(1) NOT NULL DEFAULT '1',
        PRIMARY KEY (`id`)
    ) ".dba::get_db_engine($_SESSION['mysql_dbengine'])." DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;",false,false,true);

    //===============================================================
    //-> Squads =====================================================
    //===============================================================
    db("DROP TABLE IF EXISTS `".dba::get('squads')."`;",false,false,true);
    db("CREATE TABLE IF NOT EXISTS `".dba::get('squads')."` (
      `id` int(5) NOT NULL AUTO_INCREMENT,
      `name` varchar(40) NOT NULL DEFAULT '',
      `game` varchar(40) NOT NULL DEFAULT '',
      `icon` varchar(20) NOT NULL DEFAULT '',
      `pos` int(1) NOT NULL DEFAULT '0',
      `shown` int(1) NOT NULL DEFAULT '0',
      `navi` int(1) NOT NULL DEFAULT '1',
      `status` int(1) NOT NULL DEFAULT '1',
      `beschreibung` text,
      `team_show` int(1) NOT NULL DEFAULT '1',
      PRIMARY KEY (`id`)
    ) ".dba::get_db_engine($_SESSION['mysql_dbengine'])." DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;",false,false,true);

    //===============================================================
    //-> Squadusers =================================================
    //===============================================================
    db("DROP TABLE IF EXISTS `".dba::get('squaduser')."`;",false,false,true);
    db("CREATE TABLE IF NOT EXISTS `".dba::get('squaduser')."` (
      `id` int(11) NOT NULL AUTO_INCREMENT,
      `user` int(5) NOT NULL DEFAULT '0',
      `squad` int(2) NOT NULL DEFAULT '0',
      PRIMARY KEY (`id`),
      KEY `user` (`user`)
    ) ".dba::get_db_engine($_SESSION['mysql_dbengine'])." DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;",false,false,true);

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
    ) ".dba::get_db_engine($_SESSION['mysql_dbengine'])." DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;",false,false,true);

    //===============================================================
    //-> Usergallery ================================================
    //===============================================================
    db("DROP TABLE IF EXISTS `".dba::get('usergallery')."`;",false,false,true);
    db("CREATE TABLE IF NOT EXISTS `".dba::get('usergallery')."` (
      `id` int(5) NOT NULL AUTO_INCREMENT,
      `user` int(5) NOT NULL DEFAULT '0',
      `beschreibung` text,
      `pic` varchar(200) NOT NULL DEFAULT '',
      PRIMARY KEY (`id`)
    ) ".dba::get_db_engine($_SESSION['mysql_dbengine'])." DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;",false,false,true);

    //===============================================================
    //-> UserGB =====================================================
    //===============================================================
    db("DROP TABLE IF EXISTS `".dba::get('usergb')."`;",false,false,true);
    db("CREATE TABLE IF NOT EXISTS `".dba::get('usergb')."` (
      `id` int(5) NOT NULL AUTO_INCREMENT,
      `user` int(5) NOT NULL DEFAULT '0',
      `datum` int(20) NOT NULL DEFAULT '0',
      `nick` varchar(50) NOT NULL DEFAULT '',
      `email` varchar(130) NOT NULL DEFAULT '',
      `hp` varchar(100) NOT NULL DEFAULT '',
      `reg` int(1) NOT NULL DEFAULT '0',
      `nachricht` text NOT NULL,
      `ip` varchar(50) NOT NULL DEFAULT '',
      `editby` text,
      PRIMARY KEY (`id`),
      KEY `user` (`user`)
    ) ".dba::get_db_engine($_SESSION['mysql_dbengine'])." DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;",false,false,true);

    //===============================================================
    //-> Userposis ==================================================
    //===============================================================
    db("DROP TABLE IF EXISTS `".dba::get('userpos')."`;",false,false,true);
    db("CREATE TABLE IF NOT EXISTS `".dba::get('userpos')."` (
      `id` int(11) NOT NULL AUTO_INCREMENT,
      `user` int(5) NOT NULL DEFAULT '0',
      `posi` int(5) NOT NULL DEFAULT '0',
      `squad` int(5) NOT NULL DEFAULT '0',
      PRIMARY KEY (`id`)
    ) ".dba::get_db_engine($_SESSION['mysql_dbengine'])." DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;",false,false,true);

    //===============================================================
    //-> Users ======================================================
    //===============================================================
    db("DROP TABLE IF EXISTS `".dba::get('users')."`;",false,false,true);
    db("CREATE TABLE IF NOT EXISTS `".dba::get('users')."` (
      `id` int(5) NOT NULL AUTO_INCREMENT,
      `user` varchar(200) NOT NULL DEFAULT '',
      `nick` varchar(50) NOT NULL DEFAULT '',
      `pwd` varchar(255) NOT NULL DEFAULT '',
      `pwd_encoder` int(1) NOT NULL DEFAULT '2',
      `sessid` varchar(32) DEFAULT NULL,
      `pkey` varchar(50) NOT NULL DEFAULT '',
      `actkey` varchar(50) NOT NULL DEFAULT '',
      `country` varchar(20) NOT NULL DEFAULT 'de',
      `language` varchar(15) NOT NULL DEFAULT 'default',
      `ip` varchar(50) NOT NULL DEFAULT '',
      `regdatum` int(20) NOT NULL DEFAULT '0',
      `email` varchar(200) NOT NULL DEFAULT '',
      `icq` varchar(20) NOT NULL DEFAULT '',
      `skype` varchar(100) NOT NULL DEFAULT '',
      `xbox` varchar(100) NOT NULL DEFAULT '',
      `psn` varchar(100) NOT NULL DEFAULT '',
      `origin` varchar(100) NOT NULL DEFAULT '',
      `bnet` varchar(100) NOT NULL DEFAULT '',
      `xfire` varchar(100) NOT NULL DEFAULT '',
      `steamurl` varchar(200) NOT NULL DEFAULT '',
      `level` varchar(15) NOT NULL DEFAULT '',
      `rlname` varchar(200) NOT NULL DEFAULT '',
      `city` varchar(200) NOT NULL DEFAULT '',
      `sex` int(1) NOT NULL DEFAULT '0',
      `bday` varchar(20) NOT NULL DEFAULT '',
      `hobbys` varchar(249) NOT NULL DEFAULT '',
      `motto` varchar(249) NOT NULL DEFAULT '',
      `hp` varchar(200) NOT NULL DEFAULT '',
      `cpu` varchar(200) NOT NULL DEFAULT '',
      `ram` varchar(200) NOT NULL DEFAULT '',
      `monitor` varchar(200) NOT NULL DEFAULT '',
      `maus` varchar(200) NOT NULL DEFAULT '',
      `mauspad` varchar(200) NOT NULL DEFAULT '',
      `headset` varchar(200) NOT NULL DEFAULT '',
      `board` varchar(200) NOT NULL DEFAULT '',
      `os` varchar(200) NOT NULL DEFAULT '',
      `graka` varchar(200) NOT NULL DEFAULT '',
      `hdd` varchar(200) NOT NULL DEFAULT '',
      `inet` varchar(200) NOT NULL DEFAULT '',
      `signatur` text,
      `position` int(2) NOT NULL DEFAULT '0',
      `status` int(1) NOT NULL DEFAULT '1',
      `ex` varchar(200) NOT NULL DEFAULT '',
      `job` varchar(200) NOT NULL DEFAULT '',
      `time` int(20) NOT NULL DEFAULT '0',
      `listck` int(1) NOT NULL DEFAULT '0',
      `online` int(1) NOT NULL DEFAULT '0',
      `nletter` int(1) NOT NULL DEFAULT '1',
      `whereami` text,
      `drink` varchar(249) NOT NULL DEFAULT '',
      `essen` varchar(249) NOT NULL DEFAULT '',
      `film` varchar(249) NOT NULL DEFAULT '',
      `musik` varchar(249) NOT NULL DEFAULT '',
      `song` varchar(249) NOT NULL DEFAULT '',
      `buch` varchar(249) NOT NULL DEFAULT '',
      `autor` varchar(249) NOT NULL DEFAULT '',
      `person` varchar(249) NOT NULL DEFAULT '',
      `sport` varchar(249) NOT NULL DEFAULT '',
      `sportler` varchar(249) NOT NULL DEFAULT '',
      `auto` varchar(249) NOT NULL DEFAULT '',
      `game` varchar(249) NOT NULL DEFAULT '',
      `favoclan` varchar(249) NOT NULL DEFAULT '',
      `spieler` varchar(249) NOT NULL DEFAULT '',
      `map` varchar(249) NOT NULL DEFAULT '',
      `waffe` varchar(249) NOT NULL DEFAULT '',
      `rasse` varchar(249) NOT NULL DEFAULT '',
      `url2` varchar(249) NOT NULL DEFAULT '',
      `url3` varchar(249) NOT NULL DEFAULT '',
      `beschreibung` text,
      `gmaps_koord` varchar(255) NOT NULL DEFAULT '',
      `pnmail` int(1) NOT NULL DEFAULT '1',
      `profile_access` int(1) NOT NULL DEFAULT '0',
      `rss_key` varchar(50) NOT NULL DEFAULT '',
      `startpage` int(5) NOT NULL DEFAULT '0',
      PRIMARY KEY (`id`),
      UNIQUE KEY `id` (`id`)
    ) ".dba::get_db_engine($_SESSION['mysql_dbengine'])." DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;",false,false,true);

    //===============================================================
    //-> Userstats ==================================================
    //===============================================================
    db("DROP TABLE IF EXISTS `".dba::get('userstats')."`;",false,false,true);
    db("CREATE TABLE IF NOT EXISTS `".dba::get('userstats')."` (
      `id` int(5) NOT NULL AUTO_INCREMENT,
      `user` int(10) NOT NULL DEFAULT '0',
      `logins` int(100) NOT NULL DEFAULT '0',
      `writtenmsg` int(10) NOT NULL DEFAULT '0',
      `lastvisit` int(20) NOT NULL DEFAULT '0',
      `hits` int(249) NOT NULL DEFAULT '0',
      `votes` int(5) NOT NULL DEFAULT '0',
      `profilhits` int(20) NOT NULL DEFAULT '0',
      `forumposts` int(5) NOT NULL DEFAULT '0',
      `cws` int(5) NOT NULL DEFAULT '0',
      `akl` int(5) NOT NULL DEFAULT '1',
      PRIMARY KEY (`id`)
    ) ".dba::get_db_engine($_SESSION['mysql_dbengine'])." DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;",false,false,true);

    //===============================================================
    //-> Votes ======================================================
    //===============================================================
    db("DROP TABLE IF EXISTS `".dba::get('votes')."`;",false,false,true);
    db("CREATE TABLE IF NOT EXISTS `".dba::get('votes')."` (
      `id` int(5) NOT NULL AUTO_INCREMENT,
      `datum` int(20) NOT NULL DEFAULT '0',
      `titel` varchar(249) NOT NULL DEFAULT '',
      `intern` int(1) NOT NULL DEFAULT '0',
      `menu` int(1) NOT NULL DEFAULT '0',
      `closed` int(1) NOT NULL DEFAULT '0',
      `von` int(10) NOT NULL DEFAULT '0',
      `forum` int(1) NOT NULL DEFAULT '0',
      PRIMARY KEY (`id`)
    ) ".dba::get_db_engine($_SESSION['mysql_dbengine'])." DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;",false,false,true);

    //===============================================================
    //-> Vote Möglichkeit ==========================================
    //===============================================================
    db("DROP TABLE IF EXISTS `".dba::get('vote_results')."`;",false,false,true);
    db("CREATE TABLE IF NOT EXISTS `".dba::get('vote_results')."` (
      `id` int(5) NOT NULL AUTO_INCREMENT,
      `vid` int(5) NOT NULL DEFAULT '0',
      `what` varchar(5) NOT NULL DEFAULT '',
      `sel` varchar(80) NOT NULL DEFAULT '',
      `stimmen` int(5) NOT NULL DEFAULT '0',
      PRIMARY KEY (`id`)
    ) ".dba::get_db_engine($_SESSION['mysql_dbengine'])." DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;",false,false,true);
}