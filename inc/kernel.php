<?php
/**
 * <DZCP-Extended Edition>
 * @package: DZCP-Extended Edition
 * @author: DZCP Developer Team || Hammermaps.de Developer Team
 * @link: http://www.dzcp.de || http://www.hammermaps.de
 */

/**
* Eine Liste der Dateien oder Verzeichnisse zusammenstellen, die sich im angegebenen Ordner befinden.
* Updated for DZCP-Extended Edition
*
* @return array
*/
function get_files($dir=null,$only_dir=false,$only_files=false,$file_ext=array(),$dir_bl=array())
{
    $files = array();
    if($handle = @opendir($dir))
    {
        if($only_dir) ## Ordner ##
        {
            while(false !== ($file = readdir($handle)))
            {
                if($file != '.' && $file != '..' && !is_file($dir.'/'.$file))
                {
                    if(count($dir_bl) == 0)
                        $files[] = $file;
                    else
                    {
                        if(!in_array($file, $dir_bl))
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
                    if(count($file_ext) == 0)
                        $files[] = $file;
                    else
                    {
                        ## Extension Filter ##
                        $exp_string = explode(".", $file);
                        if(in_array(strtolower($exp_string[1]), $file_ext))
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
                    if(count($file_ext) == 0)
                        $files[] = $file;
                    else
                    {
                        ## Extension Filter ##
                        $exp_string = explode(".", $file);
                        if(in_array(strtolower($exp_string[1]), $file_ext))
                            $files[] = $file;
                    }
                }
                else
                {
                    if($file != '.' && $file != '..')
                        $files[] = $file;
                }
            } //while end
        }

        if(!count($files))
            return false;

        @closedir($handle);
        return $files;
    }
    else
        return false;
}

/**
* Erkennen welche PHP Version ausgeführt wird.
* Added by DZCP-Extended Edition
*
* @return boolean
*/
function is_php($version='5.2.0')
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
            "apache2handler" => 'Apache 2: Handler', "cgi" => 'CGI', "cgi-fcgi" => 'Fast-CGI',
            "cli" => 'CLI', "isapi" => 'ISAPI', "nsapi" => 'NSAPI');
    return(empty($sapi_types[substr($sapi_type, 0, 3)]) ? substr($sapi_type, 0, 3) : $sapi_types[substr($sapi_type, 0, 3)]);
}

/**
 * Funktion um eine Datei im Web auf Existenz zu prüfen
 * Updated for DZCP-Extended Edition
 *
 * @return mixed
 **/
function fileExists($url)
{
    if(!fsockopen_support())
        return false;

    $url_p = @parse_url($url);
    $host = $url_p['host'];
    $port = isset($url_p['port']) ? $url_p['port'] : 80;
    unset($url_p);

    if(!ping_port($host,$port,2))
        return false;

    unset($host,$port);

    if(!$content = @file_get_contents($url))
        return false;

    return trim($content);
}

/**
 * Funktion um notige Erweiterungen zu prufen
 * Added by DZCP-Extended Edition
 *
 * @return boolean
 **/
function fsockopen_support()
{
    if(!function_exists('fsockopen'))
        return false;

    if(!function_exists("fopen"))
        return false;

    if(ini_get('allow_url_fopen') != 1)
        return false;

    if(strpos(ini_get('disable_functions'),'fsockopen') || strpos(ini_get('disable_functions'),'file_get_contents') || strpos(ini_get('disable_functions'),'fopen'))
        return false;

    return true;
}

/**
 * Pingt einen Server Port
 * Added by DZCP-Extended Edition
 *
 * @return boolean
 **/
function ping_port($ip='0.0.0.0',$port=0000,$timeout=2)
{
    if(!fsockopen_support())
        return false;

    if(($fp = @fsockopen($ip, $port, $errno, $errstr, $timeout)))
    {
        unset($ip,$port,$errno,$errstr,$timeout);
        @fclose($fp);
        return true;
    }

    return false;
}

/**
 * Gibt die IP des Besuchers / Users zurück
 * Added by DZCP-Extended Edition
 *
 * @return String
 */
