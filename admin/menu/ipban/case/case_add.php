<?php
/**
 * <DZCP-Extended Edition>
 * @package: DZCP-Extended Edition
 * @author: DZCP Developer Team || Hammermaps.de Developer Team
 * @link: http://www.dzcp.de || http://www.hammermaps.de
 */

if(_adminMenu != 'true') exit();

if(empty($_POST['ip']))
    $show = error(_ip_empty);
else if(validateIpV4Range($_POST['ip'], '[192].[168].[0-255].[0-255]') || validateIpV4Range($_POST['ip'], '[127].[0].[0-255].[0-255]') || validateIpV4Range($_POST['ip'], '[10].[0-255].[0-255].[0-255]') || validateIpV4Range($_POST['ip'], '[172].[16-31].[0-255].[0-255]'))
    $show = error(_ipban_error_pip);
else
{
    if(empty($_POST['info']))
        $info = '*Keine Info*';
    else
        $info = string::encode($_POST['info']);

    $data_array = array();
    $data_array['confidence'] = ''; $data_array['frequency'] = ''; $data_array['lastseen'] = '';
    $data_array['banned_msg'] = $info;
    db("INSERT INTO ".dba::get('ipban')." SET `time` = '".time()."', `ip` = '".$_POST['ip']."', `data` = '".bin2hex(array_to_string($data_array))."', `typ` = 3;");
    $show = info(_ipban_admin_added, "?index=admin&amp;admin=ipban");
}