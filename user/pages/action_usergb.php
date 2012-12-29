<?php
####################################
## Wird in einer Index ausgeführt ##
####################################
if (!defined('IS_DZCP'))
	exit();
	
#####################
## Userlogin Seite ##
#####################
  $where = _site_user_profil;
	if(_rows(db("SELECT `id` FROM ".$db['users']." WHERE `id` = '".(int)$_GET['id']."'")) != 0)
	{
		if($_GET['do'] == "add")
		{
			
			if(isset($userid)) $toCheck = empty($_POST['eintrag']);
			else
				$toCheck = empty($_POST['nick']) || empty($_POST['email']) || empty($_POST['eintrag']) || !check_email($_POST['email']) || $_POST['secure'] != $_SESSION['sec_'.$dir] || empty($_SESSION['sec_'.$dir]);
	
			if($toCheck)
			{
				if(isset($userid))
				{
					if(empty($_POST['eintrag'])) $error = _empty_eintrag;
	
					$form = show("page/editor_regged", array("nick" => autor($userid),
																									 "von" => _autor));
				} else {
					if(($_POST['secure'] != $_SESSION['sec_'.$dir]) || empty($_SESSION['sec_'.$dir])) $error = _error_invalid_regcode;
					elseif(empty($_POST['nick']))  $error = _empty_nick;
					elseif(empty($_POST['email'])) $error = _empty_email;
					elseif(!check_email($_POST['email'])) $error = _error_invalid_email;
					elseif(empty($_POST['eintrag'])) $error = _empty_eintrag;
	
					$form = show("page/editor_notregged", array("nickhead" => _nick,
																											"emailhead" => _email,
																											"hphead" => _hp,));
				}
	
				$error = show("errors/errortable", array("error" => $error));
	
				$index = show($dir."/usergb_add", array("titel" => _eintragen_titel,
																								"nickhead" => _nick,
																								"add_head" => _gb_add_head,
																								"bbcodehead" => _bbcode,
																								"emailhead" => _email,
																								"preview" => _preview,
																								"ed" => "&amp;uid=".$_GET['id'],
																								"whaturl" => "add",
																								"security" => _register_confirm,
																								"b1" => $u_b1,
																								"b2" => $u_b2,
																								"what" => _button_value_add,
																								"hphead" => _hp,
																								"id" => $_GET['id'],
																								"reg" => $_POST['reg'],
																								"form" => $form,
																								"postemail" => $_POST['email'],
																								"posthp" => $_POST['hp'],
																								"postnick" => re($_POST['nick']),
																								"posteintrag" => re_bbcode($_POST['eintrag']),
																								"error" => $error,
																								"ip" => _iplog_info,
																								"eintraghead" => _eintrag));
			} else {
				$qry = db("INSERT INTO ".$db['usergb']."
									 SET `user`       = '".((int)$_GET['id'])."',
											 `datum`      = '".((int)time())."',
											 `nick`       = '".up($_POST['nick'])."',
											 `email`      = '".up($_POST['email'])."',
											 `hp`         = '".links($_POST['hp'])."',
											 `reg`        = '".((int)$userid)."',
											 `nachricht`  = '".up($_POST['eintrag'],1)."',
											 `ip`         = '".$userip."'");
	
				$mgbid = "mgbid(".$_GET['id'].")";
				$qry = db("INSERT INTO ".$db['ipcheck']."
									 SET `ip`   = '".$userip."',
											 `what` = '".$mgbid."',
											 `time` = '".((int)time())."'");
	
				$index = info(_usergb_entry_successful, "?action=user&amp;id=".$_GET['id']."&show=gb");
			}
		} elseif($_GET['do'] == 'edit') {
				if($_POST['reg'] == $userid || permission('editusers'))
				{
					if($_POST['reg'] == 0)
					{
						 $addme = "`nick`       = '".up($_POST['nick'])."',
											 `email`      = '".up($_POST['email'])."',
											 `hp`         = '".links($_POST['hp'])."',";
					}
	
					$editedby = show(_edited_by, array("autor" => autor($userid),
																						 "time" => date("d.m.Y H:i", time())._uhr));
	
					$upd = db("UPDATE ".$db['usergb']."
										 SET ".$addme."
												 `nachricht`  = '".up($_POST['eintrag'],1)."',
												 `reg`        = '".((int)$_POST['reg'])."',
												 `editby`     = '".addslashes($editedby)."'
										 WHERE id = '".intval($_GET['gbid'])."'");
	
					$index = info(_gb_edited, "?action=user&show=gb&id=".$_GET['id']);
				} else {
					$index = error(_error_edit_post,1);
				}
		}
	} else{
			$index = error(_user_dont_exist,1);
	}
?>