function visitorIp()
{
    $TheIp=$_SERVER['REMOTE_ADDR'];
    if(isset($_SERVER['HTTP_X_FORWARDED_FOR']) && !empty($_SERVER['HTTP_X_FORWARDED_FOR']))
    {
        ## IP auf Gültigkeit prüfen ##
        $TheIp_XF=$_SERVER['HTTP_X_FORWARDED_FOR'];
        $TheIp_X = explode('.',$TheIp_XF);
        if(count($TheIp_X) == 4 && $TheIp_X[0]<=255 && $TheIp_X[1]<=255 && $TheIp_X[2]<=255 && $TheIp_X[3]<=255 && preg_match("!^([0-9]{1,3})\.([0-9]{1,3})\.([0-9]{1,3})\.([0-9]{1,3})$!",$TheIp_XF))
            $TheIp = $TheIp_XF;
    }

    $TheIp_X = explode('.',$TheIp);
    if(count($TheIp_X) == 4 && $TheIp_X[0]<=255 && $TheIp_X[1]<=255 && $TheIp_X[2]<=255 && $TheIp_X[3]<=255 && preg_match("!^([0-9]{1,3})\.([0-9]{1,3})\.([0-9]{1,3})\.([0-9]{1,3})$!",$TheIp))
        return trim($TheIp);

    return '0.0.0.0';
}

/**
 * Filtert Platzhalter
 *
 * @return string
 */
