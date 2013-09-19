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
    if(settings("reg_dl") == "1" && checkme() == "unlogged")
        $index = error(_error_unregistered);
    else
    {
        $get = db("SELECT url,id FROM ".dba::get('downloads')." WHERE id = '".convert::ToInt($_GET['id'])."'",false,true);

        if(count_clicks('download',$get['id']))
            db("UPDATE ".dba::get('downloads')." SET `hits` = hits+1, `last_dl` = '".time()."' WHERE id = '".$get['id']."'");

        if(links_check_url($get['url']))
            header("Location: ".$get['url']);
        else
            header("Location: ../".$get['url']);
    }
}