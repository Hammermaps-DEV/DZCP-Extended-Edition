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
include(basePath."/inc/bbcode.php");

##############
## SETTINGS ##
##############
$dir = "votes";
$where = _site_votes;
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
$whereami = preg_replace_callback("#autor_(.*?)$#",create_function('$id', 'return data("$id[1]","nick");'),$where);
$title = $pagetitle." - ".$whereami."";
$time_end = generatetime();
$time = round($time_end - $time_start,4);
page($index, $title, $where, $time);

#######################
## OUTPUT BUFFER END ##
#######################
gz_output();
?>