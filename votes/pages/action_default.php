<?php
/**
 * <DZCP-Extended Edition>
 * @package: DZCP-Extended Edition
 * @author: DZCP Developer Team || Hammermaps.de Developer Team
 * @link: http://www.dzcp.de || http://www.hammermaps.de
 */

if (!defined('IS_DZCP')) exit();
if (_version < '1.0')
    $index = _version_for_page_outofdate;
else
{
    $vote_permission = permission('votes');
    $whereIntern = (!$vote_permission ? ' WHERE intern = 0 ' : '');
    $fvote = (!settings('forum_vote') ? !empty($whereIntern) ? ' AND forum = 0 ' : ' WHERE forum = 0 ' : '');
    $orderIntern = (!$vote_permission ? 'datum DESC' : ' intern DESC');
    $qry = db('SELECT * FROM ' . dba::get('votes') . $whereIntern . $fvote . ' ORDER BY ' . $orderIntern); $show = ''; $color2 = 1;
    while($get = _fetch($qry))
    {
        $qryv = db('SELECT * FROM ' . dba::get('vote_results') . ' WHERE vid = ' . $get['id'] . ' ORDER BY id');
        $vid = 'vid_' . convert::ToInt($get['id']); $intern = ''; if($get['intern']) $intern = _votes_intern;

        $ipcheck = !count_clicks('vote',$get['id'],0,false);
        $stimmen_summe = sum(dba::get('vote_results')," WHERE vid = '".$get['id']."'","stimmen"); $color = 1; $results ='';

        while($getv = _fetch($qryv))
        {
            $class = ($color % 2) ? "contentMainSecond" : "contentMainFirst"; $color++; $votebutton = '';
            if($ipcheck || cookie::get('vid_'.$get['id']) != false || $get['closed'])
            {
                $percent = $stimmen_summe && $getv['stimmen'] ? round($getv['stimmen']/$stimmen_summe*100,2) : 0;
                $rawpercent = $stimmen_summe && $getv['stimmen'] ? round($getv['stimmen']/$stimmen_summe*100,0) : 0;
                $balken = show(_votes_balken, array("width" => $rawpercent));
                $result_head = _votes_results_head;
                $results .= show($dir."/votes_results", array("answer" => string::decode($getv['sel']),
                                                              "percent" => $percent,
                                                              "lng_stimmen" => _votes_stimmen,
                                                              "class" => $class,
                                                              "stimmen" => $getv['stimmen'],
                                                              "balken" => $balken));
            }
            else
            {
                $result_head = _votes_results_head_vote;
                $votebutton = '<input id="voteSubmit_'.$get['id'].'" type="submit" value="'._button_value_vote.'" class="submit" />';
                $results .= show($dir."/votes_vote", array("id" => $getv['id'], "answer" => string::decode($getv['sel']), "class" => $class));
            }
        }

        $showVoted = '';
        if($get['intern'] && $stimmen_summe && ($get['von'] == userid() || permission('votes')))
        {
            $showVoted = ' <a href="?action=show&amp;id=' . convert::ToInt($get['id']) .
            '"><img src="../inc/images/lupe.gif" alt="" title="' . _show_who_voted . '" class="icon" /></a>';
        }

        if($get['id'] == (isset($_GET['id']) ? $_GET['id'] : 0))
        {
            $moreicon = "collapse";
            $display = "";
        }
        else
        {
            $moreicon = "expand";
            $display = "none";
        }

        $ftitel = ($get['forum'] ? string::decode($get['titel']).' (Forum)' : string::decode($get['titel']));
        $titel = show(_votes_titel, array("titel" => $ftitel, "vid" => $get['id'], "icon" => $moreicon, "intern" => $intern));
        $closed = ($get['closed'] ? _closedicon_votes : '');
        $class = ($color2 % 2) ? "contentMainSecond" : "contentMainFirst"; $color2++;
        $show .= show($dir."/votes_show", array("datum" => date("d.m.Y", $get['datum']),
                                                "titel" => $titel,
                                                "vid" => $get['id'],
                                                "display" => $display,
                                                "result_head" => $result_head,
                                                "results" => $results,
                                                "show" => $showVoted,
                                                "closed" => $closed,
                                                "autor" => autor($get['von']),
                                                "class" => $class,
                                                "votebutton" => $votebutton,
                                                "stimmen" => $stimmen_summe));
    }

    $index = show($dir."/votes", array("head" => _votes_head, "show" => $show));
}
