<?php
/**
 * <DZCP-Extended Edition>
 * @package: DZCP-Extended Edition
 * @author: DZCP Developer Team || Hammermaps.de Developer Team
 * @link: http://www.dzcp.de || http://www.hammermaps.de
 */

if(_adminMenu != 'true') exit();

$show = show($dir."/dlkats_form", array("newhead" => _dl_new_head,
"do" => "add",
"kat" => "",
"what" => _button_value_add,
"dlkat" => _dl_dlkat));