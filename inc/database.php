<?php
/**
 * <DZCP-Extended Edition>
 * @package: DZCP-Extended Edition
 * @author: DZCP Developer Team || Hammermaps.de Developer Team
 * @link: http://www.dzcp.de || http://www.hammermaps.de
 */

function db($query,$rows=false,$fetch=false,$log=false,$real=true)
{
    database::init_pre();
    if($rows || $fetch)
    {
        if(!($real ? database::query_real($query,$log) : database::query($query,$log)))
            return false;

        if($fetch && $rows)
            $return = database::fetch(true);
        else if($fetch && !$rows)
            $return = database::fetch(false);
        else
            $return = database::rows();

        database::free_result();
        return $return;
    }

    return ($real ? database::query_real($query,$log) : database::query($query,$log));
}

function db_stmt($query,$params=array(),$rows=false,$fetch=false,$log=false)
{
    database::init_pre();
    if($rows || $fetch)
    {
        database::stmt_query($query,$params,$log);

        if($fetch && !$rows)
        {
            $return = database::fetch(false);
            $return = $return[0];
        }
        else
            $return = database::rows();

        database::free_result();
        return $return;
    }

    return database::stmt_query($query,$params);
}

/**
 * Liefert die Anzahl der Zeilen im Ergebnis
 *
 * @return integer
 **/
function _rows($mysqli_result)
{ return database::rows($mysqli_result != null && !empty($mysqli_result) ? $mysqli_result : null); }

/**
 * Liefert einen Datensatz als assoziatives Array
 *
 * @return array
 **/
function _fetch($mysqli_result=null)
{ return database::fetch(false,$mysqli_result != null && !empty($mysqli_result) ? $mysqli_result : null); }

/**
 * Funktion um diverse Dinge aus Tabellen auszaehlen zu lassen
 *
 * @return integer
 **/
function cnt($count, $where = "", $what = "id")
{
    $hash = md5('sql_cnt_'.$what.'_'.$where.'_'.$count);
    if(!RTBuffer::check($hash)) return convert::ToInt(RTBuffer::get($hash));
    $cnt_sql = db("SELECT COUNT(".$what.") AS num FROM ".$count." ".$where.";");
    if(_rows($cnt_sql))
    {
        $cnt = _fetch($cnt_sql);
        RTBuffer::set($hash,$cnt['num']);
        return convert::ToInt($cnt['num']);
    }

    return 0;
}

/**
 * Funktion um diverse Dinge aus Tabellen zusammenzaehlen zu lassen
 *
 * @return integer
 **/
function sum($db, $where = "", $what)
{
    $hash = md5('sql_sum_'.$db.'_'.$where.'_'.$what);
    if(!RTBuffer::check($hash)) return convert::ToInt(RTBuffer::get($hash));
    $cnt_sql = db("SELECT SUM(".$what.") AS num FROM ".$db.$where.";");
    if(_rows($cnt_sql))
    {
        $cnt = _fetch($cnt_sql);
        RTBuffer::set($hash,$cnt['num']);
        return convert::ToInt($cnt['num']);
    }

    return 0;
}

/**
 * Funktion um CMS Settings aus der Datenbank auszulesen
 * Updated for DZCP-Extended Edition
 *
 * @return mixed/array
 **/
function settings($what)
{ return is_array($what) ? settings::get_array($what) : settings::get($what); }

