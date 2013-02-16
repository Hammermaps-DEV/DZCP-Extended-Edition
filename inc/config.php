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
define('cache_in_debug', true); // Entscheidet ob im Debug Modus Seiten gecached werden können

define('buffer_gzip_compress', true); // Soll die Seite mit Hilfe der GZIP-Komprimierung übertragen werden
define('buffer_gzip_compress_level', 4); // Level der Kompression 1 - 9 *Optimal Level 4

define('dzcp_version_checker', true); // Version auf DZCP.de abgleichen und benachrichtigen ob eine neue Version zur verfügung steht
define('dzcp_version_checker_refresh', (30*60)); // Wie lange soll gewartet werden um einen Versionsabgleich durchzuführen

define('xfire_preloader', true); // XFire Profil per AJAX vorausladen
define('xfire_skin', 'shadow'); // Skin von XFire: shadow,kampf,scifi,fantasy,wow,default
define('xfire_refresh', (10*60)); // Wann soll das Profilbild aktualisiert werden

define('glossar_enabled', true); // Schaltet die Glossar Funktion bei Wörtern an oder aus
define('AjaxLoad', true); // Ajax Loads erlauben

define('modapi_enabled', true); // DZCP ModAPI erlauben
define('allow_additional', true); // additional functions,etc erlauben
define('cache_thumbgen', true); // Zwischenspeichern der Thumbgen generierten Bilder zulassen

/*
 * Wenn Imagick nicht verwendet wird, muss bei großen Bildern auf die PHP Einstellung "memory_limit" geachtet werden.
 * Sollte diese zu klein sein, werden mache Vorschaubilder nicht generiert.
 * Imagick ist bei großen Bildern deutlich schneller als die PHP Bildbearbeitung und GD Erweiterung.
 */
define('use_imagick', true); // Verwendet die Imagick PHP Erweiterung um Vorschaubilder zu erstellen, wenn vorhanden.

## Colors Antispam ##
$backgroundColor  = '#444444';
$textColor        = '#000000';
$noiseColor       = '#AAAAAA';
$lineColor        = '#555555';
## Colors Antispam ##

$AjaxLoad_blacklist = array('welcome','server','teamspeak','infos'); // Ajax Preload gesperrte Menüs *Nur ändern wenn nötig!
$picformat = array("jpg", "gif", "png"); // Unterstützte Bildformate

// Zeichen für den Passwort Generator:
//                           Alphabet groß:                Alphabet klein:                Zahlen:        Sonderzeichen:
$passwordComponents = array("ABCDEFGHIJKLMNOPQRSTUVWXYZ" , "abcdefghijklmnopqrstuvwxyz" , "0123456789" , "#$@!");

// DSL Geschwindigkeiten für errechnen der Download Zeit:
$dsl_formats = array("DSL 1000"=>1024, "DSL 2000"=>2048, "DSL 6000"=>6144, "DSL2+ 16000"=>16384, "VDSL 25.000"=>25600, "VDSL 50.000"=>51200);

## Downloads Filesize Extended ##
/*
 * Wenn aktiviert, wird das Hostsystem des Servers verwendet *Linux / Windows* um die Datengrößen der Downloads zu berechnen.
 * Das ist nötig, wenn Ihr Downloads habt die über 2GB hinausgehen. Daten die 4GB oder Größer sind, ist ein 64-Bit System notwendig.
 * Hinweis: PHP muss einen OS-Shell Zugriff haben und das ausführen von Befehlen auf dem Hostsystem zulassen. *exec() or *shell_exec()
 * Achtung: Bitte nur aktivieren wenn es benötigt wird!
 */

define('allow_os_shell', false);
## Downloads Filesize Extended ##

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
            "dlcomments" =>     $prefix."dlcomments",
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