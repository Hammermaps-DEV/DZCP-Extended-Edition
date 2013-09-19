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
include("../inc/buffer.php");

##############
## INCLUDES ##
##############
include(basePath."/inc/debugger.php");
include(basePath."/inc/config.php");
include(basePath."/inc/common.php");

##############
## SETTINGS ##
##############
$dir = "awards";
$where = _site_awards;
$index = "";

##############
## SECTIONS ##
##############

#########################
## Action Loader START ##
#########################
/*
$IncludeAction=include_action($dir,'default');
$page=$IncludeAction['page']; $do=$IncludeAction['do']; $addon_dir=$IncludeAction['dir'];
$IncludeAction['include'] ? require_once $IncludeAction['file'] : $index = $IncludeAction['msg'];
*/
$index = '<div style="text-align:center; color:#FF0000;"><b>Sry, This page is not available, massive MySQL errors!<p>This Side is on TODO list^^</b></div>';
#######################
## Action Loader END ##
#######################

##############
## SETTINGS ##
##############
$title = $pagetitle." - ".convert::ToString($where);
$time_end = generatetime();
$time = round($time_end - $time_start,4);
page($index, $title, $where, $time);

#######################
## OUTPUT BUFFER END ##
#######################
gz_output();