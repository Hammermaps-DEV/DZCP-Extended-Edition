<?php
$_SESSION['installer'] = true;
$host = str_replace('www.','',$_SERVER['HTTP_HOST']);

if((isset($_GET['action']) ? $_GET['action'] : '') == 'mysql_setup_tb')
    $_SESSION['db_install'] = true;

require_once(basePath."/inc/debugger.php");
require_once(basePath.'/inc/config.php');
require_once(basePath.'/inc/sessions.php');
require_once(basePath.'/inc/secure.php');
require_once(basePath.'/inc/_version.php');
require_once(basePath.'/inc/database.php');
require_once(basePath.'/inc/kernel.php');
require_once(basePath.'/inc/additional-kernel/class.ftp.inc.php');
require_once(basePath.'/_installer/system/global.php');
require_once(basePath.'/_installer/system/emlv.php');

error_reporting(E_ALL ^ E_NOTICE ^ E_DEPRECATED ^ E_WARNING);

if(is_debug)
{
    ini_set('display_errors', 1);
    error_reporting(E_ALL);
}

//-> Sichert die ausgelagerten Dateien gegen directe Ausführung
define('IN_DZCP', true);

//-> Generiert die installations Schritte
function steps()
{
    $lizenz = ''; $type = ''; $prepare = ''; $mysql = '';
    $db = ''; $update = ''; $adminacc = ''; $done = ''; $ftp = '';

    switch($_SESSION['setup_step']):
        default:
            $lizenz = show(_step,array("text" => _link_start));
            $type = show(_step,array("text" => _link_type_1));
        break;
        case 'installtype'; //Auswahl: Installation / Update
            $lizenz = show(_step,array("text" => _link_start_1));
            $type = show(_step,array("text" => _link_type));
        break;
        case'prepare'; //Schreibrechte PrÃ¼fen
            $lizenz = show(_step,array("text" => _link_start_1));
            $type = show(_step,array("text" => _link_type_1));
            $ftp = show(_step,array("text" => _link_ftp_1));
            $prepare = show(_step,array("text" => _link_prepare));
            $mysql = show(_step,array("text" => _link_mysql_1));
            $db = show(_step,array("text" => _link_db_1));

            if($_SESSION['type'] == 1)
                $update = show(_step,array("text" => _link_update_1));
            else
                $adminacc = show(_step,array("text" => _link_adminacc_1));

            $done = show(_step,array("text" => _link_done_1));
        break;
        case'ftp'; //Schreibrechte PrÃ¼fen
            $lizenz = show(_step,array("text" => _link_start_1));
            $type = show(_step,array("text" => _link_type_1));
            $ftp = show(_step,array("text" => _link_ftp));
            $prepare = show(_step,array("text" => _link_prepare_1));
            $mysql = show(_step,array("text" => _link_mysql_1));
            $db = show(_step,array("text" => _link_db_1));

            if($_SESSION['type'] == 1)
                $update = show(_step,array("text" => _link_update_1));
            else
                $adminacc = show(_step,array("text" => _link_adminacc_1));

            $done = show(_step,array("text" => _link_done_1));
        break;
        case'mysql'; //MySQL Verbindung abfragen & herstellen
            $lizenz = show(_step,array("text" => _link_start_1));
            $type = show(_step,array("text" => _link_type_1));
            $ftp = show(_step,array("text" => _link_ftp_1));
            $prepare = show(_step,array("text" => _link_prepare_1));
            $mysql = show(_step,array("text" => _link_mysql));
            $db = show(_step,array("text" => _link_db_1));

            if($_SESSION['type'] == 1)
                $update = show(_step,array("text" => _link_update_1));
            else
                $adminacc = show(_step,array("text" => _link_adminacc_1));

            $done = show(_step,array("text" => _link_done_1));
        break;
        case'mysql_setup'; //MySQL Config schreiben
            $lizenz = show(_step,array("text" => _link_start_1));
            $type = show(_step,array("text" => _link_type_1));
            $ftp = show(_step,array("text" => _link_ftp_1));
            $prepare = show(_step,array("text" => _link_prepare_1));
            $mysql = show(_step,array("text" => _link_mysql_1));
            $db = show(_step,array("text" => _link_db));

            if($_SESSION['type'] == 1)
                $update = show(_step,array("text" => _link_update_1));
            else
                $adminacc = show(_step,array("text" => _link_adminacc_1));

            $done = show(_step,array("text" => _link_done_1));
        break; //Tabellen anlegen
        case 'mysql_setup_tb';
            $lizenz = show(_step,array("text" => _link_start_1));
            $type = show(_step,array("text" => _link_type_1));
            $ftp = show(_step,array("text" => _link_ftp_1));
            $prepare = show(_step,array("text" => _link_prepare_1));
            $mysql = show(_step,array("text" => _link_mysql_1));
            $db = show(_step,array("text" => _link_db));

            if($_SESSION['type'] == 1)
                $update = show(_step,array("text" => _link_update));
            else
                $adminacc = show(_step,array("text" => _link_adminacc_1));

            $done = show(_step,array("text" => _link_done_1));
        break;
        case 'mysql_setup_users'; //Administrator anlegen,Erste Konfiguration etc.
            $lizenz = show(_step,array("text" => _link_start_1));
            $type = show(_step,array("text" => _link_type_1));
            $ftp = show(_step,array("text" => _link_ftp_1));
            $prepare = show(_step,array("text" => _link_prepare_1));
            $mysql = show(_step,array("text" => _link_mysql_1));
            $db = show(_step,array("text" => _link_db_1));
            $adminacc = show(_step,array("text" => _link_adminacc));
            $done = show(_step,array("text" => _link_done_1));
        break;
        case 'done';
            $lizenz = show(_step,array("text" => _link_start_1));
            $type = show(_step,array("text" => _link_type_1));
            $ftp = show(_step,array("text" => _link_ftp_1));
            $prepare = show(_step,array("text" => _link_prepare_1));
            $mysql = show(_step,array("text" => _link_mysql_1));
            $db = show(_step,array("text" => _link_db_1));

            if($_SESSION['type'] == 1)
                $update = show(_step,array("text" => _link_update_1));
            else
                $adminacc = show(_step,array("text" => _link_adminacc_1));

            $done = show(_step,array("text" => _link_done));
        break;
    endswitch;

    return $lizenz.$type.$ftp.$prepare.$mysql.$db.$update.$adminacc.$done;
}

