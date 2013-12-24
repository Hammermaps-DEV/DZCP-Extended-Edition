<?php
/**
 * <DZCP-Extended Edition>
 * @package: DZCP-Extended Edition
 * @author: DZCP Developer Team || Hammermaps.de Developer Team
 * @link: http://www.dzcp.de || http://www.hammermaps.de
 */

#########################
## OUTPUT BUFFER START ##
#########################
include(dirname(__FILE__)."/inc/buffer.php");

###############
## Installer ##
###############
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
    ##############
    ## INCLUDES ##
    ##############
    include(basePath."/inc/debugger.php");
    include(basePath."/inc/config.php");
    include(basePath."/inc/common.php");

    $where = ""; $dir = ""; $index = ""; $title = "";
    if(isset($_GET['index']))
        require_once(API_CORE::load_index_side($_GET['index']));
    else
        header('Location: '.startpage());
}

#################
## SITE OUTPUT ##
#################
if(empty($title))
    $title = $pagetitle." - ".convert::ToString($where);

$time_end = generatetime();
$time = round($time_end - $time_start,4);
page($index, $title, $where, $time);

#######################
## OUTPUT BUFFER END ##
#######################
gz_output();