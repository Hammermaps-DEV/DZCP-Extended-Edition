<?php
#############################################
##### Code for 'DZCP - Extended Edition #####
###### DZCP - Extended Edition >= 1.0 #######
#############################################

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
        set_cookie($prev.'language',$_GET['set']);

    header("Location: ".$_SERVER['HTTP_REFERER']);
}
?>