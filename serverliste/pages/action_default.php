<?php
/**
 * <DZCP-Extended Edition>
 * @package: DZCP-Extended Edition
 * @author: DZCP Developer Team || Hammermaps.de Developer Team
 * @link: http://www.dzcp.de || http://www.hammermaps.de
 */

####################################
## Wird in einer Index ausgeführt ##
####################################
if (!defined('IS_DZCP'))
    exit();

if (_version < '1.0') //Mindest Version pruefen
    $index = _version_for_page_outofdate;
else
{
    $qry = db("SELECT ip,port,clanname,clanurl,pwd,checked,slots
             FROM ".dba::get('serverliste')."
             WHERE checked = 1");
    if(_rows($qry))
    {
        while ($get = _fetch($qry))
        {
            $class = ($color % 2) ? "contentMainSecond" : "contentMainFirst"; $color++;
            $serverlist .= show($dir."/serverliste_show", array("aktplayers" => $aktplayers,
                    "maxplayers" => $maxplayers,
                    "clanurl" => re($get['clanurl']),
                    "slots" => $get['slots'],
                    "class" => $class,
                    "serverip" => $get['ip'],
                    "serverport" => $get['port'],
                    "clanname" => re($get['clanname']),
                    "serverpwd" => re($get['pwd']),
                    "map" => $map));
        }
    } else {
        $serverlist = show(_no_entrys_yet, array("colspan" => "4"));
    }

    $index = show($dir."/serverliste", array("serverlist" => $serverlist,
            "slist_head" => _slist_head,
            "clan" => _profil_clan,
            "serverip" => _slist_serverip,
            "slots" => _slist_slots,
            "pwd" => _pwd,
            "eintragen" => _slist_add,
            "hlswip" => _gt_addip));
}
?>