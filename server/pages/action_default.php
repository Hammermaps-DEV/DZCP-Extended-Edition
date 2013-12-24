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
    if(show_gameserver_debug && show_debug_console)
        require_once(basePath.'/server/pages/action_ajax.php'); //Debug

    if(fsockopen_support())
    {
        $sql_ext = '';
        if(isset($_GET['showID']))
        {
            $qry = db("SELECT id FROM ".dba::get('server')." WHERE `id` = '".convert::ToInt($_GET['showID'])."'");
            while($get = _fetch($qry))
            {
                $sql_ext = " AND `id` != '".convert::ToInt($_GET['showID'])."'";

                if(show_gameserver_debug && show_debug_console)
                    $index .= server_show($get['id'],(isset($_GET['showID']) ? $_GET['showID'] : 0)); //Debug
                else
                {
                    $showid=(isset($_GET['showID']) ? '&showID='.$_GET['showID'] : '');
                    $url = '../?index=server&action=ajax&sID='.$get['id'].$showid;
                    $index .= '<tr><td class="contentMainTop">
                    <div id="PageServer_'.$get['id'].'"><div style="width:100%; 0;text-align:center"><img src="inc/images/ajax-loader-bar.gif" alt="" /></div>
                    <script language="javascript" type="text/javascript">DZCP.initPageDynLoader(\'PageServer_'.$get['id'].'\',\''.$url.'\');</script></div></tr>';
                }
            }
        }

        $qry = db("SELECT id FROM ".dba::get('server')." WHERE `game` != 'nope' ".$sql_ext." ORDER BY `game` ASC");
        while($get = _fetch($qry))
        {
            if(show_gameserver_debug && show_debug_console)
                $index .= server_show($get['id'],(isset($_GET['showID']) ? $_GET['showID'] : 0)); //Debug
            else
            {
                $showid=(isset($_GET['showID']) ? '&showID='.$_GET['showID'] : '');
                $url = '../?index=server&action=ajax&sID='.$get['id'].$showid;
                $index .= '<tr><td class="contentMainTop">
                <div id="PageServer_'.$get['id'].'"><div style="width:100%; 0;text-align:center"><img src="inc/images/ajax-loader-bar.gif" alt="" /></div>
                <script language="javascript" type="text/javascript">DZCP.initPageDynLoader(\'PageServer_'.$get['id'].'\',\''.$url.'\');</script></div></tr>';
            }
        }

        $qry = db("SELECT id FROM ".dba::get('server')." WHERE `game` = 'nope' ORDER BY `id` ASC");
        while($get = _fetch($qry))
        {
            if(show_gameserver_debug && show_debug_console)
                $index .= server_show($get['id'],(isset($_GET['showID']) ? $_GET['showID'] : 0)); //Debug
            else
            {
                $showid=(isset($_GET['showID']) ? '&showID='.$_GET['showID'] : '');
                $url = '../?index=server&action=ajax&sID='.$get['id'].$showid;
                $index .= '<tr><td class="contentMainTop">
                <div id="PageServer_'.$get['id'].'"><div style="width:100%; 0;text-align:center"><img src="inc/images/ajax-loader-bar.gif" alt="" /></div>
                <script language="javascript" type="text/javascript">DZCP.initPageDynLoader(\'PageServer_'.$get['id'].'\',\''.$url.'\');</script></div></tr>';
            }
        }

        $index = show($dir."/server", array("servers" => $index));
    }
    else
        $index = error(_fopen);
}