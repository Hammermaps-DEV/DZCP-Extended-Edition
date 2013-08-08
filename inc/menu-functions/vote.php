<?php
/**
 * <DZCP-Extended Edition>
 * @package: DZCP-Extended Edition
 * @author: DZCP Developer Team || Hammermaps.de Developer Team
 * @link: http://www.dzcp.de || http://www.hammermaps.de
 */

function vote($ajax = false)
{
    $qry = db("SELECT id,closed,titel FROM ".dba::get('votes')." WHERE menu = '1' AND forum = 0");
    if(_rows($qry))
    {
        $get = _fetch($qry);
        $qryv = db("SELECT * FROM ".dba::get('vote_results')." WHERE vid = '".$get['id']."' ORDER BY what");
        $results = ""; $votebutton = ""; $stimmen = "";
        while ($getv = _fetch($qryv))
        {
            $stimmen = sum(dba::get('vote_results'), " WHERE vid = '".$get['id']."'", "stimmen");
            if($stimmen != 0)
            {
                if(ipcheck("vid_".$get['id']) || cookie::get('vid_'.$get['id']) != false || $get['closed'] == 1)
                {
                    $percent = round($getv['stimmen']/$stimmen*100,1);
                    $rawpercent = round($getv['stimmen']/$stimmen*100,0);
                    $results .= show("menu/vote_results", array("answer" => string::decode($getv['sel']), "percent" => $percent, "stimmen" => $getv['stimmen'], "width" => $rawpercent));
                }
                else
                {
                    $votebutton = '<input id="contentSubmitVote" type="submit" value="'._button_value_vote.'" class="voteSubmit" />';
                    $results .= show("menu/vote_vote", array("id" => $getv['id'], "answer" => string::decode($getv['sel'])));
                }
            }
            else
            {
                $votebutton = '<input id="contentSubmitVote" type="submit" value="'._button_value_vote.'" class="voteSubmit" />';
                $results .= show("menu/vote_vote", array("id" => $getv['id'], "answer" => string::decode($getv['sel'])));
            }
        }

        $vote = show("menu/vote", array("titel" => string::decode($get['titel']), "vid" => $get['id'], "results" => $results, "votebutton" => $votebutton, "stimmen" => $stimmen));
    }

    return empty($vote) ? '<center style="margin:2px 0">'._vote_menu_no_vote.'</center>' : ($ajax ? $vote : '<div id="navVote">'.$vote.'</div>');
}