//-> Erkennt welche Datenbank Engine verwendet wird
function mysqlTableEngine($con, $db, $table)
{
    $mysqlVersion = mysqlVersion($con);
    $engineValue = '';

    if ($mysqlVersion['int'] < 40102)
        $engineValue = 'Type';
    else
        $engineValue = 'Engine';

    $sql = "SHOW TABLE STATUS FROM " . $db . " LIKE '" . $table . "'";
    $result = @mysqli_query($con,$sql);
    $row = @mysqli_fetch_assoc($result);
    return $row[$engineValue];
}

function mysqli_result($res, $row, $field=0)
{
    $res->data_seek($row);
    $datarow = $res->fetch_array();
    return $datarow[$field];
}

function mysqlVersion($con)
{
    $vers = array();
    $sql = 'SELECT VERSION( ) AS versionsinfo';
    $result = @mysqli_query($con,$sql);
    $version = @mysqli_result( $result, 0, "versionsinfo" );
    $match = explode( '.', $version );
    $vers['txt'] = $version;
    $vers['int'] = sprintf( '%d%02d%02d', $match[0], $match[1], convert::ToInt( $match[2] ) );
    return $vers;
}

//-> Prüft MySQL Server auf ndb Erweiterung
function check_db_ndb($con)
{
    $db = mysqli_get_server_info($con);
    return (stristr($db, "ndb") !== false && stristr($db, "cluster") !== false);
}

//-> Prüft MySQL Server auf Aria Erweiterung
function check_db_aria($con)
{
    $db = mysqli_get_server_info($con);
    return (stristr($db, "MariaDB") !== false && stristr($db, "Maria") !== false);
}

//-> Nachrichten ausgeben
function writemsg($stg='',$error=false, $warn=false)
{
    if($error)
        return show("/msg/msg_error",array("error" => _error, "msg" => $stg));
    else if($warn)
        return show("/msg/msg_warn",array("warn" => _warn, "msg" => $stg));
    else
        return show("/msg/msg_successful",array("successful" => _successful, "msg" => $stg));
}

