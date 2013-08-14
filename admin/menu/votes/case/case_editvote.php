<?php
/**
 * <DZCP-Extended Edition>
 * @package: DZCP-Extended Edition
 * @author: DZCP Developer Team || Hammermaps.de Developer Team
 * @link: http://www.dzcp.de || http://www.hammermaps.de
 */
 
if(_adminMenu != 'true') exit();

        $qry = db("SELECT * FROM ".dba::get('vote_results')."
                  WHERE vid = '".convert::ToInt($_GET['id'])."'");
        $get = _fetch($qry);

        $upd = db("UPDATE ".dba::get('votes')."
                   SET `titel`  = '".string::encode($_POST['question'])."',
                       `intern` = '".convert::ToInt($_POST['intern'])."',
                       `closed` = '".convert::ToInt($_POST['closed'])."'
                   WHERE id = '".convert::ToInt($_GET['id'])."'");

        $upd1 = db("UPDATE ".dba::get('vote_results')."
                    SET `sel` = '".string::encode($_POST['a1'])."'
                    WHERE what = 'a1'
                    AND vid = '".convert::ToInt($_GET['id'])."'");

        $upd2 = db("UPDATE ".dba::get('vote_results')."
                    SET `sel` = '".string::encode($_POST['a2'])."'
                    WHERE what = 'a2'
                    AND vid = '".convert::ToInt($_GET['id'])."'");

        for($i=3; $i<=10; $i++)
        {
          if(!empty($_POST['a'.$i.'']))
          {
            if(cnt(dba::get('vote_results'), " WHERE vid = '".convert::ToInt($_GET['id'])."' AND what = 'a".$i."'") != 0)
            {
              $upd = db("UPDATE ".dba::get('vote_results')."
                         SET `sel` = '".string::encode($_POST['a'.$i.''])."'
                         WHERE what = 'a".$i."'
                         AND vid = '".convert::ToInt($_GET['id'])."'");
            } else {
              $ins = db("INSERT INTO ".dba::get('vote_results')."
                         SET `vid` = '".$_GET['id']."',
                             `what` = 'a".$i."',
                             `sel` = '".string::encode($_POST['a'.$i.''])."'");
            }
          }

          if(cnt(dba::get('vote_results'), " WHERE vid = '".convert::ToInt($_GET['id'])."' AND what = 'a".$i."'") != 0 && empty($_POST['a'.$i.'']))
          {
            $del = db("DELETE FROM ".dba::get('vote_results')."
                       WHERE vid = '".convert::ToInt($_GET['id'])."'
                       AND what = 'a".$i."'");
          }
        }

        $show = info(_vote_admin_successful_edited, "?admin=votes");