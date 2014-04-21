<?php
/**
 * <DZCP-Extended Edition>
 * @package: DZCP-Extended Edition
 * @author: DZCP Developer Team || Hammermaps.de Developer Team
 * @link: http://www.dzcp.de || http://www.hammermaps.de
 */

/**
* Eine Liste der Dateien oder Verzeichnisse zusammenstellen, die sich im angegebenen Ordner befinden.
*
* @return array
*/
function get_files($dir=null,$only_dir=false,$only_files=false,$file_ext=array(),$preg_match=false,$blacklist=array())
{
    $files = array();
    if(!file_exists($dir) && !is_dir($dir)) return $files;
    $hash = md5($dir.$only_dir.$only_files.count($file_ext).$preg_match.count($blacklist));

    if(!RTBuffer::check($hash))
        return RTBuffer::get($hash);

    if($handle = @opendir($dir))
    {
        if($only_dir) ## Ordner ##
        {
            while(false !== ($file = readdir($handle)))
            {
                if($file != '.' && $file != '..' && !is_file($dir.'/'.$file))
                {
                    if(!count($blacklist) && ($preg_match ? preg_match($preg_match,$file) : true))
                        $files[] = $file;
                    else
                    {
                        if(!in_array($file, $blacklist) && ($preg_match ? preg_match($preg_match,$file) : true))
                            $files[] = $file;
                    }
                }
            } //while end
        }
        else if($only_files) ## Dateien ##
        {
            while(false !== ($file = readdir($handle)))
            {
                if($file != '.' && $file != '..' && is_file($dir.'/'.$file))
                {
                    if(!in_array($file, $blacklist) && !count($file_ext) && ($preg_match ? preg_match($preg_match,$file) : true))
                        $files[] = $file;
                    else
                    {
                        ## Extension Filter ##
                        $exp_string = array_reverse(explode(".", $file));
                        if(!in_array($file, $blacklist) && in_array(strtolower($exp_string[0]), $file_ext) && ($preg_match ? preg_match($preg_match,$file) : true))
                            $files[] = $file;
                    }
                }
            } //while end
        }
        else ## Ordner & Dateien ##
        {
            while(false !== ($file = readdir($handle)))
            {
                if($file != '.' && $file != '..' && is_file($dir.'/'.$file))
                {
                    if(!in_array($file, $blacklist) && !count($file_ext) && ($preg_match ? preg_match($preg_match,$file) : true))
                        $files[] = $file;
                    else
                    {
                        ## Extension Filter ##
                        $exp_string = array_reverse(explode(".", $file));
                        if(!in_array($file, $blacklist) && in_array(strtolower($exp_string[0]), $file_ext) && ($preg_match ? preg_match($preg_match,$file) : true))
                            $files[] = $file;
                    }
                }
                else
                {
                    if(!in_array($file, $blacklist) && $file != '.' && $file != '..' && ($preg_match ? preg_match($preg_match,$file) : true))
                        $files[] = $file;
                }
            } //while end
        }

        if(is_resource($handle))
            closedir($handle);

        if(!count($files))
            return false;

        RTBuffer::set($hash,$files);
        return $files;
    }
    else
        return false;
}

/**
* Erkennen welche PHP Version ausgeführt wird.
*
* @return boolean
*/
function is_php($version='5.3.0')
{ return (floatval(phpversion()) >= $version); }

/**
 * PHPInfo in ein Array lesen und zurückgeben
 *
 * @return array
 **/
function parsePHPInfo()
{
    ob_start();
        phpinfo();
        $s = ob_get_contents();
    ob_end_clean();

   $s = strip_tags($s,'<h2><th><td>');
   $s = preg_replace('/<th[^>]*>([^<]+)<\/th>/',"<info>\\1</info>",$s);
   $s = preg_replace('/<td[^>]*>([^<]+)<\/td>/',"<info>\\1</info>",$s);
   $vTmp = preg_split('/(<h2[^>]*>[^<]+<\/h2>)/',$s,-1,PREG_SPLIT_DELIM_CAPTURE);

   $vModules = array();
   for ($i=1;$i<count($vTmp);$i++)
   {
        if(preg_match('/<h2[^>]*>([^<]+)<\/h2>/',$vTmp[$i],$vMat))
        {
            $vName = trim($vMat[1]);
            $vTmp2 = explode("\n",$vTmp[$i+1]);
            foreach ($vTmp2 AS $vOne)
            {
                $vPat = '<info>([^<]+)<\/info>';
                $vPat3 = "/$vPat\s*$vPat\s*$vPat/";
                $vPat2 = "/$vPat\s*$vPat/";

                if(preg_match($vPat3,$vOne,$vMat))
                    $vModules[$vName][trim($vMat[1])] = array(trim($vMat[2]),trim($vMat[3]));
                else if(preg_match($vPat2,$vOne,$vMat))
                    $vModules[$vName][trim($vMat[1])] = trim($vMat[2]);
            }
        }
  }

  return $vModules;
}

/**
 * Prüft wie PHP ausgeführt wird
 * Added by DZCP-Extended Edition
 *
 * @return string
 **/
