<?php
/**
 * <DZCP-Extended Edition>
 * @package: DZCP-Extended Edition
 * @author: DZCP Developer Team || Hammermaps.de Developer Team
 * @link: http://www.dzcp.de || http://www.hammermaps.de
 */

function perm_sendnews($uID)
{
    $team = db("SELECT s1.news FROM ".dba::get('permissions')." AS s1 LEFT JOIN ".dba::get('userpos')." AS s2 ON s1.pos = s2.posi WHERE s2.user = '".convert::ToInt($uID)."' AND s1.news = '1' AND s2.posi != '0'",true);
    $user = db("SELECT id FROM ".dba::get('permissions')." WHERE user = '".convert::ToInt($uID)."' AND `news` = '1'",true);
    return ($user || $team ? true : false);
}