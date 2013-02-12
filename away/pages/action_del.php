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
    if($chkMe == "unlogged" || $chkMe < "2")
    {
        $index = error(_error_wrong_permissions, 1);
    } else {
        $qry = db("DELETE FROM ".$db['away']." WHERE id = '".convert::ToInt($_GET['id'])."'");

        $index = info(_away_successful_del, "../away/");
    }
}
?>