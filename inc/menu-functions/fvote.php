<?php
/**
 * <DZCP-Extended Edition>
 * @package: DZCP-Extended Edition
 * @author: DZCP Developer Team || Hammermaps.de Developer Team
 * @link: http://www.dzcp.de || http://www.hammermaps.de
 */

function fvote($id, $ajax=false)
{
    if(!permission("votes")) $intern = ' AND intern = 0';
        $qry = db("SELECT * FROM ".dba::get('votes')."  WHERE id = '".$id."' ".$intern);

    if(_rows($qry))
    {
        $get = _fetch($qry); $results = '';
        $qryv = db("SELECT * FROM ".dba::get('vote_results')." WHERE vid = '".$get['id']."' ORDER BY id ASC");
        while($getv = _fetch($qryv))
        {
            $votebutton = "";
            if(($stimmen = sum(dba::get('vote_results'), " WHERE vid = '".$get['id']."'", "stimmen")) != 0)
            {
                if(ipcheck("vid_".$get['id']) || cookie::get('vid_'.$get['id']) != false || $get['closed'] == 1)
                {
                    $percent = round($getv['stimmen']/$stimmen*100,1);
                    $rawpercent = round($getv['stimmen']/$stimmen*100,0);
                    $balken = show(_votes_balken, array("width" => $rawpercent));

                    $votebutton = "";
                    $results .= show("forum/vote_results", array("answer" => string::decode($getv['sel']),
                                                                "percent" => $percent,
                                                                "stimmen" => $getv['stimmen'],
                                                                    "balken" => $balken));
                }
                else
                {
                    $votebutton = '<input id="contentSubmitFVote" type="submit" value="'._button_value_vote.'" class="voteSubmit" />';
                    $results .= show("forum/vote_vote", array("id" => $getv['id'], "answer" => string::decode($getv['sel'])));
                }
            }
            else
            {
                $votebutton = '<input id="contentSubmitFVote" type="submit" value="'._button_value_vote.'" class="voteSubmit" />';
                $results .= show("forum/vote_vote", array("id" => $getv['id'], "answer" => string::decode($getv['sel'])));
            }
        }

        $getf = db("SELECT id,kid FROM ".dba::get('f_threads')." WHERE vote = '".$get['id']."'",false,true);
        $vote = show("forum/vote", array("titel" => string::decode($get['titel']), "vid" => $get['id'], "fid" => $getf['id'], "kid" => $getf['kid'], "umfrage" => _forum_vote, "results" => $results,
                                         "votebutton" => $votebutton, "stimmen" => $stimmen));
    }

    return empty($vote) ? '' : ($ajax ? $vote : '<div id="navFVote">'.$vote.'</div>');
}