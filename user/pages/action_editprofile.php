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
else if($chkMe == "unlogged")
	$index = error(_error_have_to_be_logged, 1);
else
{
	#####################
	## Userlogin Seite ##
	#####################
	$where = _site_user_editprofil;

	if(isset($_GET['gallery']) ? $_GET['gallery'] : '' == "delete")
	{
	            $qrygl = db("SELECT * FROM ".$db['usergallery']." WHERE user = '".$userid."' AND id = '".intval($_GET['gid'])."'");
	    	    while($getgl = _fetch($qrygl))
	    	    {
	                db("DELETE FROM ".$db['usergallery']." WHERE id = '".intval($_GET['gid'])."'");
	                $unlinkgallery = show(_gallery_edit_unlink, array("img" => $getgl['pic'], "user" => $userid));
	                unlink($unlinkgallery);
	            }
	
	            $index = info(_info_edit_gallery_done, "?action=editprofile&show=gallery");
	
	        } 
	        else if(isset($_GET['do']) ? $_GET['do'] : '' == "edit")
	        {
	            $check_user = db("SELECT id FROM ".$db['users']." WHERE user = '".$_POST['user']."' AND id != '".$userid."'");
	            $check_nick = db("SELECT id FROM ".$db['users']." WHERE nick = '".$_POST['nick']."' AND id != '".$userid."'");
	            $check_email = db("SELECT id  FROM ".$db['users']." WHERE email = '".$_POST['email']."' AND id != '".$userid."'");
	
	            if(empty($_POST['user'])) $index = error(_empty_user, 1);
		        else if(empty($_POST['nick'])) $index = error(_empty_nick, 1);
	            else if(empty($_POST['email'])) $index = error(_empty_email, 1);
	            else if(!check_email($_POST['email'])) $index = error(_error_invalid_email, 1);
		        else if(_rows($check_user)) $index = error(_error_user_exists, 1);
		        else if(_rows($check_nick)) $index = error(_error_nick_exists, 1);
		        else if(_rows($check_email)) $index = error(_error_email_exists, 1);
		        else 
		        {
	                if ($_POST['pwd'])
	                {
	                    $newpwd = "pwd = '".md5($_POST['pwd'])."',";
	                   $index = info(_info_edit_profile_done, "?action=user&amp;id=".$userid."");
	                   $_SESSION['pwd'] = md5($_POST['pwd']);
			        } 
			        else 
			        {
				        $newpwd = "";
	                    $index = info(_info_edit_profile_done, "?action=user&amp;id=".$userid."");
			        }
	
			        $icq = preg_replace("=-=Uis","",$_POST['icq']);
	
	                if($_POST['t'] && $_POST['m'] && $_POST['j']) 
	                    $bday = cal($_POST['t']).".".cal($_POST['m']).".".$_POST['j'];
	
			        $qrycustom = db("SELECT feldname,type FROM ".$db['profile']);
			        $customfields = '';
		            while($getcustom = _fetch($qrycustom))
		            {
			            if($getcustom['type'] == 2) 
			                $customfields .= " ".$getcustom['feldname']." = '".links($_POST[$getcustom['feldname']])."', ";
			            else 
			                $customfields .= " ".$getcustom['feldname']." = '".up($_POST[$getcustom['feldname']])."', ";
			        }
	        
	                db("UPDATE ".$db['users']." SET	".$newpwd." ".$customfields."
	                   `country`      = '".$_POST['land']."',
	                   `user`         = '".up($_POST['user'])."',
				  	   `nick`         = '".up($_POST['nick'])."',
					   `rlname`       = '".up($_POST['rlname'])."',
					   `sex`          = '".((int)$_POST['sex'])."',
					   `status`       = '".((int)$_POST['status'])."',
					   `bday`         = '".$bday."',
					   `email`        = '".up($_POST['email'])."',
					   `nletter`      = '".((int)$_POST['nletter'])."',
					   `pnmail`       = '".((int)$_POST['pnmail'])."',
					   `city`         = '".up($_POST['city'])."',
					   `gmaps_koord`  = '".up($_POST['gmaps_koord'])."',
					   `hp`           = '".links($_POST['hp'])."',
					   `icq`          = '".((int)$icq)."',
					   `xfire`       = '".up($_POST['xfire'])."',
					   `signatur`     = '".up($_POST['sig'],1)."',
					   `beschreibung` = '".up($_POST['ich'],1)."'
					    WHERE id = ".$userid);
			  }
		  } 
		  elseif(isset($_GET['do']) ? $_GET['do'] : '' == "delete") 
		  {
					$getdel = db("SELECT id,nick,email,hp FROM ".$db['users']." WHERE id = '".intval($userid)."'",false,true);
	
					db("UPDATE ".$db['f_threads']."
										 SET `t_nick`   = '".up($getdel['nick'])."',
												 `t_email`  = '".up($getdel['email'])."',
												 `t_hp`			= '".links($getdel['hp'])."',
												 `t_reg`		= '0'
										 WHERE t_reg = '".intval($getdel['id'])."'");        
		
					db("UPDATE ".$db['f_posts']."
										 SET `nick`   = '".up($getdel['nick'])."',
												 `email`  = '".up($getdel['email'])."',
												 `hp`			= '".links($getdel['hp'])."',
												 `reg`		= '0'
										 WHERE reg = '".intval($getdel['id'])."'");				
		
					db("UPDATE ".$db['newscomments']."
										 SET `nick`     = '".up($getdel['nick'])."',
												 `email`    = '".up($getdel['email'])."',
												 `hp`       = '".links($getdel['hp'])."',
												 `reg`			= '0'
										 WHERE reg = '".intval($getdel['id'])."'");
		
					db("UPDATE ".$db['acomments']."
										 SET `nick`     = '".up($getdel['nick'])."',
												 `email`    = '".up($getdel['email'])."',
												 `hp`       = '".links($getdel['hp'])."',
												 `reg`			= '0'
										 WHERE reg = '".intval($getdel['id'])."'");
	
					db("DELETE FROM ".$db['msg']." WHERE von = '".intval($getdel['id'])."' OR an = '".intval($getdel['id'])."'");
					db("DELETE FROM ".$db['news']." WHERE autor = '".intval($getdel['id'])."'");
					db("DELETE FROM ".$db['permissions']." WHERE user = '".intval($getdel['id'])."'");
					db("DELETE FROM ".$db['squaduser']." WHERE user = '".intval($getdel['id'])."'");
					db("DELETE FROM ".$db['buddys']." WHERE user = '".intval($getdel['id'])."' OR buddy = '".intval($getdel['id'])."'");
					db("UPDATE ".$db['usergb']." SET `reg` = 0 WHERE reg = ".intval($getdel['id'])."");
					db("DELETE FROM ".$db['userpos']." WHERE user = '".intval($getdel['id'])."'");
					db("DELETE FROM ".$db['users']." WHERE id = '".intval($getdel['id'])."'");
					db("DELETE FROM ".$db['userstats']." WHERE user = '".intval($getdel['id'])."'");
		
					foreach($picformat as $tmpendung)
					{
						if(file_exists(basePath."/inc/images/uploads/userpics/".intval($getdel['id']).".".$tmpendung))
						{
							@unlink(basePath."/inc/images/uploads/userpics/".intval($getdel['id']).".".$tmpendung);
						}
						if(file_exists(basePath."/inc/images/uploads/useravatare/".intval($getdel['id']).".".$tmpendung))
						{
							@unlink(basePath."/inc/images/uploads/useravatare/".intval($getdel['id']).".".$tmpendung);
						}
					}
		
					$index = info(_info_account_deletet, '../news/');
	    } else {
	      $qry = db("SELECT * FROM ".$db['users']." WHERE id = '".$userid."'");
		    $get = _fetch($qry);
	
		    if($get['sex'] == "1") $sex = _pedit_male;
		    elseif($get['sex'] == "2") $sex = _pedit_female;
		    else $sex = _pedit_sex_ka;
	
		    if($get['status'] == 1) $status = _pedit_aktiv;
	      else $status = _pedit_inaktiv;
	
		    $qryl = db("SELECT * FROM ".$db['users']."
			    					WHERE id = '".$userid."'");
		    $getl = _fetch($qryl);
	
		    if($getl['level'] == 1)
			    $clan = '<input type="hidden" name="status" value="1" />';
		    else 
		    {
	            $qrycustom = db("SELECT * FROM ".$db['profile']." WHERE kid = '2' AND shown = '1' ORDER BY id ASC");
	            $custom_clan = '';
	            while($getcustom = _fetch($qrycustom))
	            {
	                $getcontent = db("SELECT ".$getcustom['feldname']." FROM ".$db['users']." WHERE id = '".$userid."'",false,true);
	                $custom_clan .= show(_profil_edit_custom, array("name" => pfields_name($getcustom['name']).":", "feldname" => $getcustom['feldname'], "value" => re($getcontent[$getcustom['feldname']])));
	            }
	
	            $clan = show($dir."/edit_clan", array("clan" => _profil_clan,
				                                      "pstatus" => _profil_status,
						  					          "pexclans" => _profil_exclans,
							  				          "status" => $status,
								  			          "exclans" => $get['ex'],
									  		          "custom_clan" => $custom_clan));
	      }
	
	      $bdayday = 0; $bdaymonth = 0; $bdayyear = 0;
		  if(!empty($get['bday']))
			list($bdayday, $bdaymonth, $bdayyear) = explode('.', $get['bday']);
			
	      if($_GET['show'] == "gallery")
	      {
	          $qrygl = db("SELECT * FROM ".$db['usergallery']." WHERE user = '".$userid."' ORDER BY id DESC");
	          $color = 1; $gal = '';
		      while($getgl = _fetch($qrygl))
		      {
	          $pic = show(_gallery_pic_link, array("img" => $getgl['pic'],
	                                               "user" => $userid));
	          $delete = show(_gallery_deleteicon, array("id" => $getgl['id']));
	          $edit = show(_gallery_editicon, array("id" => $getgl['id']));
	          $class = ($color % 2) ? "contentMainSecond" : "contentMainFirst"; $color++;
	
	          $gal .= show($dir."/edit_gallery_show", array("picture" => img_size("inc/images/uploads/usergallery"."/".$userid."_".$getgl['pic']),
	                                                        "beschreibung" => bbcode($getgl['beschreibung']),
	                                                        "class" => $class,
	                                                        "delete" => $delete,
	                                                        "edit" => $edit));
	        }
	        $show = show($dir."/edit_gallery", array("galleryhead" => _gallery_head,
	                                                 "pic" => _gallery_pic,
	                                                 "new" => _gallery_edit_new,
	                                                 "del" => _deleteicon_blank,
	                                                 "edit" => _editicon_blank,
	                                                 "beschr" => _gallery_beschr,
	                                                 "showgallery" => $gal));
	      } else {
	        $dropdown_age = show(_dropdown_date, array("day" => dropdown("day",$bdayday,1),
				 	        	                                 "month" => dropdown("month",$bdaymonth,1),
	                                      	           "year" => dropdown("year",$bdayyear,1)));
	
	        $qrycustom = db("SELECT * FROM ".$db['profile']." WHERE kid = '1' AND shown = '1' ORDER BY id ASC");
		      while($getcustom = _fetch($qrycustom))
		      {
			      $qrycontent = db("SELECT ".$getcustom['feldname']." FROM ".$db['users']."
		  			  	              WHERE id = '".$userid."'
							                LIMIT 1");
	          $getcontent = _fetch($qrycontent);
	
	          $custom_about .= show(_profil_edit_custom, array("name" => re(pfields_name($getcustom['name'])).":",
			  	                           											   "feldname" => $getcustom['feldname'],
							  								                             "value" => re($getcontent[$getcustom['feldname']])));
			    }
	
	        $qrycustom = db("SELECT * FROM ".$db['profile']." WHERE kid = '3' AND shown = '1' ORDER BY id ASC");
		      while($getcustom = _fetch($qrycustom))
		      {
			      $qrycontent = db("SELECT ".$getcustom['feldname']." FROM ".$db['users']."
		  		        		        WHERE id = '".$userid."'
							                LIMIT 1");
	          $getcontent = _fetch($qrycontent);
			      $custom_contact .= show(_profil_edit_custom, array("name" => re(pfields_name($getcustom['name'])).":",
			  	                             											   "feldname" => $getcustom['feldname'],
							  								                               "value" => re($getcontent[$getcustom['feldname']])));
			    }
	
			    $qrycustom = db("SELECT * FROM ".$db['profile']."
		  	          			   WHERE kid = '4' AND shown = '1'
	                         ORDER BY id ASC");
		      while($getcustom = _fetch($qrycustom))
		      {
			      $qrycontent = db("SELECT ".$getcustom['feldname']." FROM ".$db['users']."
		  		        		        WHERE id = '".$userid."'
							                LIMIT 1");
			      $getcontent = _fetch($qrycontent);
	          $custom_favos .= show(_profil_edit_custom, array("name" => re(pfields_name($getcustom['name'])).":",
			  	                            											 "feldname" => $getcustom['feldname'],
							  								                             "value" => re($getcontent[$getcustom['feldname']])));
			    }
	
			    $qrycustom = db("SELECT * FROM ".$db['profile']."
		             				   WHERE kid = '5' AND shown = '1'
	                         ORDER BY id ASC");
		      while($getcustom = _fetch($qrycustom))
		      {
			      $qrycontent = db("SELECT ".$getcustom['feldname']." FROM ".$db['users']." WHERE id = '".$userid."' LIMIT 1");
			      $getcontent = _fetch($qrycontent);
	
	          		$custom_hardware .= show(_profil_edit_custom, array("name" => re(pfields_name($getcustom['name'])).":", "feldname" => $getcustom['feldname'], "value" => re($getcontent[$getcustom['feldname']])));
			    }
	
	        if(!empty($get['icq']) && $get['icq'] != 0) $icq = $get['icq'];
	        if($get['nletter'] == 1) $pnl = "checked=\"checked\"";
			if($get['pnmail'] == 1) $pnm = "checked=\"checked\"";
	
	        $pic = userpic($get['id']);
	        $avatar = useravatar($get['id']);
	        if(!preg_match("#nopic#",$pic))
	          $deletepic = "| "._profil_delete_pic;
	        if(!preg_match("#noavatar#",$avatar))
	          $deleteava = "| "._profil_delete_ava;
			  $gmaps = show('membermap/geocoder', array('form' => 'editprofil'));
	
	        
		    if($userid == $rootAdmin) $delete = _profil_del_admin;
			else $delete = show("page/button_delete_account", array("id" => $get['id'],"action" => "action=editprofile&amp;do=delete", "value" => _button_title_del_account, "del" => convSpace(_confirm_del_account)));
	
	        $show = show($dir."/edit_profil", array("hardware" => _profil_hardware,
	                                                "hphead" => _profil_hp,
	                                                "country" => show_countrys($get['country']),
	                                                "pcountry" => _profil_country,
													"about" => _profil_about,
													"picturehead" => _profil_pic,
													"contact" => _profil_contact,
													"preal" => _profil_real,
													"pnick" => _nick,
													"pemail1" => _email,
	                                                "php" => _hp,
													"pava" => _profil_avatar,
													"pbday" => _profil_bday,
													"psex" => _profil_sex,
													"pname" => _loginname,
													"ppwd" => _new_pwd,
													"picq" => _icq,
													"psig" => _profil_sig,
													"ppic" => _profil_ppic,
													"pxfire" => _xfire,
	                                                "pcity" => _profil_city,
	                                                "city" => re($get['city']),
	                                                "nletter" => _profil_nletter,
													"pnmail" => _profil_pnmail,
	                                                "pnl" => $pnl,
													"pnm" => $pnm,
	                                                "pwd" => "",
	                                                "dropdown_age" => $dropdown_age,
	                                                "ava" => $avatar,
	                                                "hp" => re($get['hp']),
	                                                "gmaps" => $gmaps,
													"nick" => re($get['nick']),
													"name" => re($get['user']),
	                                                "gmaps_koord" => re($get['gmaps_koord']),
													"rlname" => re($get['rlname']),
													"bdayday" => $bdayday,
													"bdaymonth" => $bdaymonth,
													"bdayyear" =>$bdayyear,
													"sex" => $sex,
													"email" => re($get['email']),
													"icqnr" => $icq,
													"sig" => re_bbcode($get['signatur']),
													"xfire" => $get['xfire'],
													"clan" => $clan,
													"pic" => $pic,
													"editpic" => _profil_edit_pic,
	                                                "editava" => _profil_edit_ava,
	                                                "deleteava" => $deleteava,
			  									    "deletepic" => $deletepic,
	                                                "favos" => _profil_favos,
													"pich" => _profil_ich,
													"pposition" => _profil_position,
													"pstatus" => _profil_status,
													"position" => getrank($get['id']),
	                                                "value" => _button_value_edit,
									  			    "status" => $status,
	                                                "lang" => $language,
													"sonst" => _profil_sonst,
													"custom_about" => $custom_about,
													"custom_contact" => $custom_contact,
													"custom_favos" => $custom_favos,
													"custom_hardware" => $custom_hardware,
													"ich" => re_bbcode($get['beschreibung']),
													"del" => _profil_del_account,
													"delete" => $delete));
	      }
	
		    $index = show($dir."/edit", array("profilhead" => _profil_edit_head,
	                                        "editgallery" => _profil_edit_gallery_link,
	                                        "editprofil" => _profil_edit_profil_link,
	                                        "nick" => autor($get['id']),
	                                        "show" => $show));
	    }
}
?>