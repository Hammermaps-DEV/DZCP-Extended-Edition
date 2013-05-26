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
define('is_debug', false); // Schaltet den Debug Modus ein, zeigt alle Fehler und Notices etc.
define('cache_in_debug', true); // Entscheidet ob im Debug, Seiten gecached werden können
define('show_debug_console', true); //Zeigt die Debug Console
define('save_debug_console', false); //Speichert alle in der Debug Console ausgegebenen Texte in eine Log Datei

define('buffer_gzip_compress', true); // Seite mit Hilfe der GZIP-Komprimierung übertragen
define('buffer_gzip_compress_level', 1); // Level der Kompression 1 - 9 *Optimal Level 1

define('dzcp_version_checker', true); // Version auf DZCP.de abgleichen und benachrichtigen ob eine neue Version zur verfügung steht
define('dzcp_version_checker_refresh', (30*60)); // Wie lange soll gewartet werden um einen Versionsabgleich auszuführen

define('xfire_preloader', true); // XFire Profil per AJAX laden
define('xfire_skin', 'shadow'); // Skin von XFire: shadow,kampf,scifi,fantasy,wow,default
define('xfire_refresh', (10*60)); // Wann soll das Profilbild aktualisiert werden

define('glossar_enabled', true); // Schaltet die Glossar Funktion bei Wörtern an oder aus
define('AjaxLoad', true); // Menüs per Ajax laden

define('modapi_enabled', true); // DZCP ModAPI erlauben
define('allow_additional', true); // additional functions,etc erlauben
define('cache_thumbgen', true); // zwischenspeichern der Thumbgen generierten Bildern
define('use_curl', true); // Verwendet die CURL PHP Erweiterung, anstelle von file_get_contents() für externe Zugriffe, wenn vorhanden.
define('use_trash_mails', false); // Erlaubt Trashmail Server als E-Mail Adresse.
define('trash_mail_url', 'http://www.mogelmail.de/mogelmails.xml'); // Liste der Trashmail Server * XML Format

/*
* Wenn Imagick 'nicht' verwendet wird, muss bei großen Bildern auf die PHP Einstellung "memory_limit" geachtet werden.
* Sollte diese zu klein sein, werden mache Vorschaubilder nicht generiert.
* Imagick ist bei großen Bildern deutlich schneller als die PHP Bildbearbeitung und GD Erweiterung.
*/
define('use_imagick', true); // Verwendet die Imagick PHP Erweiterung um Vorschaubilder zu erstellen, wenn vorhanden.

define('count_clicks_expires', (24*60*60)); // Wie Lange die IPs für den Click-Counter gespeichert bleiben.
define('cookie_expires', (60*60*24*30*12)); // Wie Lange die Cookies des CMS ihre Gültigkeit behalten.
define('users_online', (15*60)); // Wie Lange ein User untätig sein muss, um als Offline zu gelten.
define('counter_reload', (24*3600)); // Ab wann der Besucherzähler eine aktualisierung durchführt * User basierend *
define('rss_cache_public_news', (15*60)); // Wann soll der Public RSS Feed aktualisiert werden.
define('rss_cache_private_news', (10*60)); // Wann soll der Interne RSS Feed aktualisiert werden.

## Colors Antispam ##
$backgroundColor  = '#444444';
$textColor        = '#000000';
$noiseColor       = '#AAAAAA';
$lineColor        = '#555555';

## Copyrightlinks ##
$cp_color = '#FFFFFF'; //Hex Farbcode der Hintergrundfarbe Copyrightlinks am Ende der Homepage

// DSL Geschwindigkeiten für errechnen der Download Zeiten * Es kann hier einfach erweitert werden, es wird automatisch auf der Seite eingefügt *
$dsl_formats = array("DSL 1000"=>1024, "DSL 2000"=>2048, "DSL 6000"=>6144, "DSL2+ 16000"=>16384, "VDSL 25.000"=>25600, "VDSL 50.000"=>51200);

// Zeichen für den Passwort Generator:
//                           Alphabet groß:                Alphabet klein:                Zahlen:        Sonderzeichen:
$passwordComponents = array("ABCDEFGHIJKLMNOPQRSTUVWXYZ" , "abcdefghijklmnopqrstuvwxyz" , "0123456789" , "#$@!");

$AjaxLoad_blacklist = array('welcome','server','teamspeak','infos'); // Ajax Preload gesperrte Menüs *Nur ändern wenn nötig!
$picformat = array('jpg', 'jpeg', 'gif', 'png'); // Unterstützte Bildformate

## Downloads Filesize Extended START ##
/*
 * Wenn aktiviert, wird das Hostsystem des Servers verwendet *Linux / Windows* um die Datengrößen der Downloads zu berechnen.
 * Das ist nötig, wenn Ihr Downloads habt die über 2GB hinausgehen. Daten die 4GB oder Größer sind, ist ein 64-Bit System notwendig.
 * Hinweis: PHP muss einen OS-Shell Zugriff haben und das ausführen von Befehlen auf dem Hostsystem zulassen. *exec() or *shell_exec()
 * Achtung: Bitte nur aktivieren wenn es benötigt wird.
 */
define('allow_os_shell', false);
## Downloads Filesize Extended END ##

define('server_show_empty_players', false); //Alle Spieler anzeigen, die deren Namen nicht angezeigt werden können, User werden sonst entfernt.

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
    { $sql_host = ''; $sql_user = ''; $sql_pass = ''; $sql_db = ''; $sql_prefix = '';  }

