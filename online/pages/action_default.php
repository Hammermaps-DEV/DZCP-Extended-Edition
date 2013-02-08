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
    $qry = db("SELECT id,nick,whereami FROM ".$db['users']."
             WHERE time+'".$useronline."'>'".time()."'
             AND online = 1
             ORDER BY nick");
    while($get = _fetch($qry))
    {
        if(!preg_match("#autor_#is",$get['whereami'])) $whereami = re($get['whereami']);
        else $whereami =  preg_replace_callback("#autor_(.*?)$#",create_function('$id', 'return autor("$id[1]");'),$get['whereami']);

        $class = ($color % 2) ? "contentMainSecond" : "contentMainFirst"; $color++;
        $show .= show($dir."/online_show", array("nick" => autor($get['id']),
                "whereami" => $whereami,
                "class" => $class));
    }

    $qry = db("SELECT * FROM ".$db['c_who']."
             WHERE online+'".$useronline."'>'".time()."'
             AND login = 0
             ORDER BY whereami");
    while($get = _fetch($qry))
    {
        if(!preg_match("#autor_#is",$get['whereami'])) $whereami = re($get['whereami']);
        else $whereami = preg_replace_callback("#autor_(.*?)$#",create_function('$id', 'return autor("$id[1]");'),$get['whereami']);

        $online_ip = preg_replace("#^(.*)\.(.*)#","$1",$get['ip']);
        $online_host = preg_replace("#^(.*?)\.(.*)#","$2",gethostbyaddr($get['ip']));
        $online_ip = $online_ip.'.XX (*.'.$online_host.')';

        $class = ($color % 2) ? "contentMainSecond" : "contentMainFirst"; $color++;
        $show .= show($dir."/online_show", array("nick" => $online_ip,
                "whereami" => $whereami,
                "class" => $class));
    }

    $index = show($dir."/online", array("show" => $show,
            "head" => _online_head,
            "user" => _status_user.'/'._server_ip,
            "where" => _online_whereami));
}
?>