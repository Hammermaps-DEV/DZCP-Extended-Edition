<?php
/**
 * <DZCP-Extended Edition>
 * @package: DZCP-Extended Edition
 * @author: DZCP Developer Team || Hammermaps.de Developer Team
 * @link: http://www.dzcp.de || http://www.hammermaps.de
 */

if(_adminMenu != 'true') exit();

$qry = db("SELECT sid FROM ".dba::get('f_skats')."
                     WHERE id = '".convert::ToInt($_GET['id'])."'");
$get = _fetch($qry);

$del = db("DELETE FROM ".dba::get('f_skats')."
                     WHERE id = '".convert::ToInt($_GET['id'])."'");

$del = db("DELETE FROM ".dba::get('f_threads')."
                     WHERE kid = '".convert::ToInt($_GET['id'])."'");

$del = db("DELETE FROM ".dba::get('f_posts')."
                     WHERE kid = '".convert::ToInt($_GET['id'])."'");

$show = info(_config_forum_skat_deleted, "?admin=forum&amp;expand=".convert::ToInt($get['sid'])."");

