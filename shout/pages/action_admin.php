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

if (_version < '1.0') //Mindest Version pruefen
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
            header("Location: ".$_SERVER['HTTP_REFERER'].'#shoutbox');
        }
    }
}