function php_sapi_type()
{
    $sapi_type = php_sapi_name();
    $sapi_types = array("apache" => 'Apache HTTP Server', "apache2filter" => 'Apache 2: Filter',
    "apache2handler" => 'Apache 2: Handler', "cgi" => 'CGI', "cgi-fcgi" => 'Fast-CGI', "cli" => 'CLI', "isapi" => 'ISAPI', "nsapi" => 'NSAPI');
    return(empty($sapi_types[substr($sapi_type, 0, 3)]) ? substr($sapi_type, 0, 3) : $sapi_types[substr($sapi_type, 0, 3)]);
}
/**
 * Funktion um zu prüfen ob mod_rewrite aktiviert ist
 * Added by DZCP-Extended Edition
 *
 * @return boolean
 **/
function check_mod_rewrite()
{
    if(!use_mod_rewrite) return false;
    if(function_exists('apache_get_modules'))
        return in_array('mod_rewrite', apache_get_modules());

    return getenv('HTTP_MOD_REWRITE') == 'On' ? true : false ;
}
/**
 * Funktion um eine Datei im Web auf Existenz zu prüfen und abzurufen
 * Updated for DZCP-Extended Edition
 *
 * @return String
 **/
function fileExists($url,$timeout=2)
{
    if((!fsockopen_support() && !use_curl || (use_curl && !extension_loaded('curl'))))
        return false;

    $url_p = @parse_url($url);
    $host = $url_p['host'];
    $port = isset($url_p['port']) ? $url_p['port'] : 80;
    unset($url_p);

    if(!ping_port($host,$port,$timeout))
        return false;

    unset($host,$port);
    if(use_curl && extension_loaded('curl')) //curl
    {
        if(!$curl = curl_init())
            return false;

        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_CONNECTTIMEOUT , $timeout);
        curl_setopt($curl, CURLOPT_TIMEOUT, $timeout * 2); // x 2

        if(!$content = curl_exec($curl))
            return false;

        @curl_close($curl);
        unset($curl);
    }
    else
    {
        ini_set('default_socket_timeout', $timeout * 2);
        if(!$content = @file_get_contents($url))
            return false;
    }

    return convert::ToString(trim($content));
}

/**
 * Funktion um notige Erweiterungen zu prufen
 *
 * @return boolean
 **/
function fsockopen_support()
{
    if(!function_exists('fsockopen') || !function_exists("fopen") || !allow_fsockopen)
        return false;

    if(strpos(ini_get('disable_functions'),'fsockopen') || strpos(ini_get('disable_functions'),'file_get_contents') || strpos(ini_get('disable_functions'),'fopen'))
        return false;

    return true;
}

/**
 * Pingt einen Server Port
 *
 * @return boolean
 **/
function ping_port($address='',$port=0000,$timeout=2,$udp=false)
{
    if(!fsockopen_support())
        return false;

    $errstr = NULL; $errno = NULL;
    if(!$ip = DNSToIp($address))
        return false;

    if($fp = @fsockopen(($udp ? "udp://".$ip : $ip), $port, $errno, $errstr, $timeout))
    {
        unset($ip,$port,$errno,$errstr,$timeout);
        @fclose($fp);
        return true;
    }

    return false;
}

/**
 * Wandelt eine DNS Adresse in eine IPv4 um
 *
 * @return String / IPv4
 */
function DNSToIp($address='')
{
    if(!preg_match('#^(?:(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.){3}(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)$#', $address))
    {
        if(!($result = gethostbyname($address)))
            return false;

        if ($result === $address)
            $result = false;
    }
    else
        $result = $address;

    return $result;
}

/**
 * Gibt die IP des Besuchers / Users zurück
 * Forwarded IP Support
 *
 * @return String
 */
function visitorIp()
{
    //Move to 'inc/sessions.php'
    global $session;
    return $session->visitorIp();
}

/**
 * Prüft eine IP gegen eine IP-Range
 * @param ipv4 $ip
 * @param ipv4 range $range
 * @return boolean
 */
function validateIpV4Range ($ip, $range)
{
    if(!is_array($range))
    {
        $counter = 0;
        $tip = explode ('.', $ip);
        $rip = explode ('.', $range);
        foreach ($tip as $targetsegment)
        {
            $rseg = $rip[$counter];
            $rseg = preg_replace ('=(\[|\])=', '', $rseg);
            $rseg = explode ('-', $rseg);
            if (!isset($rseg[1]))
                $rseg[1] = $rseg[0];

            if ($targetsegment < $rseg[0] || $targetsegment > $rseg[1])
                return false;

            $counter++;
        }
    }
    else
    {
        foreach ($range as $range_num)
        {
            $counter = 0;
            $tip = explode ('.', $ip);
            $rip = explode ('.', $range_num);
            foreach ($tip as $targetsegment)
            {
                $rseg = $rip[$counter];
                $rseg = preg_replace ('=(\[|\])=', '', $rseg);
                $rseg = explode ('-', $rseg);
                if (!isset($rseg[1]))
                    $rseg[1] = $rseg[0];

                if ($targetsegment < $rseg[0] || $targetsegment > $rseg[1])
                    return false;

                $counter++;
            }
        }
    }

    return true;
}

/**
 * Filtert Platzhalter
 *
 * @return string
 */
