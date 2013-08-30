<?php
/**
 * <DZCP-Extended Edition>
 * @package: DZCP-Extended Edition
 * @author: DZCP Developer Team || Hammermaps.de Developer Team
 * @link: http://www.dzcp.de || http://www.hammermaps.de
 */

if(_adminMenu != 'true') exit();

$qry = db("SELECT * FROM ".dba::get('dl_kat')."
                   WHERE id = '".convert::ToInt($_GET['id'])."'");
$get = _fetch($qry);

$show = show($dir."/dlkats_form", array("newhead" => _dl_edit_head,
                                               "do" => "editkat&amp;id=".$_GET['id']."",
                                               "kat" => string::decode($get['name']),
                                               "what" => _button_value_edit,
                                               "dlkat" => _dl_dlkat));