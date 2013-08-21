<?php
/**
 * <DZCP-Extended Edition>
 * @package: DZCP-Extended Edition
 * @author: DZCP Developer Team || Hammermaps.de Developer Team
 * @link: http://www.dzcp.de || http://www.hammermaps.de
 */

## Error Reporting ##
if(!defined('DEBUG_LOADER'))
    exit('<b>Die Debug-Console wurde nicht included oder wurde nicht geladen!<p>
    Bitte prüfen Sie ob jede index.php einen "include(basePath."/inc/debugger.php");" Eintrag hat.</b>');

error_reporting(E_ALL ^ E_NOTICE ^ E_DEPRECATED);

if(is_debug)
{
    ini_set('display_errors', 1);
    error_reporting(E_ALL);
    DebugConsole::initCon();

    if(show_debug_console)
        set_error_handler('dzcp_error_handler');
}

## AjaxJob ##
$ajaxJob = (!isset($ajaxJob) ? false : $ajaxJob);
$ajaxThumbgen = (!isset($ajaxThumbgen) ? false : $ajaxThumbgen);

## INCLUDES/REQUIRES ##
require_once(basePath.'/inc/secure.php');
require_once(basePath.'/inc/_version.php');
require_once(basePath.'/inc/database.php');

if(!$ajaxThumbgen)
{
    require_once(basePath."/inc/apic.php"); // Core
    require_once(basePath."/inc/apie.php"); // Events
    require_once(basePath."/inc/api.php"); // API
}

require_once(basePath.'/inc/kernel.php');

if(!$ajaxThumbgen)
    require_once(basePath."/inc/cookie.php");

require_once(basePath."/inc/cache.php");

if(!$ajaxThumbgen)
    require_once(basePath.'/inc/gameq.php');

// IP Prüfung
check_ip();
define('IS_DZCP', true);

## FUNCTIONS ##
//-> Legt die UserID desRootadmins fest
//-> (dieser darf bestimmte Dinge, den normale Admins nicht duerfen, z.B. andere Admins editieren)
$rootAdmin = 1;

## Settingstabelle auslesen ##
$settings = settings(array('prev','tmpdir','clanname','pagetitel'));
$prev = string::decode($settings['prev']).'_';
$sdir = string::decode($settings['tmpdir']);
$clanname = string::decode($settings["clanname"]);
$pagetitle = string::decode($settings["pagetitel"]);
unset($settings);

## Cookie initialisierung ##
if(!$ajaxThumbgen) { cookie::init($prev.'dzcp'); }

## Einzelne Definitionen ##
$subfolder = basename(dirname(dirname($_SERVER['PHP_SELF']).'../'));
$httphost = $_SERVER['HTTP_HOST'].(empty($subfolder) ? '' : '/'.$subfolder);
$pagetitle = (empty($pagetitle) ? $clanname : $pagetitle);

## Configtabelle auslesen ##
$config = config(array('allowhover','cache_engine'));
$allowHover = convert::ToInt($config['allowhover']);
$cache_engine = string::decode($config['cache_engine']);
unset($config);

//-> Cache
Cache::loadClasses();
Cache::setType($cache_engine);
Cache::init();

//-> GameQ
if(!$ajaxThumbgen)
    spl_autoload_register(array('GameQ', 'auto_load'));

// -> Prüft ob die IP gesperrt und gültig ist
function check_ip()
{
    global $ajaxThumbgen,$ajaxJob;

    if(!$ajaxThumbgen && !$ajaxJob && !isBot())
    {
        if(($ip=visitorIp()) == '0.0.0.0' || $ip == false || empty($ip))
            die('Deine IP ist ung&uuml;ltig!<p>Your IP is invalid!');

        //Banned IP
        $banned_ip_sql = db("SELECT id,typ FROM `".dba::get('ipban')."` WHERE `ip` = '".$ip."' AND `enable` = '1'");
        if(_rows($banned_ip_sql) >= 1)
        {
            $banned_ip = _fetch($banned_ip_sql);
            if($banned_ip['typ'] == '2' || $banned_ip['typ'] == '3')
                die('Deine IP ist gesperrt!<p>Your IP is banned!');
        }

        unset($banned_ip,$banned_ip_sql);
        sfs::check(); //SFS Update
        if(sfs::is_spammer())
            die('Deine IP-Adresse ist auf <a href="http://www.stopforumspam.com/" target="_blank">http://www.stopforumspam.com/</a> gesperrt, die IP wurde zu oft für Spam Angriffe auf Webseiten verwendet.<p>
                 Your IP address is known on <a href="http://www.stopforumspam.com/" target="_blank">http://www.stopforumspam.com/</a>, your IP has been used for spam attacks on websites.');
    }
}

//-> Auslesen der Cookies und automatisch anmelden
if(!$ajaxThumbgen && !isBot())
{
    if(cookie::get('id') != false && cookie::get('pkey') != false && !$ajaxJob && checkme() == "unlogged")
    {
        ## Debug Log ##
        DebugConsole::insert_initialize('inc/bbcode.php', 'Autologin');
        DebugConsole::insert_info('inc/bbcode.php', 'Autologin for ID: '.cookie::get('id').' & Permanent-Key: '.cookie::get('pkey'));

        ## User aus der Datenbank suchen ##
        $sql = db_stmt("SELECT id,user,nick,pwd,email,level,time,pkey,language FROM ".dba::get('users')." WHERE id = ? AND pkey = ? AND level != '0'",array('is', cookie::get('id'),cookie::get('pkey'))); //Use prepare sql statement
        if(_rows($sql))
        {
            $get = _fetch($sql);
            DebugConsole::insert_successful('inc/bbcode.php', 'Autologin for "'.$get['user'].' => ID: '.$get['id'].'" is successfully');

            ## Generiere neuen permanent-key ##
            $permanent_key = pass_hash(mkpwd(6,true),0);
            cookie::put('pkey', $permanent_key);
            cookie::save();
            DebugConsole::insert_info('inc/bbcode.php', 'Update Permanent-Key for ID: '.$get['id'].' to "'.$permanent_key.'"'); //Debug Log

            ## Schreibe Werte in die Server Sessions ##
            $_SESSION['id']         = $get['id'];
            $_SESSION['pwd']        = $get['pwd'];
            $_SESSION['lastvisit']  = $get['time'];
            $_SESSION['ip']         = visitorIp();

            if(string::decode($get['language']) != 'default')
                language::run_language(string::decode($get['language']));

            if(data($get['id'], "ip") != $_SESSION['ip'])
                $_SESSION['lastvisit'] = data($get['id'], "time");

            if(empty($_SESSION['lastvisit']))
                $_SESSION['lastvisit'] = data($get['id'], "time");

            ## Aktualisiere Datenbank ##
            db("UPDATE ".dba::get('users')." SET `online` = '1', `sessid` = '".session_id()."', `ip` = '".visitorIp()."', `pkey` = '".$permanent_key."' WHERE id = '".$get['id']."'");

            ## Aktualisiere die User-Statistik ##
            db("UPDATE ".dba::get('userstats')." SET `logins` = logins+1 WHERE user = '".$get['id']."'");
        }
        else
        {
            DebugConsole::insert_error('inc/bbcode.php', 'Autologin for ID: '.cookie::get('id').'" was not successful'); //Debug Log
            logout(); ## User Logout ##
        }
    }

    //-> Change Language
    if(isset($_GET['set_language']) && !empty($_GET['set_language']) && file_exists(basePath."/inc/lang/languages/".$_GET['set_language'].".php"))
    {
        language::run_language($_GET['set_language']);
        header("Location: ".$_SERVER['HTTP_REFERER']);
    }
    else
        language::run_language();

    $userid = userid(); //Used only for Mods/Addons
    $chkMe = checkme(); //Used only for Mods/Addons

    if(checkme() == "unlogged")
    {
        $_SESSION['id']        = '';
        $_SESSION['pwd']       = '';
        $_SESSION['ip']        = '';
        $_SESSION['lastvisit'] = '';
    }
}

//-> User Anmeldung
function login($username='',$pwd='',$permanent=false)
{
    if(empty($username) || empty($pwd))
        return false;

    ## User aus der Datenbank suchen ##
    $sql = db_stmt("SELECT id,user,pwd,pwd_encoder,time,language FROM ".dba::get('users')." WHERE user = ? AND level != '0'",array('s', $username)); //Use prepare sql statement
    if(_rows($sql))
    {
        $get = _fetch($sql);
        if($get['pwd'] != pass_hash($pwd,$get['pwd_encoder']))
        {
            ## Schreibe Adminlog ##
            wire_ipcheck("tryloginpwd(".$get['id'].")");
            return false;
        }

        ## Autologin ##
        if($permanent)
        {
            cookie::put('id', $get['id']);

            ## Generiere neuen permanent-key ##
            $permanent_key = pass_hash(mkpwd(6,true),0);
            cookie::put('pkey', $permanent_key);
        }
        else
            $permanent_key = '';

        ## Schreibe Werte in die Server Sessions ##
        $_SESSION['id']         = $get['id'];
        $_SESSION['pwd']        = $get['pwd'];
        $_SESSION['lastvisit']  = $get['time'];
        $_SESSION['ip']         = visitorIp();

        if(string::decode($get['language']) != 'default')
            language::run_language(string::decode($get['language']));

        ## Aktualisiere Datenbank ##
        db("UPDATE ".dba::get('users')." SET `online` = '1', `sessid` = '".session_id()."', `ip` = '".visitorIp()."', `pkey` = '".$permanent_key."' WHERE id = '".$get['id']."'");

        ## Aktualisiere die User-Statistik ##
        db("UPDATE ".dba::get('userstats')." SET `logins` = logins+1 WHERE user = '".$get['id']."'");

        ## Ereignis in den Adminlog schreiben ##
        wire_ipcheck("login(".$get['id'].")");
        return true;
    }

    return false;
}

//-> Aktualisierung des Online Status *preview
function update_user_status_preview()
{
    ## User aus der Datenbank suchen ##
    $sql = db("SELECT id,time FROM ".dba::get('users')." WHERE id = '".convert::ToInt($_SESSION['id'])."'
    AND sessid = '".session_id()."' AND ip = '".visitorIp()."' AND level != '0'");

    if(_rows($sql))
    {
        $get = _fetch($sql);

        ## Schreibe Werte in die Server Sessions ##
        $_SESSION['lastvisit']  = $get['time'];

        if(data($get['id'], "ip") != $_SESSION['ip'])
            $_SESSION['lastvisit'] = data($get['id'], "time");

        if(empty($_SESSION['lastvisit']))
            $_SESSION['lastvisit'] = data($get['id'], "time");

        ## Aktualisiere Datenbank ##
        db("UPDATE ".dba::get('users')." SET `online` = '1' WHERE id = '".$get['id']."'");
    }
}

//-> User Abmeldung
function logout()
{
    if(userid() != 0)
        db("UPDATE ".dba::get('users')." SET online = '0', sessid = '', pkey = '' WHERE id = '".userid()."'");

    cookie::clear();
    cookie::save();
    session_unset();
    session_destroy();
    session_regenerate_id();

    //-> Set DZCP-Install default variable after Logout
    if(!isset($_SESSION['installer']))
        $_SESSION['installer'] = false;

    if(!isset($_SESSION['db_install']))
        $_SESSION['db_install'] = false;
}

//-> Auslesen der UserID
function userid()
{
    if(empty($_SESSION['id']) || empty($_SESSION['pwd'])) return 0;
    $hash = md5("SELECT id FROM ".dba::get('users')." WHERE id = ".$_SESSION['id']." AND pwd = ".$_SESSION['pwd']);

    if(Cache::is_mem())
    {
        //MEM
        if(Cache::check($hash))
        {
            $sql = db_stmt("SELECT id FROM ".dba::get('users')." WHERE id = ? AND pwd = ?",array('is', $_SESSION['id'], $_SESSION['pwd'])); //Use prepare sql statement
            if(!_rows($sql)) return 0;

            $get = _fetch($sql);
            Cache::set($hash,$get['id'],2);
            return convert::ToInt($get['id']);
        }
        else
            return convert::ToInt(Cache::get($hash));
    }
    else
    {
        //RTBuffer
        if(RTBuffer::check($hash))
        {
            $sql = db_stmt("SELECT id FROM ".dba::get('users')." WHERE id = ? AND pwd = ?",array('is', $_SESSION['id'], $_SESSION['pwd'])); //Use prepare sql statement
            if(!_rows($sql)) return 0;

            $get = _fetch($sql);
            RTBuffer::set($hash,$get['id']);
            return convert::ToInt($get['id']);
        }
        else
            return convert::ToInt(RTBuffer::get($hash));
    }
}

//-> Prueft, ob User eingeloggt ist und wenn ja welches Level er besitzt
function checkme($userid_set=0)
{
    if(!$userid = ($userid_set != 0 ? convert::ToInt($userid_set) : userid()))
        return "unlogged";

    $qry = "SELECT level FROM ".dba::get('users')." WHERE id = ".convert::ToInt($userid)." AND pwd = '".$_SESSION['pwd']."' AND ip = '".$_SESSION['ip']."'";
    $hash = md5($qry);

    if(Cache::is_mem())
    {
        //MEM
        if(Cache::check($hash))
        {
            $qry = db($qry);
            if(_rows($qry))
            {
                $get = _fetch($qry);
                Cache::set($hash,$get['level'],2);
                return $get['level'];
            }
            else
                return "unlogged";
        }
        else
            return Cache::get($hash);
    }
    else
    {
        //RTBuffer
        if(RTBuffer::check($hash))
        {
            $qry = db($qry);
            if(_rows($qry))
            {
                $get = _fetch($qry);
                RTBuffer::set($hash,$get['level']);
                return $get['level'];
            }
            else
                return "unlogged";
        }
        else
            return RTBuffer::get($hash);
    }
}

//-> Templateswitch
if(!$ajaxThumbgen)
{
    $files = get_files(basePath.'/inc/_templates_/',true,false);
    if(cookie::get('tmpdir') != false)
        $tmpdir = (file_exists(basePath."/inc/_templates_/".cookie::get('tmpdir')."/index.html") ? cookie::get('tmpdir') : $files[0]);
    else
        $tmpdir = (file_exists(basePath."/inc/_templates_/".$sdir."/index.html") ? $sdir : $files[0]);

    $designpath = '../inc/_templates_/'.$tmpdir;

    //-> Languagefiles einlesen *Run
    //-> API & RSS call after Templateswitch & Language
    API_CORE::init();
    rss_feed::init();

    //-> BBOCDE
    bbcode::init();

    //-> Mail
    mailmgr::init();
}

//-> User bearbeiten, Level Menu
function get_level_dropdown_menu($selected_level=0,$userid=0,$list=false,$unset=false)
{
    $levels = array(
            'banned' => array('value' => '0', 'lang' => _admin_level_banned, 'only_admin' => false),
            'ruser' => array('value' => '1', 'lang' => _status_user, 'only_admin' => false),
            'trial' => array('value' => '2', 'lang' => _status_trial, 'only_admin' => false),
            'member' => array('value' => '3', 'lang' => _status_member, 'only_admin' => false),
            'admin' => array('value' => '4', 'lang' => _status_admin, 'only_admin' => true));

    if($unset != false && !is_array($unset)) unset($levels[$unset]);
    else if($unset != false && is_array($unset))
    { foreach ($unset as $unset_d) { unset($levels[$unset_d]); } }

    $option = '';
    foreach($levels as $level => $array)
    {
        if($list || !$array['only_admin'] || checkme(convert::ToInt($userid)) == 4)
            $option .= '<option value="'.$array['value'].'" '.($selected_level == $array['value'] ? 'selected="selected"' : '').'>'.$array['lang'].'</option>';
    }

    return $option;
}

//-> Userspezifiesche Dinge
if(userid() != 0 && $ajaxJob != true && !$ajaxThumbgen)
{ db("UPDATE ".dba::get('userstats')." SET `hits` = hits+1, `lastvisit` = '".convert::ToInt($_SESSION['lastvisit'])."'  WHERE user = ".userid()); }

function regexChars($txt)
{
    $search = array('"','\\','<','>','/','.',':','^','$','|','?','*','+','-','(',')','[',']','}','{','\r','\n');
    $replace = array('&quot;','\\\\','\<','\>','\/','\.','\:','\^','\$','\|','\?','\*','\+','\-','\(','\)','\[','\]','\}','\{','','');
    return str_replace($search, $replace, strip_tags($txt));
}

//-> Funktion um Bestimmte Textstellen zu markieren
function hl($text, $word)
{
    $ret = array('text' => $text, 'class' => 'class="commentsRight"');
    if(!empty($_GET['hl']) && $_SESSION['search_type'] == 'text')
    {
        if($_SESSION['search_con'] == 'or')
        {
            $words = explode(" ",$word);
            for($x=0;$x<count($words);$x++)
                $ret['text'] = preg_replace("#".$words[$x]."#i",'<span class="fontRed" title="'.$words[$x].'">'.$words[$x].'</span>',$text);
        }
        else
            $ret['text'] = preg_replace("#".$word."#i",'<span class="fontRed" title="'.$word.'">'.$word.'</span>',$text);

        $ret['class'] = (!preg_match("#<span class=\"fontRed\" title=\"(.*?)\">#", $ret['text']) ? 'class="commentsRight"' : 'class="highlightSearchTarget"');
    }

    return $ret;
}

//-> Emailadressen in Unicode umwandeln
function eMailAddr($email)
{
    $output = '';
    for($i=0;$i<strlen($email);$i++)
    { $output .= str_replace(substr($email,$i,1),"&#".ord(substr($email,$i,1)).";",substr($email,$i,1)); }
    return $output;
}

//-> Leerzeichen mit + ersetzen (w3c)
function convSpace($string)
{
    return str_replace(" ","+",$string);
}

//-> Funktion um Ausgaben zu kuerzen
function cut($str, $length = 0, $dots = true)
{
    if($length == 0) return '';

    $start = 0;
    $dots = ($dots == true && strlen(html_entity_decode($str)) > $length) ? '...' : '';

    if(strpos($str, '&') === false)
        return (($length === null) ? substr($str, $start) : substr($str, $start, $length)).$dots;

    $chars = preg_split('/(&[^;\s]+;)|/', $str, -1, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_OFFSET_CAPTURE);
    $html_length = count($chars);

    if(($start >= $html_length) || (isset($length) && ($length <= -$html_length)))
        return '';

    if($start >= 0)
        $real_start = $chars[$start][1];
    else
    {
        $start = max($start,-$html_length);
        $real_start = $chars[$html_length+$start][1];
    }

    if (!isset($length))
        return substr($str, $real_start).$dots;
    else if($length > 0)
        return (($start+$length >= $html_length) ? substr($str, $real_start) : substr($str, $real_start, $chars[max($start,0)+$length][1] - $real_start)).$dots;
    else
        return substr($str, $real_start, $chars[$html_length+$length][1] - $real_start).$dots;
}

function wrap($str, $width = 75, $break = "\n", $cut = true)
{
    return strtr(str_replace(htmlentities($break), $break, htmlentities(wordwrap(html_entity_decode($str), $width, $break, $cut), ENT_QUOTES)), array_flip(get_html_translation_table(HTML_SPECIALCHARS, ENT_COMPAT)));
}

//-> Counter updaten
function updateCounter()
{
    if(db("SELECT id FROM ".dba::get('c_ips')." WHERE datum+".counter_reload." <= ".time()." OR FROM_UNIXTIME(datum,'%d.%m.%Y') != '".date("d.m.Y")."'",true) >= 1)
        db("DELETE FROM ".dba::get('c_ips')." WHERE datum+".counter_reload." <= ".time()." OR FROM_UNIXTIME(datum,'%d.%m.%Y') != '".date("d.m.Y")."'");

    if(($count=db("SELECT id,visitors,today FROM ".dba::get('counter')." WHERE today = '".date("j.n.Y")."'",true)) >= 1)
    {
        $get = db("SELECT id,ip,datum FROM ".dba::get('c_ips')." WHERE ip = '".VisitorIP()."' AND FROM_UNIXTIME(datum,'%d.%m.%Y') = '".date("d.m.Y")."'",false,true);
        $sperrzeit = $get['datum']+counter_reload;
        if($sperrzeit <= time())
        {
            db("DELETE FROM ".dba::get('c_ips')." WHERE ip = '".VisitorIP()."'");
            db(($count ? "UPDATE ".dba::get('counter')." SET `visitors` = visitors+1 WHERE today = '".date("j.n.Y")."'" : "INSERT INTO ".dba::get('counter')." SET `visitors` = '1', `today` = '".date("j.n.Y")."'"));
            db("INSERT INTO ".dba::get('c_ips')." SET `ip` = '".VisitorIP()."', `datum`  = '".time()."'");
        }
    }
    else
    {
        db(($count ? "UPDATE ".dba::get('counter')." SET `visitors` = visitors+1 WHERE today = '".date("j.n.Y")."'" : "INSERT INTO ".dba::get('counter')." SET `visitors` = '1', `today` = '".date("j.n.Y")."'"));
        db("INSERT INTO ".dba::get('c_ips')." SET `ip` = '".VisitorIP()."', `datum`  = '".time()."'");
    }
}

//-> Updatet die Maximalen User die gleichzeitig online sind
function update_maxonline()
{
    $get = db("SELECT maxonline FROM ".dba::get('counter')." WHERE today = '".date("j.n.Y")."'",false,true);
    $count = cnt(dba::get('c_who'));

    if($get['maxonline'] <= $count)
        db("UPDATE ".dba::get('counter')." SET `maxonline` = '".convert::ToInt($count)."' WHERE today = '".date("j.n.Y")."'");
}

//-> Prueft, wieviele Besucher gerade online sind
function online_guests($where='')
{
    if(!isBot())
    {
        db("DELETE FROM ".dba::get('c_who')." WHERE online < ".time());
        db("REPLACE INTO ".dba::get('c_who')." SET `ip` = '".VisitorIP()."', `online` = '".convert::ToInt((time()+users_online))."', `whereami` = '".string::encode($where)."', `login` = '".(checkme() == 'unlogged' ? '0' : '1')."'");
        return cnt(dba::get('c_who'));
    }
}

//-> Gibt einen Teil eines nummerischen Arrays wieder
function limited_array($array=array(),$begin,$max)
{
    $array_exp = array();
    $range=range($begin=($begin-1), ($begin+$max-1));
    foreach($array as $key => $wert)
    {
        if(array_var_exists($key, $range))
            $array_exp[$key] = $wert;
    }

    return $array_exp;
}

//-> Prueft, ob ein User diverse Rechte besitzt
function permission($check)
{
    if(checkme() == 4) return true;
    $hash = md5(userid().'_'.$check);

    if(Cache::is_mem())
    {
        //MEM
        if(Cache::check($hash))
        {
            if(userid() && !empty($check))
            {
                // check rank permission
                $team = db("SELECT s1.".$check." FROM ".dba::get('permissions')." AS s1 LEFT JOIN ".dba::get('userpos')." AS s2 ON s1.pos = s2.posi
                WHERE s2.user = '".userid()."' AND s1.".$check." = '1' AND s2.posi != '0'",true);

                // check user permission
                $user = db("SELECT id FROM ".dba::get('permissions')." WHERE user = '".userid()."' AND ".$check." = '1'",true);

                if($user || $team)
                {
                    Cache::set($hash,true,2);
                    return true;
                }
            }
        }
        else
            return Cache::get($hash);
    }
    else
    {
        //RTBuffer
        if(RTBuffer::check($hash))
        {
            if(userid() && !empty($check))
            {
                // check rank permission
                $team = db("SELECT s1.".$check." FROM ".dba::get('permissions')." AS s1 LEFT JOIN ".dba::get('userpos')." AS s2 ON s1.pos = s2.posi
                WHERE s2.user = '".userid()."' AND s1.".$check." = '1' AND s2.posi != '0'",true);

                // check user permission
                $user = db("SELECT id FROM ".dba::get('permissions')." WHERE user = '".userid()."' AND ".$check." = '1'",true);

                if($user || $team)
                {
                    RTBuffer::set($hash,true);
                    return true;
                }
            }
        }
        else
            return RTBuffer::get($hash);
    }

    return false;
}

//-> Checkt, ob neue Nachrichten vorhanden sind
function check_msg()
{
    if(db("SELECT id FROM ".dba::get('msg')." WHERE an = '".$_SESSION['id']."' AND page = '0'",true))
    {
        db("UPDATE ".dba::get('msg')." SET `page` = '1' WHERE an = '".$_SESSION['id']."'");
        return show("user/new_msg", array("new" => _site_msg_new));
    }

    return '';
}

//-> Funktion um bei Clanwars Endergebnisse auszuwerten
function cw_result($punkte, $gpunkte)
{
    if($punkte > $gpunkte)
        return '<span class="CwWon">'.$punkte.':'.$gpunkte.'</span> <img src="../inc/images/won.gif" alt="" class="icon" />';
    else if($punkte < $gpunkte)
        return '<span class="CwLost">'.$punkte.':'.$gpunkte.'</span> <img src="../inc/images/lost.gif" alt="" class="icon" />';
    else
        return '<span class="CwDraw">'.$punkte.':'.$gpunkte.'</span> <img src="../inc/images/draw.gif" alt="" class="icon" />';
}

function cw_result_pic($punkte, $gpunkte)
{
    if($punkte > $gpunkte)
        return '<img src="../inc/images/won.gif" alt="" class="icon" />';
    else if($punkte < $gpunkte)
        return '<img src="../inc/images/lost.gif" alt="" class="icon" />';
    else
        return '<img src="../inc/images/draw.gif" alt="" class="icon" />';
}

//-> Funktion um bei Clanwars Endergebnisse auszuwerten ohne bild
function cw_result_nopic($punkte, $gpunkte)
{
    if($punkte > $gpunkte)
        return '<span class="CwWon">'.$punkte.':'.$gpunkte.'</span>';
    else if($punkte < $gpunkte)
        return '<span class="CwLost">'.$punkte.':'.$gpunkte.'</span>';
    else
        return '<span class="CwDraw">'.$punkte.':'.$gpunkte.'</span>';
}

//-> Funktion um bei Clanwars Endergebnisse auszuwerten ohne bild und ohne farbe
function cw_result_nopic_nocolor($punkte, $gpunkte)
{
    if($punkte > $gpunkte)
        return $punkte.':'.$gpunkte;
    else if($punkte < $gpunkte)
        return $punkte.':'.$gpunkte;
    else
        return $punkte.':'.$gpunkte;
}

//-> Flaggen ausgeben
function flag($code,$tinymce=false)
{
    global $picformat;

    if($tinymce)
    {
        foreach($picformat AS $end)
        {
            if(file_exists(basePath.'/inc/images/flaggen/'.$code.'.'.$end))
                return'<img src="../../../../inc/images/flaggen/'.$code.'.'.$end.'" alt="" style="vertical-align:middle" />';
        }

        return '<img src="../../../../inc/images/flaggen/nocountry.gif" alt="" style="vertical-align:middle" />';
    }
    else
    {
        if(empty($code))
            return '<img src="../inc/images/flaggen/nocountry.gif" alt="" class="icon" />';

        foreach($picformat AS $end)
        {
            if(file_exists(basePath.'/inc/images/flaggen/'.$code.'.'.$end))
                return '<img src="../inc/images/flaggen/'.$code.'.'.$end.'" alt="" class="icon" />';
        }

        return '<img src="../inc/images/flaggen/nocountry.gif" alt="" class="icon" />';
    }
}

function rawflag($code,$tinymce=false)
{
    global $picformat;

    if($tinymce)
    {
        foreach($picformat AS $end)
        {
            if(file_exists(basePath.'/inc/images/flaggen/'.$code.'.'.$end))
                return $code;
        }

        return 'nocountry';
    }
    else
    {
        if(empty($code))
            return '<img src=../inc/images/flaggen/nocountry.gif alt= class=icon />';

        foreach($picformat AS $end)
        {
            if(file_exists(basePath.'/inc/images/flaggen/'.$code.'.'.$end))
                return '<img src=../inc/images/flaggen/'.$code.'.'.$end.' alt= class=icon />';
        }

        return '<img src=../inc/images/flaggen/nocountry.gif alt= class=icon />';
    }
}

//-> Liste der Laender ausgeben
function show_countrys($i="")
{
    if(!empty($i))
        $options = preg_replace('#<option value="'.$i.'">(.*?)</option>#', '<option value="'.$i.'" selected="selected"> \\1</option>', _country_list);
    else
        $options = preg_replace('#<option value="de"> Deutschland</option>#', '<option value="de" selected="selected"> Deutschland</option>', _country_list);

    return '<select id="land" name="land" class="dropdown">'.$options.'</select>';
}

//-> Gameicon ausgeben
function squad($code)
{
    global $picformat;

    if(file_exists(basePath.'/inc/images/gameicons/custom/'.$code))
        return '<img src="../inc/images/gameicons/custom/'.$code.'" alt="" class="icon" />';

    return '<img src="../inc/images/gameicons/nogame.gif" alt="" class="icon" />';
}

//-> Funktion um bei DB-Eintraegen URLs einem http:// zuzuweisen
function links($hp)
{
    return (!empty($hp) ? links_check_url($hp) ? $hp : "http://".$hp : $hp);
}

//-> Funktion um URL Adressen zu erkennen
function links_check_url($string='')
{
    if(!empty($string))
    {
        if(stristr($string, 'http://') != false || stristr($string, 'https://') != false ||
           stristr($string, 'ftp://') != false || stristr($string, 'ftps://') != false ||
           stristr($string, 'http:\\') != false || stristr($string, 'https:\\') != false ||
           stristr($string, 'ftp:\\') != false || stristr($string, 'ftps:\\') != false)
        return true;
    }

    return false;
}

//-> Infomeldung ausgeben
function info($msg, $url, $timeout = 5)
{
    if(config('direct_refresh'))
        return header('Location: '.str_replace('&amp;', '&', $url));

    $u = parse_url($url); $parts = '';
    if(array_key_exists('query',$u) && !empty($u['query']))
    {
        $u['query'] = str_replace('&amp;', '&', $u['query']);
        foreach(explode('&', $u['query']) as $p)
        {
            $p = explode('=', $p);
            if(count($p) == 2)
            $parts .= '<input type="hidden" name="'.$p[0].'" value="'.$p[1].'" />'."\r\n";
        }
    }

    return show("errors/info", array("msg" => $msg,
                                     "url" => (array_key_exists('path',$u) && !empty($u['path']) ? $u['path'] : ''),
                                     "rawurl" => html_entity_decode($url),
                                     "parts" => $parts,
                                     "timeout" => $timeout,
                                     "info" => _info,
                                     "weiter" => _next,
                                     "backtopage" => _error_fwd));
}

//-> Errormmeldung ausgeben
function error($error = '', $back = '1', $show_back = true)
{
    return show("errors/".($show_back ? "error" : "error2"), array("error" => $error, "back" => $back));
}

//-> EMail wird auf korrekten Syntax überprüft
function check_email($email)
{
    return preg_match('#^[a-z0-9.!\#$%&\'*+-/=?^_`{|}~]+@([0-9.]+|([^\s\'"<>@,;]+\.+[a-z]{2,6}))$#si', $email);
}

//-> EMail wird auf Trashmail Server überprüft
function check_email_trash_mail($email)
{
    if(empty($email)) return false;
    if(!use_trash_mails)
    {
        if(Cache::check('trashmail_servers'))
        {
            if($stream = fileExists(trash_mail_url))
            {
                if(!empty($stream) && $stream)
                    Cache::set('trashmail_servers',$stream,172800); //48h
                else
                    return false;
            }
        }
        else
            $stream = Cache::get('trashmail_servers');

        $xml = new SimpleXMLElement($stream);
        $domains = array();
        foreach($xml->domainitem as $domain)
        { $domains[] = (string)$domain->domain; }
        $email_host = substr($email, strrpos($email, '@')+1, strlen($email));
        return in_array($email_host, $domains) ? true : false;
    }

    return false;
}

//-> Bilder verkleinern
function img_size($img,$width=0,$height=0)
{
    $width = ($width != 0 ? '&width='.$width : '');
    $height = ($height != 0 ? '&height='.$height : '');
    return "<a href=\"../inc/images/uploads/".$img."\" data-lightbox=\"l_".convert::ToInt($img)."\"><img src=\"../inc/ajax.php?loader=thumbgen&file=uploads/".$img.$width.$height."\" alt=\"\" /></a>";
}

//-> Blaetterfunktion
function nav($entrys, $perpage, $urlpart, $icon=true)
{
    global $page;

    if(!$perpage)
        return "&#xAB; <span class=\"fontSites\">0</span> &#xBB;";

    if($icon)
        $icon = '<img src="../inc/images/multipage.png" alt="" class="icon" /> <span class="fontSites">'._seiten.'</span>';

    if($entrys <= $perpage)
        return '';

    if(!$page || $page < 1)
        $page = 2;

    $pages = ceil($entrys/$perpage);

    if(($page-5) <= 2 && $page != 1)
        $first = '<a class="sites" href="'.$urlpart.'&amp;page='.($page-1).'">&#xAB;</a><span class="fontSitesMisc">&#xA0;</span> <a  class="sites" href="'.$urlpart.'&amp;page=1">1</a> ';
    else if($page > 1)
        $first = '<a class="sites" href="'.$urlpart.'&amp;page='.($page-1).'">&#xAB;</a><span class="fontSitesMisc">&#xA0;</span> <a class="sites" href="'.$urlpart.'&amp;page=1">1</a>...';
    else
        $first = '<span class="fontSitesMisc">&#xAB;&#xA0;</span>';

    if($page == $pages)
        $last = '<span class="fontSites">'.$pages.'</span><span class="fontSitesMisc">&#xA0;&#xBB;<span>';
    else if(($page+5) >= $pages)
        $last = '<a class="sites" href="'.$urlpart.'&amp;page='.($pages).'">'.$pages.'</a>&#xA0;<a class="sites" href="'.$urlpart.'&amp;page='.($page+1).'">&#xBB;</a>';
    else
        $last = '...<a class="sites" href="'.$urlpart.'&amp;page='.($pages).'">'.$pages.'</a>&#xA0;<a class="sites" href="'.$urlpart.'&amp;page='.($page+1).'">&#xBB;</a>';

    $result = '';
    for($i = $page;$i<=($page+5) && $i<=($pages-1);$i++)
    {
        if($i == $page)
            $result .= '<span class="fontWichtig">'.$i.'</span><span class="fontSitesMisc">&#xA0;</span>';
        else
            $result .= '<a class="sites" href="'.$urlpart.'&amp;page='.$i.'">'.$i.'</a><span class="fontSitesMisc">&#xA0;</span>';
    }

    $resultm = '';
    for($i=($page-5);$i<=($page-1);$i++)
    {
        if($i >= 2)
            $resultm .= '<a class="sites" href="'.$urlpart.'&amp;page='.$i.'">'.$i.'</a> ';
    }

    return $icon.' '.$first.$resultm.$result.$last;
}

//-> Nickausgabe mit Profillink oder Emaillink (reg/nicht reg)
function autor($uid="", $class="", $nick="", $email="", $cut="",$add="")
{
    if(empty($uid)) $uid = userid();

    if($uid != 0)
    {
        $qry = db("SELECT nick,country FROM ".dba::get('users')." WHERE id = '".convert::ToInt($uid)."'");
        if(_rows($qry))
        {
            $get = _fetch($qry);
            $nickname = (!empty($cut)) ? cut(string::decode($get['nick']), $cut) : string::decode($get['nick']);
            return show(_user_link, array("id" => $uid, "country" => flag($get['country']), "class" => $class, "get" => $add, "nick" => $nickname));
        }
    }

    $nickname = (!empty($cut)) ? cut(string::decode($nick), $cut) : string::decode($nick);
    return show(_user_link_noreg, array("nick" => $nickname, "class" => $class, "email" => eMailAddr($email)));
}

function cleanautor($uid, $class="", $nick="", $email="", $cut="")
{
    if(!$uid = ($uid != 0 ? convert::ToInt($uid) : userid()))
        return "";

    $qry = "SELECT nick,country FROM ".dba::get('users')." WHERE id = '".convert::ToInt($uid)."'";
    $hash = md5($qry);

    if(Cache::is_mem())
    {
        //MEM
        if(Cache::check($hash))
        {
            $qry = db($qry);
            if(_rows($qry))
            {
                $get = _fetch($qry);
                Cache::set($hash,$get,2);
            }
            else
                return show(_user_link_noreg, array("nick" => string::decode($nick), "class" => $class, "email" => eMailAddr($email)));
        }
        else
            $get = Cache::get($hash);
    }
    else
    {
        //RTBuffer
        if(RTBuffer::check($hash))
        {
            $qry = db($qry);
            if(_rows($qry))
            {
                $get = _fetch($qry);
                RTBuffer::set($hash,$get);
            }
            else
                return show(_user_link_noreg, array("nick" => string::decode($nick), "class" => $class, "email" => eMailAddr($email)));
        }
        else
            $get = RTBuffer::get($hash);
    }

    return show(_user_link_preview, array("id" => $uid, "country" => flag($get['country']), "class" => $class, "nick" => string::decode($get['nick'])));
}

function rawautor($uid)
{
    if(!$uid = ($uid != 0 ? convert::ToInt($uid) : userid()))
        return "";

    $qry = "SELECT nick,country FROM ".dba::get('users')." WHERE id = '".convert::ToInt($uid)."'";
    $hash = md5($qry);

    if(Cache::is_mem())
    {
        //MEM
        if(Cache::check($hash))
        {
            $qry = db($qry);
            if(_rows($qry))
            {
                $get = _fetch($qry);
                Cache::set($hash,convert::objectToArray($get),2);
            }
            else
                return rawflag('')." ".jsconvert(string::decode($uid));
        }
        else
            $get = Cache::get($hash);
    }
    else
    {
        //RTBuffer
        if(RTBuffer::check($hash))
        {
            $qry = db($qry);
            if(_rows($qry))
            {
                $get = _fetch($qry);
                RTBuffer::set($hash,$get);
            }
            else
                return rawflag('')." ".jsconvert(string::decode($uid));
        }
        else
            $get = convert::objectToArray(RTBuffer::get($hash));
    }

    return rawflag($get['country'])." ".jsconvert(string::decode($get['nick']));
}

//-> Nickausgabe ohne Profillink oder Emaillink für das ForenAbo
function fabo_autor($uid,$show=_user_link_fabo)
{
    if(!$uid = ($uid != 0 ? convert::ToInt($uid) : userid()))
        return "";

    $qry = "SELECT nick FROM ".dba::get('users')." WHERE id = '".$uid."'";
    $hash = md5($qry);

    if(Cache::is_mem())
    {
        //MEM
        if(Cache::check($hash))
        {
            $qry = db($qry);
            if(_rows($qry))
            {
                $get = _fetch($qry);
                Cache::set($hash,convert::objectToArray($get),2);
            }
            else
                return '';
        }
        else
            $get = Cache::get($hash);
    }
    else
    {
        //RTBuffer
        if(RTBuffer::check($hash))
        {
            $qry = db($qry);
            if(_rows($qry))
            {
                $get = _fetch($qry);
                RTBuffer::set($hash,$get);
            }
            else
                return '';
        }
        else
            $get = convert::objectToArray(RTBuffer::get($hash));
    }

    return show($show, array("nick" => string::decode($get['nick'])));
}

//-> Rechte abfragen
function jsconvert($txt)
{
    return str_replace(array("'", "&#039;", "\"", "\r", "\n"), array("\'", "\'", "&quot;", '', ''), $txt);
}

//-> interner Forencheck
function fintern($id)
{
    $fget = db("SELECT s1.intern,s2.id FROM ".dba::get('f_kats')." AS s1 LEFT JOIN ".dba::get('f_skats')." AS s2 ON s2.sid = s1.id WHERE s2.id = '".convert::ToInt($id)."'",false,true);

    if(checkme() == "unlogged")
        return empty($fget['intern']) ? true : false;
    else
    {
        $team = db("SELECT * FROM ".dba::get('f_access')." AS s1 LEFT JOIN ".dba::get('userpos')." AS s2 ON s1.pos = s2.posi WHERE s2.user = '".userid()."' AND s2.posi != '0' AND s1.forum = '".convert::ToInt($id)."'",true);
        $user = db("SELECT * FROM ".dba::get('f_access')." WHERE `user` = '".userid()."' AND `forum` = '".convert::ToInt($id)."'",true);

        if($user || $team || checkme() == 4 || !$fget['intern'])
            return true;
    }

    return false;
}

/**
 * Funktion um User-Daten aus der Datenbank zu ermitteln
 * Input: UserID , String/Array
 *
 * @return mixed/array
 **/
function data($tid, $what)
{
    if(empty($tid) || empty($what))
        return false;

    if(is_array($what))
    {
        $sql='';
        foreach($what as $qy)
        { $sql .= $qy.", "; }
        $sql = substr($sql, 0, -2);
        return db("SELECT ".$sql." FROM `".dba::get('users')."` WHERE id = '".convert::ToInt($tid)."'",false,true);
    }
    else
    {
        $get = db("SELECT ".$what." FROM `".dba::get('users')."` WHERE id = '".convert::ToInt($tid)."'",false,true);
        return $get[$what];
    }
}

/**
 * Funktion um User-Statistiken aus der Datenbank zu ermitteln
 * Input: UserID , String/Array
 *
 * @return mixed/array
 **/
function userstats($tid, $what)
{
    if(is_array($what))
    {
        $sql='';
        foreach($what as $qy)
        { $sql .= $qy.", "; }
        $sql = substr($sql, 0, -2);
        return db("SELECT ".$sql." FROM `".dba::get('userstats')."` WHERE user = '".convert::ToInt($tid)."'",false,true);
    }
    else
    {
        $get = db("SELECT ".$what." FROM `".dba::get('userstats')."` WHERE user = '".convert::ToInt($tid)."'",false,true);
        return convert::ToString($get[$what]);
    }
}

function check_msg_email()
{
    global $clanname,$httphost;

    $qry = "SELECT s1.an,s1.page,s1.titel,s1.sendmail,s1.id AS mid,s2.id,s2.nick,s2.email,s2.pnmail FROM ".dba::get('msg')." AS s1 LEFT JOIN ".dba::get('users')." AS s2 ON s2.id = s1.an WHERE page = 0 AND sendmail = 0";
    $hash = md5($qry);

    if(!Cache::is_mem() || Cache::check($hash))
    {
        $qry = db($qry);
        while($get = _fetch($qry))
        {
            if($get['pnmail'])
            {
                db("UPDATE ".dba::get('msg')." SET `sendmail` = '1' WHERE id = '".$get['mid']."'");
                $subj = show(string::decode(settings('eml_pn_subj')), array("domain" => $httphost));
                $message = show(string::decode(settings('eml_pn')), array("nick" => string::decode($get['nick']), "domain" => $httphost, "titel" => $get['titel'], "clan" => $clanname));
                mailmgr::AddContent($subj,$message);
                mailmgr::AddAddress(string::decode($get['email']));
            }
        }

        if(Cache::is_mem()) Cache::set($hash,'',check_msg_email);
    }
}

if(!$ajaxThumbgen && !$ajaxJob)
{ check_msg_email(); }

//-> DropDown Mens Date/Time
function dropdown($what, $wert, $age = 0)
{
    $return='';
    if($what == "day")
    {
        if($age == 1)
            $return .='<option value="" class="dropdownKat">'._day.'</option>'."\n";

        for($i=1; $i<32; $i++)
        {
            if($i==$wert)
                $return .= "<option value=\"".$i."\" selected=\"selected\">".$i."</option>\n";
            else
                $return .= "<option value=\"".$i."\">".$i."</option>\n";
        }
    }
    else if($what == "month")
    {
        if($age == 1)
            $return .='<option value="" class="dropdownKat">'._month.'</option>'."\n";

        for($i=1; $i<13; $i++)
        {
            if($i==$wert)
                $return .= "<option value=\"".$i."\" selected=\"selected\">".$i."</option>\n";
            else
                $return .= "<option value=\"".$i."\">".$i."</option>\n";
        }
    }
    else if($what == "year")
    {
        if($age == 1)
        {
            $return .='<option value="" class="dropdownKat">'._year.'</option>'."\n";
            for($i=date("Y",time())-80; $i<date("Y",time())-10; $i++)
            {
                if($i==$wert)
                    $return .= "<option value=\"".$i."\" selected=\"selected\">".$i."</option>\n";
                else
                    $return .= "<option value=\"".$i."\">".$i."</option>\n";
            }
        }
        else
        {
            for($i=date("Y",time())-3; $i<date("Y",time())+3; $i++)
            {
                if($i==$wert)
                    $return .= "<option value=\"".$i."\" selected=\"selected\">".$i."</option>\n";
                else
                    $return .= "<option value=\"".$i."\">".$i."</option>\n";
            }
        }
    }
    else if($what == "hour")
    {
        for($i=0; $i<24; $i++)
        {
            if($i==$wert)
                $return .= "<option value=\"".$i."\" selected=\"selected\">".$i."</option>\n";
            else
                $return .= "<option value=\"".$i."\">".$i."</option>\n";
        }
    }
    else if($what == "minute")
    {
        for($i="00"; $i<60; $i++)
        {
            if($i == 0 || $i == 15 || $i == 30 || $i == 45)
            {
                if($i==$wert)
                    $return .= "<option value=\"".$i."\" selected=\"selected\">".$i."</option>\n";
                else
                    $return .= "<option value=\"".$i."\">".$i."</option>\n";
            }
        }
    }

    return $return;
}

//Umfrageantworten selektieren
function voteanswer($what, $vid)
{
    $get = db("SELECT sel FROM ".dba::get('vote_results')." WHERE what = '".string::encode($what)."' AND vid = '".$vid."'",false,true);
    return $get['sel'];
}

//-> Geburtstag errechnen
function getAge($bday)
{
    if(!empty($bday) || $bday == '..')
    {
        list($tiday,$iMonth,$iYear) = explode(".",$bday);
        $iCurrentDay = date('j');
        $iCurrentMonth = date('n');
        $iCurrentYear = date('Y');
        return (($iCurrentMonth>$iMonth) || (($iCurrentMonth==$iMonth) && ($iCurrentDay>=$tiday))) ? $iCurrentYear - $iYear : $iCurrentYear - ($iYear + 1);
    }

    return '-';
}

//-> Ausgabe des Userlevels
function getuserlvl($userid=0)
{
    switch (data(convert::ToInt($userid),"level"))
    {
        case 1: return _status_user; break;
        case 2: return _status_trial; break;
        case 3: return _status_member; break;
        case 4: return _status_admin; break;
    }

    return '';
}

//-> Ausgabe der Position des einzelnen Members
function getrank($tid, $squad=false, $profil=false)
{
    if($squad)
    {
       if($profil)
            $qry = db("SELECT posi,squad FROM ".dba::get('userpos')." AS s1 LEFT JOIN ".dba::get('squads')." AS s2 ON s1.squad = s2.id WHERE s1.user = '".convert::ToInt($tid)."' AND s1.squad = '".convert::ToInt($squad)."' AND s1.posi != '0'");
        else
            $qry = db("SELECT posi,squad FROM ".dba::get('userpos')." WHERE user = '".convert::ToInt($tid)."' AND squad = '".convert::ToInt($squad)."' AND posi != '0'");

        if(_rows($qry))
        {
            while($get = _fetch($qry))
            {
                $gets = db("SELECT name FROM ".dba::get('squads')." WHERE id = '".convert::ToInt($get['squad'])."'",false,true);
                $getp = db("SELECT position FROM ".dba::get('pos')." WHERE id = '".convert::ToInt($get['posi'])."'",false,true);
                return(!empty($gets['name']) ? '<b>'.$gets['name'].':</b> '.$getp['position'] : $getp['position']);
            }
        }
        else
        {
            $get = db("SELECT level,actkey FROM ".dba::get('users')." WHERE id = '".convert::ToInt($tid)."'",false,true);
            switch ($get['level'])
            {
                case 0: return !empty($get['actkey']) ? _profil_admin_locked : _status_unregged; break;
                case 1: return _status_user; break;
                case 2: return _status_trial; break;
                case 3: return _status_member; break;
                case 4: return _status_admin; break;
                case 9: return _status_banned; break;
                default: return _gast; break;
            }
        }
    }
    else
    {
        $qry = db("SELECT s1.*,s2.position FROM ".dba::get('userpos')." AS s1 LEFT JOIN ".dba::get('pos')." AS s2 ON s1.posi = s2.id WHERE s1.user = '".convert::ToInt($tid)."' AND s1.posi != '0' ORDER BY s2.pid ASC");
        if(_rows($qry))
        {
            $get = _fetch($qry);
            return $get['position'];
        }
        else
        {
            $get = db("SELECT level,actkey FROM ".dba::get('users')." WHERE id = '".convert::ToInt($tid)."'",false,true);
            switch ($get['level'])
            {
                case 0: return !empty($get['actkey']) ? _profil_admin_locked : _status_unregged; break;
                case 1: return _status_user; break;
                case 2: return _status_trial; break;
                case 3: return _status_member; break;
                case 4: return _status_admin; break;
                case 9: return _status_banned; break;
                default: return _gast; break;
            }
        }
    }
}

//-> Session fuer den letzten Besuch setzen
function set_lastvisit()
{
    if(userid() != 0)
    {
        if(!db("SELECT id FROM ".dba::get('users')." WHERE id = ".userid()." AND time+'".users_online."'>'".time()."'",true))
        {
            $time = data(userid(), "time");
            $_SESSION['lastvisit'] = $time;
        }
    }
}

//-> Checkt welcher User gerade noch online ist
function onlinecheck($tid)
{
    if(db("SELECT id FROM ".dba::get('users')." WHERE id = '".convert::ToInt($tid)."' AND time+'".users_online."'>'".time()."' AND online = 1",true))
        return '<img src="../inc/images/online.png" alt="" class="icon" />';
    else if(db("SELECT id FROM ".dba::get('users')." WHERE id = '".convert::ToInt($tid)."' AND `actkey` IS NOT NULL AND level = 0",true))
        return '<img src="../inc/images/static.png" alt="" class="icon" />';
    else
        return '<img src="../inc/images/offline.png" alt="" class="icon" />';
}

//-> Setzt bei einem Tag >10 eine 0 vorran (Kalender)
function cal($i)
{
    if(!preg_match("=10|20|30=Uis",$i))
        $i = preg_replace("=0=", "", $i);

    return ($i < 10 ? "0".$i : $i);
}

//-> Konvertiert Platzhalter in die jeweiligen bersetzungen
function navi_name($name)
{
    $name = trim($name);
    if(preg_match("#^_(.*?)_$#Uis",$name))
    {
        $name = preg_replace("#_(.*?)_#Uis", "$1", $name);
        return language::display("_".$name);
    } else return $name;

    return $name;
}

// Userpic ausgeben
function userpic($userid, $width=170,$height=210)
{
    global $picformat;
    foreach($picformat as $endung)
    {
        if(file_exists(basePath."/inc/images/uploads/userpics/".convert::ToInt($userid).".".$endung))
            return show(_userpic_link, array("id" => convert::ToInt($userid), "endung" => $endung, "width" => $width, "height" => $height));
    }

    return show(_no_userpic, array("width" => $width, "height" => $height));
}

// Useravatar ausgeben
function useravatar($userid, $width=100,$height=100)
{
    global $picformat;
    foreach($picformat as $endung)
    {
        if(file_exists(basePath."/inc/images/uploads/useravatare/".convert::ToInt($userid).".".$endung))
            return show(_userava_link, array("id" => convert::ToInt($userid), "endung" => $endung, "width" => $width, "height" => $height));
    }

    return show(_no_userava, array("width" => $width, "height" => $height));
}

// Userpic feur Hoverinformationen ausgeben
function hoveruserpic($userid, $width=170,$height=210)
{
    global $picformat; $pic = '';
    foreach($picformat as $endung)
    {
        if(file_exists(basePath."/inc/images/uploads/userpics/".convert::ToInt($userid).".".$endung))
        { $pic = "../inc/images/uploads/userpics/".convert::ToInt($userid).".".$endung."', '".$width."', '".$height.""; break; }
    }

    return(empty($pic) ? "../inc/images/nopic.gif', '".$width."', '".$height."" : $pic);
}

// Adminberechtigungen ueberpruefen
function admin_perms($userid)
{
    global $rootAdmin;

    if(empty($userid) || !$userid)
        return false;

    if(checkme() == "unlogged" || checkme() == "banned")
        return false;

    // no need for these admin areas
    $e = array('gb', 'shoutbox', 'editusers', 'votes', 'contact', 'joinus', 'intnews', 'forum', 'gs_showpw');

    // check user permission
    if(db("SELECT id FROM ".dba::get('permissions')." WHERE user = '".convert::ToInt($userid)."'",true))
    {
        $admin_settings = array();
        $dirs = get_files(basePath.'/admin/menu/',true);
        foreach($dirs AS $dir)
        {
            ## XML Auslesen ##
            $XMLTag = 'admin_'.$dir;
            if(xml::openXMLfile($XMLTag,"admin/menu/".$dir."/config.xml"))
                $admin_settings[((string)xml::getXMLvalue($XMLTag, 'Rights'))] = array('Only_Admin' => xml::bool(xml::getXMLvalue($XMLTag, 'Only_Admin')), 'Only_Root' => xml::bool(xml::getXMLvalue($XMLTag, 'Only_Root')));
        }

        $dirs_addon = API_CORE::call_additional_adminmenu_xml();
        foreach($dirs_addon AS $dir_addon)
        {
            ## XML Auslesen ##
            $XMLTag = 'admin_'.$dir_addon;
            if(xml::openXMLfile($XMLTag,$dir_addon))
                $admin_settings[((string)xml::getXMLvalue($XMLTag, 'Rights'))] = array('Only_Admin' => xml::bool(xml::getXMLvalue($XMLTag, 'Only_Admin')), 'Only_Root' => xml::bool(xml::getXMLvalue($XMLTag, 'Only_Root')));
        }

        $check = db("SELECT * FROM ".dba::get('permissions')." WHERE user = '".convert::ToInt($userid)."'",false,true);
        foreach($check AS $v => $k)
        {
            if($v != 'id' && $v != 'user' && $v != 'pos' && !in_array($v, $e))
            {
                if($k == 1)
                {
                    $admin_config = (array_key_exists($v, $admin_settings) ? $admin_settings[$v] : array('Only_Root' => false, 'Only_Admin' => false));
                    if(!$admin_config['Only_Root'] && !$admin_config['Only_Admin'])
                        return true;
                    else if($admin_config['Only_Root'] && convert::ToInt($userid) == convert::ToInt($rootAdmin))
                        return true;
                    else if($admin_config['Only_Admin'] && checkme() == 4)
                        return true;
                }
            }
        }
    }

    // check rank permission
    if(db("SELECT id FROM `".dba::get('userpos')."` WHERE `user` = ".convert::ToInt($userid)." LIMIT 1",true))
    {
        $qry = db("SELECT s1.* FROM ".dba::get('permissions')." AS s1 LEFT JOIN ".dba::get('userpos')." AS s2 ON s1.pos = s2.posi WHERE s2.user = '".convert::ToInt($userid)."' AND s2.posi != '0'");
        while($r = _fetch($qry))
        {
            foreach($r AS $v => $k)
            {
                if($v != 'id' && $v != 'user' && $v != 'pos' && !in_array($v, $e))
                {
                    if($k == 1)
                        return true;
                }
            }
        }
    }

    return (checkme() == 4) ? true : false;
}

//-> Rechte abfragen
function getPermissions($checkID = 0, $pos = 0)
{
    if(!empty($checkID))
    {
        $check = empty($pos) ? 'user' : 'pos'; $checked = array();
        $qry = db("SELECT * FROM ".dba::get('permissions')." WHERE `".$check."` = '".convert::ToInt($checkID)."'");

        if(_rows($qry))
        {
            foreach(_fetch($qry) AS $k => $v)
                $checked[$k] = $v;
        }
    }

    $permission = array();
    $qry = db("SHOW COLUMNS FROM ".dba::get('permissions')."");
    while($get = _fetch($qry))
    {
        if($get['Field'] != 'id' && $get['Field'] != 'user' && $get['Field'] != 'pos' && $get['Field'] != 'intforum')
        {
            $chk = empty($checked[$get['Field']]) ? '' : ' checked="checked"';
            $permission[] = '<input type="checkbox" class="checkbox" id="'.$get['Field'].'" name="perm[p_'.$get['Field'].']" value="1"'.$chk.' /><label for="'.$get['Field'].'"> '.language::display("_perm_".$get['Field']).'</label> ';
        }
    }

    natcasesort($permission); $p = ''; $break = 1;
    foreach($permission AS $perm)
    {
        $br = ($break % 2) ? '<br />' : ''; $break++;
        $p .= $perm.$br;
    }

    return $p;
}

//-> interne Foren-Rechte abfragen
function getBoardPermissions($checkID = 0, $pos = 0)
{
    global $dir;
    $qry = db("SELECT id,name FROM ".dba::get('f_kats')." WHERE intern = '1' ORDER BY `kid` ASC"); $i_forum = '';
    while($get = _fetch($qry))
    {
        unset($kats, $fkats, $break);
        $kats = (empty($katbreak) ? '' : '<div style="clear:both">&nbsp;</div>').'<table class="hperc" cellspacing="1"><tr><td class="contentMainTop"><b>'.string::decode($get["name"]).'</b></td></tr></table>';

        $katbreak = 1; $break = 1; $fkats = '';
        $qry2 = db("SELECT kattopic,id FROM ".dba::get('f_skats')." WHERE `sid` = '".$get['id']."' ORDER BY `kattopic` ASC");
        while($get2 = _fetch($qry2))
        {
            $br = ($break % 2) ? '<br />' : ''; $break++;
            $chk =  db("SELECT * FROM ".dba::get('f_access')." WHERE `".(empty($pos) ? 'user' : 'pos')."` = '".convert::ToInt($checkID)."' AND ".(empty($pos) ? 'user' : 'pos')." != '0' AND `forum` = '".$get2['id']."'",true) ? ' checked="checked"' : '';
            $fkats .= '<input type="checkbox" class="checkbox" id="board_'.$get2['id'].'" name="board['.$get2['id'].']" value="'.$get2['id'].'"'.$chk.' /><label for="board_'.$get2['id'].'"> '.string::decode($get2['kattopic']).'</label> '.$br;
        }

        $i_forum .= $kats.$fkats;
    }

    return $i_forum;
}

//-> Startseite für User abrufen
function startpage()
{
    if(cookie::get('id') != false && cookie::get('pkey') != false && !($startpageID = data(userid(), 'startpage')))
        return 'user/?action=userlobby';

    $sql = db("SELECT url,level FROM `".dba::get('startpage')."` WHERE `id` = ".$startpageID." LIMIT 1");

    if(!_rows($sql))
        return checkme() >= 1 ? 'user/?action=userlobby' : 'news/';

    $get = _fetch($sql);
    return $get['level'] <= checkme() ? string::decode($get['url']) : 'user/?action=userlobby';
}

//-> Show Xfire Status
function xfire($username='')
{
    if(empty($username))
        return '-';

    switch(xfire_skin)
    {
        case 'shadow': $skin = 'sh'; break;
        case 'kampf': $skin = 'co'; break;
        case 'scifi': $skin = 'sf'; break;
        case 'fantasy': $skin = 'os'; break;
        case 'wow': $skin = 'wow'; break;
        default: $skin = 'bg'; break;
    }

    if(xfire_preloader)
    {
        if(Cache::check_binary('xfire_'.$username))
        {
            if(!$img_stream = fileExists('http://de.miniprofile.xfire.com/bg/'.$skin.'/type/0/'.$username.'.png'))
                return show(_xfireicon,array('username' => $username, 'img' => 'http://de.miniprofile.xfire.com/bg/'.$skin.'/type/0/'.$username.'.png'));

            Cache::set_binary('xfire_'.$username, $img_stream, '', xfire_refresh);
            return show(_xfireicon,array('username' => $username, 'img' => 'data:image/png;base64,'.base64_encode($img_stream)));
        }
        else
            return show(_xfireicon,array('username' => $username, 'img' => 'data:image/png;base64,'.base64_encode(Cache::get_binary('xfire_'.$username))));
    }

    return show(_xfireicon,array('username' => $username, 'img' => 'http://de.miniprofile.xfire.com/bg/'.$skin.'/type/0/'.$username.'.png'));
}

// Prüft ob die Seite in der Navigation als Intern eingestellt ist.
function check_internal_url()
{
    if(checkme() != "unlogged")
        return false;

    $url = '..'.str_ireplace('index.php','',str_ireplace(str_ireplace('\\','/',basePath),'',$_SERVER['SCRIPT_FILENAME']));
    $url_query = $url.'?'.$_SERVER['QUERY_STRING'];
    $sql_url_query = db("SELECT internal FROM `".dba::get('navi')."` WHERE `url` LIKE '".$url_query."' LIMIT 1");
    $sql_url = db("SELECT internal FROM `".dba::get('navi')."` WHERE `url` LIKE '".$url."' LIMIT 1");
    $sql_found_row = false;
    if(_rows($sql_url_query))
    {
        $sql_found_row = true;
        $get = _fetch($sql_url_query);
        if($get['internal'])
            return true;
    }
    else if(_rows($sql_url) && !$sql_found_row)
    {
        $get = _fetch($sql_url);
        if($get['internal'])
            return true;
    }
    else
        return false;
}

// Prüft die ausgelagerten Seiten für Zugriff
function include_action($page_dir='',$default='default')
{
    $do = convert::ToString((isset($_GET['do']) && !empty($_GET['do']) ? htmlentities(strtolower($_GET['do'])) : (isset($_POST['do']) && !empty($_POST['do']) ? htmlentities(strtolower($_POST['do'])) : '')));
    $page = convert::ToInt((isset($_GET['page']) ? $_GET['page'] : (isset($_POST['page']) ? $_POST['page'] : 1)));
    $action = convert::ToString(isset($_GET['action']) && !empty($_GET['action']) ? htmlentities(strtolower($_GET['action'])) : (isset($_POST['action']) && !empty($_POST['action']) ? htmlentities(strtolower($_POST['action'])) : strtolower($default)));
    if(check_internal_url())
        return array('include' => false, 'page' => $page, 'do' => $do, 'msg' => error(_error_have_to_be_logged));
    else if(($modul_file=API_CORE::load_additional_page($page_dir,$action)))
        return array('include' => true, 'page' => $page, 'do' => $do, 'file' => $modul_file);
    else if(file_exists(($modul_file=basePath.'/'.$page_dir.'/pages/action_'.$action.'.php')))
        return array('include' => true, 'page' => $page, 'do' => $do, 'file' => $modul_file);
    else
        return array('include' => false, 'page' => $page, 'do' => $do, 'msg' => show(_include_action_error,array('file' => $page_dir.'/pages/action_'.$action.'.php')));
}

//Preuft ob alle clicks nur einmal gezahlt werden *gast/user
function count_clicks($side_tag='',$clickedID=0,$update=true)
{
    $qry = db("SELECT id,side FROM ".dba::get('clicks_ips')." WHERE uid = 0 AND time <= ".time());
    if(_rows($qry)) while($get = _fetch($qry)) { if($get['side'] != 'vote') db("DELETE FROM ".dba::get('clicks_ips')." WHERE `id` = ".$get['id']); }

    if(checkme() != 'unlogged')
    {
        if(db("SELECT id FROM ".dba::get('clicks_ips')." WHERE `uid` = '".userid()."' AND `ids` = '".$clickedID."' AND `side` = '".$side_tag."'",true))
            return false;

        if(db("SELECT id FROM ".dba::get('clicks_ips')." WHERE `ip` = '".visitorIp()."' AND `ids` = '".$clickedID."' AND `side` = '".$side_tag."'",true))
        {
            if($update)
                db("UPDATE `".dba::get('clicks_ips')."` SET `uid` = '".userid()."', `time` = '".(time()+count_clicks_expires)."' WHERE `ip` = '".visitorIp()."' AND `ids` = '".$clickedID."' AND `side` = '".$side_tag."'");

            return false;
        }
        else
        {
            if($update)
                db("INSERT INTO ".dba::get('clicks_ips')." (`id` ,`ip` ,`uid` ,`ids`, `side`, `time`) VALUES (NULL , '".visitorIp()."', '".userid()."', '".$clickedID."', '".$side_tag."', '".(time()+count_clicks_expires)."')");

            return true;
        }
    }
    else
    {
        if(!db("SELECT id FROM ".dba::get('clicks_ips')." WHERE `ip` = '".visitorIp()."' AND `ids` = '".$clickedID."' AND `side` = '".$side_tag."'",true))
        {
            if($update)
                db("INSERT INTO ".dba::get('clicks_ips')." (`id` ,`ip` ,`uid` ,`ids`, `side`, `time`) VALUES (NULL , '".visitorIp()."', '0', '".$clickedID."', '".$side_tag."', '".(time()+count_clicks_expires)."')");

            return true;
        }
    }

    return false;
}

/**
 * Löscht angelegte Thumbgen Files
 **/
function thumbgen_delete($filename,$width='100',$height='')
{
    if(!isset($filename) || empty($filename) || !file_exists(basePath.'/inc/images/uploads/'.$filename))
        return false;

    list($breite, $hoehe, $type) = getimagesize(basePath.'/inc/images/uploads/'.$filename);
    $neueBreite = empty($width) || $width <= 1 ? $breite : convert::ToInt($width);
    $neueHoehe = empty($height) || $height <= 1 ? intval($hoehe*$neueBreite/$breite) : convert::ToInt($height);
    return (Cache::delete_binary('thumbgen_file_'.$filename.'_'.$neueBreite.'_'.$neueHoehe.'_'.$type) ? true : false);
}

/**
 * Laden der Menu XML Files
 * @param string $phold
 * @return multitype:boolean multitype:string boolean Ambigous <XMLObj, boolean>
 */
function load_menu_xml()
{
    global $ajaxThumbgen;
    if(($add_menu_functions = get_files(basePath.'/inc/menu-functions/',false,true,array('php'))) && !$ajaxThumbgen)
    {
        if(count($add_menu_functions) >= 1)
        {
            foreach($add_menu_functions as $func)
            {
                $func_name = str_replace('.php', '', $func);
                if(@file_exists(basePath.'/inc/menu-functions/'.$func_name.'.xml')) //XML Extension
                    xml::openXMLfile('menu_'.$func_name,'inc/menu-functions/'.$func_name.'.xml');
            }
        }
    }
}

function get_menu_xml($phold='')
{
    $xml_config=false; $MenuConfig = array();
    if(@file_exists(basePath.'/inc/menu-functions/'.$phold.'.xml')) //XML Extension
    {
        $MenuConfig['AjaxLoad'] = xml::bool(xml::getXMLvalue('menu_'.$phold, 'AjaxLoad'));
        $MenuConfig['Only_Users'] = xml::bool(xml::getXMLvalue('menu_'.$phold, 'Only_Users'));
        $MenuConfig['Only_Admin'] = xml::bool(xml::getXMLvalue('menu_'.$phold, 'Only_Admin'));
        $MenuConfig['Only_Root'] = xml::bool(xml::getXMLvalue('menu_'.$phold, 'Only_Root'));
        $MenuConfig['AjaxLoad_Img'] = xml::getXMLvalue('menu_'.$phold, 'AjaxLoad_Img');
        $MenuConfig['update'] = convert::ToString(xml::getXMLvalue('menu_'.$phold, 'Update'));

        $ajax_width = convert::ToString(xml::getXMLvalue('menu_'.$phold, 'Div_Width'));
        $MenuConfig['div_width'] = empty($ajax_width) ? '' : 'width:'.$ajax_width.'px;';

        $ajax_height = convert::ToString(xml::getXMLvalue('menu_'.$phold, 'Div_Height'));
        $MenuConfig['div_height'] = empty($ajax_height) ? '' : 'height:'.$ajax_height.'px;';

        $MenuConfig['AjaxLoad_Img_Use'] = xml::bool(xml::getXMLvalue('menu_'.$phold, 'AjaxLoad_Img_Use'));
        $xml_config=true;
    }

    return array('xml' => $xml_config, 'config' => $MenuConfig);
}

/**
 *  Neue Languages & Neue Funktionen einbinden
 */
if(!$ajaxThumbgen)
{
    if(($add_languages = API_CORE::load_additional_language()) != false)
    { foreach($add_languages as $language) include($language); }

    if(($add_functions = API_CORE::load_additional_functions()) != false)
    { foreach($add_functions as $func) include($func); }
    unset($add_languages,$add_functions);
}

//-> Navigation einbinden
if(!$ajaxThumbgen)
{
    if(file_exists(basePath.'/inc/menu-functions/navi.php'))
        include_once(basePath.'/inc/menu-functions/navi.php');
}

/**
 * Sendet Daten von PHP nach javascript, beim page() Aufruf.
 */
class javascript
{
    /**
     * Keys:
     * dialog_button_00, dialog_button_01 => confirm box
     */

    private static $data_array = array();

    public static function add_array($array=array())
    { self::$data_array = array_merge(self::$data_array, $array); }

    public static function add($key='',$var='')
    { self::$data_array[$key] = convert::UTF8($var); }

    public static function remove($key='')
    { unset(self::$data_array[$key]); }

    public static function get($key='')
    { return convert::UTF8_Reverse(self::$data_array[$key]); }

    public static function encode()
    { return json_encode(self::$data_array); }
}

//-> Ausgabe des Indextemplates
function page($index,$title,$where,$time,$index_templ=false)
{
    global $userip,$tmpdir,$AjaxLoad_blacklist;
    global $designpath,$cp_color,$rootAdmin,$clanname;

    // installer vorhanden?
    if(file_exists(basePath."/_installer") && checkme() == 4 && !is_debug)
        $index = _installdir;

    // user gebannt? Logge aus!
    if(checkme() == 'banned')
    {
        logout();
        header("Location: ../user/?action=login");
    }

    //Send E-Mail
    mailmgr::Send();

    // JS-Dateine einbinden
    javascript::add_array(array('dialog_button_00' => _yes, 'dialog_button_01' => _no, 'maxW' => config('maxwidth'), 'lng' => (language::get_language()=='deutsch') ? 'de':'en', 'domain' => settings('i_domain'),
    'extern' => convert::BoolToInt(extern_urls_detect), 'worker' => convert::BoolToInt(use_html5_worker), 'tmpdir' => '../inc/_templates_/'.$tmpdir));
    $java_vars = "<script language=\"javascript\" type=\"text/javascript\">var json_from_php = '".javascript::encode()."';</script>";
    $login = ''; $check_msg = ''; $ukrss = '';

    if(settings("wmodus") && checkme() != 4)
    {
        $secure = (config('securelogin') ? show("menu/secure", array("help" => _login_secure_help, "security" => _register_confirm)) : '');
        $login = show("errors/wmodus_login", array("what" => _login_login, "secure" => $secure, "signup" => _login_signup, "permanent" => _login_permanent, "lostpwd" => _login_lostpwd));
        echo show("errors/wmodus", array("tmpdir" => $tmpdir, "java_vars" => $java_vars, "dir" => $designpath, "title" => string::decode(strip_tags($title)), "login" => $login));
    }
    else
    {
        updateCounter();
        update_maxonline();
        load_menu_xml();

        //check permissions
        if(checkme() == "unlogged")
            $login = show("menu/login", array("secure" => (config('securelogin') ? show("menu/secure", array("help" => _login_secure_help)) : '')));
        else
        {
            $check_msg = check_msg();
            set_lastvisit();
            db("UPDATE ".dba::get('users')." SET `time` = '".time()."', `whereami` = '".string::encode($where)."' WHERE id = '".userid()."'");
            $get_rss_key = db("SELECT rss_key FROM `".dba::get('users')."` WHERE id = '".userid()."' LIMIT 1",false,true);
            $ukrss = $get_rss_key['rss_key'];
        }

        //init templateswitch
        if(!Cache::is_mem() || Cache::check('template_menu'))
        {
            $tmpldir=''; $tmps = get_files(basePath.'/inc/_templates_/',true,false);
            foreach($tmps as $tmp)
            {
                $selt = ($tmpdir == $tmp ? 'selected="selected"' : '');
                $tmpldir .= show(_select_field, array("value" => "../user/?action=switch&amp;set=".$tmp,  "what" => $tmp,  "sel" => $selt));
            }

            Cache::set('template_menu',$tmpldir,5);
        }
        else $tmpldir = Cache::get('template_menu');

        //misc vars
        $template_switch = show("menu/tmp_switch", array("templates" => $tmpldir));
        $time = show(_generated_time, array("time" => $time));
        $headtitle = show(_index_headtitle, array("clanname" => $clanname));
        $rss = $clanname;
        $title = string::decode(strip_tags($title));
        $charset = !defined('_charset') ? 'iso-8859-1' : _charset;
        $lng_meta = language::get_meta();
        $index = empty($index) ? '' : (empty($check_msg) ? '' : $check_msg).'<table class="mainContent" cellspacing="1" style="margin-top:0">'.$index.'</table>';
        $where = preg_replace_callback("#autor_(.*?)$#",create_function('$id', 'return data("$id[1]","nick");'),$where);

        //check if placeholders are given
        $pholder = file_get_contents($designpath."/index.html");

        //filter placeholders
        $pholdervars = '';
        $blArr = array("[title]","[copyright]","[java_vars]","[login]", "[template_switch]","[headtitle]","[index]", "[time]","[rss]","[dir]","[charset]","[ukrss]","[lng_meta]","[where]");
        foreach($blArr as $bl)
        {
            if(preg_match("#".$bl."#",$pholder))
                $pholdervars .= $bl;

            $pholder = str_replace($bl,"",$pholder);
        }

        $pholder = pholderreplace($pholder);
        $pholdervars = pholderreplace($pholdervars);

        //put placeholders in array
        $arr = array();
        $pholder = explode("^",$pholder);
        foreach($pholder as $phold)
        {
            if(strstr($phold, 'nav_'))
                $arr[$phold] = navi($phold);
            else
            {
                if(@file_exists(basePath.'/inc/menu-functions/'.$phold.'.php'))
                {
                    ## DZCP-Extended Edition START ##
                    $menu_xml = get_menu_xml($phold);
                    $MenuConfig = $menu_xml['config'];

                    if($menu_xml['xml'])
                    {
                        $arr[$phold] = '';
                        if(!$MenuConfig['AjaxLoad'] || (array_key_exists($phold, $AjaxLoad_blacklist) && !$AjaxLoad_blacklist[$phold]) || !AjaxLoad)
                        {
                            if((!$MenuConfig['Only_Root'] && !$MenuConfig['Only_Admin'] && !$MenuConfig['Only_Users']) ||
                            (!$MenuConfig['Only_Root'] && !$MenuConfig['Only_Admin'] && $MenuConfig['Only_Users'] &&  checkme() != "unlogged" && checkme() != "banned") ||
                            (!$MenuConfig['Only_Root'] && $MenuConfig['Only_Admin'] &&  checkme() == 4) ||
                            ($MenuConfig['Only_Root'] && checkme() == 4 && userid() == convert::ToInt($rootAdmin)))
                            {
                                include_once(basePath.'/inc/menu-functions/'.$phold.'.php');
                                $arr[$phold] = call_user_func($phold);
                            }
                        }
                        else
                        {
                            if((!$MenuConfig['Only_Root'] && !$MenuConfig['Only_Admin'] && !$MenuConfig['Only_Users']) ||
                            (!$MenuConfig['Only_Root'] && !$MenuConfig['Only_Admin'] && $MenuConfig['Only_Users'] &&  checkme() != "unlogged" && checkme() != "banned") ||
                            (!$MenuConfig['Only_Root'] && $MenuConfig['Only_Admin'] &&  checkme() == 4) ||
                            ($MenuConfig['Only_Root'] && checkme() == 4 && userid() == convert::ToInt($rootAdmin)))
                            {
                                $icon_html = '<img src="../inc/images/'.$MenuConfig['AjaxLoad_Img'].'" alt="" />';
                                $menu_index_hash = md5_file(basePath.'/inc/menu-functions/'.$phold.'.php');
                                $Ajax_img = ($MenuConfig['AjaxLoad_Img_Use'] ? "<div style=\"".$MenuConfig['div_width']." ".$MenuConfig['div_height']." padding:10px;text-align:center;\">".$icon_html."</div>" : "");
                                $arr[$phold] = "<div style=\"".$MenuConfig['div_width']." ".$MenuConfig['div_height']."\" id=\"menu_".$phold."\">".$Ajax_img."<script language=\"javascript\" type=\"text/javascript\">DZCP.initDynLoader('menu_".$phold."','menu','&hash=".$menu_index_hash."');</script></div>";
                            }
                        }
                    }
                    else
                    {
                        include_once(basePath.'/inc/menu-functions/'.$phold.'.php');
                        $arr[$phold] = call_user_func($phold);
                    }
                }
                else if(function_exists($phold))
                    $arr[$phold] = call_user_func($phold);
                ## DZCP-Extended Edition END ##
            }
        }

        $pholdervars = explode("^",$pholdervars);
        foreach($pholdervars as $pholdervar)
        { if(isset($$pholdervar)) $arr[$pholdervar] = $$pholdervar; }

        if(save_debug_console)
            DebugConsole::save_log();

        //index output
        echo (is_debug && show_debug_console ? DebugConsole::show_logs() : '') . show((($index_templ != false ? file_exists(basePath."/inc/_templates_/".$tmpdir."/".$index_templ.".html") : false) ? $index_templ : 'index') , $arr);
    }

    // Cookie speichern
    cookie::save();

    // Datenbankverbindung beenden
    database::close();
}

//Initialisierung der Addon Calls
if(!$ajaxThumbgen)
    API_CORE::call_addons_init();
