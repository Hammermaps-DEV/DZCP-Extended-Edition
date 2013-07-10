<?php
#####################
## Admin Menu-File ##
#####################
if(_adminMenu != 'true')
    exit();

$where = $where.': '._teamspeak_admin_head;
switch($do)
{
    case 'new':
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

                db("INSERT INTO ".dba::get('ts')." SET
                        `host_ip_dns` = '".string::encode($_POST['ip'])."',
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
    break;

    case 'edit':
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

                db("UPDATE ".dba::get('ts')." SET
                        `host_ip_dns` = '".string::encode($_POST['ip'])."',
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
                $show = info(_config_ts_updated,"?admin=teamspeak");
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
    break;

    case 'menu':
        $qry = db("SELECT id FROM ".dba::get('ts')." WHERE `show_navi` = 1");
        if(_rows($qry))
        {
            while($get = _fetch($qry))
            { db("UPDATE ".dba::get('ts')." SET `show_navi` = '0' WHERE `id` = ".$get['id'].";"); }
        }

        db("UPDATE ".dba::get('ts')." SET `show_navi` = '1' WHERE `id` = ".convert::ToInt($_GET['id']).";");
        $show = header("Location: ?admin=teamspeak");
    break;

    case 'default_server':
        $qry = db("SELECT id FROM ".dba::get('ts')." WHERE `default_server` = 1");
        if(_rows($qry))
        {
            while($get = _fetch($qry))
            { db("UPDATE ".dba::get('ts')." SET `default_server` = '0' WHERE `id` = ".$get['id'].";"); }
        }

        db("UPDATE ".dba::get('ts')." SET `default_server` = '1' WHERE `id` = ".convert::ToInt($_GET['id']).";");
        $show = header("Location: ?admin=teamspeak");
    break;

    case 'delete':
        $get = db("SELECT host_ip_dns,server_port FROM ".dba::get('ts')." WHERE `id` = ".convert::ToInt($_GET['id'])." LIMIT 1",false,true);
        $ip_port = TS3Renderer::tsdns($get['host_ip_dns']);
        $host = ($ip_port != false && is_array($ip_port) ? $ip_port['ip'] : $get['host_ip_dns']);
        $port = ($ip_port != false && is_array($ip_port) ? $ip_port['port'] : $get['server_port']);
        Cache::delete('teamspeak_'.md5($host.':'.$port));
        db("DELETE FROM ".dba::get('ts')." WHERE id = '".convert::ToInt($_GET['id'])."'");
        $show = info(show(_server_admin_deleted,array('host'=>$host.':'.$port)), "?admin=teamspeak");
    break;

    default:
        $qry = db("SELECT * FROM ".dba::get('ts')." ORDER BY id"); $color = 1;
        while($get = _fetch($qry))
        {
            $edit = show("page/button_edit_single", array("id" => $get['id'],"action" => "admin=teamspeak&amp;do=edit","title" => _button_title_edit));
            $delete = show("page/button_delete_single", array("id" => $get['id'],"action" => "admin=teamspeak&amp;do=delete","title" => _button_title_del,"del" => _confirm_del_server));

            $class = ($color % 2) ? "contentMainSecond" : "contentMainFirst"; $color++;
            $menu = (!$get['show_navi'] ? show(_teamspeak_menu_icon_yes, array("id" => $get['id'])) : show(_teamspeak_menu_icon_no, array("id" => $get['id'])));
            $default = ($get['default_server'] ? show(_teamspeak_default_icon_yes, array("id" => $get['id'])) : show(_teamspeak_default_icon_no, array("id" => $get['id'])));
            $show .= show($dir."/teamspeak_show", array("serverip" => cut(string::decode($get['host_ip_dns']),26,true),
                    "serverport" => $get['server_port'],
                    "serverqport" => $get['query_port'],
                    "menu" => $menu,
                    "default" => $default,
                    "edit" => $edit,
                    "class" => $class,
                    "delete" => $delete));
        }

        $show = show($dir."/teamspeak", array("show" => $show));
    break;
}