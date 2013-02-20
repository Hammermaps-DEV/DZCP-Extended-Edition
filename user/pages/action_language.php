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
    ###################
    ## User Language ##
    ###################
    if(isset($_GET['set']) && !empty($_GET['set']) && file_exists(basePath."/inc/lang/languages/".$_GET['set'].".php"))
    {
        cookie::put('language', $_GET['set']);
        cookie::save();
    }

    header("Location: ".$_SERVER['HTTP_REFERER']);
}
?>