function pholderreplace($pholder)
{
    $search = array('@<script[^>]*?>.*?</script>@si','@<style[^>]*?>.*?</style>@siU','@<[\/\!][^<>]*?>@si','@<![\s\S]*?--[ \t\n\r]*>@');

    //Replace
    $pholder = preg_replace("#<script(.*?)</script>#is","",$pholder);
    $pholder = preg_replace("#<style(.*?)</style>#is","",$pholder);
    $pholder = preg_replace($search, '', $pholder);
    $pholder = str_replace(" ","",$pholder);
    $pholder = preg_replace("#&(.*?);#s","",$pholder);
    $pholder = str_replace("\r","",$pholder);
    $pholder = str_replace("\n","",$pholder);
    $pholder = preg_replace("#\](.*?)\[#is","][",$pholder);
    $pholder = str_replace("][","^",$pholder);
    $pholder = preg_replace("#^(.*?)\[#s","",$pholder);
    $pholder = preg_replace("#\](.*?)$#s","",$pholder);
    $pholder = str_replace("[","",$pholder);
    return str_replace("]","",$pholder);
}

/**
* Sucht nach Platzhaltern und ersetzt diese.
*
* @return string
*/
function show($tpl="", $array=array(), $array_lang_constant=array(), $array_block=array())
{
    global $tmpdir,$addon_dir;

    if(!empty($tpl) && $tpl != null)
    {
        ## DZCP-Extended Edition START ##
        $template = $_SESSION['installer'] ? basePath."/_installer/html/".$tpl : basePath."/inc/_templates_/".$tmpdir."/".$tpl;
        $template_additional = $_SESSION['installer'] ? false : basePath."/inc/additional-tpl/".$tmpdir."/".$tpl;
        $array['dir'] = $_SESSION['installer'] ? "html": 'inc/_templates_/'.$tmpdir;
        $array['dir_img'] = $_SESSION['installer'] ? "html/img/": 'inc/_templates_/'.$tmpdir.'/images/';
        $array['dir_addon'] = $addon_dir;
        $array['tmpdir'] = $tmpdir;

        if($template_additional != false && file_exists($template_additional.".html"))
            $tpl = file_get_contents($template_additional.".html");
        else if(allow_additional && $template_additional != false && !file_exists($template_additional.".html") && ($tpli=API_CORE::load_additional_tpl($tpl)))
            $tpl = $tpli;
        else if(file_exists($template.".html") && !file_exists($template_additional.".html"))
            $tpl = file_get_contents($template.".html");
        ## DZCP-Extended Edition END ##

        //put placeholders in array
        $pholder = explode("^",pholderreplace($tpl));
        for($i=0;$i<=count($pholder)-1;$i++)
        {
            if(in_array($pholder[$i],$array_block))
                continue;

            if(array_key_exists($pholder[$i],$array))
                continue;

            if(!strstr($pholder[$i], 'lang_'))
                continue;

            if(defined(substr($pholder[$i], 4)))
                $array[$pholder[$i]] = (count($array_lang_constant) >= 1 ? show(constant(substr($pholder[$i], 4)),$array_lang_constant) : constant(substr($pholder[$i], 4)));
        }

        unset($pholder);

        ## DZCP-Extended Edition START ##
        $tpl = ((function_exists('checkme') ? checkme() : 'unlogged') == 'unlogged' ? preg_replace("|<logged_in>.*?</logged_in>|is", "", $tpl) : preg_replace("|<logged_out>.*?</logged_out>|is", "", $tpl));
        $tpl = str_ireplace(array("<logged_in>","</logged_in>","<logged_out>","</logged_out>"), '', $tpl);
        ## DZCP-Extended Edition END ##

        if(count($array) >= 1)
        {
            foreach($array as $value => $code)
            { $tpl = str_replace('['.$value.']', $code, $tpl); }
        }
    }

    return $tpl;
}

/**
 * Gibt requires Fehler aus und stoppt die Ausführung des CMS
 **/
if(!is_php('5.3.0') && !$_SESSION['installer'])
    die('<b>Requires failed:</b><br /><ul>'.
            '<li><b>The DZCP-Extended Edition requires PHP 5.3.0 or newer.</b>'.
            '<li><b>Die DZCP-Extended Edition ben&ouml;tigt PHP 5.3.0 oder neuer.</b>');

/**
 * Generiert Passwörter
 * Updated for DZCP-Extended Edition
 *
 * @return String
 */
function mkpwd($passwordLength=8,$specialcars=true)
{
    global $passwordComponents;
    $componentsCount = count($passwordComponents);

    if(!$specialcars && $componentsCount == 4) //Keine Sonderzeichen
    {
        unset($passwordComponents[3]);
        $componentsCount = count($passwordComponents);
    }

    shuffle($passwordComponents); $password = '';
    for ($pos = 0; $pos < $passwordLength; $pos++)
    {
        $componentIndex = ($pos % $componentsCount);
        $componentLength = strlen($passwordComponents[$componentIndex]);
        $random = rand(0, $componentLength-1);
        $password .= $passwordComponents[$componentIndex]{ $random };
    }

    unset($random,$componentLength,$componentIndex);
    return $password;
}

/**
 * Generiert eine XXXXXXXX-XXXX-XXXX-XXXX-XXXXXXXXXXXX unique id
 *
 * @return string
 */
function GenGuid()
{
    $s = strtoupper(md5(uniqid(rand(),true)));
    return substr($s,0,8) .'-'.substr($s,8,4).'-'.substr($s,12,4).'-'.substr($s,16,4).'-'. substr($s,20);
}

/**
 * Funktion zum schreiben der Adminlogs
 */