$db_array = array('host' => $sql_host, 'user' => $sql_user, 'pass' => $sql_pass, 'db' => $sql_db, 'prefix' => $sql_prefix);

//-> Redirect to Installer
if(empty($sql_user) && empty($sql_pass) && empty($sql_db) && !$_SESSION['installer'] && file_exists(basePath."/_installer/index.php"))
    header('Location: ../_installer/index.php');

//-> MySQL-Datenbankangaben
//          [TAG]                [TABELLE]              [NAME + PREFIX]
$db_array['artikel']          = 'artikel';             # dzcp_artikel
$db_array['acomments']        = 'acomments';           # dzcp_acomments
$db_array['awards']           = 'awards';              # dzcp_awards
$db_array['away']             = 'away';                # dzcp_away
$db_array['buddys']           = 'userbuddys';          # dzcp_userbuddys
$db_array['ipcheck']          = 'ipcheck';             # dzcp_ipcheck
$db_array['clankasse']        = 'clankasse';           # dzcp_clankasse
$db_array['c_kats']           = 'clankasse_kats';      # dzcp_clankasse_kats
$db_array['c_payed']          = 'clankasse_payed';     # dzcp_clankasse_payed
$db_array['config']           = 'config';              # dzcp_config
$db_array['counter']          = 'counter';             # dzcp_counter
$db_array['c_ips']            = 'counter_ips';         # dzcp_counter_ips
$db_array['c_who']            = 'counter_whoison';     # dzcp_counter_whoison
$db_array['cw']               = 'clanwars';            # dzcp_clanwars
$db_array['cw_comments']      = 'cw_comments';         # dzcp_cw_comments
$db_array['cw_player']        = 'clanwar_players';     # dzcp_clanwar_players
$db_array['downloads']        = 'downloads';           # dzcp_downloads
$db_array['dl_kat']           = 'download_kat';        # dzcp_download_kat
$db_array['dl_comments']      = 'dlcomments';          # dzcp_dlcomments
$db_array['events']           = 'events';              # dzcp_events
$db_array['f_access']         = 'f_access';            # dzcp_f_access
$db_array['f_abo']            = 'f_abo';               # dzcp_f_abo
$db_array['f_kats']           = 'forumkats';           # dzcp_forumkats
$db_array['f_posts']          = 'forumposts';          # dzcp_forumposts
$db_array['f_skats']          = 'forumsubkats';        # dzcp_forumsubkats
$db_array['f_threads']        = 'forumthreads';        # dzcp_forumthreads
$db_array['gallery']          = 'gallery';             # dzcp_gallery
$db_array['gb']               = 'gb';                  # dzcp_gb
$db_array['gb_comments']      = 'gb_comments';         # dzcp_gb_comments
$db_array['glossar']          = 'glossar';             # dzcp_glossar
$db_array['geo']              = 'geometry';            # dzcp_geometry
$db_array['ipban']            = 'ipban';               # dzcp_ipban
$db_array['links']            = 'links';               # dzcp_links
$db_array['linkus']           = 'linkus';              # dzcp_linkus
$db_array['msg']              = 'messages';            # dzcp_messages
$db_array['news']             = 'news';                # dzcp_news
$db_array['navi']             = 'navi';                # dzcp_navi
$db_array['navi_kats']        = 'navi_kats';           # dzcp_navi_kats
$db_array['newscomments']     = 'newscomments';        # dzcp_newscomments
$db_array['newskat']          = 'newskat';             # dzcp_newskat
$db_array['partners']         = 'partners';            # dzcp_partners
$db_array['permissions']      = 'permissions';         # dzcp_permissions
$db_array['pos']              = 'positions';           # dzcp_positions
$db_array['profile']          = 'profile';             # dzcp_profile
$db_array['rankings']         = 'rankings';            # dzcp_rankings
$db_array['rss']              = 'rss_config';          # dzcp_rss_config
$db_array['server']           = 'server';              # dzcp_server
$db_array['serverliste']      = 'serverliste';         # dzcp_serverliste
$db_array['settings']         = 'settings';            # dzcp_settings
$db_array['shout']            = 'shoutbox';            # dzcp_shoutbox
$db_array['sites']            = 'sites';               # dzcp_sites
$db_array['squads']           = 'squads';              # dzcp_squads
$db_array['squaduser']        = 'squaduser';           # dzcp_squaduser
$db_array['sponsoren']        = 'sponsoren';           # dzcp_sponsoren
$db_array['taktik']           = 'taktiken';            # dzcp_taktiken
$db_array['ts']               = 'teamspeak';           # dzcp_teamspeak
$db_array['users']            = 'users';               # dzcp_users
$db_array['usergallery']      = 'usergallery';         # dzcp_usergallery
$db_array['usergb']           = 'usergb';              # dzcp_usergb
$db_array['userpos']          = 'userposis';           # dzcp_userposis
$db_array['userstats']        = 'userstats';           # dzcp_userstats
$db_array['votes']            = 'votes';               # dzcp_votes
$db_array['vote_results']     = 'vote_results';        # dzcp_vote_results
$db_array['clicks_ips']       = 'clicks_ips';          # dzcp_clicks_ips
$db_array['cache']            = 'cache';               # dzcp_cache
