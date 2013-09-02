<?php
/**
 * <DZCP-Extended Edition>
 * @package: DZCP-Extended Edition
 * @author: DZCP Developer Team || Hammermaps.de Developer Team
 * @link: http://www.dzcp.de || http://www.hammermaps.de
 */

if(_adminMenu != 'true') exit();

$what = db("SELECT id FROM ".dba::get('f_skats')." WHERE sid = '".convert::ToInt($_GET['id'])."'");
$get = _fetch($what);
$qry = db("DELETE FROM ".dba::get('f_kats')." WHERE id = '".convert::ToInt($_GET['id'])."'");
$qry = db("DELETE FROM ".dba::get('f_threads')." WHERE kid = '".convert::ToInt($get['id'])."'");
$qry = db("DELETE FROM ".dba::get('f_posts')." WHERE kid = '".convert::ToInt($get['id'])."'");
$qry = db("DELETE FROM ".dba::get('f_skats')."  WHERE sid = '".convert::ToInt($_GET['id'])."'");

$show = info(_config_forum_kat_deleted, "?admin=forum");
