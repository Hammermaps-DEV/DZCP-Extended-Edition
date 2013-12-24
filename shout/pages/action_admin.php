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
    if(!permission("shoutbox"))
        $index = error(_error_wrong_permissions);
    else
    {
        if(isset($_GET['do']) ? ($_GET['do'] == "delete") : false)
        {
            db("DELETE FROM ".dba::get('shout')." WHERE id = '".convert::ToInt($_GET['id'])."'");
            Cache::delete('shoutbox');
            header("Location: ".$_SERVER['HTTP_REFERER'].'#shoutbox');
        }
    }
}