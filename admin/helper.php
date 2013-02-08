<?php
/**
 * <DZCP-Extended Edition>
 * @package: DZCP-Extended Edition
 * @author: DZCP Developer Team || Hammermaps.de Developer Team
 * @link: http://www.dzcp.de || http://www.hammermaps.de
 */

/**
* Prüft online ob DZCP aktuell ist.
*
* @return array
*/
function show_dzcp_version()
{
    global $cacheTag;
    $dzcp_version_info = 'onmouseover="DZCP.showInfo(\'<tr><td colspan=2 align=center padding=3 class=infoTop>DZCP Versions Checker</td></tr><tr><td>'._dzcp_vcheck.'</td></tr>\')" onmouseout="DZCP.hideInfo()"';
    $return = array();
    if(dzcp_version_checker || !fsockopen_support())
    {
        if(Cache::check($cacheTag,'dzcp_version'))
        {
            if($dzcp_online_v = fileExists("http://www.hammermaps.de/dzcp_version.txt"))
            {
                if($dzcp_online_v <= _version)
                {
                    $return['version'] = '<b>'._akt_version.': <a href="" [info]><span class="fontGreen">'._version.'</span></a> '._edition.'</b>';
                    $return['version'] = show($return['version'],array('info' => $dzcp_version_info));
                    $return['old'] = "";
                }
                else
                {
                    $return['version'] = '<a href="http://www.dzcp.de/" target="_blank" title="external Link: www.dzcp.de"><b>'._akt_version.':</b> <span class="fontRed">'._version.'</span> / <span class="fontGreen">'.$dzcp_online_v.'</span></a> '._edition;
                    $return['old'] = "_old";
                }

                Cache::set($cacheTag,'dzcp_version', $return, dzcp_version_checker_refresh);
            }
            else
            {
                $return['version'] = '<b>'._akt_version.': <a href="" [info]><font color="#FFFF00">'._version.'</font></a> '._edition.'</b>';
                $return['version'] = show($return['version'],array('info' => $dzcp_version_info));
                $return['old'] = "";
            }
        }
        else
            $return = Cache::get($cacheTag,'dzcp_version');
    }
    else
    {
        //check disabled
        $return['version'] = '<b><font color="#999999">'._akt_version.': '._version.'</font> '._edition.'</b>';
        $return['old'] = "";
    }

    return $return;
}
?>
