<?php
## OUTPUT BUFFER START ##
include("../inc/buffer.php");

## INCLUDES ##
$ajaxJob = true;
include(basePath."/inc/config.php");
include(basePath."/inc/bbcode.php");

## FUNCTIONS ##
require_once(basePath."/inc/menu-functions/server.php");
require_once(basePath."/inc/menu-functions/shout.php");
require_once(basePath."/inc/menu-functions/teamspeak.php");
require_once(basePath."/inc/menu-functions/kalender.php");
require_once(basePath."/inc/menu-functions/team.php");
## SETTINGS ##

$dir = "sites";
## SECTIONS ##
switch (isset($_GET['i']) ? $_GET['i'] : ''):
    case 'kalender';
        die(kalender($_GET['month'],$_GET['year']));
    break;
    case 'teams';
        die(team($_GET['tID']));
    break;
    case 'server';
        die('<table class="hperc" cellspacing="0">'.server($_GET['serverID']).'</table>');
    break;
    case 'shoutbox';
        die('<table class="hperc" cellspacing="1">'.shout(1).'</table>');
    break;
    case 'teamspeak';
        die('<table class="hperc" cellspacing="0">'.teamspeak().'</table>');
    break;
    case 'xfire';
        die(xfire($_GET['username']));
    break;
endswitch;
?>