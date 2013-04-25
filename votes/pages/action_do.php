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
    if($_GET['what'] == "vote")
    {
        if(empty($_POST['vote']))
        {
            $index = error(_vote_no_answer);
        } else {
            $get = db("SELECT * FROM ".$db['votes']." WHERE id = '".convert::ToInt($_GET['id'])."'",false,true);

            if($get['intern'] == 1)
            {
                $vid = "vid_".$_GET['id'];
                $ipcheck = db("SELECT * FROM ".$db['ipcheck']." WHERE what = '".$vid."'",false,true);
                if($ipcheck['ip'] == convert::ToInt($userid))
                {
                    $index = error(_error_voted_again,1);
                } elseif($get['closed'] == 1)
                {
                    $index = error(_error_vote_closed,1);
                } else {
                    db("UPDATE ".$db['userstats']." SET `votes` = votes+1 WHERE user = '".convert::ToInt($userid)."'");

                    $qry = db("UPDATE ".$db['vote_results']."
                     SET `stimmen` = stimmen+1
                     WHERE id = '".convert::ToInt($_POST['vote'])."'");

                    wire_ipcheck($vid);

                    if(!isset($_GET['ajax'])) $index = info(_vote_successful, "?action=show&amp;id=".$_GET['id']."");
                }
            } else {
                if(ipcheck("vid_".$_GET['id'])) $index = error(_error_voted_again,1);
                elseif($get['closed'] == 1)     $index = error(_error_vote_closed,1);
                else {
                    if(!empty($userid) && $userid != 0)
                    {
                        $time = convert::ToInt($userid);
                        $update = db("UPDATE ".$db['userstats']."
                          SET `votes` = votes+1
                          WHERE user = '".convert::ToInt($userid)."'");
                    } else $time = "0";

                    db("UPDATE ".$db['vote_results']."
                     SET `stimmen` = stimmen+1
                     WHERE id = '".convert::ToInt($_POST['vote'])."'");

                    wire_ipcheck("vid_".$_GET['id']);
                    wire_ipcheck("vid(".$_GET['id'].")");

                    if(!isset($_GET['ajax']))
                        $index = info(_vote_successful, "?action=show&amp;id=".$_GET['id']."");
                }
                if(!empty($userid) && $userid != 0) $cookie = convert::ToInt($userid);
                else $cookie = "voted";
            }

            cookie::put('vid_'.$_GET['id'], $cookie);
        }
    }

    if($_GET['ajax'] == 1)
    {
        header("Content-type: text/html; charset=utf-8");
        include(basePath.'/inc/menu-functions/vote.php');
        echo '<table class="navContent" cellspacing="0">'.vote(1).'</table>';
        exit;
    }

    if($_GET['what'] == "fvote")
    {
        if(empty($_POST['vote']))
        {
            $index = error(_vote_no_answer);
        } else {
            $qry = db("SELECT * FROM ".$db['votes']."
                 WHERE id = '".convert::ToInt($_GET['id'])."'");
            $get = _fetch($qry);

            if(ipcheck("vid_".$_GET['id'])) $index = error(_error_voted_again,1);
            elseif($get['closed'] == 1)     $index = error(_error_vote_closed,1);
            else {
                if(!empty($userid) && $userid != 0)
                {
                    $time = convert::ToInt($userid);
                    $update = db("UPDATE ".$db['userstats']."
                        SET `votes` = votes+1
                        WHERE user = '".convert::ToInt($userid)."'");
                } else $time = "0";

                $qry = db("UPDATE ".$db['vote_results']."
                   SET `stimmen` = stimmen+1
                   WHERE id = '".convert::ToInt($_POST['vote'])."'");

                wire_ipcheck("vid_".$_GET['id']);
                wire_ipcheck("vid(".$_GET['id'].")");

                if(!isset($_GET['fajax'])) $index = info(_vote_successful, "forum/?action=showthread&amp;kid=".$_POST['kid']."&amp;id=".$_POST['fid']."");
            }
            if(!empty($userid) && $userid != 0) $cookie = convert::ToInt($userid);
            else $cookie = "voted";
        }

        cookie::put('vid_'.$_GET['id'], $cookie);
    }

    if($_GET['fajax'] == 1)
    {
        include_once(basePath.'/inc/menu-functions/fvote.php');
        header("Content-type: text/html; charset=utf-8");
        echo fvote($_GET['id'], 1);
        exit;
    }
}
?>