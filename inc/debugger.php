<?php
#########################################
//-> Debug Console Settings Start
#########################################

define('show_initialize', false);
define('show_loaded', false);
define('show_info', false);
define('show_warning', false);
define('show_cache_debug', false);

#############################################
############### Debug Console ###############
#############################################
define('DEBUG_LOADER', true);
class DebugConsole // Class by DZCP-Extended Edition
{
    private static $log_array = array(array());
    private static $file_data = '';

    public static final function initCon()
    { self::$log_array=array(array()); self::$file_data=''; self::insert_initialize('inc/debugger.php','Debugger'); }

    public static final function insert_log($file,$msg,$back=false,$func="",$line=0)
    { self::$log_array[$file][] = ($line != 0 ? 'Line:"'.$line.'" => ' : "").($back ? $msg.$func : $func.$msg); }

    public static final function insert_initialize($file,$func)
    { if(show_initialize) self::$log_array[$file][] = '<font color="#0000FF">Initialize '.$func.'</font>'; }

    public static final function insert_successful($file,$func)
    { self::$log_array[$file][] = '<font color="#009900">'.$func.'</font>'; }

    public static final function insert_error($file,$msg)
    { self::$log_array[$file][] = '<font color="#FF0000">'.$msg.'</font>'; }

    public static final function insert_loaded($file,$func)
    { if(show_loaded) self::$log_array[$file][] = '<font color="#009900">'.$func.' Loaded</font>'; }

    public static final function insert_info($file,$info)
    { if(show_info) self::$log_array[$file][] = '<font color="#9900CC">'.$info.'</font>'; }

    public static final function insert_warning($file,$func)
    { if(show_warning) self::$log_array[$file][] = '<font color="#FFFF00">'.$func.'</font>'; }

    public static final function sql_error_handler($sqlmsg, $sqldb, $query, $file, $line)
    { self::$log_array[$file][] = ($line != 0 ? 'Line:"'.$line.'" => ' : "").'SQL DB: '.$sqldb.' => SQL Query: '.$query.' : '.'<font color="#FF0000">'.$sqlmsg.'</font>'; }

    public static final function save_log()
    {
        foreach(self::$log_array as $file => $msg_array)
        { foreach($msg_array as $msg) { self::$file_data .= strip_tags('"'.$file.'" => "'.$msg.'"')."\n"; } }
        file_put_contents(basePath.'/inc/debug_'.date("s-i-h").'_'.date("d_m_Y").'.txt', self::$file_data);
    }

    public static final function show_logs()
    {
        $data = ''; $color = 0; $i=0;
        foreach(self::$log_array as $file => $msg_array)
        {
            foreach($msg_array as $msg)
            {
                $set_color = ($color % 2) ? "#CCCCCC" : "#E6E6E6"; $color++;
                $data .= '<tr><td width="40%" bgcolor="'.$set_color.'"><b><div align="center"><font color="#000000" style="font-size:11px">"'.$file.'"</font></div></b></td>
                <td width="60%" bgcolor="'.$set_color.'"><b><div align="center"><font color="#000000" style="font-size:11px">"'.$msg.'"</font></div></b></td></tr>'; $i++;
            }
        }

        if(!$i) return '';
        return '<style type="text/css"><!-- .boxdebug { color: #000000; font-weight: bold;} -->
        </style><table bgcolor="#000000" width="100%" border="0" ><tr><td bgcolor="#00FF00"><span class="boxdebug" style="font-size:11px">Debug Log: ( '.$i.' Eintr&auml;ge )<a name="log" id="log"></a></span></td>
        </tr><tr><td><table width="100%" border="0" cellpadding="0" cellspacing="0"><tr><td width="40%" bgcolor="#999999"><div align="center" class="boxdebug" style="font-size:11px">File/Code:</div></td>
        <td width="60%" bgcolor="#999999"><div align="center" class="boxdebug" style="font-size:11px">Action/Msg:</div></td></tr></table><table width="100%" border="0" cellpadding="0" cellspacing="0">
        '.$data.'</table></td></tr></table><table width="100%" border="0" cellpadding="0" cellspacing="0"><tr><td width="100%" bgcolor="#999999">&nbsp;</td></tr></table>';
    }
}

function dzcp_error_handler($code, $msg, $file, $line, $context)
{
    $file = str_replace(basePath, '', $file);
    switch ($code)
    {
        case E_WARNING:
        case E_USER_WARNING:
            DebugConsole::insert_log("<b>WARNUNG:' ".$file." '</b>", $msg, false, "", $line);
        break;
        case E_NOTICE:
        case E_USER_NOTICE:
            DebugConsole::insert_log("<b>HINWEIS:' ".$file." '</b>", $msg, false, "", $line);
        break;
        default:
            DebugConsole::insert_log("Unbekannt:' ".$file." ' [".$code."]", $msg, false, "", $line);
        break;
    }

    return true;
}
?>