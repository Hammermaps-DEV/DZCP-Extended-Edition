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
    $get = db("SELECT * FROM ".dba::get('votes')." WHERE id = '".convert::ToInt($_GET['id'])."'",false,true);
    if($get['intern'])
    {
        $qryv = db("SELECT * FROM ".dba::get('ipcheck')." WHERE what = 'vid_".$get['id']."' ORDER BY time DESC"); $show = ''; $color = 1;
        while($getv = _fetch($qryv))
        {
            $class = ($color % 2) ? "contentMainSecond" : "contentMainFirst"; $color++;
            $show .= show($dir."/voted_show", array("user" => autor($getv['ip']), "date" => date("d.m.y H:i",$getv['time'])._uhr, "class" => $class));
        }

        $index = show($dir."/voted", array("show" => $show));
    }
    else
        $index = error(_error_vote_show);
}