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
	
#######################
## User Logout Seite ##
#######################
$where = _site_user_logout;

## Ereignis in den Adminlog schreiben ##
wire_ipcheck("logout(".$userid.")");

## User Abmelden ##
logout(); //Find in BBCode

## Zur News Seite weiterleiten ##
header("Location: ../news/");
?>