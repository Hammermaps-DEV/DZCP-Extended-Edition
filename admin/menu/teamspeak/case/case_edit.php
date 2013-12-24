<?php
/**
 * <DZCP-Extended Edition>
 * @package: DZCP-Extended Edition
 * @author: DZCP Developer Team || Hammermaps.de Developer Team
 * @link: http://www.dzcp.de || http://www.hammermaps.de
 */

if(_adminMenu != 'true') exit();

$error = ''; $show = '';
if(isset($_POST['ip']))
{
    if(empty($_POST['ip']))
        $error = _ts_empty_ip_dns;
    else if(empty($_POST['port']))
        $error = _ts_empty_port;
    else if(empty($_POST['sport']))
        $error = _ts_empty_qport;

    if(empty($error))
    {
        if(isset($_POST['defaults']))
        {
            $qry = db("SELECT id FROM ".dba::get('ts')." WHERE `default_server` = 1");
            if(_rows($qry))
            {
                while($get = _fetch($qry))
                { db("UPDATE ".dba::get('ts')." SET `default_server` = '0' WHERE `id` = ".$get['id'].";"); }
            }
        }

        db("UPDATE ".dba::get('ts')." SET `host_ip_dns` = '".string::encode($_POST['ip'])."',
                                          `server_port` = '".convert::ToInt($_POST['port'])."',
                                          `query_port` = '".convert::ToInt($_POST['sport'])."',
                                            `customicon` = '".convert::ToInt($_POST['customicon'])."',
                                            `showchannel` = '".convert::ToInt($_POST['showchannel'])."',
                                          `default_server` = ".(isset($_POST['defaults']) ? '1' : '0')."
                                          WHERE `id` = ".convert::ToInt($_GET['id']).";");

        $ip_port = TS3Renderer::tsdns(string::encode($_POST['ip']));
        $host = ($ip_port != false && is_array($ip_port) ? $ip_port['ip'] : string::encode($_POST['ip']));
        $port = ($ip_port != false && is_array($ip_port) ? $ip_port['port'] : convert::ToInt($_POST['port']));
        Cache::delete('teamspeak_'.md5($host.':'.$port));
        $show = info(_config_ts_updated,"?index=admin&amp;admin=teamspeak");
    }
}

if(empty($show))
{
    $get = db("SELECT * FROM ".dba::get('ts')." WHERE `id` = ".convert::ToInt($_GET['id']).";",false,true);
    $show = show($dir."/teamspeak_edit", array('id' => convert::ToInt($_GET['id']),
                                               'error' => (!empty($error) ? show("errors/errortable", array("error" => $error)) : ""),
                                               'ip' => (isset($_POST['ip']) ? $_POST['ip'] : $get['host_ip_dns']),
                                               'port' => (isset($_POST['port']) ? $_POST['port'] : $get['server_port']),
                                               'sport' => (isset($_POST['sport']) ? $_POST['sport'] : $get['query_port']),
                                               'fport' => (isset($_POST['fport']) ? $_POST['fport'] : $get['file_port']),
                                               'selected_showchannel' => (isset($_POST['showchannel']) ? 'selected="selected"' : $get['showchannel'] ? 'selected="selected"' : ''),
                                               'checked_defaults' => (isset($_POST['defaults']) ? 'checked="checked"' : $get['default_server'] ? 'checked="checked"' : ''),
                                               'selected_customicon' => (isset($_POST['customicon']) ? 'selected="selected"' : $get['customicon'] ? 'selected="selected"' : '')));
}