<?php
/**
 * <DZCP-Extended Edition>
 * @package: DZCP-Extended Edition
 * @author: DZCP Developer Team || Hammermaps.de Developer Team
 * @link: http://www.dzcp.de || http://www.hammermaps.de
 */

if(!defined('IS_DZCP'))
{
    include("../inc/buffer.php");
    include(basePath."/inc/debugger.php");
    include(basePath."/inc/config.php");
    include(basePath."/inc/common.php");
    header('Location: ../'.startpage('user'));
}

##############
## INCLUDES ##
##############
include(basePath."/user/helper.php");

##############
## SETTINGS ##
##############
$dir = "user";
$where = _site_user;

#########################
## Action Loader START ##
#########################
$IncludeAction=include_action($dir,(checkme() == "unlogged" ? 'login' : 'userlobby'));
$page=$IncludeAction['page']; $do=$IncludeAction['do']; $addon_dir=$IncludeAction['dir'];
$IncludeAction['include'] ? require_once $IncludeAction['file'] : $index = $IncludeAction['msg'];
#######################
## Action Loader END ##
#######################