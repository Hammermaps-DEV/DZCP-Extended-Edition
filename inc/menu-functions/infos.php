<?php
/**
 * <DZCP-Extended Edition>
 * @package: DZCP-Extended Edition
 * @author: DZCP Developer Team || Hammermaps.de Developer Team
 * @link: http://www.dzcp.de || http://www.hammermaps.de
 */

function infos()
{
    // Debug Mode is enabled, Infobox
    $dg = (is_debug ? '<tr><td><table width="100%" class="subPersInfos"><tr class="persInfosContent"><td width="100%"><span class="fontBoldPersInfos">'._debug_on.'</td></tr><tr class="persInfosContent"><td width="100%"><span class="fontBoldPersInfos">[time]</td></tr></table></td></tr>' : '');

    if(settings("persinfo"))
    {
        $mac_icon = ' <img align="absmiddle" src="inc/images/macintosh_os.png" width="16" height="16" /> ';
        $linux_icon = ' <img align="absmiddle" src="inc/images/linux_os.png" width="16" height="16" /> ';
        $windows_icon = ' <img align="absmiddle" src="inc/images/windows_os.png" width="16" height="16" /> ';
        $iphone_icon = ' <img align="absmiddle" src="inc/images/iphone.png" width="16" height="16" /> ';
        $android_icon = ' <img align="absmiddle" src="inc/images/android.png" width="16" height="16" /> ';

        $data = $_SERVER['HTTP_USER_AGENT'];
        if(preg_match("/Android/i",$data))                      $system = $android_icon."Android";
        elseif(preg_match ("/Linux/i",$data))                   $system = $linux_icon."Linux";
        elseif(preg_match("/SunOS/i",$data))                    $system = "Sun OS";
        elseif(preg_match("/Macintosh/i",$data))                $system = $mac_icon."Macintosh";
        elseif(preg_match("/Mac_PowerPC/i",$data))              $system = $mac_icon."Macintosh";
        elseif(preg_match("/Windows 2000/i",$data))             $system = $windows_icon."Windows 2000";
        elseif(preg_match("/Windows XP/i",$data))               $system = $windows_icon."Windows XP";
        elseif(preg_match("/NT 5.2/i",$data))                   $system = $windows_icon."Windows XP x64";
        elseif(preg_match("/NT 5.1/i",$data))                   $system = $windows_icon."Windows XP";
        elseif(preg_match("/NT 5.0/i",$data))      	            $system = $windows_icon."Windows 2000";
        elseif(preg_match("/NT 4.0/i",$data))                   $system = $windows_icon."Windows NT 4";
        elseif(preg_match("/NT 6.0/i",$data))                   $system = $windows_icon."Windows Vista";
        elseif(preg_match("/NT 6.1/i",$data))                   $system = $windows_icon."Windows 7";
        elseif(preg_match("/NT 6.2/i",$data))                   $system = $windows_icon."Windows 8";
        elseif(preg_match("/OS (.*?) like Mac OS X/i",$data))   $system = $iphone_icon."iOS";
        else                                                    $system = _unknown_system;

        $opera_icon = ' <img align="absmiddle" src="inc/images/opera.png" width="16" height="16" /> ';
        $konqueror_icon = ' <img align="absmiddle" src="inc/images/konqueror.png" width="16" height="16" /> ';
        $firefox_icon = ' <img align="absmiddle" src="inc/images/firefox.png" width="16" height="16" /> ';
        $ie_icon = ' <img align="absmiddle" src="inc/images/ie.png" width="16" height="16" /> ';
        $safari_icon = ' <img align="absmiddle" src="inc/images/safari.png" width="16" height="16" /> ';
        $chrome_icon = ' <img align="absmiddle" src="inc/images/chrome.png" width="16" height="16" /> ';

        if(preg_match("/Opera/i",$data))          $browser = $opera_icon."Opera";
        elseif(preg_match("/Konqueror/i",$data))  $browser = $konqueror_icon."Konqueror";
        elseif(preg_match("/Firefox/i",$data))    $browser = $firefox_icon."Mozilla Firefox";
        elseif(preg_match("/chrome/i",$data))     $browser = $chrome_icon."Google Chrome";
        elseif(preg_match("/Safari/i",$data))     $browser = $safari_icon."Safari";
        elseif(preg_match("/MSIE 6/i",$data))     $browser = $ie_icon."Internet Explorer 6";
        elseif(preg_match("/MSIE 7/i",$data))     $browser = $ie_icon."Internet Explorer 7";
        elseif(preg_match("/MSIE 8/i",$data))     $browser = $ie_icon."Internet Explorer 8";
        elseif(preg_match("/MSIE 9/i",$data))     $browser = $ie_icon."Internet Explorer 9";
        elseif(preg_match("/MSIE 10/i",$data))    $browser = $ie_icon."Internet Explorer 10";
        else                                      $browser = _unknown_browser;

      $res = "<script language=\"javascript\" type=\"text/javascript\"> doc.write(screen.width + ' x ' + screen.height)</script>";
      $size_icon = ' <img align="absmiddle" src="inc/images/sitze.png" width="16" height="16" /> ';
      $ip_icon = ' <img align="absmiddle" src="inc/images/network.png" width="16" height="16" /> ';

      return $dg.show("menu/pers.infos", array("ip" => $ip_icon.($userip=visitorIp()),
                                              "info_ip" => _info_ip,
                                              "host" => gethostbyaddr($userip),
                                              "info_browser" => _info_browser,
                                              "browser" => $browser,
                                              "info_res" => _info_res,
                                              "res" => $size_icon.$res,
                                              "info_sys" => _info_sys,
                                              "sys" => $system));
    }

    return $dg;
}