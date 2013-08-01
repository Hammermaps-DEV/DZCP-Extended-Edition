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
    switch (isset($_GET['what']) ? $_GET['what'] : '')
    {
        case 'vote': //Menu Vote
            if(empty($_POST['vote']))
                $index = error(_vote_no_answer);
            else
            {
                $get = db("SELECT intern,id,closed FROM ".dba::get('votes')." WHERE id = '".($id=convert::ToInt($_GET['id']))."'",false,true);
                if($get['intern'])
                {
                    if(!count_clicks('vote',$get['id']))
                        $index = error(_error_voted_again);
                    else if($get['closed'])
                        $index = error(_error_vote_closed);
                    else
                    {
                        db("UPDATE ".dba::get('userstats')." SET `votes` = votes+1 WHERE user = '".userid()."'");
                        db("UPDATE ".dba::get('vote_results')." SET `stimmen` = stimmen+1 WHERE id = '".convert::ToInt($_POST['vote'])."'");
                        wire_ipcheck($vid);
                    }

                    if(!isset($_GET['ajax']))
                        $index = info(_vote_successful, "?id=".$id."");
                }
                else
                {
                   if(!count_clicks('vote',($id=convert::ToInt($_GET['id']))))
                        $index = error(_error_voted_again);
                   else if($get['closed'])
                        $index = error(_error_vote_closed);
                    else
                    {
                        if(userid() != 0)
                            db("UPDATE ".dba::get('userstats')." SET `votes` = votes+1 WHERE user = '".userid()."'");

                        db("UPDATE ".dba::get('vote_results')." SET `stimmen` = stimmen+1 WHERE id = '".convert::ToInt($_POST['vote'])."'");

                        wire_ipcheck("vid_".$id);
                        wire_ipcheck("vid(".$id.")");

                        if(!isset($_GET['ajax']))
                            $index = info(_vote_successful, "?id=".$id."");
                    }

                    $cookie = (userid() != 0 ? userid() : "voted");
                    cookie::put('vid_'.$id, $cookie);
                }
            }

            if(isset($_GET['ajax']))
            {
                header("Content-type: application/x-www-form-urlencoded;charset=utf-8");
                include(basePath.'/inc/menu-functions/vote.php');
                exit('<table class="navContent" cellspacing="0">'.vote(true).'</table>');
            }
        break;

        case 'fvote': //Forum Vote
            if(empty($_POST['vote']))
                $index = error(_vote_no_answer);
            else
            {
                $get = db("SELECT id,closed FROM ".dba::get('votes')." WHERE id = '".convert::ToInt($_GET['id'])."'",false,true);

                if(!count_clicks('vote',$get['id']))
                    $index = error(_error_voted_again);
                else if($get['closed'])
                    $index = error(_error_vote_closed);
                else
                {
                    if(userid() != 0)
                        db("UPDATE ".dba::get('userstats')." SET `votes` = votes+1 WHERE user = '".userid()."'");

                    db("UPDATE ".dba::get('vote_results')." SET `stimmen` = stimmen+1 WHERE id = '".convert::ToInt($_POST['vote'])."'");

                    wire_ipcheck("vid_".convert::ToInt($get['id']));
                    wire_ipcheck("vid(".convert::ToInt($get['id']).")");

                    if(!isset($_GET['fajax']))
                        $index = info(_vote_successful, "forum/?action=showthread&amp;kid=".$_POST['kid']."&amp;id=".$_POST['fid']."");
                }

                $cookie = (userid() != 0 ? userid() : 'voted');
                cookie::put('vid_'.convert::ToInt($_GET['id']), $cookie);
            }

            if(isset($_GET['fajax']) && isset($_GET['id']) && !empty($_GET['id']))
            {
                include_once(basePath.'/inc/menu-functions/fvote.php');
                header("Content-type: application/x-www-form-urlencoded;charset=utf-8");
                echo fvote(convert::ToInt($_GET['id']), true);
                exit();
            }
        break;
    }
}