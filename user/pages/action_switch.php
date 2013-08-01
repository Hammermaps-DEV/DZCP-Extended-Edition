<?php
/**
 * <DZCP-Extended Edition>
 * @package: DZCP-Extended Edition
 * @author: DZCP Developer Team || Hammermaps.de Developer Team
 * @link: http://www.dzcp.de || http://www.hammermaps.de
 */

if (!defined('IS_DZCP')) exit();

#####################
## Template switch ##
#####################
cookie::put('tmpdir', $_GET['set']);
cookie::save();
header("Location: ".$_SERVER['HTTP_REFERER']);