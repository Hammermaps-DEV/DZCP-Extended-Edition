<?php
/**
 * <DZCP-Extended Edition>
 * @package: DZCP-Extended Edition
 * @author: DZCP Developer Team || Hammermaps.de Developer Team
 * @link: http://www.dzcp.de || http://www.hammermaps.de
 */

if(_adminMenu != 'true') exit();

$get = db("SELECT katimg,id FROM ".dba::get('newskat')." WHERE id = '".convert::ToInt($_GET['id'])."'",false,true);

if(file_exists(basePath."/inc/images/uploads/newskat/".$get['katimg']))
    @unlink(basePath."/inc/images/uploads/newskat/".$get['katimg']);

db("DELETE FROM ".dba::get('newskat')." WHERE id = '".$get['id']."'");
$show = info(_config_newskat_deleted, "?index=admin&amp;admin=news");