function wire_ipcheck($what='')
{
    db("INSERT INTO ".dba::get('ipcheck')." SET `ip` = '".visitorIp()."',`what` = '".$what."',`time` = '".time()."'");
}

/**
 * Checkt versch. Dinge anhand der Hostmaske eines Users
 *
* @return boolean
 */
function ipcheck($what,$time = "")
{
    $get = db("SELECT time,what FROM ".dba::get('ipcheck')." WHERE what = '".$what."' AND ip = '".visitorIp()."' ORDER BY time DESC",false,true);
    if(preg_match("#vid#", $get['what']))
        return true;
    else
    {
        if($get['time']+$time<time())
            db("DELETE FROM ".dba::get('ipcheck')." WHERE what = '".$what."' AND ip = '".visitorIp()."' AND time+'".$time."'<'".time()."'");

        if($get['time']+$time>time())
            return true;
        else
            return false;
    }
}

/**
 * Erkennt Spider und Crawler um sie von der Besucherstatistik auszuschliessen.
 *
 * @return boolean
 */
function isBot()
{
    $bots_basic = array('bot', 'b o t', 'spider', 'spyder', 'crawl', 'slurp', 'robo', 'yahoo', 'ask', 'google', '80legs', 'acoon',
            'altavista', 'al_viewer', 'appie', 'appengine-google', 'arachnoidea', 'archiver', 'asterias', 'ask jeeves', 'beholder',
            'bildsauger', 'bingsearch', 'bingpreview', 'bumblebee', 'bramptonmoose', 'cherrypicker', 'crescent', 'coccoc', 'cosmos',
            'docomo', 'drupact', 'emailsiphon', 'emailwolf', 'extractorpro', 'exalead ng', 'ezresult', 'feedfetcher', 'fido', 'fireball',
            'flipboardproxy', 'gazz', 'getweb', 'gigabaz', 'gulliver', 'harvester', 'hcat', 'heritrix', 'hloader', 'hoge', 'httrack',
            'incywincy', 'infoseek', 'infohelfer', 'inktomi', 'indy library', 'informant', 'internetami', 'internetseer', 'link', 'larbin',
            'jakarta', 'mata hari', 'medicalmatrix', 'mercator', 'miixpc', 'moget', 'msnptc', 'muscatferret', 'netcraftsurveyagent',
            'openxxx', 'picmole', 'piranha', 'pldi.net', 'p357x', 'quosa', 'rambler', 'rippers', 'rganalytics', 'scan', 'scooter', 'ScoutJet',
            'siclab', 'siteexplorer', 'sly', 'suchen', 'searchme', 'spy', 'swisssearch', 'sqworm', 'trivial', 't-h-u-n-d-e-r-s-t-o-n-e', 'teoma',
            'twiceler', 'ultraseek', 'validator', 'webbandit', 'webmastercoffee', 'webwhacker', 'wevika', 'wisewire', 'yandex', 'zyborg', 'agentname');

    $hash = md5('bot_detect_'.visitorIp());
    if(cache::is_mem() && !cache::check($hash)) return cache::get($hash);
    $UserAgent = trim($_SERVER['HTTP_USER_AGENT']);
    if(empty($UserAgent)) return false; $return = false;
    if($UserAgent != str_ireplace($bots_basic, '#', $UserAgent)) $return = true;
    if(cache::is_mem()) cache::set($hash,$return,30);
    return $return;
}

/**
 * Wandelt einen Json-String in ein Array um.
 * Optional‎: Kann komprimierten Json-String decodieren und in ein Array umwandeln.
 *
 * @return array
 */
function string_to_array($str='',$compress=false)
{
    if($compress) $str = gzuncompress(hextobin($str));
    return json_decode($str, true);
}

/**
 * Wandelt einen Array in einen Json-String um.
 * Optional‎: Der Json-String kann komprimiert werden und wird als Hex zurückgegeben.
 *
 * @return String
 */
function array_to_string($arr=array(),$compress=false)
{
    $json = json_encode($arr,JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_QUOT|JSON_HEX_AMP);
    return $compress ? bin2hex(gzcompress($json)) : $json;
}

/**
 * Wird verwendet um die Ladezeit der Seite zu errechnen.
 *
 * @return float
 */
function generatetime()
{
    list($usec, $sec) = explode(" ",microtime());
    return ((float)$usec + (float)$sec);
}

/**
 * Funktion um Sonderzeichen zu konvertieren
 *
 * @return string
 */
function spChars($txt,$reverse=false)
{
    $var0 = array("€", "'", "\"");
    $var1 = array("&euro;","&apostroph;","&quot;");
    return spChars_uml($reverse ? str_replace($var1, $var0, $txt) : str_replace($var0, $var1, $txt),$reverse);
}

/**
 * Funktion um Umlaute in html Code umzuwandeln
 *
 * @return string
 */
function spChars_uml($txt,$reverse=false)
{
    $var0 = array("Ä", "Ö", "Ü", "ä", "ö", "ü", "ß");
    $var1 = array("&Auml;", "&Ouml;", "&Uuml;", "&auml;", "&ouml;", "&uuml;", "&szlig;");
    return $reverse ? str_replace($var1, $var0, $txt) : str_replace($var0, $var1, $txt);
}

/**
 * Funktion um eine Variable prüfung in einem Array durchzuführen
 *
 * @return boolean
 */
function array_var_exists($var,$search)
{ foreach($search as $key => $var_) { if($var_==$var) return true; } return false; }

