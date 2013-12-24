<?php
/**
 * <DZCP-Extended Edition>
 * @package: DZCP-Extended Edition
 * @author: DZCP Developer Team || Hammermaps.de Developer Team
 * @link: http://www.dzcp.de || http://www.hammermaps.de
 */

function welcome()
{
    $return = "<script language=\"javascript\" type=\"text/javascript\">
                date = new Date(); hour = date.getHours();
                if(hour>=18)      document.write('"._welcome_18."');
                else if(hour>=13) document.write('"._welcome_13."');
                else if(hour>=11) document.write('"._welcome_11."');
                else if(hour>=5)  document.write('"._welcome_5."');
                else if(hour>=0)  document.write('"._welcome_0."');
              </script>";

    return $return.' '.(checkme() == 'unlogged' ? _welcome_guest : autor(userid(), "welcome"));
}