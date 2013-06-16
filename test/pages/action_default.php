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


echo '<pre>';

print_r(db_stmt("SELECT * FROM `dzcp_partners` WHERE `id` = ? LIMIT 0 , 30",array('i', '4'),true));

echo '<p>';

$test = db_stmt("SELECT * FROM `dzcp_partners` LIMIT 0 , 30",array());
foreach($test as $get)
{
    print_r($get);
}




die('run');