/**
 * Informationen über die MySQL-Datenbank abrufen
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

// #######################################
// ################ CLASS ################
// #######################################
class database
{
    public static $mysqli = null;
    public static $sql_query = null;
    public static $insert_id = 0;
    public static $runned = false;
    public static $stmt = null;
    public static $remote = false;
    public static $fetch_fields = array();

    public static final function init_pre()
    {
        if(self::$remote)
            remote_database::init(null);
        else
            self::init();
    }

    /**
     * Datenbank Connect
     */
    public static function init()
    {
        global $db_array;

        if(self::$remote)
        {
            if(is_resource(self::$mysqli) && self::$mysqli != null)
                mysqli_close(self::$mysqli);

            self::$remote = false;
        }

        if(!self::$runned)
        {
            DebugConsole::insert_initialize('database::init()', 'DZCP Database-Core');
            self::$runned = true;
        }

        if(is_resource(self::$mysqli) && self::$mysqli != null) //already connected
            return;

        if($_SESSION['installer'] && !$_SESSION['db_install']) //For Installer
            return;

        if(!empty($db_array['host']) && !empty($db_array['user']) && !empty($db_array['db']))
        {
            if(!self::$mysqli = mysqli_init())
                die('mysqli_init failed');

            if(!mysqli_options(self::$mysqli, MYSQLI_OPT_CONNECT_TIMEOUT, 5))
                die('Setting MYSQLI_OPT_CONNECT_TIMEOUT failed');

            $use_persistconns = (ini_get('mysqli.allow_persistent') == '0' || ini_get('mysqli.max_persistent') == '0' ? false : runtime_sql_persistconns);
            if (!self::$mysqli=mysqli_connect(($use_persistconns ? 'p:' : '').$db_array['host'], $db_array['user'], $db_array['pass'], $db_array['db']))
                self::print_db_error();
        }
        else
        {
            self::$mysqli = null;
            echo '<html><head><meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" /></head><body><b>';
            if(empty($db_array['host']))
                echo "Das MySQL-Hostname fehlt in der Configuration!<p>";

            if(empty($db_array['user']))
                echo "Der MySQL-Username fehlt in der Configuration!<p>";

            if(empty($db_array['db']))
                echo "Der MySQL-Datenbankname fehlt in der Configuration!<p>";

            die("Bitte überprüfe deine mysql.php!</b></body></html>");
        }
    }

    /**
     * Datenbank Query senden *REAL*
     * @param string $sql_query
     */
    public static function query_real($sql_query='',$log=false)
    {
        global $db_array;
        if(!$_SESSION['installer'] || $_SESSION['db_install']) //For Installer
        {
            if($log || debug_all_sql_querys) DebugConsole::wire_log('debug', 9, 'SQL_Query', $sql_query);
            if(!mysqli_real_query(self::$mysqli, $sql_query))
                DebugConsole::sql_error_handler($sql_query);
            else
            {
                self::$sql_query = mysqli_store_result(self::$mysqli);
                self::$fetch_fields = self::$sql_query != false && is_object(self::$sql_query) ? convert::objectToArray(mysqli_fetch_fields(self::$sql_query)) : array();
                self::$insert_id = mysqli_insert_id(self::$mysqli);
                unset($sql_query);
                return self::$sql_query;
            }
        }
    }

    /**
     * Datenbank Query senden
     * @param string $sql_query
     */
    public static function query($sql_query='',$log=false)
    {
        if(!$_SESSION['installer'] || $_SESSION['db_install']) //For Installer
        {
            if($log || debug_all_sql_querys) DebugConsole::wire_log('debug', 9, 'SQL_Query', $sql_query);
            if(!(self::$sql_query = mysqli_query(self::$mysqli, $sql_query)))
                DebugConsole::sql_error_handler($sql_query);
            else
            {
                self::$fetch_fields = self::$sql_query != false && is_object(self::$sql_query) ? convert::objectToArray(mysqli_fetch_fields(self::$sql_query)) : array();
                self::$insert_id = mysqli_insert_id(self::$mysqli);
                unset($sql_query);
                return self::$sql_query;
            }
        }
    }

    /**
     * Datenbank Query senden als prepare SQL statement
     * @param string $static_query
     * @param array params
     */
    public static function stmt_query($query='SELECT * FROM test WHERE name=?',$params=array('si', 'hallo', '4'), $log=false)
    {
        if($log || debug_all_sql_querys) DebugConsole::wire_log('debug', 9, 'SQL_Query', $query);
        if(self::$stmt = mysqli_prepare(self::$mysqli, $query))
        {
            /**
             *  i 	corresponding variable has type integer
             *  d 	corresponding variable has type double
             *  s 	corresponding variable has type string
             *  b 	corresponding variable is a blob and will be sent in packets
             */
            call_user_func_array(array(self::$stmt, 'bind_param'), self::refValues($params));
            mysqli_stmt_execute(self::$stmt);

            $meta = mysqli_stmt_result_metadata(self::$stmt);
            if(!$meta || empty($meta)) { mysqli_stmt_close(self::$stmt); self::close(); return; }
            $row = array(); $parameters = array(); $results = array();
            while ( $field = mysqli_fetch_field($meta) )
            {
                $parameters[] = &$row[$field->name];
            }

            mysqli_stmt_store_result(self::$stmt);
            $results['_stmt_rows_'] = mysqli_stmt_num_rows(self::$stmt);
            call_user_func_array(array(self::$stmt, 'bind_result'), self::refValues($parameters));

            while ( mysqli_stmt_fetch(self::$stmt) )
            {
                $x = array();
                foreach( $row as $key => $val )
                {
                    $x[$key] = $val;
                }

                $results[] = $x;
            }

            self::$sql_query = $results;

            /* close statement */
            self::$insert_id = mysqli_insert_id(self::$mysqli);
            mysqli_stmt_close(self::$stmt);
            self::close();

            return self::$sql_query;
        }

        return false;
    }

    public static function get_fetch_fields()
    { if(count(self::$fetch_fields) >= 1) return self::$fetch_fields; return false; }

    private static function refValues($arr)
    {
        if (strnatcmp(phpversion(),'5.3') >= 0)
        {
            $refs = array();
            foreach($arr as $key => $value)
                $refs[$key] = &$arr[$key];

            return $refs;
        }

        return $arr;
    }

    /**
     * Datenbankverbindung übergeben
     */
    public static function get_insert_id()
    { return self::$insert_id; }

    /**
     * Liefert einen Datensatz als assoziatives Array
     *
     * @param boolean $array
     * @param mysqli_result $result
     */
    public static function fetch($array=false,$result=null)
    {
        if(is_array($result) || (!empty(self::$sql_query) && count(self::$sql_query) >= 1) && is_array(self::$sql_query))
        {
            if(array_key_exists('_stmt_rows_', is_array($result) ? $result : self::$sql_query))
            {
                if(is_array($result)) unset($result['_stmt_rows_']);
                else unset(self::$sql_query['_stmt_rows_']);
            }

            return is_array($result) ? $result[0] : self::$sql_query;
        }
        else if(empty(self::$sql_query) && !count(self::$sql_query) && is_array(self::$sql_query))
            return false;
        else if(!$_SESSION['installer'] || $_SESSION['db_install'] && (!empty(self::$sql_query) || !empty($result))) //For Installer
        {
            if($array)
                return mysqli_fetch_array(empty($result) || $result == null ? self::$sql_query : $result, MYSQLI_BOTH);
             else
                return mysqli_fetch_assoc(empty($result) || $result == null ? self::$sql_query : $result);
        }

        return false;
    }

    /**
     * Liefert die Anzahl der Zeilen im Ergebnis
     *
     * @param mysqli_result $result
     */
    public static function rows($result=null)
    {
        if(is_array($result) || is_array(self::$sql_query))
            return is_array($result) ? $result['_stmt_rows_'] : self::$sql_query['_stmt_rows_'];

        if(!$_SESSION['installer'] || $_SESSION['db_install']) //For Installer
            return mysqli_num_rows(empty($result) || $result == null ? self::$sql_query : $result);

        return false;
    }

    /**
     * Gibt den Ergebnisspeicher frei
     */
    public static function free_result()
    {
        if(self::$sql_query != null && !empty(self::$sql_query) && !is_array(self::$sql_query))
            mysqli_free_result(self::$sql_query);
    }

    /**
     * Schließt die Datenbankverbindung
     */
    public static function close()
    {
        if(is_resource(self::$mysqli) && self::$mysqli != null && !runtime_sql_persistconns)
            mysqli_close(self::$mysqli);
    }

    /**
     * Gibt Datenbank Fehler aus und stoppt die Ausführung des CMS
     **/
    public static function print_db_error($query=false)
    {
        global $prefix;
        echo '<b>MySQL-Query failed:</b><br /><ul>'.
                '<li><b>ErrorNo</b> = '.mysqli_connect_errno().
                '<li><b>Error</b>   = '.mysqli_connect_error().
                ($query ? '<li><b>Query</b>   = '.$query.'</ul>' : '');
        self::close();
        die();
    }

    /**
     * Gibt die MySQL Version zurück
     */
    public static function version()
    { return mysqli_get_server_info(self::$mysqli); }

    /**
     * Gibt eine Liste der vorhandenen MySQL Tabellen aus.
     * @param string $db
     * @return array/boolean
     */
    public static function list_tables($db='')
    {
        global $db_array;
        $db = (empty($db) ? $db_array['db'] : $db);
        if(!self::query('SHOW TABLES FROM '.$db.';')) return false;
        $tables = array();
        while($row = self::fetch())
        { $tables[] = $row['Tables_in_'.$db]; }
        return count($tables) >= 1 ? $tables : false;
    }

    /**
     * Erstellt ein Datenbank Backup
     *
     * @return string
     */
    public static function backup()
    {
        global $db_array;
        $backup_table_data = array();

        //Table Drop
        self::query('SHOW TABLE STATUS');
        while($table = self::fetch())
        { $backup_table_data[$table['Name']]['drop'] = 'DROP TABLE IF EXISTS `'.$table['Name'].'`;'; }
        unset($table);

        //Table Create
        foreach($backup_table_data as $table => $null)
        {
            unset($null); self::query('SHOW CREATE TABLE '.$table.';');
            while($table = self::fetch())
            { $backup_table_data[$table['Table']]['create'] = $table['Create Table'].';'; }
        }
        unset($table);

        //Cleanup
        foreach($backup_table_data as $table => $null)
        {
            unset($null);
            if($table == dba::get('cache')) //Clear Cache
                self::query('TRUNCATE TABLE `'.dba::get('cache').'`');
        }
        unset($table);

        //Insert Create
        foreach($backup_table_data as $table => $null)
        {
            unset($null); $backup = '';
            self::query('SELECT * FROM '.$table.' ;');
            while($dt = self::fetch())
            {
                if(!empty($dt))
                {
                    $backup_data = '';
                    foreach ($dt as $key => $var)
                    { $backup_data .= "`".$key."` = '".convert::ToString(str_replace("'", "`", $var))."',"; }

                    $backup .= "INSERT INTO `".$table."` SET ".substr($backup_data, 0, -1).";\r\n";
                    unset($backup_data);
                }
            }

            $backup_table_data[$table]['insert'] = $backup;
            unset($backup);
        }
        unset($table);

        $sql_backup =  "-- -------------------------------------------------------------------\r\n";
        $sql_backup .= "-- Datenbank Backup von deV!L`z Clanportal v."._version." "._edition."\r\n";
        $sql_backup .= "-- Build: "._release." * "._build."\r\n";
        $sql_backup .= "-- Host: ".$db_array['host']."\r\n";
        $sql_backup .= "-- Erstellt am: ".date("d.m.Y")." um ".date("H:i")."\r\n";
        $sql_backup .= "-- MySQL-Version: ".mysqli_get_server_info(self::$mysqli)."\r\n";
        $sql_backup .= "-- PHP Version: ".phpversion()."\r\n";
        $sql_backup .= "-- -------------------------------------------------------------------\r\n\r\n";
        $sql_backup .= "--\r\n-- Datenbank: `".$db_array['db']."`\r\n--\n\n";
        $sql_backup .= "-- -------------------------------------------------------------------\r\n";
        foreach($backup_table_data as $table => $data)
        {
            $sql_backup .= "\r\n--\r\n-- Tabellenstruktur: `".$table."`\r\n--\r\n\r\n";
            $sql_backup .= $data['drop']."\r\n";
            $sql_backup .= $data['create']."\r\n";

            if(!empty($data['insert']))
            {
                $sql_backup .= "\r\n--\r\n-- Datenstruktur: `".$table."`\r\n--\r\n\r\n";
                $sql_backup .= $data['insert']."\r\n";
            }
        }

        unset($data);
        return $sql_backup;
    }
}