/**
 * Sortiert ein Array anhand eines Keys
 *
 * @param array $records
 * @param strin $field
 * @param boolean $reverse
 * @return array
 */
function record_sort($named_recs, $order_by, $rev=false, $flags=0)
{
    if(is_array($named_recs) && !empty($order_by))
    {
        $named_hash = array();
        foreach($named_recs as $key=>$fields)
            $named_hash["$key"] = $fields[$order_by];

        $rev ? arsort($named_hash,$flags=0) : asort($named_hash, $flags=0);

        $sorted_records = array();
        foreach($named_hash as $key=>$val)
            $sorted_records["$key"]= $named_recs[$key];

        return $sorted_records;
    }

    return $named_recs;
}

/**
 * Funktion um Passwörter in einen Hash umzurechnen
 *
 * Info Metode:
 * 0 => md5
 * 1 => sha1
 * 2 => sha256
 * 3 => sha512
 *
 * @return string/hash
 */
function pass_hash($pass_key='',$metode=0)
{
    global $sql_salt;
    switch($metode)
    {
        case 1: return sha1($pass_key . (salt ? $sql_salt : ''),false); break;
        case 2: return hash('sha256', $pass_key . (salt ? $sql_salt : ''),false); break;
        case 3: return hash('sha512', $pass_key . (salt ? $sql_salt : ''),false); break;
        default: return md5($pass_key,false); break;
    }
}

/**
 * Funktion um Sekunden in Tage:Stinden:Minuten:Sekunden Formate umzuwandeln
 *
 * @return string
 */
function sec_format($seconds)
{
    $units = array(_sec_format_day=>86400, _sec_format_hour=>3600, _sec_format_minute=>60, _sec_format_second=>1);
    if($seconds < 1) return _sec_format_lower_second;
    else
    {
        $show = false; $out = '';
        foreach($units as $key=>$value)
        {
            $t = round($seconds/$value);
            $seconds = $seconds%$value;
            list($s, $pl) = explode("|", $key);
            if($t > 0 || $show)
            {
                if($t == 1)
                    $out .= $t." ".$s.", ";
                else
                    $out .= $t." ".$s.$pl.", ";

                $show = false;
            }
        }

        return substr($out, 0, strlen($out)-2);
    }
}

/**
 * Funktion um Datengrößen zu ermitteln.
 *
 * @return int
 */
function filesize_extended($file=null)
{
    if(links_check_url($file))
        return remote_filesize($file);

    if(allow_os_shell) // Standardmäßig deaktiviert, sehe config.php
        return os_filesize($file);

    return @filesize($file);
}

/**
 * Funktion um Datengrößen > 2 GB anzeigen zu können * OS-Shell Zugriff nötig und ein 64 Bit System
 * Standardmäßig deaktiviert, sehe config.php
 *
 * @return int
 */
function os_filesize($file=null)
{
    if (is_file($file)) //Nur Daten!
    {
        if(strtoupper(substr(php_uname('s'), 0, 3)) === 'WIN')
            return function_exists('exec') ? exec('FOR %A IN ("'.$file.'") DO @ECHO %~zA') : filesize($file); ## WIN ##
        else
            return function_exists('shell_exec') ? shell_exec('ls -l "'.$file.'" | cut -f 5 -d " "') : filesize($file); ## Linux ##
    }
    else
        return 0;
}

/**
 * Funktion um Datengrößen auf Remote Servern zu ermitteln.
 *
 * @return int
 */
function remote_filesize($url)
{
    if(Cache::check('remote_filesize_'.md5($url)))
    {
        $head = ""; $url_p = parse_url($url);
        if(array_key_exists('host', $url_p))
        {
            $host = $url_p["host"];
            if(!preg_match("/[0-9]*\.[0-9]*\.[0-9]*\.[0-9]*/",$host))
            {
                $ip=gethostbyname($host);
                if(!preg_match("/[0-9]*\.[0-9]*\.[0-9]*\.[0-9]*/",$ip)) return -1;
            }

            $port = (isset($url_p["port"]) ? $url_p["port"] : 80);

            if(!$port) $port=80;
            $path = $url_p["path"];

            if(!ping_port($host,$port,1))
                return 0;

            $fp = fsockopen($host, $port, $errno, $errstr, 20);
            fputs($fp, "HEAD "  . $url  . " HTTP/1.1\r\n");
            fputs($fp, "HOST: " . $host . "\r\n");
            fputs($fp, "User-Agent: Mozilla/5.0 (Windows NT 6.1; WOW64; rv:18.0) Gecko/20100101 Firefox/1\r\n");
            fputs($fp, "Connection: close\r\n\r\n");
            $headers = "";
            while (!feof($fp))
            { $headers .= fgets ($fp, 128); }
            fclose ($fp);

            $return = -2;
            $arr_headers = explode("\n", $headers);
            foreach($arr_headers as $header)
            {
                $s1 = "HTTP/1.1"; $s2 = "Content-Length: "; $s3 = "Location: ";
                if(substr(strtolower ($header), 0, strlen($s1)) == strtolower($s1)) $status = substr($header, strlen($s1));
                if(substr(strtolower ($header), 0, strlen($s2)) == strtolower($s2)) $size   = substr($header, strlen($s2));
                if(substr(strtolower ($header), 0, strlen($s3)) == strtolower($s3)) $newurl = substr($header, strlen($s3));
            }

            if(!isset($size) || empty($size)) return 0;
            $return= (convert::ToInt($size) > 0 ? convert::ToInt($size) : $status);
            if (convert::ToInt($status)==302 && strlen($newurl) > 0)
                $return = remote_filesize($newurl);

            Cache::set('remote_filesize_'.md5($url), $return, 28800); //Update alle 8h
            return $return;
        }
        else
            return 0;
    }

    return Cache::get('remote_filesize_'.md5($url));
}

