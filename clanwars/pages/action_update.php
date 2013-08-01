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
    if(checkme() == "unlogged")
    {
        $index = error(_error_have_to_be_logged);
    } else {
        $qry = db("SELECT * FROM ".dba::get('cw_player')."
               WHERE cwid = '".convert::ToInt($_GET['id'])."'
               AND member = '".userid()."'");
        if(_rows($qry))
        {
            $upd = db("UPDATE ".dba::get('cw_player')."
                 SET `status` = '".convert::ToInt($_POST['status'])."'
                 WHERE cwid = '".convert::ToInt($_GET['id'])."'
                 AND member = '".userid()."'");
        } else {
            $ins = db("INSERT INTO ".dba::get('cw_player')."
                 SET `cwid`   = '".convert::ToInt($_GET['id'])."',
                     `member` = '".userid()."',
                     `status` = '".convert::ToInt($_POST['status'])."'");
        }

        $index = info(_cw_status_set, "?action=details&amp;id=".$_GET['id']."");
    }
}