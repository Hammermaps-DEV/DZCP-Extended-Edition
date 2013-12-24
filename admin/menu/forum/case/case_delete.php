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

if(file_exists(basePath."/inc/images/uploads/forum/mainkat/".convert::ToInt($_GET['id']).".jpg"))
    @unlink(basePath."/inc/images/uploads/forum/mainkat/".convert::ToInt($_GET['id']).".jpg");
elseif(file_exists(basePath."/inc/images/uploads/forum/mainkat/".convert::ToInt($_GET['id']).".png"))
    @unlink(basePath."/inc/images/uploads/forum/mainkat/".convert::ToInt($_GET['id']).".png");
elseif(file_exists(basePath."/inc/images/uploads/forum/mainkat/".convert::ToInt($_GET['id']).".gif"))
    @unlink(basePath."/inc/images/uploads/forum/mainkat/".convert::ToInt($_GET['id']).".gif");

$show = info(_config_forum_kat_deleted, "?index=admin&amp;admin=forum");
