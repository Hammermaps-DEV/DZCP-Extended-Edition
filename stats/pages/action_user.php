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
    $stats = show($dir."/user", array("head" => _site_user,
            "users" => _stats_users_regged,
            "member" => _stats_users_regged_member,
            "nmember" => cnt(dba::get('users'), " WHERE level != 1"),
            "logins" => _stats_users_logins,
            "nlogins" => sum(dba::get('userstats'),"", "logins"),
            "msg" => _stats_users_msg,
            "nmsg" => sum(dba::get('userstats'),"", "writtenmsg"),
            "votes" => _stats_users_votes,
            "nvotes" => sum(dba::get('userstats'),"","votes"),
            "aktmsg" => _stats_users_aktmsg,
            "naktmsg" => cnt(dba::get('acomments'), " WHERE `von` != '0'"),
            "buddys" => _stats_users_buddys,
            "nbuddys" => cnt(dba::get('buddys')),
            "nusers" => cnt(dba::get('users'))));

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