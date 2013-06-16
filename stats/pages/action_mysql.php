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
    $dbinfo = dbinfo();
    $stats = show($dir."/mysql", array("head" => _stats_mysql,
            "size" => _stats_mysql_size,
            "nsize" => $dbinfo["size"],
            "entrys" => _stats_mysql_entrys,
            "nentrys" => $dbinfo["entrys"],
            "rows" => _stats_mysql_rows,
            "nrows" => $dbinfo["rows"]));

    $index = show($dir."/stats", array("head" => _stats,
            "news" => _site_news,
            "stats" => $stats,
            "user" => _user,
            "dl" => _site_dl,
            "mysql" => _stats_mysql,
            "awards" => _site_awards,
            "cw" => _site_clanwars,
            "gb" =>  _site_gb,
            "forum" => _site_forum));
}