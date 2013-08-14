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

        db("INSERT INTO ".dba::get('ts')." SET `host_ip_dns` = '".string::encode($_POST['ip'])."',
                                               `server_port` = '".convert::ToInt($_POST['port'])."',
                                               `query_port` = '".convert::ToInt($_POST['sport'])."',
                                               `customicon` = '".convert::ToInt($_POST['customicon'])."',
                                               `showchannel` = '".convert::ToInt($_POST['showchannel'])."',
                                               `default_server` = ".(isset($_POST['defaults']) ? '1' : '0').",
                                               `show_navi` = 0");

        $show = info(_config_ts_added,"?admin=teamspeak");
    }
}

if(empty($show))
    $show = show($dir."/teamspeak_add", array('error' => (!empty($error) ? show("errors/errortable", array("error" => $error)) : ""),
                                              'ip' => (isset($_POST['ip']) ? $_POST['ip'] : ''),
                                              'port' => (isset($_POST['port']) ? $_POST['port'] : '9987'),
                                              'sport' => (isset($_POST['sport']) ? $_POST['sport'] : '10011'),
                                              'selected_showchannel' => (isset($_POST['showchannel']) ? $_POST['showchannel'] == '1' ? 'selected="selected"' : '' : ''),
                                              'checked_defaults' => (isset($_POST['defaults']) ? 'checked="checked"' : ''),
                                              'selected_customicon' => (isset($_POST['customicon']) ? $_POST['customicon'] == '1' ? 'selected="selected"' : '' : '')));