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

if (_version < '1.0') //Mindest Version pruefen
    $index = _version_for_page_outofdate;
else
{
    $get = db("SELECT intern,id FROM ".dba::get('votes')." WHERE id = '".convert::ToInt($_GET['id'])."'",false,true);
    if($get['intern'] || checkme() == 4)
    {
        $qryv = db("SELECT * FROM ".dba::get('ipcheck')." WHERE what = 'vid_".$get['id']."' ORDER BY time DESC"); $show = ''; $color = 1;
        while($getv = _fetch($qryv))
        {
            $sqluid = db("SELECT uid FROM `".dba::get('clicks_ips')."` WHERE `ids` = ".$get['id']." AND `side` = 'vote' LIMIT 1");

            if(_rows($sqluid))
                $getuid = _fetch($sqluid);

            $class = ($color % 2) ? "contentMainSecond" : "contentMainFirst"; $color++;;
            $show .= show($dir."/voted_show", array("user" => _rows($sqluid) ? autor($getuid['uid']) : $getv['ip'], "date" => date("d.m.y H:i",$getv['time'])._uhr, "class" => $class));
        }

        $index = show($dir."/voted", array("show" => $show));
    }
    else
        $index = error(_error_vote_show);
}