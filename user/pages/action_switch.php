<?php
####################################
## Wird in einer Index ausgef�hrt ##
####################################
if (!defined('IS_DZCP'))
    exit();

#####################
## Userlogin Seite ##
#####################
set_cookie($prev.'tmpdir',$_GET['set']);
header("Location: ".$_SERVER['HTTP_REFERER']);
?>