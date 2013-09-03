<?php
/**
 * <DZCP-Extended Edition>
 * @package: DZCP-Extended Edition
 * @author: DZCP Developer Team || Hammermaps.de Developer Team
 * @link: http://www.dzcp.de || http://www.hammermaps.de
 */

if(_adminMenu != 'true') exit();

if(isset($_GET['kat'])&&($_GET['kat']=="mainkat"||$_GET['kat']=="subkat")&&!empty($_GET[id]))
{
	if($_GET['kat']=="mainkat")
	{
		if(file_exists(basePath."/inc/images/uploads/forum/mainkat/".convert::ToInt($_GET['id']).".jpg"))
			@unlink(basePath."/inc/images/uploads/forum/mainkat/".convert::ToInt($_GET['id']).".jpg");
		elseif(file_exists(basePath."/inc/images/uploads/forum/mainkat/".convert::ToInt($_GET['id']).".png"))
			@unlink(basePath."/inc/images/uploads/forum/mainkat/".convert::ToInt($_GET['id']).".png");
		elseif(file_exists(basePath."/inc/images/uploads/forum/mainkat/".convert::ToInt($_GET['id']).".gif"))
			@unlink(basePath."/inc/images/uploads/forum/mainkat/".convert::ToInt($_GET['id']).".gif");
		header('Location: ?admin=forum&do=edit&id='.convert::ToInt($_GET['id']));

	}
	elseif($_GET['kat']=="subkat")
	{

		if(file_exists(basePath."/inc/images/uploads/forum/subkat/".convert::ToInt($_GET['id']).".jpg"))
			@unlink(basePath."/inc/images/uploads/forum/subkat/".convert::ToInt($_GET['id']).".jpg");
		elseif(file_exists(basePath."/inc/images/uploads/forum/subkat/".convert::ToInt($_GET['id']).".png"))
			@unlink(basePath."/inc/images/uploads/forum/subkat/".convert::ToInt($_GET['id']).".png");
		elseif(file_exists(basePath."/inc/images/uploads/forum/subkat/".convert::ToInt($_GET['id']).".gif"))
			@unlink(basePath."/inc/images/uploads/forum/subkat/".convert::ToInt($_GET['id']).".gif");
		header('Location: ?admin=forum&do=editsubkat&id='.convert::ToInt($_GET['id']));
	}
}
else $show = error(_config_forum_icon_delete_error);