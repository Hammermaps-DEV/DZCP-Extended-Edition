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
else
{
    $get = db("SELECT id,data FROM ".dba::get('ipban')." WHERE id = '".convert::ToInt($_GET['id'])."'",false,true);
    $data_array = string_to_array(hextobin($get['data']));
    $data_array['banned_msg'] = string::encode($_POST['info']);
    db("UPDATE ".dba::get('ipban')." SET `ip` = '".$_POST['ip']."', `time` = '".time()."', `data` = '".bin2hex(array_to_string($data_array))."' WHERE id = '".$get['id']."'");
    $show = info(_ipban_admin_edited, "?index=admin&amp;admin=ipban");
}