//-> Schreibe Datenbank
function sql_installer($insert=false,$db_infos=array(),$newinstall=true)
{
    if($newinstall)
    {
        require_once(basePath.'/_installer/system/sqldb/newinstall/mysql_create_tb.php');
        require_once(basePath.'/_installer/system/sqldb/newinstall/mysql_insert_db.php');
        ($insert ? install_mysql_insert($db_infos) : install_mysql_create());
    }
    else
    {
        $versions = array();
        if($files = get_files(basePath.'/_installer/system/sqldb/update/',false,true,array('php')))
        {
            $updates_array=array();
            foreach($files as $update)
            { require_once(basePath.'/_installer/system/sqldb/update/'.$update); }
        }

        //-> Updates Sortieren
        foreach($versions as $res)
        $sort[] = $res['update_id'];
        array_multisort($sort, SORT_ASC, $versions);

        if($db_infos!=0)
        {
            foreach($versions as $version_array)
            {
                $result = array_search($db_infos, $version_array, true); //Suche
                if($result!='')
                break;
            }

            for($i = ($result-1); $i < count($versions); $i++)
            {

                if($versions[$i]['call'] != false && function_exists($func=('install_'.$versions[$i]['call'].'_update')))
                    @call_user_func($func);
            }
        }

        header('Location: index.php?action=done');
    }
}

//-> Eine Liste der Versionen anzeigen
function versions($detect=false)
{
    $versions = array(); $show='';
    if($files = get_files(basePath.'/_installer/system/sqldb/update/',false,true,array('php')))
    {
        foreach($files as $update)
        { require_once(basePath.'/_installer/system/sqldb/update/'.$update); }
    }

    //-> Liste ausgeben
    $count = count($versions);
    foreach($versions as $id => $version)
    {
        $checked = ''; $disabled = '';
        if($detect)
        {
            if($version['dbv'] == $detect && $version['dbv'] != false)
                $checked = 'checked="checked"';
            else
            {
                $count--;
                $disabled = 'disabled="disabled"';
            }
        }

        $show .= show(version_input,array("version_num" => $version[$version['update_id']], "version_num_view" => $version['version_list'], "checked" => $checked, "disabled" => $disabled));
    }


    return array('version' => $show, 'msg' => (!$count ? writemsg(no_db_update,false,true) : ''), 'disabled' => (!$count ? 'disabled="disabled"' : ''));
}

//-> Schreibe Inhalt in die "mysql.php"
function write_sql_config()
{
    $stream_sql = file_get_contents(basePath.'/_installer/system/sql_vorlage.txt');
    $stream_salt = file_get_contents(basePath.'/_installer/system/sql_salt_vorlage.txt');
    $var = array("{prefix}", "{host}", "{user}" ,"{pass}" ,"{db}","{salt}","{dbengine}");
    $data = array($_SESSION['mysql_prefix'], $_SESSION['mysql_host'], $_SESSION['mysql_user'], $_SESSION['mysql_password'], $_SESSION['mysql_database'], $salt=mkpwd(), $_SESSION['mysql_dbengine']);
    $_SESSION['mysql_salt'] = $salt;
    file_put_contents(basePath.'/inc/mysql.php', str_replace($var, $data, $stream_sql));
    file_put_contents(basePath.'/inc/mysql_salt.php', str_replace($var, $data, $stream_salt));
    unset($stream_sql,$stream_salt);
    return (file_exists(basePath.'/inc/mysql.php') && file_exists(basePath.'/inc/mysql_salt.php'));
}

//-> Prüft ob Datei existiert und ob auf ihr geschrieben werden kann
function is_writable_array($array)
{
    $i=0; $data=array(); $status=true;
    foreach($array as $file)
    {
        $what = "Ordner:&nbsp;";

        if(is_file('../'.$file))
            $what = "Datei:&nbsp;";

        $_file = preg_replace("#\.\.#Uis", "", '../'.$file);

        if(is_writable('../'.$file))
            $data[$i] = "<table width=\"100%\" border=\"0\" cellspacing=\"1\" cellpadding=\"0\"><tr><td width=\"90\"><font color='green'>"._true."<b>".$what."</b></font></td><td><font color='green'>".$_file."</font></td></tr></table>";
        else
        {
            $data[$i] = "<table width=\"100%\" border=\"0\" cellspacing=\"1\" cellpadding=\"0\"><tr><td width=\"90\"><font color='red'>"._false."<b>".$what."</b></font></td><td><font color='red'>".$_file."</font><br /></td></tr></table>";
            $status=false;
        }

        $i++;
    }

    return array('return' => $data, 'status' => $status);
}

//-> EMail wird auf korrekten Syntax überprüft
function check_email($email)
{ return preg_match('#^[a-z0-9.!\#$%&\'*+-/=?^_`{|}~]+@([0-9.]+|([^\s\'"<>@,;]+\.+[a-z]{2,6}))$#si', $email); }