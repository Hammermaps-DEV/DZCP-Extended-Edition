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

if (_version < 1.0) //Mindest Version pruefen
    $index = _version_for_page_outofdate;
else
{
    #################
    ## User Logout ##
    #################
    $where = _site_user_logout;

    ## Ereignis in den Adminlog schreiben ##
    wire_ipcheck("logout(".convert::ToInt($userid).")");

    ## User Abmelden ##
    logout(); //Find in BBCode

    ## Zur News Seite weiterleiten ##
    header("Location: ../news/");
}
?>