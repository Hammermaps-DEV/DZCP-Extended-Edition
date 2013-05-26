<?php
/**
 * <DZCP-Extended Edition>
 * @package: DZCP-Extended Edition
 * @author: DZCP Developer Team || Hammermaps.de Developer Team
 * @link: http://www.dzcp.de || http://www.hammermaps.de
 */

//-> Prueft den Zahlstatus eines Users (Clankasse)
function paycheck($tocheck)
{
    return ($tocheck >= time() ? true : false);
}