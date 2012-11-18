<?php
/////////// ADMINNAVI \\\\\\\\\
// Typ:       settingsmenu
// Rechte:    permission('editusers')
///////////////////////////////
if(_adminMenu != 'true') exit;

$where = $where.': '._config_useradd_head;

## DO ##
$error = "";
if(isset($_POST['do']))
{
   	## Get POST ##
   	$username = $_POST['user'];
   	$nickname = $_POST['nick'];
   	$email = $_POST['email'];
   	$rlname = $_POST['rlname'];
	$sex = $_POST['sex'];
	$city = $_POST['city'];
	$land = $_POST['land'];
	$level = $_POST['level'];
    	
	if(empty($username))
		$error = _empty_user;
	else if(empty($nickname))
		$error = _empty_nick;
	else if(empty($email))
		$error = _empty_email;
	else if(!check_email($email))
		$error = _error_invalid_email;
	else if(db("SELECT id FROM ".$db['users']." WHERE user = '".($username)."'",true))
		$error = _error_user_exists;
	else if(db("SELECT id FROM ".$db['users']." WHERE nick = '".($nickname)."'",true))
		$error = _error_nick_exists;
	else if(db("SELECT id FROM ".$db['users']." WHERE email = '".($email)."'",true))
		$error = _error_email_exists;
    else
    {           	
		if(empty($_POST['pwd']))
			$mkpwd = mkpwd();
		else					  
			$mkpwd = $_POST['pwd'];
		    	
		$pwd = md5($mkpwd);
	
		if($_POST['t'] && $_POST['m'] && $_POST['j']) 
			$bday = cal($_POST['t']).".".cal($_POST['m']).".".$_POST['j'];
		
		db("INSERT INTO ".$db['users']." SET 
				`user`     = '".($username)."',
				`nick`     = '".($nickname)."',
				`email`    = '".($email)."',
				`pwd`      = '".$pwd."',
				`rlname`   = '".(up($rlname))."',
				`sex`      = '".($sex)."',
				`bday`     = '".($bday)."',
				`city`     = '".(up($city))."',
				`country`  = '".($land)."',
				`regdatum` = '".((int)time())."',
				`level`    = '".($level)."',
				`time`     = '".time()."',
				`status`   = '1'");
	
		$insert_id = mysql_insert_id();
		ipcheck_write("createuser(".$_SESSION['id']."_".$insert_id.")");
	                       
		## Permissions ##
		$p = "";
		if(!empty($_POST['perm']))
		{
			foreach($_POST['perm'] AS $v => $k) 
				$p .= "`".substr($v, 2)."` = '".((int)$k)."',";
					
			if(!empty($p)) 
				$p = ', '.substr($p, 0, strlen($p) - 1);
										  
				db("INSERT INTO ".$db['permissions']." SET `user` = '".((int)$insert_id)."'".$p);
		}
	    
		## Internal boardpermissions ##
		if(!empty($_POST['board']))
		{
			foreach($_POST['board'] AS $v)
			{
				db("INSERT INTO ".$db['f_access']." SET `user` = '".((int)$insert_id)."', `forum` = '".$v."'");
			}
		}
	
		$sq = db("SELECT * FROM ".$db['squads']."");
		while($getsq = sql_fetch($sq))
		{
			if(isset($_POST['squad'.$getsq['id']]))
		       	db("INSERT INTO ".$db['squaduser']." SET `user`  = '".((int)$insert_id)."', `squad` = '".((int)$_POST['squad'.$getsq['id']])."'");
		
		    if(isset($_POST['squad'.$getsq['id']]))
				db("INSERT INTO ".$db['userpos']." SET `user` = '".((int)$insert_id)."', `posi` = '".((int)$_POST['sqpos'.$getsq['id']])."', `squad` = '".((int)$getsq['id'])."'");
		}
	
		$tmpname = $_FILES['file']['tmp_name'];
		$name = $_FILES['file']['name'];
		$endung = explode(".", $name);
		$endung = strtolower($endung[count($endung)-1]);
	  
		if($tmpname)
		{
		   	$imageinfo = getimagesize($tmpname);
		   	
			foreach($picformat as $tmpendung)
		    {
		       	if(file_exists(basePath."/inc/images/uploads/userpics/".$insert_id.".".$tmpendung))
		           	@unlink(basePath."/inc/images/uploads/userpics/".$insert_id.".".$tmpendung);
		    }
		        
		    move_uploaded_file($tmpname, basePath."/inc/images/uploads/userpics/".$insert_id.".".strtolower($endung));
		}
			
		db("INSERT INTO ".$db['userstats']." SET `user` = '".((int)$insert_id)."', `lastvisit`	= '".((int)time())."'");
			
		## E-Mail senden ##
		$message = show(settings('eml_reg'), array("user" => up($username), "pwd" => $mkpwd));
		$subject = settings('eml_reg_subj');
		sendMail($email,$subject,$message);
			
		$show = info(_uderadd_info, "../admin/");
	}
}

if(empty($show))
{
	## Show ##
	$dropdown_age = show(_dropdown_date, array("day" => dropdown("day",(isset($_POST['t']) ? $_POST['t'] : null),1), "month" => dropdown("month",(isset($_POST['m']) ? $_POST['m'] : null),1), "year" => dropdown("year",(isset($_POST['j']) ? $_POST['j'] : null),1)));
		
	$esquads = ""; 
	$qrysq = db("SELECT id,name FROM ".$db['squads']." ORDER BY pos");
	while($getsq = _fetch($qrysq))
	{
		$qrypos = db("SELECT id,position FROM ".$db['pos']." ORDER BY pid");
		$posi = "";
		while($getpos = _fetch($qrypos))
	    {
	    	if(db("SELECT * FROM ".$db['userpos']." WHERE posi = '".((int)$getpos['id'])."' AND squad = '".((int)$getsq['id'])."' AND user = '".(isset($_GET['edit']) ? ((int)$_GET['edit']) : '')."'",true)) 
	       		$sel = "selected=\"selected\"";
	       	else 
	       		$sel = "";
	
	       	$posi .= show(_select_field_posis, array("value" => ((int)$getpos['id']), "sel" => $sel, "what" => re($getpos['position'])));
	    }    	
	       	
	    $esquads .= show(_checkfield_squads, array("id" => $getsq['id'], "check" => '', "eposi" => $posi, "noposi" => _user_noposi, "squad" => re($getsq['name'])));   
	}
		
	## Sex ##
	if(isset($_POST['sex']))
	{
		$sel_sex0 = ''; $sel_sex1 = ''; $sel_sex2 = '';
			
		if($_POST['sex'] == 0)
			$sel_sex0 = 'selected="selected"';
		else if ($_POST['sex'] == 1)
			$sel_sex1 = 'selected="selected"';
		else
			$sel_sex2 = 'selected="selected"';
	}
	else
	{ $sel_sex0 = ''; $sel_sex1 = ''; $sel_sex2 = ''; }
		
	$show = show($dir."/register", array("registerhead" => _useradd_head,
									     "wname" => (isset($_POST['user']) ? $username : ""),
									     "wnick" => (isset($_POST['nick']) ? $nickname : ""),
									     "wemail" => (isset($_POST['email']) ? $email : ""),
									     "wrlname" => (isset($_POST['rlname']) ? $rlname : ""),
									     "wcity" => (isset($_POST['city']) ? $city : ""),
									     "pname" => _loginname,
										 "pnick" => _nick,
										 "pemail" => _email,
										 "pbild" => _config_c_upicsize,
										 "ppwd" => _pwd,
										 "squadhead" => _admin_user_squadhead,
										 "squad" => _member_admin_squad,
										 "posi" => _profil_position,
										 "esquad" => $esquads,
										 "about" => _useradd_about,
										 "level_info" => _level_info,
										 "rechte" => _config_positions_rights,
										 "getpermissions" => getPermissions(),
										 "getboardpermissions" => getBoardPermissions(),
										 "forenrechte" => _config_positions_boardrights,
										 "preal" => _profil_real,
										 "psex" => _profil_sex,
										 "sex" => show(_pedit_sex, array("sel0" => $sel_sex0, "sel1" => $sel_sex1, "sel2" => $sel_sex2)),
										 "pbday" => _profil_bday,
										 "dropdown_age" => $dropdown_age,
										 "pcity" => _profil_city,
										 "pcountry" => _profil_country,
										 "country" => show_countrys(),
										 "level" => _admin_user_level,
										 "ruser" => _status_user,
										 "trial" => _status_trial,
	                                     "member" => _status_member,
										 "admin" => _status_admin,
	    								 "error" => (!empty($error) ? show("errors/errortable", array("error" => $error)) : ""),
										 "banned" => _admin_level_banned,
										 "value" => _button_value_reg));
}
?>