function pholderreplace($pholder)
{
    $search = array('@<script[^>]*?>.*?</script>@si','@<style[^>]*?>.*?</style>@siU','@<[\/\!][^<>]*?>@si',/*'@<[\/\!]*?[^<>]*?>@si',*/'@<![\s\S]*?--[ \t\n\r]*>@');

    //Replace
    $pholder = preg_replace("#<script(.*?)</script>#is","",$pholder);
    $pholder = preg_replace("#<style(.*?)</style>#is","",$pholder);
    $pholder = preg_replace($search, '', $pholder);
    $pholder = str_replace(" ","",$pholder);
    $pholder = preg_replace("#[0-9]#is","",$pholder);
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
* Updated for DZCP-Extended Edition
*
* @return string
*/
function show($tpl="", $array=array(), $array_lang_constant=array())
{
    global $tmpdir,$chkMe;

    if(!empty($tpl) && $tpl != null)
    {
        ## DZCP-Extended Edition START ##
        $template = $_SESSION['installer'] ? basePath."/_installer/html/".$tpl : basePath."/inc/_templates_/".$tmpdir."/".$tpl;
        $template_additional = $_SESSION['installer'] ? false : basePath."/inc/additional-tpl/".$tmpdir."/".$tpl;
        $array['dir'] = $_SESSION['installer'] ? "html": '../inc/_templates_/'.$tmpdir;

        if($template_additional != false && file_exists($template_additional.".html"))
            $tpl = file_get_contents($template_additional.".html");
        else if($template_additional != false && ($tpli=API_CORE::load_additional_tpl($tpl)) && !file_exists($template_additional.".html"))
            $tpl = $tpli;
        else if(allow_additional && file_exists($template.".html") && !file_exists($template_additional.".html"))
            $tpl = file_get_contents($template.".html");
        ## DZCP-Extended Edition END ##

        //put placeholders in array
        $pholder = explode("^",pholderreplace($tpl));
        for($i=0;$i<=count($pholder)-1;$i++)
        {
            if(array_key_exists($pholder[$i],$array))
                continue;

            if(!strstr($pholder[$i], 'lang_'))
                continue;

            if(defined(substr($pholder[$i], 4)))
                $array[$pholder[$i]] = (count($array_lang_constant) >= 1 ? show(constant(substr($pholder[$i], 4)),$array_lang_constant) : constant(substr($pholder[$i], 4)));
        }

        unset($pholder);

        ## DZCP-Extended Edition START ##
        $tpl = ($chkMe == 'unlogged' ? preg_replace("|<logged_in>.*?</logged_in>|is", "", $tpl) : preg_replace("|<logged_out>.*?</logged_out>|is", "", $tpl));
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
 * Datenbank Connect
 * Todo: Code überarbeiten, Update auf MySQLi
 **/
if(!$_SESSION['installer'] || $_SESSION['db_install']) //For Installer
{
    if(!isset($db)) //tinymce fix
        require_once(basePath."/inc/config.php");

    if(!empty($db['host']) && !empty($db['user']) && !empty($db['pass']) && !empty($db['db']))
    {
        if(!$msql = @mysql_connect($db['host'], $db['user'], $db['pass']))
        {
            echo "<b>Fehler beim Zugriff auf die Datenbank!<p>";
            print_db_error(false);
        }

        if(!@mysql_select_db($db['db'],$msql))
        {
            echo "<b>Die angegebene Datenbank <i>".$db['db']."</i> existiert nicht!<p>";
            print_db_error(false);
        }
    }
    else
    {
        echo '<html><head><meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" /></head><body><b>';
        if(empty($db['host']))
            echo "Das MySQL-Hostname fehlt in der Configuration!<p>";

        if(empty($db['user']))
            echo "Der MySQL-Username fehlt in der Configuration!<p>";

        if(empty($db['pass']))
            echo "Das MySQL-Passwort fehlt in der Configuration!<p>";

        if(empty($db['db']))
            echo "Der MySQL-Datenbankname fehlt in der Configuration!<p>";

        die("Bitte überprüfe deine mysql.php!</b></body></html>");
    }
}

/**
 * Gibt requires Fehler aus und stoppt die Ausführung des CMS
 * Added by DZCP-Extended Edition
 **/
function check_of_php52()
{
    if(!is_php('5.2.0'))
    {
        die('<b>Requires failed:</b><br /><ul>'.
                '<li><b>The DZCP-Extended Edition requires PHP 5.2.0 or newer.</b>'.
                '<li><b>Die DZCP-Extended Edition ben&ouml;tigt PHP 5.2.0 oder neuer.</b>');
    }
}

check_of_php52();

/**
 * Gibt Datenbank Fehler aus und stoppt die Ausführung des CMS
 * Added by DZCP-Extended Edition
 **/
function print_db_error($query=false)
{
    global $prefix;
    die('<b>MySQL-Query failed:</b><br /><ul>'.
            '<li><b>ErrorNo</b> = '.str_replace($prefix,'',mysql_errno()).
            '<li><b>Error</b>   = '.str_replace($prefix,'',mysql_error()).
            ($query ? '<li><b>Query</b>   = '.str_replace($prefix,'',$query).'</ul>' : ''));
}

/**
 * Datenbank Query senden
 * Updated for DZCP-Extended Edition
 * Todo: Code überarbeiten, Update auf MySQLi + SQL-Inception Schutz
 *
 * @return resource/array/int
 **/
function db($query,$rows=false,$fetch=false,$clear_output=false)
{
    if(!$qry = mysql_query($query))
        print_db_error($query);

    if($fetch && $rows)
        return mysql_fetch_array($qry);
    else if($fetch && !$rows)
        return mysql_fetch_assoc($qry);
    else if(!$fetch && $rows)
        return mysql_num_rows($qry);
    else if(!$clear_output)
        return $qry;
    else
        unset($qry); //clear mem
}

/**
 * Informationen über die MySQL-Datenbank abrufen
 * Todo: Code überarbeiten, Update auf MySQLi
 *
 * @return resource
 **/
function dbinfo()
{
    $info = array(); $sum = 0; $rows = 0; $entrys = 0;
    $qry = db("Show table status");
    while($data = _fetch($qry))
    {
        $allRows = $data["Rows"];
        $dataLength  = $data["Data_length"];
        $indexLength = $data["Index_length"];

        $tableSum = $dataLength + $indexLength;

        $sum += $tableSum;
        $rows += $allRows;
        $entrys ++;
    } //while end

    $info["entrys"] = $entrys;
    $info["rows"] = $rows;
    $info["size"] = @round($sum/1048576,2);

    return $info;
}

/**
 * Liefert die Anzahl der Zeilen im Ergebnis
 * Todo: Code überarbeiten, Update auf MySQLi
 *
 * @return integer
 **/
function _rows($rows)
{
    return mysql_num_rows($rows);
}

/**
 * Liefert einen Datensatz als assoziatives Array
 * Todo: Code überarbeiten, Update auf MySQLi
 *
 * @return array
 **/
function _fetch($fetch)
{
    return mysql_fetch_assoc($fetch);
}

/**
 * Funktion um diverse Dinge aus Tabellen auszaehlen zu lassen
 * Todo: Code überarbeiten, Update auf MySQLi
 *
 * @return integer
 **/
function cnt($count, $where = "", $what = "id")
{
    $cnt = db("SELECT COUNT(".$what.") AS num FROM ".$count." ".$where,false,true);
    return $cnt['num'];
}

/**
 * Funktion um diverse Dinge aus Tabellen zusammenzaehlen zu lassen
 * Todo: Code überarbeiten, Update auf MySQLi
 *
 * @return integer
 **/
function sum($db, $where = "", $what)
{
    $cnt = db("SELECT SUM(".$what.") AS num FROM ".$db.$where,false,true);
    return convert::ToInt($cnt['num']);
}

/**
 * Funktion um CMS Settings aus der Datenbank auszulesen
 * Updated for DZCP-Extended Edition
 * Todo: Code überarbeiten, Update auf MySQLi
 *
 * @return mixed/array
 **/
function settings($what)
{
    global $db;
    if(is_array($what))
    {
        $sql='';
        foreach($what as $qy)
        { $sql .= $qy.", "; }
        $sql = substr($sql, 0, -2);
        return db("SELECT ".$sql." FROM `".$db['settings']."`",false,true);
    }
    else
    {
        $get = db("SELECT ".$what." FROM `".$db['settings']."`",false,true);
        return $get[$what];
    }
}

/**
 * Funktion um die CMS Config aus der Datenbank auszulesen
 * Updated for DZCP-Extended Edition
 * Todo: Code überarbeiten, Update auf MySQLi
 *
 * @return mixed/array
 **/
function config($what)
{
    global $db;
    if(is_array($what))
    {
        $sql='';
        foreach($what as $qy)
        { $sql .= $qy.", "; }
        $sql = substr($sql, 0, -2);
        return db("SELECT ".$sql." FROM `".$db['config']."`",false,true);
    }
    else
    {
        $get = db("SELECT ".$what." FROM `".$db['config']."`",false,true);
        return $get[$what];
    }
}

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
 * Funktion zum schreiben der Adminlogs
 * Added by DZCP-Extended Edition
 */
function wire_ipcheck($what='')
{
    global $db;
    db("INSERT INTO ".$db['ipcheck']." SET `ip` = '".visitorIp()."',`what` = '".$what."',`time` = '".time()."'");
}

/**
 * Checkt versch. Dinge anhand der Hostmaske eines Users
 * Updated for DZCP-Extended Edition
 *
* @return boolean
 */
function ipcheck($what,$time = "")
{
    global $db;
    $get = db("SELECT time,what FROM ".$db['ipcheck']." WHERE what = '".$what."' AND ip = '".visitorIp()."' ORDER BY time DESC",false,true);
    if(preg_match("#vid#", $get['what']))
        return true;
    else
    {
        if($get['time']+$time<time())
            db("DELETE FROM ".$db['ipcheck']." WHERE what = '".$what."' AND ip = '".visitorIp()."' AND time+'".$time."'<'".time()."'");

        if($get['time']+$time>time())
            return true;
        else
            return false;
    }
}

/**
 * Wandelt einen Boolean zu einem Boolean-String um.
 * Added by DZCP-Extended Edition
 *
 * @return String
 */
function Bool_to_StringConverter($bool)
{
    return ($bool ? "+#bool#+" : "-#bool#-");
}

/**
 * Wandelt einen Boolean-String zu Boolean um.
 * Added by DZCP-Extended Edition
 *
 * @return boolean
 */
function String_to_boolConverter($bool_coded)
{
    return ($bool_coded == "+#bool#+" ? true : false);
}

/**
 * Wandelt einen Array-String zu einem Array um.
 * Added by DZCP-Extended Edition
 *
 * @return array
 */
function string_to_array($str,$counter=1)
{
    $arr=array(); $temparr=array();
    $temparr=explode("|$counter|",$str);
    foreach( $temparr as $key => $value )
    {
        $t1=explode("=$counter>",$value);
        $kk=$t1[0];

        if($t1[1] == "+#bool#+" or $t1[1] == "-#bool#-")
            $vv=String_to_boolConverter($t1[1]);
        else
            $vv=convert::UTF8_Reverse($t1[1]);

        if(isset($t1[2]) && $t1[2]=="~Y~")
            $arr[$kk]=string_to_array($vv,($counter+1));
        else
            $arr[$kk]=$vv;
    }

    return $arr;
}

/**
 * Wandelt einen Array zu einem Array-String um.
 * Added by DZCP-Extended Edition
 *
 * @return String
 */
function array_to_string($arr,$counter=1)
{
    $str="";
    foreach( $arr as $key => $value)
    {
        if(is_array($value))
            $str.= $key."=$counter>".array_to_string($value,($counter+1))."=".$counter.">~Y~|".$counter."|";
        else
        {
            if(is_bool($value))
                $value = Bool_to_StringConverter($value);

            $str.=$key."=$counter>".convert::UTF8($value)."|$counter|";
        }
    }

    return rtrim($str,"|$counter|");
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
 * Erkennt Spider und Crawler um sie von der Besucherstatistik auszuschliessen.
 *
 * @return boolean
 */
function isSpider()
{
    $uagent = $_SERVER['HTTP_USER_AGENT'];
    $ex = explode("\n", file_get_contents(basePath.'/inc/_spiders.txt'));
    for($i=0;$i<=count($ex)-1;$i++)
    {
        if(stristr($uagent, trim($ex[$i])))
            return true;
    }

    return false;
}

/**
 * Funktion um Sonderzeichen zu konvertieren
 *
 * @return string
 */
function spChars($txt)
{
    $search = array("Ä", "Ö", "Ü", "ä", "ö", "ü", "ß", "€");
    $replace = array("&Auml;", "&Ouml;", "&Uuml;", "&auml;", "&ouml;", "&uuml;", "&szlig;", "&euro;");
    return str_replace($search, $replace, $txt);
}

/**
 * Funktion um eine Variable prüfung in einem Array durchzuführen
 * Added by DZCP-Extended Edition
 *
 * @return boolean
 */
function array_var_exists($var,$search)
{ foreach($search as $key => $var_) { if($var_==$var) return true; } return false; }

/**
 * Funktion um Passwörter in einen Hash umzurechnen
 * Added by DZCP-Extended Edition
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
    switch($metode)
    {
        case 1: return sha1($pass_key,false); break;
        case 2: return hash('sha256', $pass_key,false); break;
        case 3: return hash('sha512', $pass_key,false); break;
        default: return md5($pass_key,false); break;
    }
}

/**
 * Funktion um Sekunden in Tage:Stinden:Minuten:Sekunden Formate umzuwandeln
 * Added by DZCP-Extended Edition
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
 * Added by DZCP-Extended Edition
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
 * Added by DZCP-Extended Edition
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
 * Added by DZCP-Extended Edition
 *
 * @return int
 */
function remote_filesize($url)
{
    global $cacheTag;
    if(Cache::check($cacheTag,'remote_filesize_'.md5($url)))
    {
        $head = ""; $url_p = parse_url($url); $host = $url_p["host"];
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

        Cache::set($cacheTag,'remote_filesize_'.md5($url), $return, 28800); //Update alle 8h
        return $return;
    }

    return Cache::get($cacheTag,'remote_filesize_'.md5($url));
}


/**
 * Funktion um einen Binärstring zu Dekodieren.
 * Added by DZCP-Extended Edition
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
 * Funktion um fehlende Klassen zu laden
 * Added by DZCP-Extended Edition
 */
spl_autoload_register(function ($class)
{
    if(file_exists(basePath. "/inc/additional-kernel/".$class.".php"))
    { include_once(basePath. "/inc/additional-kernel/".$class.".php"); }
    else { trigger_error("Could not load class '".$class."' from file 'inc/additional-kernel/".$class.".php'<p>Add '".$class.".php' with 'class ".$class."' to 'inc/additional-kernel/'<p>", E_USER_WARNING); }
});

#############################################
#################### XML ####################
#############################################
class xml // Class by DZCP-Extended Edition
{
    private static $xmlobj = array(array()); //XML

    /**
    * XML Datei Laden
    */
    public static function openXMLfile($XMLTag,$XMLFile,$oneModule=false)
    {
        if(!array_key_exists($XMLTag,self::$xmlobj))
        {
            self::$xmlobj[$XMLTag]['xmlFile'] = $XMLFile;

            if(!$oneModule)
            {
                if(!file_exists(basePath . '/' . $XMLFile))
                    file_put_contents(basePath . '/' . $XMLFile, '<?xml version="1.0"?><'.$XMLTag.'></'.$XMLTag.'>');
            }

            self::$xmlobj[$XMLTag]['objekt'] = simplexml_load_file(basePath . '/' . $XMLFile);

            if(self::$xmlobj[$XMLTag]['objekt'] != false)
                return true;
            else
                return false;
        }
        else
            return true;
    }

    /**
    * XML Wert auslesen
    *
    * @return XMLObj / boolean
    */
    public static function getXMLvalue($XMLTag, $xmlpath)
    {
        if(array_key_exists($XMLTag,self::$xmlobj))
        {
            $xmlobj = self::$xmlobj[$XMLTag]['objekt']->xpath($xmlpath);
            return ($xmlobj) ? $xmlobj[0] : false;
        }
        else
            return false;
    }

    /**
    * XML Werte ändern
    *
    * @return boolean
     */
    public static function changeXMLvalue($XMLTag, $xmlpath, $xmlnode, $xmlvalue='')
    {
        if(array_key_exists($XMLTag,self::$xmlobj))
        {
            $xmlobj = self::$xmlobj[$XMLTag]['objekt']->xpath($xmlpath);
            $xmlobj[0]->{$xmlnode} = htmlspecialchars($xmlvalue);
            return true;
        }
        else
            return false;
    }

    /**
    * Einen neuen XML Knoten hinzufügen
    *
    * @return boolean
    */
    public static function createXMLnode($XMLTag, $xmlpath, $xmlnode, $attributes=array(), $text='')
    {
        if(array_key_exists($XMLTag,self::$xmlobj))
        {
             $xmlobj = self::$xmlobj[$XMLTag]['objekt']->xpath($xmlpath);
             $xmlobj2 = $xmlobj[0]->addChild($xmlnode, htmlspecialchars($text));
             foreach($attributes as $attr => $value)
                 $xmlobj2->addAttribute($attr, htmlspecialchars($value));
             return true;
        }
        else
            return false;
    }

    /**
    *  XML-Datei speichern
    *
    * @return boolean
    */
    public static function saveXMLfile($XMLTag)
    {
        if(!array_key_exists($XMLTag,self::$xmlobj))
        {
            trigger_error('Die Datei "'.self::$xmlobj[$XMLTag]['xmlFile'].'" wurde nie geöffnet.');
            return false;
        }

        $xmlFileValue = self::$xmlobj[$XMLTag]['objekt']->asXML();
        file_put_contents(basePath . '/' . self::$xmlobj[$XMLTag]['xmlFile'], $xmlFileValue);
        return true;
    }

    /**
    * Einen XML Knoten löschen
    *
    * @return boolean
    */
    public static function deleteXMLnode($XMLTag, $xmlpath, $xmlnode)
    {
        if(array_key_exists($XMLTag,self::$xmlobj))
        {
            $parent = self::getXMLvalue($XMLTag, $xmlpath);
            unset($parent->$xmlnode);
            return true;
        }
        else
            return false;
    }

    /**
    * Einen XML Knoten Attribut löschen
    *
    * @return boolean
    */
    public static function deleteXMLattribut($XMLTag, $xmlpath, $key, $value )
    {
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
        else
            return false;
    }

    /**
     * Einen XML Boolean umwandeln
     *
     * @return boolean
     */
    public static function bool($value)
    { return ($value == 'true' ? true : false); }
}

#############################################
############### TypeConverter ###############
#############################################
class convert // Class by DZCP-Extended Edition
{
    public static final function ToString($input)
    { return (string)$input; }

    public static final function BoolToInt($input)
    { return ($input == true ? 1 : 0); }

    public static final function IntToBool($input)
    { return ($input == 0 ? false : true); }

    public static final function ToInt($input)
    { return (int)$input; }

    public static final function UTF8($input)
    { return self::ToString(utf8_encode($input)); }

    public static final function UTF8_Reverse($input)
    { return utf8_decode($input); }

    public static final function ToHTML($input)
    { return htmlentities($input, ENT_COMPAT | ENT_HTML5, _charset); }
}
?>
