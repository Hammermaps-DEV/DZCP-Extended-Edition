<?php
/**
 * <DZCP-Extended Edition>
 * @package: DZCP-Extended Edition
 * @author: DZCP Developer Team || Hammermaps.de Developer Team
 * @link: http://www.dzcp.de || http://www.hammermaps.de
 */

if(_adminMenu != 'true') exit();

$show = show($dir."/ipban_form", array("newhead" => _ipban_new_head, "do" => "add", "ip_set" => '', "info" => '', "what" => _button_value_add));