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
    header('Location: ../'.startpage('kalender'));
}

##############
## INCLUDES ##
##############
include(basePath."/kalender/helper.php");

##############
## SETTINGS ##
##############
$dir = "kalender";
$where = _site_kalender;

#########################
## Action Loader START ##
#########################
$IncludeAction=include_action($dir,'default');
$page=$IncludeAction['page']; $do=$IncludeAction['do']; $addon_dir=$IncludeAction['dir'];
$IncludeAction['include'] ? (($fcache = Cache::file_to_cache($IncludeAction['file'])) && $fcache['use_eval'] ? eval($fcache['eval']) : require_once($IncludeAction['file'])) : $index = $IncludeAction['msg'];
#######################
## Action Loader END ##
#######################