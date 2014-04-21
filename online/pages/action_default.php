<?php
/**
 * <DZCP-Extended Edition>
 * @package: DZCP-Extended Edition
 * @author: DZCP Developer Team || Hammermaps.de Developer Team
 * @link: http://www.dzcp.de || http://www.hammermaps.de
 */

if (!defined('IS_DZCP'))
    exit();

if (_version < '1.0')
    $index = _version_for_page_outofdate;
else
{
    //Update DB
    if($chkMe)
        db("UPDATE ".dba::get('users')." SET `time` = '".((int)time())."', `whereami` = '".string::encode($where)."' WHERE id = '".userid()."'");

    //User
    $qry = db("SELECT id,nick,whereami,ip FROM ".dba::get('users')." WHERE time+'".users_online."'>'".time()."' AND online = 1 ORDER BY nick"); $color = 1; $show = '';
    if(_rows($qry))
    {
        while($get = _fetch($qry))
        {
            $online_ip = '';
            if($chkMe == 4)
            {
                $online_ip = $get['ip'];
                $online_host = ($gethostbyaddr=gethostbyaddr($get['ip']));
                $online_ip = ' * '.($get['ip'] == $gethostbyaddr ? $online_ip : $online_ip.' ('.$online_host.')');
            }

            $whereami = !preg_match("#autor_#is",$get['whereami']) ? string::decode($get['whereami']) : preg_replace_callback("#autor_(.*?)$#",create_function('$id', 'return autor("$id[1]");'),$get['whereami']);
            $class = ($color % 2) ? "contentMainSecond" : "contentMainFirst"; $color++;
            $show .= show($dir."/online_show", array("nick" => autor($get['id']).$online_ip, "whereami" => $whereami, "class" => $class));
        }
    }

    //Gast
    $qry = db("SELECT * FROM ".dba::get('c_who')." WHERE online+'".users_online."'>'".time()."' AND login = 0 ORDER BY whereami");
    if(_rows($qry))
    {
        while($get = _fetch($qry))
        {
            $whereami = !preg_match("#autor_#is",$get['whereami']) ? string::decode($get['whereami']) : preg_replace_callback("#autor_(.*?)$#",create_function('$id', 'return autor("$id[1]");'),$get['whereami']);

            if($chkMe == 4)
            {
                $online_ip = $get['ip'];
                $online_host = ($gethostbyaddr=gethostbyaddr($get['ip']));
            }
            else
            {
                $online_ip = preg_replace("#^(.*)\.(.*)#","$1",$get['ip']);
                $online_host = preg_replace("#^(.*?)\.(.*)#","$2",($gethostbyaddr=gethostbyaddr($get['ip'])));
            }

            $online_ip = ($get['ip'] == $gethostbyaddr ? $online_ip.'.XX' : $online_ip.'.XX (*.'.$online_host.')');
            $class = ($color % 2) ? "contentMainSecond" : "contentMainFirst"; $color++;
            $show .= show($dir."/online_show", array("nick" => $online_ip, "whereami" => $whereami, "class" => $class));
        }
    }

    $index = show($dir."/online", array("show" => $show));
}