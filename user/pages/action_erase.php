<?php
/**
 * <DZCP-Extended Edition>
 * @package: DZCP-Extended Edition
 * @author: DZCP Developer Team || Hammermaps.de Developer Team
 * @link: http://www.dzcp.de || http://www.hammermaps.de
 */

if (!defined('IS_DZCP')) exit();
if (_version < '1.0')
    $index = _version_for_page_outofdate;
else
{
    ################
    ## User Erase ##
    ################
    $_SESSION['lastvisit'] = data(userid(), "time");
    db("UPDATE ".dba::get('userstats')." SET `lastvisit` = '".convert::ToInt($_SESSION['lastvisit'])."' WHERE user = '".userid()."'");
    header("Location: ?index=user&action=userlobby");
}