class remote_database extends database
{
    private static $db_array_save = array();
    private static $runned_remote = false;

    /**
     * Datenbank Connect
     */
    public static function init($db_array=array())
    {
        if(!self::$runned_remote)
        {
            DebugConsole::insert_initialize('remote_database::init()', 'DZCP Database-Core * Remote');
            self::$runned_remote = true;
        }

        if(count(self::$db_array_save) >= 1 && !count($db_array) || $db_array == null)
            $db_array = self::$db_array_save;
        else
            self::$db_array_save = $db_array;

        if(!self::$remote)
            self::$mysqli = null;

        if(is_resource(self::$mysqli) && self::$mysqli != null) //already connected
            return;

        if(!empty($db_array['host']) && !empty($db_array['user']) && !empty($db_array['pass']) && !empty($db_array['db']))
        {
            if(!self::$mysqli = mysqli_init())
                die('mysqli_init failed');

            if(!mysqli_options(self::$mysqli, MYSQLI_OPT_CONNECT_TIMEOUT, 5))
                die('Setting MYSQLI_OPT_CONNECT_TIMEOUT failed');

            if (!self::$mysqli=mysqli_connect($db_array['host'], $db_array['user'], $db_array['pass'], $db_array['db']))
                self::print_db_error();

            self::$remote = true;
        }
        else
        {
            self::$mysqli = null;
            echo '<html><head><meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" /></head><body><b>';
            if(empty($db_array['host']))
                echo "Das MySQL-Hostname fehlt in der Configuration!<p>";

            if(empty($db_array['user']))
                echo "Der MySQL-Username fehlt in der Configuration!<p>";

            if(empty($db_array['pass']))
                echo "Das MySQL-Passwort fehlt in der Configuration!<p>";

            if(empty($db_array['db']))
                echo "Der MySQL-Datenbankname fehlt in der Configuration!<p>";

            die("</b></body></html>");
        }
    }

}

