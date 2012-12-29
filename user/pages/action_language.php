<?php
####################################
## Wird in einer Index ausgeführt ##
####################################
if (!defined('IS_DZCP'))
	exit();
	
#####################
## Userlogin Seite ##
#####################
  if(file_exists(basePath."/inc/lang/languages/".$_GET['set'].".php"))
    $index = set_cookie($prev.'language',$_GET['set']);

  header("Location: ".$_SERVER['HTTP_REFERER']);
?>