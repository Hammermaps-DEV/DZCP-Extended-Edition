<?php
/**
 * <DZCP-Extended Edition>
 * @package: DZCP-Extended Edition
 * @author: DZCP Developer Team || Hammermaps.de Developer Team
 * @link: http://www.dzcp.de || http://www.hammermaps.de
 */
define('basePath', dirname(__FILE__));
if(file_exists(basePath."/inc/mysql.php"))
    require_once(basePath."/inc/mysql.php");
else
{ $sql_host = ''; $sql_user = ''; $sql_pass = ''; $sql_db = ''; $sql_prefix = ''; }

if(empty($sql_user) && empty($sql_pass) && empty($sql_db))
    header('Location: _installer/index.php');
else
{
    require_once(basePath."/inc/debugger.php");
    require_once(basePath."/inc/config.php");
    require_once(basePath."/inc/buffer.php");
    require_once(basePath."/inc/bbcode.php");
    header('Location: '.(cookie::get('id') != false && cookie::get('pkey') != false ? 'user/?action=userlobby' : 'news/'));
}
?>