############# DBA #############
class dba
{
    private static $dba = array();

    public static final function init()
    {
        global $db_array;
        foreach ($db_array as $dba_key => $dba_val)
        {
            if($dba_key == 'host' || $dba_key == 'user' || $dba_key == 'pass' || $dba_key == 'db' || $dba_key == 'prefix')
                continue;

            self::$dba[$dba_key] = $db_array['prefix'].$dba_val; // Add prefix
        }
    }

    public static function get($tag = '')
    {
        if(empty($tag) || $tag == false || !array_key_exists($tag, self::$dba))
            return '';
        else
            return self::$dba[$tag];
    }

    public static function set($tag = '', $table = '')
    {
        global $db_array;

        if(array_key_exists($tag, self::$dba))
            return false;

        self::$dba[$tag] = $db_array['prefix'].$table; // Add prefix
        return true;
    }

    //array(array('test' => 'test123'),array('dl' => 'downloads'));
    public static function set_array($array = array())
    {
        global $db_array;

        if(!is_array($array) || !count($array))
            return false;

        $i=0;
        foreach($array as $dba_key => $dba_val)
        {
            if(array_key_exists($dba_key, self::$dba)) continue;
            self::$dba[$dba_key] = $db_array['prefix'].$dba_val; // Add prefix
            $i++;
        }

        return $i >= 1 ? true : false;
    }