/**
 * Funktion um einen Binärstring zu Dekodieren.
 *
 * @return binary/string
 */
function hextobin($hexstr)
{
    if(is_php('5.4.0'))
        return hex2bin($hexstr);

    // < PHP 5.4
    $n = strlen($hexstr);
    $sbin="";
    $i=0;
    while($i<$n)
    {
        $a =substr($hexstr,$i,2);
        $c = pack("H*",$a);
        if ($i==0){$sbin=$c;}
        else {$sbin.=$c;}
        $i+=2;
    }

    return $sbin;
}

/**
 * Runtime Buffer
 * Funktion um Werte kurzzeitig zu speichern.
 * *Aktuelle Laufzeit, keine AJAX Unterstüzung
 */
final class RTBuffer
{
    protected static $buffer = array();
    public static final function set($tag='',$data='',$time=1)
    { self::$buffer[$tag]['ttl'] = (time()+$time); self::$buffer[$tag]['data'] = json_encode($data,JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_QUOT|JSON_HEX_AMP); }

    public static final function get($tag)
    { return (array_key_exists($tag, self::$buffer) ? json_decode(self::$buffer[$tag]['data']):false); }

    public static final function check($tag)
    { if(!runtime_buffer) return true; if(!array_key_exists($tag, self::$buffer)) return true; else if(self::$buffer[$tag]['ttl'] < time())
    { unset(self::$buffer[$tag]['data']); unset(self::$buffer[$tag]['ttl']); return true; } else return false; }
}

/**
 * Gibt die Argumente für POST,GET und REQUEST zurück.
 * @param string $tag
 * @param string $type * post,get,req *
 * @param string $null_default
 * @return mixed
 */
function getArgs($tag,$null_default='',$type='post')
{
    switch ($type)
    {
        case 'post': return isset($_POST[$tag]) ? string::decode($_POST[$tag]) : $null_default; break;
        case 'get': return isset($_GET[$tag]) ? string::decode($_GET[$tag]) : $null_default; break;
        default: return isset($_REQUEST[$tag]) ? string::decode($_REQUEST[$tag]) : $null_default; break;
    }
}

#############################################
#################### XML ####################
#############################################
class xml // Class by DZCP-Extended Edition
{
    private static $xmlobj = array(); //XML

    /**
    * XML Datei Setzen
    */
    public static function openXMLfile($XMLTag,$XMLFile,$oneModule=false)
    {
        if(empty($XMLTag) || empty($XMLFile)) return false;
        if(array_key_exists($XMLTag,self::$xmlobj)) return true;
        if(file_exists(basePath . '/' . $XMLFile) || !$oneModule)
        {
            self::$xmlobj[$XMLTag]['xmlFile'] = $XMLFile;
            if(!$oneModule)
            {
                if(!file_exists(basePath . '/' . $XMLFile))
                    file_put_contents(basePath . '/' . $XMLFile, '<?xml version="1.0"?><'.$XMLTag.'></'.$XMLTag.'>');
            }

            self::$xmlobj[$XMLTag]['xmlStream'] = file_get_contents(basePath . '/' . $XMLFile);

            if(strpos(self::$xmlobj[$XMLTag]['xmlStream'], 'not found') === false)
                return self::LoadXMLStream($XMLTag);
        }

        return false;
    }

    /**
     * XML Stream setzen
     */
    public static function openXMLStream($XMLTag,$XMLStream)
    {
        if(empty($XMLTag) || empty($XMLStream)) return false;
        if(array_key_exists($XMLTag,self::$xmlobj)) return true;
        self::$xmlobj[$XMLTag]['xmlFile'] = '';
        self::$xmlobj[$XMLTag]['xmlStream'] = $XMLStream;

        if(strpos(self::$xmlobj[$XMLTag]['xmlStream'], 'not found') === false)
            return self::LoadXMLStream($XMLTag);

        return false;
    }

    /**
     * XML Stream Laden
     */
    public static function LoadXMLStream($XMLTag)
    {
        if(empty($XMLTag)) return false;
        if(array_key_exists($XMLTag,self::$xmlobj))
        {
            if(empty(self::$xmlobj[$XMLTag]['xmlStream'])) return false;
            self::$xmlobj[$XMLTag]['objekt'] = simplexml_load_string(self::$xmlobj[$XMLTag]['xmlStream'], 'SimpleXMLElement', LIBXML_NOCDATA);
            return(self::$xmlobj[$XMLTag]['objekt'] != false ? true : false);
        }

        return false;
    }

    /**
     * Gibt die XML Datei als Array zurück
     *
     * @return array / boolean
     */
    public static function getXMLasArray($XMLTag)
    {
        if(empty($XMLTag)) return false;
        return (!array_key_exists($XMLTag,self::$xmlobj)) ? false : convert::objectToArray(self::$xmlobj);
    }

