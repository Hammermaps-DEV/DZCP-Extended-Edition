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
    $fvote = '';
    if($forum_vote == 0) {
        $fvote = ' AND forum = 0 ';
    }
    if(!permission('votes')) {
        $whereIntern = ' WHERE intern = 0 ';
        $orderIntern = '';
    } else {
        $whereIntern = '';
        $orderIntern = ' intern DESC,';
    }
    $qry = db('SELECT * FROM ' . $db['votes'] .
            $whereIntern . $fvote . ' ORDER BY ' . $orderIntern .
            ' datum DESC');
    while($get = _fetch($qry)) {
        $qryv = db('SELECT * FROM ' . $db['vote_results'] .
                ' WHERE vid = ' . (int) $get['id'] .
                ' ORDER BY id');
        $results = '';
        $check = '';
        $stimmen = 0;
        $vid = 'vid_' . (int) $get['id'];
        if($get['intern'] == 1) {
            $showVoted = '';
            $check = db('SELECT * FROM ' . $db['ipcheck'] .
                    ' WHERE what = "' . $vid .
                    '" AND ip = ' . (int) $userid . '');

            $ipcheck = _rows($check) == 1;
            $intern = _votes_intern;
        } else {
            $ipcheck = false;
            $intern = '';
        }
        $hostIpcheck = ipcheck($vid);
        while($getv = _fetch($qryv)) {
            $stimmen += $getv['stimmen'];
            $class = ($color % 2) ? "contentMainSecond" : "contentMainFirst"; $color++;
            if($hostIpcheck || $ipcheck || isset($_COOKIE[$prev."vid_".$get['id']]) || $get['closed'] == 1) {
                $percent = @round($getv['stimmen']/$stimmen*100,2);
                $rawpercent = @round($getv['stimmen']/$stimmen*100,0);

                $balken = show(_votes_balken, array("width" => $rawpercent));

                $result_head = _votes_results_head;
                $votebutton = "";
                $results .= show($dir."/votes_results", array("answer" => re($getv['sel']),
                        "percent" => $percent,
                        "lng_stimmen" => _votes_stimmen,
                        "class" => $class,
                        "stimmen" => $getv['stimmen'],
                        "balken" => $balken));
            } else {
                $result_head = _votes_results_head_vote;
                $votebutton = '<input id="voteSubmit_'.$get['id'].'" type="submit" value="'._button_value_vote.'" class="submit" />';
                $results .= show($dir."/votes_vote", array("id" => $getv['id'],
                        "answer" => re($getv['sel']),
                        "class" => $class));
            }
        }

        if($get['intern'] == 1 && $stimmen != 0 && ($get['von'] == $userid || permission('votes'))) {
            $showVoted = ' <a href="?action=showvote&amp;id=' . (int) $get['id'] .
            '"><img src="../inc/images/lupe.gif" alt="" title="' .
            _show_who_voted . '" class="icon" /></a>';
        }

        if(($_GET['action'] == "show" && $get['id'] == $_GET['id']) || isset($_GET['show']) && $get['id'] == $_GET['show'])
        {
            $moreicon = "collapse";
            $display = "";
        } else {
            $moreicon = "expand";
            $display = "none";
        }

        if($get['forum'] == 1) $ftitel = re($get['titel']).' (Forum)';
        else $ftitel = re($get['titel']);

        $titel = show(_votes_titel, array("titel" => $ftitel,
                "vid" => $get['id'],
                "icon" => $moreicon,
                "intern" => $intern));

        if($get['closed'] == 1) $closed = _closedicon_votes;
        else                    $closed = "";

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
                "menu" => $menu,
                "class" => $class,
                "votebutton" => $votebutton,
                "stimmen" => $stimmen));
    }

    $index = show($dir."/votes", array("head" => _votes_head,
            "show" => $show,
            "titel" => _titel,
            "autor" => _autor,
            "datum" => _datum,
            "stimmen" => _votes_stimmen));
}
?>