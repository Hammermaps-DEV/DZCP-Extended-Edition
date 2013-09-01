<?php
/**
 * <DZCP-Extended Edition>
 * @package: DZCP-Extended Edition
 * @author: DZCP Developer Team || Hammermaps.de Developer Team
 * @link: http://www.dzcp.de || http://www.hammermaps.de
 */

if(_adminMenu != 'true') exit();

$get = db("SELECT * FROM ".dba::get('ipban')." WHERE id = '".intval($_GET['id'])."'",false,true);
$data_array = string_to_array(hextobin($get['data']));
$show = show($dir."/ipban_form", array("newhead" => _ipban_edit_head,"do" => "edit_save&amp;id=".$_GET['id']."","ip_set" => $get['ip'],"info" => string::decode($data_array['banned_msg']),"what" => _button_value_edit));