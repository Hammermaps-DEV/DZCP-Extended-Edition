<?php
#############################################
##### Code for 'DZCP - Extended Edition #####
###### DZCP - Extended Edition >= 1.0 #######
#############################################

####################################
## Wird in einer Index ausgeführt ##
####################################
if (!defined('IS_DZCP'))
	exit();

if (_version < '1.0') //Mindest Version pruefen
	$index = _version_for_page_outofdate;
else
{
	##############################
	## Passwort vergessen Seite ##
	##############################
	$where = _site_user_lostpwd;
	if($chkMe == "unlogged")
	{
		## Lost-PW Seite anzeigen ##
		$index = show($dir."/lostpwd", array("value" => _button_value_send));
	
		## Prüfung auf eine Action ##
		if($do == "sended")
		{
			if(isset($_POST['user']) && isset($_POST['email']) && !empty($_POST['user']) && !empty($_POST['email']))
			{
				## Userdaten aus der Datenbank abrufen ##
				$qry = db("SELECT id,user,level,pwd FROM ".$db['users']." WHERE user= '".$_POST['user']."' AND email = '".$_POST['email']."'");
			
				## Der Secure Code richtig und wurde ein Account gefunden ##
				if(_rows($qry) && ($_POST['secure'] == $_SESSION['sec_lostpwd'] && $_SESSION['sec_lostpwd'] != NULL))
				{
					$get = _fetch($qry);
					
					## Neues Passwort generieren ##
					$pwd = mkpwd();
					
					## Neues Passwort MD5 verschlüsseln und Speichern ##
					db("UPDATE ".$db['users']." SET `pwd` = '".md5($pwd)."' WHERE user = '".$_POST['user']."' AND email = '".$_POST['email']."'");
					
					## Ereignis in den Adminlog schreiben ##
					wire_ipcheck("pwd(".$get['id'].")");
					
					## User E-Mail zusammenstellen und senden ##
					sendMail($_POST['email'],settings('eml_pwd_subj'),show(settings('eml_pwd'), array("user" => $_POST['user'], "pwd" => $pwd)));
					
					## Infobox anzeigen ##
					$index = info(_lostpwd_valid, "../user/?action=login");
				}
				else 
				{
					## Ereigniss in den Adminlog schreiben und Error ausgeben ##
					if(_rows($qry))
					{
						$get = _fetch($qry);
						wire_ipcheck("trypwd(".$get['id'].")");
					}
					
					$index = ($_POST['secure'] != $_SESSION['sec_lostpwd'] || empty($_SESSION['sec_lostpwd']) ? error(_error_invalid_regcode,1) : error(_lostpwd_failed, 1));
				}
			}
			else
				$index = error(_error_invalid_login_mail,1);
		}
	} 
	else 
	{
		## Error, du bist bereits angemeldet ##
		$index = error(_error_user_already_in, 1);
	}
}
?>