    /**
     * Ist XML Datei geladen
     *
     * @return boolean
     */
    public static function loadedXML($XMLTag)
    {
        if(empty($XMLTag)) return false;
        return (!array_key_exists($XMLTag,self::$xmlobj)) ? false : true;
    }

    /**
    * XML Wert auslesen
    *
    * @return XMLObj / boolean
    */
    public static function getXMLvalue($XMLTag, $xmlpath)
    {
        if(empty($XMLTag) || empty($xmlpath)) return false;
        if(array_key_exists($XMLTag,self::$xmlobj))
        {
            if(!is_object(self::$xmlobj[$XMLTag]['objekt'])) return false;
            $xmlobj = self::$xmlobj[$XMLTag]['objekt']->xpath($xmlpath);
            return ($xmlobj) ? $xmlobj[0] : false;
        }

        return false;
    }

    /**
    * XML Werte �ndern
    *
    * @return boolean
     */
    public static function changeXMLvalue($XMLTag, $xmlpath, $xmlnode, $xmlvalue='')
    {
        if(empty($XMLTag) || empty($xmlpath) || empty($xmlnode)) return false;
        if(array_key_exists($XMLTag,self::$xmlobj))
        {
            if(!is_object(self::$xmlobj[$XMLTag]['objekt'])) return false;
            $xmlobj = self::$xmlobj[$XMLTag]['objekt']->xpath($xmlpath);
            $xmlobj[0]->{$xmlnode} = htmlspecialchars($xmlvalue);
            return true;
        }

        return false;
    }

    /**
    * Einen neuen XML Knoten hinzuf�gen
    *
    * @return boolean
    */
    public static function createXMLnode($XMLTag, $xmlpath, $xmlnode, $attributes=array(), $text='')
    {
        if(empty($XMLTag) || empty($xmlpath) || empty($xmlnode)) return false;
        if(array_key_exists($XMLTag,self::$xmlobj))
        {
            if(!is_object(self::$xmlobj[$XMLTag]['objekt'])) return false;
             $xmlobj = self::$xmlobj[$XMLTag]['objekt']->xpath($xmlpath);
             $xmlobj2 = $xmlobj[0]->addChild($xmlnode, htmlspecialchars($text));
             foreach($attributes as $attr => $value)
                 $xmlobj2->addAttribute($attr, htmlspecialchars($value));
             return true;
        }

        return false;
    }

    /**
    *  XML-Datei speichern
    *
    * @return boolean
    */
    public static function saveXMLfile($XMLTag)
    {
        if(empty($XMLTag)) return false;
        if(!array_key_exists($XMLTag,self::$xmlobj))
        {
            DebugConsole::insert_warning('xml::saveXMLfile()','Die Datei "'.self::$xmlobj[$XMLTag]['xmlFile'].'" ist nicht geladen!');
            return false;
        }

        $xmlFileValue = self::$xmlobj[$XMLTag]['objekt']->asXML();
        file_put_contents(basePath . '/' . self::$xmlobj[$XMLTag]['xmlFile'], $xmlFileValue);
        return file_exists(basePath . '/' . self::$xmlobj[$XMLTag]['xmlFile']);
    }

    /**
    * Einen XML Knoten l�schen
    *
    * @return boolean
    */
    public static function deleteXMLnode($XMLTag, $xmlpath, $xmlnode)
    {
        if(empty($XMLTag) || empty($xmlpath) || empty($xmlnode)) return false;
        if(array_key_exists($XMLTag,self::$xmlobj))
        {
            $parent = self::getXMLvalue($XMLTag, $xmlpath);
            unset($parent->$xmlnode);
            return true;
        }

        return false;
    }

    /**
    * Einen XML Knoten Attribut l�schen
    *
    * @return boolean
    */
    public static function deleteXMLattribut($XMLTag, $xmlpath, $key, $value )
    {
        if(empty($XMLTag) || empty($xmlpath) || empty($key) || empty($value)) return false;
        if(array_key_exists($XMLTag,self::$xmlobj))
        {
            $nodes = self::getXMLvalue($XMLTag, $xmlpath);
            foreach($nodes as $node)
            {
                if((string)$node->attributes()->$key==$value)
                {
                    unset($node[0]);
                    break;
                }
            }

            return true;
        }

        return false;
    }

    /**
     * Einen XML Boolean umwandeln
     *
     * @return boolean
     */
    public static function bool($value)
    { return ($value == 'true' ? true : false); }

    /**
     * Speichert die XML Basis im Memory Cache zwischen
     */
    public static function save()
    {
        if(Cache::is_mem())
        {
            if(Cache::check('XML-Core-Parser'))
            {
                $mem_xml_save = array(); $i=0;
                foreach(self::$xmlobj as $key => $obj)
                {
                    if(!empty($obj['xmlStream']))
                    {
                        $mem_xml_save[$key] = bin2hex($obj['xmlStream']);
                        $i++;
                    }
                }

                Cache::set('XML-Core-Parser',array_to_string($mem_xml_save),4);
                if(show_xml) DebugConsole::insert_successful('xml::save()', 'XML Database "'.$i.'" records saved');
            }

            unset($mem_xml_save);
        }
    }

