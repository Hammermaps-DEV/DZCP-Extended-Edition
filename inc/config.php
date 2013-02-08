<?php
/**
 * <DZCP-Extended Edition>
 * @package: DZCP-Extended Edition
 * @author: DZCP Developer Team || Hammermaps.de Developer Team
 * @link: http://www.dzcp.de || http://www.hammermaps.de
 */

#########################################
//-> DZCP Settings Start
#########################################
define('is_debug', false); // Schaltet den Debug Modus ein, zeigt alle fehler und Notices etc.
define('cache_in_debug', false); // Entscheidet ob im Debug Modus Seiten gecached werden k�nnen

define('buffer_gzip_compress', true); // Soll die Seite mit Hilfe der GZIP-Komprimierung �bertragen werden
define('buffer_gzip_compress_level', 4); // Level der Kompression 1 - 9 *Optimal Level 4

define('dzcp_version_checker', true); // Version auf DZCP.de abgleichen und benachrichtigen ob eine neue Version zur verf�gung steht
define('dzcp_version_checker_refresh', (30*60)); // Wie lange soll gewartet werden um einen Versionsabgleich durchzuf�hren

define('xfire_preloader', true); // XFire Profil per AJAX vorausladen
define('xfire_skin', 'shadow'); // Skin von XFire: shadow,kampf,scifi,fantasy,wow,default
define('xfire_refresh', (10*60)); // Wann soll das Profilbild aktualisiert werden

define('glossar_enabled', true); // Schaltet die Glossar Funktion bei W�rtern an oder aus
define('AjaxLoad', true); // Ajax Loads erlauben

define('modapi_enabled', true); // DZCP ModAPI erlauben
define('allow_additional', true); // additional functions,etc erlauben

## Colors Antispam ##
$backgroundColor  = '#444444';
$textColor        = '#000000';
$noiseColor       = '#AAAAAA';
$lineColor        = '#555555';
## Colors Antispam ##

$AjaxLoad_blacklist = array('welcome','server','teamspeak','infos'); // Ajax Preload gesperrte Men�s *Nur �ndern wenn n�tig!
$picformat = array("jpg", "gif", "png"); // Unterst�tzte Bildformate

// Zeichen f�r den Passwort Generator:
//                           Alphabet gro�:                Alphabet klein:                Zahlen:        Sonderzeichen:
$passwordComponents = array("ABCDEFGHIJKLMNOPQRSTUVWXYZ" , "abcdefghijklmnopqrstuvwxyz" , "0123456789" , "#$@!");

#########################################
//-> DZCP Settings Ende
#########################################

//-> DZCP-Install default variable
if(!isset($_SESSION['installer']))
    $_SESSION['installer'] = false;

if(!isset($_SESSION['db_install']))
    $_SESSION['db_install'] = false;

## REQUIRES ##
if(file_exists(basePath."/inc/mysql.php"))
    require_once(basePath."/inc/mysql.php");
else
{ $sql_host = ''; $sql_user = ''; $sql_pass = ''; $sql_db = ''; $sql_prefix = ''; }

//-> Redirect to Installer
if(empty($sql_user) && empty($sql_pass) && empty($sql_db) && !$_SESSION['installer'] && file_exists(basePath."/_installer/index.php"))
    header('Location: ../_installer/index.php');

//-> MySQL-Datenbankangaben
$prefix = $sql_prefix;
$db = array("host" =>           $sql_host,
            "user" =>           $sql_user,
            "pass" =>           $sql_pass,
            "db" =>             $sql_db,
            "artikel" =>        $prefix."artikel",
            "acomments" =>      $prefix."acomments",
            "awards" =>         $prefix."awards",
            "away" =>           $prefix."away",
            "buddys" =>         $prefix."userbuddys",
            "ipcheck" =>        $prefix."ipcheck",
            "clankasse" =>      $prefix."clankasse",
            "c_kats" =>         $prefix."clankasse_kats",
            "c_payed" =>        $prefix."clankasse_payed",
            "config" =>         $prefix."config",
            "counter" =>        $prefix."counter",
            "c_ips" =>          $prefix."counter_ips",
            "c_who" =>          $prefix."counter_whoison",
            "cw" =>             $prefix."clanwars",
            "cw_comments" =>    $prefix."cw_comments",
            "cw_player" =>      $prefix."clanwar_players",
            "downloads" =>      $prefix."downloads",
            "dl_kat" =>         $prefix."download_kat",
            "events" =>         $prefix."events",
            "f_access" =>       $prefix."f_access",
            "f_abo" =>          $prefix."f_abo",
            "f_kats" =>         $prefix."forumkats",
            "f_posts" =>        $prefix."forumposts",
            "f_skats" =>        $prefix."forumsubkats",
            "f_threads" =>      $prefix."forumthreads",
            "gallery" =>        $prefix."gallery",
            "gb" =>             $prefix."gb",
            "glossar" =>        $prefix."glossar",
            "geometry" =>       $prefix."geometry",
            "links" =>          $prefix."links",
            "linkus" =>         $prefix."linkus",
            "msg" =>            $prefix."messages",
            "news" =>           $prefix."news",
            "navi" =>           $prefix."navi",
            "navi_kats" =>      $prefix."navi_kats",
            "newscomments" =>   $prefix."newscomments",
            "newskat" =>        $prefix."newskat",
            "partners" =>       $prefix."partners",
            "permissions" =>    $prefix."permissions",
            "pos" =>            $prefix."positions",
            "profile" =>        $prefix."profile",
            "rankings" =>       $prefix."rankings",
            "reg" =>            $prefix."reg",
            "server" =>         $prefix."server",
            "serverliste" =>    $prefix."serverliste",
            "settings" =>       $prefix."settings",
            "shout" =>          $prefix."shoutbox",
            "sites" =>          $prefix."sites",
            "squads" =>         $prefix."squads",
            "squaduser" =>      $prefix."squaduser",
            "sponsoren" =>      $prefix."sponsoren",
            "taktik" =>         $prefix."taktiken",
            "users" =>          $prefix."users",
            "usergallery" =>    $prefix."usergallery",
            "usergb" =>         $prefix."usergb",
            "userpos" =>        $prefix."userposis",
            "userstats" =>      $prefix."userstats",
            "votes" =>          $prefix."votes",
            "vote_results" =>   $prefix."vote_results",
            "clicks_ips" =>     $prefix."clicks_ips",
            "cache" =>          $prefix."cache");
?>