    public static function replace($tag = '', $new_table = '')
    {
        global $db_array;

        if(!array_key_exists($tag, self::$dba))
            return false;

        self::$dba[$tag] = $db_array['prefix'].$new_table; // Add prefix
        return true;
    }
}

dba::init(); //Run DBA

############# CMS Settings #############
class settings
{
    protected static $index = array();

    /**
     * Gibt eine Einstellung aus der Settings Tabelle zurück
     * @param string $what
     * @return string|int|boolean
     */
    public static function get($what='')
    {
        $what = strtolower($what);
        if(array_key_exists($what, self::$index))
        {
            $data = self::$index[$what];
            return $data['value'];
        }
        else
            DebugConsole::insert_error('settings::get()', 'Setting "'.$what.'" not found in '.dba::get('settings'));

        return false;
    }

    /**
     * Gibt mehrere Einstellungen aus der Settings Tabelle zurück
     * @param string $what
     * @return array|boolean
     */
    public static function get_array($what=array())
    {
        if(!is_array($what) || !count($what) || empty($what))
            return false;

        $return = array();
        foreach ($what as $key)
        {
            $key = strtolower($key);
            if(array_key_exists($key, self::$index))
            {
                $data = self::$index[$key];
                $return[$key] = $data['value'];
                $data = array();
            }
        }

        if(count($return) >= 1) return $return;
        return false;
    }

    /**
     * Gibt die Standard Einstellung einer Einstellung zurück
     * @param string $what
     * @return mixed|boolean
     */
    public static function get_default($what='')
    {
        $what = strtolower($what);
        if(array_key_exists($what, self::$index))
        {
            $data = self::$index[$what];
            return $data['default'];
        }
        else
            DebugConsole::insert_error('settings::get_default()', 'Setting "'.$what.'" not found in '.dba::get('settings'));

        return false;
    }

    /**
     * Aktualisiert die Werte innerhalb der Settings Tabelle
     * @param string $what
     * @param string $var
     * @return boolean
     */
    public static function set($what='',$var='')
    {
        $what = strtolower($what);
        if(array_key_exists($what, self::$index))
        {
            $data = self::$index[$what];
            $data['value'] = ($data['length'] >= 1 ? cut($var,((int)$data['length']),false) : $var);
            self::$index[$what] = $data;
            DebugConsole::insert_successful('settings::set()', 'Set "'.$what.'" to "'.$var.'"');
            return db("UPDATE `".dba::get('settings')."` SET `value` = '".($data['length'] >= 1 ? cut($var,((int)$data['length']),false) : $var)."' WHERE `key` = '".$what."';") ? true : false;
        }

        return false;
    }

