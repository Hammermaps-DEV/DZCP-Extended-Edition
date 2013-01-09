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

if (_version < '1.0') //Mindest Version pruefen
    $index = _version_for_page_outofdate;
else
{
    ################
    ## User Erase ##
    ################
    $_SESSION['lastvisit'] = data($userid, "time");
    db("UPDATE ".$db['userstats']." SET `lastvisit` = '".((int)$_SESSION['lastvisit'])."' WHERE user = '".$userid."'");
    header("Location: ?action=userlobby");
}
?>

