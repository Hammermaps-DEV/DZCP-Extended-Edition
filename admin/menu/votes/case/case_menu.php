<?php
/**
 * <DZCP-Extended Edition>
 * @package: DZCP-Extended Edition
 * @author: DZCP Developer Team || Hammermaps.de Developer Team
 * @link: http://www.dzcp.de || http://www.hammermaps.de
 */

if(_adminMenu != 'true') exit();

        $qryv = db("SELECT intern FROM ".dba::get('votes')."
                    WHERE id = '".convert::ToInt($_GET['id'])."'
                    AND intern = 1");
        if(_rows($qryv))
        {
          $show = error(_vote_admin_menu_isintern, 1);
        } else {
          $qrys = db("SELECT * FROM ".dba::get('votes')."
                      WHERE id = '".convert::ToInt($_GET['id'])."'");
          $get = _fetch($qrys);

          if($get['menu'] == 1)
          {
            $qry = db("UPDATE ".dba::get('votes')."
                       SET menu = '0'");

            header("Location: ?index=admin&admin=votes");
          } else {
            $qry = db("UPDATE ".dba::get('votes')."
                       SET menu = '0'");

            $qry = db("UPDATE ".dba::get('votes')."
                       SET menu = '1'
                       WHERE id = '".convert::ToInt($_GET['id'])."'");

            header("Location: ?index=admin&admin=votes");
          }
        }