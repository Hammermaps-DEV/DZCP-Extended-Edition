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
include(basePath."/inc/config.php");
include(basePath."/inc/bbcode.php");

##############
## SETTINGS ##
##############
$dir = "clanwars";
$where = _site_clanwars;
$index = "";

##############
## SECTIONS ##
##############

#########################
## Action Loader START ##
#########################
$IncludeAction=include_action($dir,'default');
$page=$IncludeAction['page']; $do=$IncludeAction['do'];
$IncludeAction['include'] ? require_once $IncludeAction['file'] : $index = $IncludeAction['msg'];
#######################
## Action Loader END ##
#######################

##############
## SETTINGS ##
##############
$title = $pagetitle." - ".$where."";
$time_end = generatetime();
$time = round($time_end - $time_start,4);
page($index, $title, $where, $time);

#######################
## OUTPUT BUFFER END ##
#######################
gz_output();
?>