    /**
     * Laden der XML Basis aus dem Memory Cache
     */
    public static function load()
    {
        if(Cache::is_mem())
        {
            if(Cache::check('XML-Core-Parser')) return; $i=0;
            foreach(string_to_array(Cache::get('XML-Core-Parser')) as $key => $hex)
            {
                if(empty($key) || empty($hex)) continue;
                if(!array_key_exists($key,self::$xmlobj))
                {
                    self::$xmlobj[$key]['xmlFile'] = '';
                    self::$xmlobj[$key]['xmlStream'] = hextobin($hex);
                    self::LoadXMLStream($key); $i++;
                }
            }

            unset($hex,$key);
            if(show_xml) DebugConsole::insert_successful('xml::load()', 'XML Database "'.$i.'" records loaded');
        }
    }
}

/**
 * Language loader class
 */
class language
{
    private static $language = '';
    private static $languages = '';
    private static $language_files = array();
    private static $user_agent = '';

    /**
     * Erkenne die Sprache des Users am Browser
     */
    private static function detect_language()
    {
        if(isset($_SERVER['HTTP_ACCEPT_LANGUAGE']))
            self::$language = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'],0,2);
        else if ($_SERVER['HTTP_USER_AGENT'])
        {
            self::$user_agent = explode(";", $_SERVER['HTTP_USER_AGENT']);
            for ($i=0; $i < sizeof(self::$user_agent); $i++)
            {
                self::$languages = explode("-",self::$user_agent[$i]);
                if (sizeof(self::$languages) == 2)
                {
                    if (strlen(trim(self::$languages[0])) == 2)
                    {
                        $size = sizeof(self::$language);
                        self::$language[$size]=trim(self::$languages[0]);
                    }
                }
            }
        }
        else
            self::$language = settings('language');
    }

    private static function check_language($lng='')
    { return(file_exists(basePath.'/inc/lang/languages/'.$lng.'.php')); }

    public static function set_language($language = '')
    {
        if ($language != '')
            $_SESSION['language'] = $language;
        else
        {
            self::detect_language();
            $_SESSION['language'] = (cookie::get('language') ? cookie::get('language') : self::$language);
        }

        if(isset($_SESSION['language']))
        {
            if (self::check_language($_SESSION['language']))
            {
                self::$language = $_SESSION['language'];
                cookie::put('language', self::$language);
            }
            else
            {
                self::$language = settings('language');
                cookie::put('language', self::$language);
            }
        }
        else
        {
            self::$language = settings('language');
            cookie::put('language', self::$language);
        }

        cookie::save(); // Save Cookie
    }

    public static function run_language($language='')
    {
        if(!count(self::$language_files=get_files(basePath.'/inc/lang/languages/',false,true,array('php'))))
            die('No language files found in "inc/lang/languages/*"!');

        self::set_language($language);
        require_once(basePath."/inc/lang/global.php");
        require_once(basePath.'/inc/lang/languages/'.self::$language.'.php');
        header("Content-type: text/html; charset="._charset);
    }

    public static function get_language()
    { return self::$language; }

    public static function get_language_tag()
    {
        switch (self::$language) {
            case 'deutsch': return 'de';
            default: return 'en';
        }
    }

    public static function get_language_files()
    { return self::$language_files; }

    public static function get_meta()
    {
        $meta='';
        if(count(self::$language_files) >= 1)
        {
            foreach(self::$language_files as $file)
            {
                $file = explode('.',$file);
                $file = substr($file[0], 0, 2);
                $meta .= '    <meta http-equiv="Content-Language" content="'.$file.'"/>'."\n";
            }
        }

        return substr($meta, 0, -1);
    }

    public static function get_menu($lang='')
    {
        $options = '';
        if(count(self::$language_files) >= 1)
        {
            foreach(self::$language_files as $file)
            {
                $file = explode('.',$file);
                $firstString = substr($file[0], 0,1);
                $lang_name = strtoupper($firstString).substr($file[0], 1);
                $options .= '<option value="'.$file[0].'" '.($file[0] == $lang ? 'selected="selected"' : '').'> '.$lang_name.'</option>';
            }
        }

        return '<select id="language" name="language" class="dropdown">'.'<option value="default" '.( $lang == 'default' ? 'selected="selected"' : '').'> '._default.'</option>'.$options.'</select>';
    }

    /* Converts place holder into language*/
    public static function display($lang='')
    {
        if(defined($lang))
            return constant($lang);

        return $lang;
    }
}

/**
 * Erkennt ob das ZendFramework vorhanden ist
 *
 * @return boolean
 */
function ZendFramework()
{
    if(!zend_support) return false;
    if($zendLoaderPresent = @fopen('Zend/Loader/Autoloader.php', 'r', true))
    { @fclose($zendLoaderPresent); return true; }
    return false;
}

/**
 * Funktion um zusätzliche Klassen zu laden
 * Added by DZCP-Extended Edition
 */
$additional_kernel = get_files(basePath.'/inc/additional-kernel/',false,true,array('php'),"#^class.(.*?).inc#");
if(count($additional_kernel) >= 1 && !empty($additional_kernel))
{ foreach($additional_kernel as $function) { if(file_exists(basePath.'/inc/additional-kernel/'.$function)) { include_once(basePath.'/inc/additional-kernel/'.$function); } } }
unset($additional_kernel,$function);

/**
 * Funktion um fehlende Klassen zu laden
 * Added by DZCP-Extended Edition
 */
spl_autoload_register(function ($class)
{
    if(file_exists(basePath. "/inc/additional-kernel/".$class.".php"))
    { include_once(basePath. "/inc/additional-kernel/".$class.".php"); }
});