<?php
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
feed();
$where = _site_news;
$title = $pagetitle." - ".$where."";
$dir = "news";
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
$time_end = generatetime();
$time = round($time_end - $time_start,4);
page($index, $title, $where, $time);

#######################
## OUTPUT BUFFER END ##
#######################
gz_output();
?>