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

function forumicon($id,$main="subkat")
{
	$id=convert::ToInt($id);
	if($main=="subkat")
	{
		if(file_exists(basePath."/inc/images/uploads/forum/subkat/".$id.".jpg"))
			$icon="<img class=\"icon\" src=\"../inc/images/uploads/forum/subkat/".$id.".jpg\" alt=\"\" title=\"\"/>";
		elseif(file_exists(basePath."/inc/images/uploads/forum/subkat/".$id.".png"))
			$icon="<img class=\"icon\" src=\"../inc/images/uploads/forum/subkat/".$id.".png\" alt=\"\" title=\"\"/>";
		elseif(file_exists(basePath."/inc/images/uploads/forum/subkat/".$id.".gif"))
			$icon="<img class=\"icon\" src=\"../inc/images/uploads/forum/subkat/".$id.".gif\" alt=\"\" title=\"\"/>";
		else $icon="";
	}
	else
	{
		if(file_exists(basePath."/inc/images/uploads/forum/mainkat/".$id.".jpg"))
			$icon="<img class=\"icon\" src=\"../inc/images/uploads/forum/mainkat/".$id.".jpg\" alt=\"\" title=\"\"/>";
		elseif(file_exists(basePath."/inc/images/uploads/forum/mainkat/".$id.".png"))
			$icon="<img class=\"icon\" src=\"../inc/images/uploads/forum/mainkat/".$id.".png\" alt=\"\" title=\"\"/>";
		elseif(file_exists(basePath."/inc/images/uploads/forum/mainkat/".$id.".gif"))
			$icon="<img class=\"icon\" src=\"../inc/images/uploads/forum/mainkat/".$id.".gif\" alt=\"\" title=\"\"/>";
		else $icon="";
	}
	return $icon;
}