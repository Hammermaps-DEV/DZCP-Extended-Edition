<?php
/**
 * <DZCP-Extended Edition>
 * @package: DZCP-Extended Edition
 * @author: DZCP Developer Team || Hammermaps.de Developer Team
 * @link: http://www.dzcp.de || http://www.hammermaps.de
 */

if(_adminMenu != 'true') exit();

$get = db("SELECT id,kat FROM ".dba::get('c_kats')." WHERE id = '".convert::ToInt($_GET['id'])."'",false,true);
$show = show($dir."/form_clankasse_edit", array( "do" => "editkat&amp;id=".$_GET['id']."", "kat" => string::decode($get['kat'])));