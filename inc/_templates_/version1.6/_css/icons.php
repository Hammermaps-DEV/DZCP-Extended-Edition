<?php
function getIcons($dir)
{
    $dp = @opendir($dir);
    $allicons = array();
    while($icons = @readdir($dp))
    {
      if($icons != '.' && $icons != '..')
        array_push($allicons, $icons);
    }
    @closedir($dp);
    sort($allicons);

    return($allicons);
}

ob_start();
    header("Content-type: text/css");

    //Gameicons
    $games = getIcons('../../../images/gameicons/custom/');
    for($i=0; $i<count($games); $i++)
    {
        if(preg_match("=\.gif|.jpg|.png=Uis",$games[$i]))
        {
            echo "option[value=".preg_replace("#\.#","\.",$games[$i])."]:before {";
            echo "  content: url(\"../../../images/gameicons/custom/".$games[$i]."\");";
            echo "}";
        }
    }
ob_end_flush();