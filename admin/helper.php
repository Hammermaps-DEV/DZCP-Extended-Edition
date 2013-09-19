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
    $dzcp_version_info = 'onmouseover="DZCP.showInfo(\'<tr><td colspan=2 align=center padding=3 class=infoTop>DZCP Versions Checker</td></tr><tr><td>'._dzcp_vcheck.'</td></tr>\')" onmouseout="DZCP.hideInfo()"';
    $return = array();
    if(dzcp_version_checker || !fsockopen_support())
    {
        if(Cache::check('dzcp_version'))
        {
            if($dzcp_online_v = fileExists("http://www.hammermaps.de/dzcp_version.xml"))
                Cache::set('dzcp_version', $dzcp_online_v, dzcp_version_checker_refresh);
        }
        else
            $dzcp_online_v = Cache::get('dzcp_version');

        if($dzcp_online_v && !empty($dzcp_online_v))
        {
            xml::openXMLStream('dzcp_version', $dzcp_online_v); $_build = _build;
            $exp_online = explode(':',convert::ToString(xml::getXMLvalue('dzcp_version', 'build')));
            $exp_local = explode(':',_build);
            if($exp_online[2] > $exp_local[3]) $_build = '<font color="#FF0000">'._build.'</font> => <font color="#00FF00">'.$exp_online[2].'</font>';

            if(convert::ToFloat(xml::getXMLvalue('dzcp_version', 'version')) <= _version)
            {
                $return['version'] = '<b>'._akt_version.': <a href="" [info]><span class="fontGreen">'._version.'</span></a> '._edition.' / Release: '._release.' / Build: '.$_build.'</b>';
                $return['version'] = show($return['version'],array('info' => $dzcp_version_info));
                $return['version_img'] = '<img src="../inc/images/admin/version.gif" align="absmiddle" width="111" height="14" />';
            }
            else
            {
                $return['version'] = '<a href="http://www.dzcp.de/" target="_blank" title="external Link: www.dzcp.de"><b>'._akt_version.':</b> <span class="fontRed">'._version.'</span> / Update Version: <span class="fontGreen">'.convert::ToFloat(xml::getXMLvalue('dzcp_version', 'version')).'</span></a> / Release: <span class="fontGreen">'.xml::getXMLvalue('dzcp_version', 'release').'</span> / Build: <span class="fontGreen">'.xml::getXMLvalue('dzcp_version', 'build').'</span>';
                $return['version_img'] = '<img src="../inc/images/admin/version_old.gif" align="absmiddle" width="111" height="14" />';
            }
        }
        else
        {
            $return['version'] = '<b>'._akt_version.': <a href="" [info]><font color="#FFFF00">'._version.'</font></a> '._edition.' / Release: '._release.' / Build: '._build.'</b>';
            $return['version'] = show($return['version'],array('info' => $dzcp_version_info));
            $return['version_img'] = '<img src="../inc/images/admin/version.gif" align="absmiddle" width="111" height="14" />';
        }
    }
    else
    {
        //check disabled
        $return['version'] = '<b><font color="#999999">'._akt_version.': '._version.'</font> '._edition.' / Release: '._release.' / Build: '._build.'</b>';
        $return['version_img'] = '<img src="../inc/images/admin/version.gif" align="absmiddle" width="111" height="14" />';
    }

    return $return;
}