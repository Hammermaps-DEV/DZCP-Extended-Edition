<?php
/**
 * <DZCP-Extended Edition>
 * @package: DZCP-Extended Edition
 * @author: DZCP Developer Team || Hammermaps.de Developer Team
 * @link: http://www.dzcp.de || http://www.hammermaps.de
 */
 
if(_adminMenu != 'true') exit();

        $qry = db("DELETE FROM ".dba::get('votes')."
                   WHERE id = '".convert::ToInt($_GET['id'])."'");

        $qry = db("DELETE FROM ".dba::get('vote_results')."
                   WHERE vid = '".convert::ToInt($_GET['id'])."'");

        $vid = "vid_".$_GET['id'];
        $qry = db("DELETE FROM ".dba::get('ipcheck')."
                   WHERE what = '".$vid."'");

        $show = info(_vote_admin_delete_successful, "?admin=votes");
