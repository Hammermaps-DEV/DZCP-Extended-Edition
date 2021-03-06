<?php
/**
 * <DZCP-Extended Edition>
 * @package: DZCP-Extended Edition
 * @author: DZCP Developer Team || Hammermaps.de Developer Team
 * @link: http://www.dzcp.de || http://www.hammermaps.de
 */

if(_adminMenu != 'true') exit();

    $PhpInfo = parsePHPInfo();
    $support  = "#####################\r\n";
    $support .= "Support Informationen\r\n";
    $support .= "#####################\r\n";

    $support .= "\r\n";
    $support .= "#####################\r\n";
    $support .= "DZCP Allgemein \r\n";
    $support .= "#####################\r\n";
    $support .= "DZCP Version: "._version."\r\n";
    $support .= "DZCP Release: "._release."\r\n";
    $support .= "DZCP Build: "._build."\r\n";
    $support .= "DZCP Edition: "._edition."\r\n";
    $support .= "DZCP Datenbank: Rev. V".settings('db_version')."\r\n";
    $support .= "DZCP Template: ".$tmpdir."\r\n";
    $support .= "\r\n";
    $support .= "#####################\r\n";
    $support .= "DZCP API \r\n";
    $support .= "#####################\r\n";
    $support .= "DZCP API: ".(modapi_enabled ? 'Aktiviert' : 'Deaktiviert')."\r\n";
    $support .= "DZCP API: V".API::$version." - ".API::$time."\r\n";
    $support .= "DZCP API Events: ".(modapi_events_enabled ? 'Aktiviert' : 'Deaktiviert')."\r\n";
    $support .= "DZCP API Events: V".API_EVENTS::$version." - ".API_EVENTS::$time."\r\n";
    $support .= "\r\n";

    if(count(API_CORE::$addon_index) >= 1)
    {
        $support .= "#####################\r\n";
        $support .= "DZCP Addons \r\n";
        $support .= "#####################\r\n";
        foreach(API_CORE::$addon_index as $addon)
        {
            $support .= "===========================\r\n";
            $support .= "Addon: ".$addon['xml']['xml_addon_name']."\r\n";
            $support .= "Autor: ".$addon['xml']['xml_addon_autor']."\r\n";
            $support .= "Homepage: ".$addon['xml']['xml_addon_autor_url']."\r\n";
            $support .= "E-Mail: ".$addon['xml']['xml_addon_autor_mail']."\r\n";
            $support .= "Version: ".$addon['xml']['xml_addon_version']."\r\n";
            $support .= "===========================\r\n\r\n";
        }
    }

    $files_templates = get_files(basePath.'/inc/_templates_/',true,false);
    if(count($files_templates) >= 1)
    {
        $support .= "#####################\r\n";
        $support .= "DZCP Templates \r\n";
        $support .= "#####################\r\n";
        foreach($files_templates as $template)
        {
            $template_array = convert::objectToArray(xml::getXMLvalue('template_'.$template,'/template'));
            $support .= "===========================\r\n";
            $support .= "Ordner: ".$template."\r\n";
            $support .= "Name: ".$template_array['Name']."\r\n";
            $support .= "Autor: ".$template_array['autor']."\r\n";
            $support .= "Homepage: ".$template_array['autor_url']."\r\n";
            $support .= "E-Mail: ".$template_array['autor_mail']."\r\n";
            $support .= "Version: ".$template_array['version']."\r\n";
            $support .= "Mobile Support: ".(xml::bool($template_array['UseMobile']) ? 'Ja' : 'Nein')."\r\n";
            $support .= "Nur Mobile: ".(xml::bool($template_array['OnlyMobile']) ? 'Ja' : 'Nein')."\r\n";
            $support .= "===========================\r\n\r\n";
        }
    }
    unset($files_templates,$template_array);

    $support .= "#####################\r\n";
    $support .= "Domain & User\r\n";
    $support .= "#####################\r\n";
    $support .= "Domain: http://".$_SERVER['HTTP_HOST'].str_replace('/admin','/',dirname($_SERVER['PHP_SELF']))."\r\n";
    $support .= "System/Browser: ".$_SERVER['HTTP_USER_AGENT']."\r\n";
    $support .= "\r\n";

    $support .= "#####################\r\n";
    $support .= "Server Versionen\r\n";
    $support .= "#####################\r\n";
    $support .= "GameQ: ".GameQ::VERSION."\r\n";
    $support .= "Server OS: ".@php_uname()."\r\n";
    $support .= "Webserver: ".(array_key_exists('apache2handler', $PhpInfo) ? (array_key_exists('Apache Version', $PhpInfo['apache2handler']) ? $PhpInfo['apache2handler']['Apache Version'] : 'PHP l&auml;uft als CGI <Keine Info>' ) : 'PHP l&auml;uft als CGI <Keine Info>')."\r\n";
    $support .= "PHP-Version: ".phpversion()." (".php_sapi_type().")"."\r\n";
    $support .= "MySQL-Server Version: ".database::version()."\r\n";
    $support .= "MySQL-Erweiterung: MySQLi\r\n";
    $support .= "MySQL-Client Version: ".$PhpInfo['mysql']['Client API version']."\r\n";
    $support .= "MySQL-Persistente Datenbankverbindung: ".(runtime_sql_persistconns ? 'Aktiviert' : 'Deaktiviert')."\r\n";

    if(function_exists("zend_version"))
        $support .= "Zend-Engine: ".zend_version()."\r\n";

    $support .= "\r\n";

    $support .= "#####################\r\n";
    $support .= "Socket-Verbindungen \r\n";
    $support .= "#####################\r\n";
    $support .= "PHP fsockopen: ".(function_exists("fsockopen") ? 'Aktiviert' : 'Deaktiviert')."\r\n";
    $support .= "PHP allow_url_fopen: ".($PhpInfo['Core']['allow_url_fopen'][0] == 'On' ? 'Aktiviert' : 'Deaktiviert')."\r\n";
    $support .= "PHP Sockets: ".(function_exists("socket_create") && $PhpInfo['sockets']['Sockets Support'] == "enabled" ? 'Aktiviert' : 'Deaktiviert')."\r\n";
    $support .= "\r\n";

    $support .= "#####################\r\n";
    $support .= "Cache Support \r\n";
    $support .= "#####################\r\n";
    $support .= Cache::get_cache_support();
    $support .= "Aktuell verwendet: ".(Cache::getType($cache_engine))."\r\n";
    $support .= "\r\n";

    $support .= "#####################\r\n";
    $support .= "Servereinstellungen\r\n";
    $support .= "#####################\r\n";

    if(!is_php('5.4.0')) //Removed in PHP 5.4.x
    {
        $support .= "register_globals: ".$PhpInfo['Core']['register_globals'][0]."\r\n";
        $support .= "Save Mode: ".$PhpInfo['Core']['safe_mode'][0]."\r\n";
        if($PhpInfo['Core']['safe_mode'][0] == 'on')
        {
            $support .= "safe_mode_exec_dir: ".$PhpInfo['Core']['safe_mode_exec_dir'][0]."\r\n";
            $support .= "safe_mode_gid: ".$PhpInfo['Core']['safe_mode_gid'][0]."\r\n";
            $support .= "safe_mode_include_dir: ".$PhpInfo['Core']['safe_mode_include_dir'][0]."\r\n";
        }
    }

    $support .= "open_basedir: ".$PhpInfo['Core']['open_basedir'][0]."\r\n";
    $support .= "GD-Version: ".$PhpInfo['gd']['GD Version']."\r\n";
    $support .= "PHP-Memory Limit: ".$PhpInfo['Core']['memory_limit'][0]."\r\n";
    $support .= "Imagick Erweiterung: ".(extension_loaded('imagick')==true? 'Aktiviert' : 'Deaktiviert')."\r\n";
    $support .= "Verwendet Imagick: ".(use_imagick==true && extension_loaded('imagick') ? 'Ja' : 'Nein')."\r\n";
    $support .= "imagettftext(): ".(function_exists('imagettftext')==true? 'existiert' : 'existiert nicht')."\r\n";
    $support .= "HTTP_ACCEPT_ENCODING: ".$_SERVER["HTTP_ACCEPT_ENCODING"]."\r\n";

    if(!is_php('5.4.0')) //Removed in PHP 5.4.x
        $support .= "magic_quotes_gpc: ".$PhpInfo['Core']['magic_quotes_gpc'][0]."\r\n";

    $support .= "file_uploads: ".$PhpInfo['Core']['file_uploads'][0]."\r\n";
    $support .= "upload_max_filesize: ".$PhpInfo['Core']['upload_max_filesize'][0]."\r\n";
    $support .= "sendmail_from: ".$PhpInfo['Core']['sendmail_from'][0]."\r\n";
    $support .= "sendmail_path: ".$PhpInfo['Core']['sendmail_path'][0];
    $support .= "\r\n";

    $show = show($dir."/support", array("info" => _admin_support_info, "head" => _admin_support_head, "support" => $support));