    /**
     * Vergleicht den Aktuellen Wert mit dem neuen Wert ob ein Update erforderlich ist
     * @param string $what
     * @param string $var
     * @return boolean
     */
    public static function changed($what='',$var='')
    {
        $what = strtolower($what);
        if(array_key_exists($what, self::$index))
        {
            $data = self::$index[$what];
            return ($data['value'] == $var ? false : true);
        }

        return false;
    }

    /**
     * Prüft ob ein Key existiert
     * @param string $what
     * @return boolean
     */
    public static function is_exists($what='')
    { return (array_key_exists(strtolower($what), self::$index)); }

    /**
     * Laden der Einstellungen aus der Datenbank
     */
    public static final function load()
    {
        if(!$_SESSION['installer'] && !$_SESSION['db_install']) //For Installer
        {
            $sql = db("SELECT * FROM `".dba::get('settings')."`");
            while ($get = _fetch($sql))
            {
                $setting = array();
                $setting['value'] = !((int)$get['length']) ? $get['type'] == 'int' ? ((int)$get['value']) : ((string)$get['value'])
                : cut($get['type'] == 'int' ? ((int)$get['value']) : ((string)$get['value']),((int)$get['length']),false);
                $setting['default'] = $get['type'] == 'int' ? ((int)$get['default']) : ((string)$get['default']);
                $setting['length'] = ((int)$get['length']);
                self::$index[$get['key']] = $setting;
                unset($setting);
            }
        }
    }

    /**
     * Eine neue Einstellung in die Datenbank schreiben
     * @param string $what
     * @param string/int $var
     * @param string/int $default
     * @param int $length
     * @param boolean $int
     * @return boolean
     */
    public static function add($what='',$var='',$default='',$length='',$int=false)
    {
        $what = strtolower($what);
        if(!self::is_exists($what))
        {
            $setting = array();
            $setting['value'] = !((int)$length) ? $int ? ((int)$var) : ((string)$var)
            : cut($int ? ((int)$var) : ((string)$var),((int)$length),false);
            $setting['default'] = $int ? ((int)$default) : ((string)$default);
            $setting['length'] = ((int)$length);
            self::$index[$what] = $setting;
            unset($setting);

            DebugConsole::insert_successful('settings::add()', 'Add "'.$what.'" set to "'.$var.'"');
            return db("INSERT INTO `".dba::get('settings')."` SET
                        `key` = '".$what."',
                        `value` = '".$var."',
                        `default` = '".$default."',
                        `length` = '".$length."',
                        `type` = '".($int ? 'int' : 'string')."';",false,false,true);
        }

        return false;
    }

    /**
     * Löscht eine Einstellung aus der Datenbank
     * @param string $what
     * @return boolean
     */
    public static function remove($what='')
    {
        $what = strtolower($what);
        if(self::is_exists($what))
        {
            DebugConsole::insert_info('settings::remove()', 'Remove "'.$what.'"');
            unset(self::$index[$what]);
            return db("DELETE FROM `".dba::get('settings')."` WHERE `key` = '".$what."';",false,false,true) ? true : false;
        }

        return false;
    }
}

settings::load(); //Load all settings

#############################################
########### DB-CharsetConverter #############
#############################################
class string
{
    /**
     * Codiert Text in das UTF8 Charset.
     *
     * @param string $txt
     */
    public static function encode($txt='')
    {
        return stripcslashes(spChars(convert::ToHTML($txt)));
    }

    /**
     * Decodiert UTF8 Text in das aktuelle Charset der Seite.
     *
     * @param utf8 string $txt
     */
    public static function decode($txt='')
    {
        return trim(stripslashes(spChars(html_entity_decode($txt, ENT_COMPAT, 'iso-8859-1'),true)));
    }
}

#############################################
############### TypeConverter ###############
#############################################
class convert
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
    { return htmlentities($input, ENT_COMPAT, _charset); }

    public static final function objectToArray($d)
    { return json_decode(json_encode($d, JSON_FORCE_OBJECT), true); }
}