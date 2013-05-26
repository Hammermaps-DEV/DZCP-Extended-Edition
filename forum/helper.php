<?php
/**
 * <DZCP-Extended Edition>
 * @package: DZCP-Extended Edition
 * @author: DZCP Developer Team || Hammermaps.de Developer Team
 * @link: http://www.dzcp.de || http://www.hammermaps.de
 */

//-> Prueft sicherheitsrelevante Gegebenheiten im Forum
function forumcheck($tid, $what)
{
    return (db("SELECT ".$what." FROM ".dba::get('f_threads')." WHERE id = '".convert::ToInt($tid)."' AND ".$what." = '1'",true) >= 1);
}