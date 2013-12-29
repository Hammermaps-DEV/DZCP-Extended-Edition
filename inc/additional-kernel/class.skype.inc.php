<?php
/**
 * <DZCP-Extended Edition>
 * @package: DZCP-Extended Edition
 * @author: DZCP Developer Team || Hammermaps.de Developer Team
 * @link: http://www.dzcp.de || http://www.hammermaps.de
 */

class Skype
{
     public static function get_status($username, $image = false, $icon = false, $imgType = 'smallicon' )
     {
        if($image && $icon)
        {
            /***************************************
            Possible types of images:

            * balloon           - Balloon style
            * bigclassic        - Big Classic Style
            * smallclassic      - Small Classic Style
            * smallicon         - Small Icon (transparent background)
            * mediumicon        - Medium Icon
            * dropdown-white    - Dropdown White Background
            * dropdown-trans    - Dropdown Transparent Background
            ****************************************/
            return "http://mystatus.skype.com/".$imgType."/".$username;
        }
        else if($image)
            return "http://mystatus.skype.com/".$username;
        else
        {
            /***************************************
            Possible status  values:
             NUM        TEXT                DESCRIPTION
            * 0     UNKNOWN             	Not opted in or no data available.
            * 1     OFFLINE                 The user is Offline
            * 2     ONLINE                  The user is Online
            * 3     AWAY                    The user is Away
            * 4     NOT AVAILABLE       	The user is Not Available
            * 5     DO NOT DISTURB  		The user is Do Not Disturb (DND)
            * 6     INVISIBLE               The user is Invisible or appears Offline
            * 7     SKYPE ME                The user is in Skype Me mode
            ****************************************/
            $data = client_api_communicate::send_custom("http://mystatus.skype.com/".$username.".xml");
            if($data)
            {
                preg_match('/xml:lang="'.((language::get_language()=='deutsch') ? 'de':'en').'">(.*)</', $data, $match);
                return $match[1];
            }

            return false;
        }
     }

     public static function isOnline($username)
     {
         $status = self::send_custom("http://mystatus.skype.com/".$username.".num");
         return ($status != 1 && $status != 0);
     }
}