<?php
####################################
## Wird in einer Index ausgeführt ##
####################################
if (!defined('IS_DZCP'))
	exit();
	
#####################
## Userlogin Seite ##
#####################
  $index = set_cookie($prev.'tmpdir',$_GET['set']);

  header("Location: ".$_SERVER['HTTP_REFERER']);
?>