<?php
$versions[3] = array('update_id' => 3, 3 => '1.5.5.x', "version_list" => 'v1.5.5.x', 'call' => '155x_1600', 'dbv' => false); //Update Info

//Update von V1.5.5.x auf V1.6.0.0 DZCP-Extended Edition
function install_155x_1600_update()
{
    global $db, $prefix;

    db("ALTER TABLE `".$db['f_threads']."` CHANGE `edited` `edited` TEXT CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL",false,false,true);
    db("ALTER TABLE `".$db['users']."` CHANGE `whereami` `whereami` TEXT CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL",false,false,true);
    db("ALTER TABLE `".$db['users']."` CHANGE `hlswid` `xfire` VARCHAR( 100 ) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT ''",false,false,true);
    db("ALTER TABLE `".$db['downloads']."` ADD `last_dl` INT( 20 ) NOT NULL DEFAULT '0' AFTER `date`",false,false,true);
    db("ALTER TABLE `".$db['settings']."` CHANGE `i_autor` `i_autor` TEXT CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL",false,false,true);
    db("ALTER TABLE `".$db['gb']."` CHANGE `hp` `hp` VARCHAR(130) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL",false,false,true);
    db("ALTER TABLE `".$db['settings']."` ADD `urls_linked` INT(1) NOT NULL DEFAULT '1', ADD `ts_customicon` INT(1) NOT NULL DEFAULT '1' AFTER `ts_version`, ADD `ts_showchannel` INT(1) NOT NULL DEFAULT '0' AFTER `ts_customicon`",false,false,true);
    db("ALTER TABLE `".$db['msg']."` CHANGE `see_u` `see_u` INT( 1 ) NOT NULL DEFAULT '0'",false,false,true);
    db("ALTER TABLE `".$db['msg']."` CHANGE `page` `page` INT( 11 ) NOT NULL DEFAULT '0'",false,false,true);
    db("ALTER TABLE `".$db['away']."` CHANGE `lastedit` `lastedit` TEXT CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL",false,false,true);
    db("ALTER TABLE `".$db['settings']."` DROP `pfad`",false,false,true);
    db("ALTER TABLE `".$db['newskat']."` CHANGE `katimg` `katimg` VARCHAR( 100 ) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT ''",false,false,true);
    db("ALTER TABLE `".$db['newskat']."` CHANGE `kategorie` `kategorie` VARCHAR( 60 ) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT ''",false,false,true);
    db("ALTER TABLE `".$db['server']."` CHANGE `name` `name` TEXT CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL",false,false,true);
    db("ALTER TABLE `".$db['server']."` DROP `bl_file`, DROP `bl_path`, DROP `ftp_pwd`, DROP `ftp_login`, DROP `ftp_host`;",false,false,true);
    db("ALTER TABLE `".$db['serverliste']."` CHANGE `clanname` `clanname` VARCHAR( 50 ) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT ''",false,false,true);
    db("ALTER TABLE `".$db['serverliste']."` CHANGE `datum` `datum` INT( 11 ) NOT NULL DEFAULT '0'",false,false,true);
    db("ALTER TABLE `".$db['settings']."` ADD `db_version` VARCHAR( 5 ) NOT NULL DEFAULT '00000'");
    db("ALTER TABLE `".$db['config']."` ADD `cache_engine` varchar(50) NOT NULL DEFAULT 'file'",false,false,true);
    db("ALTER TABLE `".$db['settings']."` ADD `memcache_host` VARCHAR( 50 ) NOT NULL DEFAULT '';",false,false,true);
    db("ALTER TABLE `".$db['settings']."` ADD `memcache_port` INT( 11 ) NOT NULL DEFAULT '11211';",false,false,true);
    db("ALTER TABLE `".$db['config']."` ADD `cache_news` INT( 10 ) NOT NULL DEFAULT '5' AFTER `cache_server`;",false,false,true);
    db("ALTER TABLE `".$db['config']."` ADD `news_feed` INT( 1 ) NOT NULL DEFAULT '1'",false,false,true);
    db("ALTER TABLE `".$db['settings']."` DROP `gmaps_key`",false,false,true);
    db("ALTER TABLE `".$db['users']."` ADD `pkey` VARCHAR( 50 ) NOT NULL DEFAULT '' AFTER `sessid`;",false,false,true);
    db("ALTER TABLE `".$db['navi']."` ADD `extended_perm` varchar(50) DEFAULT NULL AFTER `editor`;",false,false,true);
    db("TABLE `".$db['settings']."` DROP `gametiger`,`squadtmpl`,`balken_vote`,`balken_vote_menu`,`balken_cw`;",false,false,true);
    db("ALTER TABLE `".$db['newscomments']."` CHANGE `editby` `editby` TEXT CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL",false,false,true);
    db("ALTER TABLE `".$db['acomments']."` CHANGE `editby` `editby` TEXT CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL",false,false,true);
    db("ALTER TABLE `".$db['cw_comments']."` CHANGE `editby` `editby` TEXT CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL",false,false,true);
    db("ALTER TABLE `".$db['gb']."` CHANGE `editby` `editby` TEXT CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL",false,false,true);
    db("ALTER TABLE `".$db['usergb']."` CHANGE `editby` `editby` TEXT CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL",false,false,true);
    db("ALTER TABLE `".$db['usergb']."` ADD INDEX ( `user` );",false,false,true);
    db("ALTER TABLE `".$db['users']."` CHANGE `gmaps_koord` `gmaps_koord` VARCHAR( 255 ) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT ''",false,false,true);
    db("ALTER TABLE `".$db['users']."` ADD `pwd_encoder` INT( 1 ) NOT NULL DEFAULT '0' AFTER `pwd`;",false,false,true);
    db("ALTER TABLE `".$db['settings']."` ADD `default_pwd_encoder` INT( 1 ) NOT NULL DEFAULT '2' AFTER `urls_linked`;",false,false,true);
    db("ALTER TABLE `".$db['artikel']."` ADD `viewed` INT( 11 ) NOT NULL DEFAULT '0' AFTER `url3`;",false,false,true);
    db("ALTER TABLE `".$db['config']."` ADD `f_downloadcom` INT( 5 ) NOT NULL DEFAULT '20' AFTER `f_artikelcom`;",false,false,true);
    db("ALTER TABLE `".$db['settings']."` ADD `reg_dlcomments` INT( 1 ) NOT NULL DEFAULT '1' AFTER `reg_newscomments`;",false,false,true);
    db("ALTER TABLE `".$db['downloads']."` ADD `comments` INT( 1 ) NOT NULL DEFAULT '0' AFTER `last_dl`;",false,false,true);
    db("ALTER TABLE `".$db['f_posts']."` CHANGE `edited` `edited` TEXT CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL",false,false,true);
    db("ALTER TABLE `".$db['rankings']."` CHANGE `lastranking` `lastranking` INT( 10 ) NOT NULL DEFAULT '0'",false,false,true);

    // Add UNIQUE INDEX
    if(db("SELECT id FROM `".$db['config']."`",true) >= 2)
    {
        $get_old = db("SELECT * FROM `".$db['config']."` LIMIT 0 , 1",false,true);
        db("TRUNCATE TABLE `".$db['config']."`",false,false,true);
        db("ALTER TABLE `".$db['config']."` ADD UNIQUE (`id`)",false,false,true);
        $count = count($get_old); $i = 1; $set = '';
        foreach ($get_old as $key => $var)
        {
            $i++;
            if($i <= $count)
                $set .= $key." = '".$var."', ";
            else
                $set .= $key." = '".$var."';";
        }

        db("INSERT INTO `".$db['config']."` SET ".$set,false,false,true);
    }
    else
        db("ALTER TABLE `".$db['config']."` ADD UNIQUE (`id`)",false,false,true);

    // Add UNIQUE INDEX
    if(db("SELECT id FROM `".$db['settings']."`",true) >= 2)
    {
        $get_old = db("SELECT * FROM `".$db['settings']."` LIMIT 0 , 1",false,true);
        db("TRUNCATE TABLE `".$db['settings']."`",false,false,true);
        db("ALTER TABLE `".$db['settings']."` ADD UNIQUE (`id`)",false,false,true);
        $count = count($get_old); $i = 1; $set = '';
        foreach ($get_old as $key => $var)
        {
            $i++;
            if($i <= $count)
                $set .= $key." = '".$var."', ";
            else
                $set .= $key." = '".$var."';";
        }

        db("INSERT INTO `".$db['settings']."` SET ".$set,false,false,true);
    }
    else
        db("ALTER TABLE `".$db['settings']."` ADD UNIQUE (`id`)",false,false,true);

    // Schreibe DB Version in Datenbank
    db("UPDATE ".$db['settings']." SET `db_version` = '1600' WHERE id = 1",false,false,true);

    // Lösche dzcp_banned Tabelle
    db("DROP TABLE `".$prefix."banned"."`",false,false,true);

    // Forum Sortieren
    db("ALTER TABLE ".$db['f_skats']." ADD `pos` int(5) NOT NULL",false,false,true);

    // Forum Sortieren funktion: schreibe id von spalte in pos feld um konflikte zu vermeiden!
    $qry = db("SELECT id FROM `".$db['f_skats']."`");
    if(_rows($qry) >= 1)
    {  while($get = _fetch($qry)) { db("UPDATE ".$db['f_skats']." SET `pos` = '".$get['id']."' WHERE `id` = '".$get['id']."';",false,false,true); } }

    // Update News einsenden Link * wenn vorhanden
    $qry = db("SELECT id,url FROM `".$db['navi']."` WHERE `name` = '_news_send_'");
    if(_rows($qry) >= 1)
    {  while($get = _fetch($qry)) { if($get['url'] == '../news/send.php') db("UPDATE ".$db['navi']." SET `url` = '../news/?action=send' WHERE `id` = '".$get['id']."';",false,false,true); } }

    // Update setze MD5 Encoder für alte User
    $qry = db("SELECT id FROM `".$db['users']."`");
    if(_rows($qry) >= 1)
    {  while($get = _fetch($qry)) { db("UPDATE ".$db['users']." SET `pwd_encoder` = 0 WHERE `id` = '".$get['id']."';",false,false,true); } }

    //===============================================================
    //-> Cache ======================================================
    //===============================================================
    db("DROP TABLE IF EXISTS `".$db['cache']."`;",false,false,true);
    db("CREATE TABLE IF NOT EXISTS `".$db['cache']."` (
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
    db("DROP TABLE IF EXISTS `".$db['clicks_ips']."`;");
    db("CREATE TABLE IF NOT EXISTS `".$db['clicks_ips']."` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `ip` varchar(15) NOT NULL DEFAULT '000.000.000.000',
    `uid` int(11) NOT NULL DEFAULT '0',
    `ids` int(11) NOT NULL DEFAULT '0',
    `side` varchar(30) NOT NULL DEFAULT '',
    PRIMARY KEY (`id`),
    KEY `ip` (`ip`)
    ) ".get_db_engine($_SESSION['mysql_dbengine'])." DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;",false,false,true);

    // Ersetze Permissions Tabelle
    $qry = db("SELECT * FROM `".$db['permissions']."`");
    if(_rows($qry) >= 1)
    {
        $cache_array_sql = array();
        while($get = _fetch($qry))
        {
            $cache_array_sql[] = "INSERT INTO `".$db['permissions']."` SET
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
    db("DROP TABLE IF EXISTS `".$db['permissions']."`;",false,false,true);
    db("CREATE TABLE IF NOT EXISTS `".$db['permissions']."` (
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
    $qry = db("SELECT id FROM `".$db['users']."`");
    if(_rows($qry))
    {
        while($get = _fetch($qry))
        { if(!db("SELECT id FROM `".$db['permissions']."` WHERE `user` = ".$get['id'],true)) db("INSERT INTO ".$db['permissions']." SET `user` = ".$get['id']); }
    }

    // Ersetze Forum Access Tabelle
    $qry = db("SELECT * FROM `".$db['f_access']."`");
    if(_rows($qry) >= 1)
    {
        $cache_array_sql = array();
        while($get = _fetch($qry))
        { $cache_array_sql[] = "INSERT INTO `".$db['f_access']."` SET `user` = ".$get['user']." , `pos` =  ".(empty($get['pos']) ? '0' : $get['pos']).", `forum` = ".$get['forum']; }

        unset($qry,$get);
    }

    //===============================================================
    //-> Forum: Access ==============================================
    //===============================================================
    db("DROP TABLE IF EXISTS `".$db['f_access']."`;",false,false,true);
    db("CREATE TABLE IF NOT EXISTS `".$db['f_access']."` (
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
    $qry = db("SELECT id FROM `".$db['navi']."` WHERE `name` = '_taktiken_'");
    if(_rows($qry))
    {
        while($get = _fetch($qry))
        { db("UPDATE `".$db['navi']."` SET `extended_perm` = 'edittactics' WHERE `id` = ".$get['id'].";"); }
    }

    $qry = db("SELECT id FROM `".$db['navi']."` WHERE `name` = '_clankasse_'");
    if(_rows($qry))
    {
        while($get = _fetch($qry))
        { db("UPDATE `".$db['navi']."` SET `extended_perm` = 'clankasse' WHERE `id` = ".$get['id'].";"); }
    }

    //===============================================================
    //-> Downloadkommentare =========================================
    //===============================================================
    db("DROP TABLE IF EXISTS `".$db['dlcomments']."`;",false,false,true);
    db("CREATE TABLE IF NOT EXISTS `".$db['dlcomments']."` (
      `id` int(10) NOT NULL AUTO_INCREMENT,
      `download` int(10) NOT NULL DEFAULT '0',
      `nick` varchar(20) NOT NULL DEFAULT '',
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

    return true;
}
?>