<?php
####################################
## Wird in einer Index ausgef�hrt ##
####################################
if (!defined('IS_DZCP'))
	exit();
	
#####################
## Userlogin Seite ##
#####################
  $_SESSION['lastvisit'] = data($userid, "time");

  $update = db("UPDATE ".$db['userstats']."
                SET `lastvisit` = '".((int)$_SESSION['lastvisit'])."'
                WHERE user = '".$userid."'");

  header("Location: ?action=userlobby");
?>

