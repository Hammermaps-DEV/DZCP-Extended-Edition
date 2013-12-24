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
    if(permission("forum"))
    {
        if($_GET['do'] == "mod")
        {
            if(getArgs('delete',false))
            {
                $qryv = db("SELECT * FROM ".dba::get('f_threads')."
                    WHERE id = '".convert::ToInt($_GET['id'])."'");
                $getv = _fetch($qryv);

                if(!empty($getv['vote']))
                {
                    $delvote = db("DELETE FROM ".dba::get('votes')."
                       WHERE id = '".$getv['vote']."'");

                    $delvr = db("DELETE FROM ".dba::get('vote_results')."
                     WHERE vid = '".$getv['vote']."'");
                    $voteid = "vid_".$getv['vote'];
                    $delip = db("DELETE FROM ".dba::get('ipcheck')."
                     WHERE what = '".$voteid."'");
                }
                $del = db("DELETE FROM ".dba::get('f_threads')."
                   WHERE id = '".convert::ToInt($_GET['id'])."'");

                // grab user to reduce post count
                $tmpSid = convert::ToInt($_GET['id']);
                $userPosts = db('SELECT p.`reg` FROM ' . dba::get('f_posts') . ' p WHERE sid = ' . $tmpSid . ' AND p.`reg` != 0');
                $userPostReduction = array();
                while($get = _fetch($userPosts)) {
                    if(!isset($userPostReduction[$get['reg']])) {
                        $userPostReduction[$get['reg']] = 1;
                    } else {
                        $userPostReduction[$get['reg']] = $userPostReduction[$get['reg']] + 1;
                    }
                }
                foreach($userPostReduction as $key_id => $value_postDecrement) {
                    db('UPDATE ' . dba::get('userstats') .
                    ' SET `forumposts` = `forumposts` - '. $value_postDecrement .
                    ' WHERE user = ' . $key_id);
                }
                $delp = db("DELETE FROM ".dba::get('f_posts')."
                    WHERE sid = '" . $tmpSid . "'");
                $delabo = db("DELETE FROM ".dba::get('f_abo')."
                      WHERE fid = '".convert::ToInt($_GET['id'])."'");
                $index = info(_forum_admin_thread_deleted, "?index=forum");
            } else
            {
                if(!getArgs('closed',false))
                {
                    db("UPDATE ".dba::get('f_threads')."
                      SET `closed` = '0'
                      WHERE id = '".convert::ToInt($_GET['id'])."'");
                }
                else if(getArgs('closed',true))
                {
                    db("UPDATE ".dba::get('f_threads')."
                       SET `closed` = '1'
                       WHERE id = '".convert::ToInt($_GET['id'])."'");
                }

                if(getArgs('sticky',false))
                {
                    db("UPDATE ".dba::get('f_threads')."
                        SET `sticky` = '1'
                        WHERE id = '".convert::ToInt($_GET['id'])."'");
                }
                else
                {
                    db("UPDATE ".dba::get('f_threads')."
                        SET `sticky` = '0'
                        WHERE id = '".convert::ToInt($_GET['id'])."'");
                }

                if(getArgs('global',false))
                {
                    db("UPDATE ".dba::get('f_threads')."
                        SET `global` = '1'
                        WHERE id = '".convert::ToInt($_GET['id'])."'");
                }
                else
                {
                    db("UPDATE ".dba::get('f_threads')."
                        SET `global` = '0'
                        WHERE id = '".convert::ToInt($_GET['id'])."'");
                }

                if(getArgs('move',false) == "lazy")
                {
                    $index = info(_forum_admin_modded, "?index=forum&amp;action=showthread&amp;id=".$_GET['id']."");
                }
                else
                {
                    $move = db("UPDATE ".dba::get('f_threads')."
                      SET `kid` = '".$_POST['move']."'
                      WHERE id = '".convert::ToInt($_GET['id'])."'");

                    $move = db("UPDATE ".dba::get('f_posts')."
                      SET `kid` = '".$_POST['move']."'
                      WHERE sid = '".convert::ToInt($_GET['id'])."'");

                    $qrym = db("SELECT s1.kid,s2.kattopic,s2.id
                      FROM ".dba::get('f_threads')." AS s1
                      LEFT JOIN ".dba::get('f_skats')." AS s2
                      ON s1.kid = s2.id
                      WHERE s1.id = '".convert::ToInt($_GET['id'])."'");
                    $getm = _fetch($qrym);

                    $i_move = show(_forum_admin_do_move, array("kat" => string::decode($getm['kattopic'])));
                    $index = info($i_move, "?index=forum&amp;action=showthread&amp;id=".$_GET['id']."");
                }
            }
        }
    }
    else
        $index = error(_error_wrong_permissions);
}