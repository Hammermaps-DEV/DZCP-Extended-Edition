<?php
/**
 * <DZCP-Extended Edition>
 * @package: DZCP-Extended Edition
 * @author: DZCP Developer Team || Hammermaps.de Developer Team
 * @link: http://www.dzcp.de || http://www.hammermaps.de
 */
require_once(dirname(__FILE__)."/inc/buffer.php");
if(file_exists(basePath."/inc/mysql.php") && file_exists(basePath."/inc/mysql_salt.php"))
{
    require_once(basePath."/inc/mysql.php");
    require_once(basePath."/inc/mysql_salt.php");
}
else
{ $sql_host = ''; $sql_user = ''; $sql_pass = ''; $sql_db = ''; $sql_prefix = ''; $sql_salt = '';}

if(empty($sql_user) && empty($sql_pass) && empty($sql_db))
    header('Location: _installer/index.php');
else
{
    require_once(basePath."/inc/debugger.php");
    require_once(basePath."/inc/config.php");
    require_once(basePath."/inc/common.php");
    header('Location: '.startpage());
}