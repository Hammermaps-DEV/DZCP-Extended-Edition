<?php
/**
 * <DZCP-Extended Edition>
 * @package: DZCP-Extended Edition
 * @author: DZCP Developer Team || Hammermaps.de Developer Team
 * @link: http://www.dzcp.de || http://www.hammermaps.de
 */

/**
 * Prueft ob ein Ereignis neu ist.
 *
 * @return boolean
 */
function check_is_new($datum = 0)
{
    global $db,$userid;

    if($userid != 0 && !empty($userid) && !empty($datum))
    {
        if(convert::ToInt($datum) >= userstats($userid, 'lastvisit'))
            return true;
    }

    return false;
}
?>