<?php
/**
 * <DZCP-Extended Edition>
 * @package: DZCP-Extended Edition
 * @author: DZCP Developer Team || Hammermaps.de Developer Team
 * @link: http://www.dzcp.de || http://www.hammermaps.de
 */

####################################
## Wird in einer Index ausgeführt ##
####################################
if (!defined('IS_DZCP'))
    exit();

###################
## Link Besuchen ##
###################
$get = db("SELECT url,id,hits FROM ".$db['links']." WHERE `id` = '".((int)$_GET['id'])."'",false,true);

if(count_clicks('link',$get['id']))
    db("UPDATE ".$db['links']." SET `hits` = ".($get['hits'] + 1)." WHERE `id` = '".$get['id']."'");

header("Location: ".$get['url']);
?>