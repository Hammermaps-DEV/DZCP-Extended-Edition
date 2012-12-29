<?php
####################################
## Wird in einer Index ausgeführt ##
####################################
if (!defined('IS_DZCP'))
	exit();
	
#####################
## Userlogin Seite ##
#####################
  $where = _user_profile_of.'autor_'.$_GET['id'];
  if(!exist($_GET['id']))
  {
    $index = error(_user_dont_exist, 1);
  } else {
    $update = db("UPDATE ".$db['userstats']."
                  SET `profilhits` = profilhits+1
                  WHERE user = '".intval($_GET['id'])."'");

  	$qry = db("SELECT * FROM ".$db['users']."
	  					 WHERE id = '".intval($_GET['id'])."'");
	  $get = _fetch($qry);

	  if($get['sex'] == "1") $sex = _male;
	  elseif($get['sex'] == "2") $sex = _female;
    else $sex = '-';

    if(empty($get['hp'])) $hp = "-";
    else $hp = "<a href=\"".$get['hp']."\" target=\"_blank\">".$get['hp']."</a>";;

	  if(empty($get['email'])) $email = "-";
    else $email = "<img src=\"../inc/images/mailto.gif\" alt=\"\" align=\"texttop\"> <a href=\"mailto:".eMailAddr($get['email'])."\" target=\"_blank\">".eMailAddr($get['email'])."</a>";

	  $pn = show(_pn_write, array("id" => $_GET['id'],
		  													"nick" => $get['nick']));

	  if(empty($get['hlswid'])) $hlsw = "-";
		else $hlsw = show(_hlswicon, array("id" => re($get['hlswid']),
					   													 "img" => "1",
							  											 "css" => ""));

	  if($get['bday'] == ".." || $get['bday'] == 0 || empty($get['bday'])) $bday = "-";
	  else $bday = $get['bday'];

	  if(empty($get['icq']))
    {
      $icq = "-";
	  } else {
      $icq = show(_icqstatus, array("uin" => $get['icq']));
      $icqnr = re($get['icq']);
    }

  	if($get['status'] == 1 || ($getl['level'] != 1 && isset($_GET['sq']))) $status = _aktiv_icon;
		else $status = _inaktiv_icon;

	  $qryl = db("SELECT * FROM ".$db['users']."
		  					WHERE id = '".intval($_GET['id'])."'");
	  $getl = _fetch($qryl);

    if($getl['level'] != 1 || isset($_GET['sq']))
		{
      $sq = db("SELECT * FROM ".$db['userpos']."
                WHERE user = '".intval($_GET['id'])."'");

      $cnt = cnt($db['userpos'], " WHERE user = '".$get['id']."'");
      $i=1;

      if(_rows($sq) && !isset($_GET['sq']))
      {
        while($getsq = _fetch($sq))
        {
          if($i == $cnt) $br = "";
          else $br = "-";

          $pos .= " ".getrank($get['id'],$getsq['squad'],1)." ".$br;
          $i++;
        }
      } elseif(isset($_GET['sq'])) $pos = getrank($get['id'],$_GET['sq'],1);
        else                       $pos = getrank($get['id']);

		  $qrycustom = db("SELECT * FROM ".$db['profile']."
	  	 			           WHERE kid = '2'
                       AND shown = '1'
                       ORDER BY id ASC");
	    while($getcustom = _fetch($qrycustom))
      {
		    $qrycontent = db("SELECT ".$getcustom['feldname']." FROM ".$db['users']."
	  		       		        WHERE id = '".intval($_GET['id'])."'
						              LIMIT 1");
		    $getcontent = _fetch($qrycontent);
		    if(!empty($getcontent[$getcustom['feldname']]))
		    {
		      if($getcustom['type'] == 2)
            $custom_clan .= show(_profil_custom_url, array("name" => re(pfields_name($getcustom['name'])),
		  			                          								 		 "value" => re($getcontent[$getcustom['feldname']])));
			    elseif($getcustom['type'] == 3)
            $custom_clan .= show(_profil_custom_mail, array("name" => re(pfields_name($getcustom['name'])),
		  			                            							 		  "value" => eMailAddr(re($getcontent[$getcustom['feldname']]))));
		      else
            $custom_clan .= show(_profil_custom, array("name" => re(pfields_name($getcustom['name'])),
		  		                      									 		 "value" => re($getcontent[$getcustom['feldname']])));
		    }
		  }

			$clan = show($dir."/clan", array("clan" => _profil_clan,
		  															   "pposition" => _profil_position,
			  														   "pstatus" => _profil_status,
					   												   "position" => $pos,
							  										   "status" => $status,
								  									   "custom_clan" => $custom_clan));
		} else {
      $clan = "";
		}

		$buddyadd = show(_addbuddyicon, array("id" => $_GET['id']));

    if(permission("editusers"))
    {
      $edituser = show("page/button_edit_single", array("id" => "",
                                                       "action" => "action=admin&amp;edit=".$_GET['id'],
                                                       "title" => _button_title_edit));
      $edituser = str_replace("&amp;id=","",$edituser);
    } else {
      $edituser = "";
    }

  if($_GET['show'] == "gallery")
  {
    $qrygl = db("SELECT * FROM ".$db['usergallery']."
                 WHERE user = '".intval($_GET['id'])."'
                 ORDER BY id DESC");
	  while($getgl = _fetch($qrygl))
	  {
      $class = ($color % 2) ? "contentMainSecond" : "contentMainFirst"; $color++;
      $gal .= show($dir."/profil_gallery_show", array("picture" => img_size("inc/images/uploads/usergallery"."/".$_GET['id']."_".$getgl['pic']),
                                                      "beschreibung" => bbcode($getgl['beschreibung']),
                                                      "class" => $class));
    }
      $show = show($dir."/profil_gallery", array("galleryhead" => _gallery_head,
                                                 "pic" => _gallery_pic,
                                                 "beschr" => _gallery_beschr,
                                                 "showgallery" => $gal));
  } elseif($_GET['show'] == "gb") {
	  $addgb = show(_usergb_eintragen, array("id" => $_GET['id']));

    if(isset($_GET['page'])) $page = $_GET['page'];
    else $page = 1;

	  $qrygb = db("SELECT * FROM ".$db['usergb']."
		  					 WHERE user = ".intval($_GET['id'])."
			  				 ORDER BY datum DESC
                 LIMIT ".($page - 1)*$maxusergb.",".$maxusergb."");

    $entrys = cnt($db['usergb'], " WHERE user = ".intval($_GET['id']));
    $i = $entrys-($page - 1)*$maxusergb;

	  while($getgb = _fetch($qrygb))
	  {
      if($getgb['hp']) $gbhp = show(_hpicon, array("hp" => $getgb['hp']));
      else $gbhp = "";

      if($getgb['email']) $gbemail = show(_emailicon, array("email" => eMailAddr($getgb['email'])));
      else $gbemail = "";



      if(permission('editusers') || $_GET['id'] == $userid)
      {
        $edit = show("page/button_edit_single", array("id" => $get['id'],
                                                      "action" => "action=user&amp;show=gb&amp;do=edit&amp;gbid=".$getgb['id'],
                                                      "title" => _button_title_edit));
        $delete = show("page/button_delete_single", array("id" => $_GET['id'],
                                                         "action" => "action=user&amp;show=gb&amp;do=delete&amp;gbid=".$getgb['id'],
                                                         "title" => _button_title_del,
                                                         "del" => convSpace(_confirm_del_entry)));
      } else {
        $edit = "";
        $delete = "";
      }
      
      if($chkMe == 4) $posted_ip = $get['ip'];
      else $posted_ip = _logged;

		  if($getgb['reg'] == 0)
		  {
        if($getgb['hp']) $hp = show(_hpicon_forum, array("hp" => $getgb['hp']));
        else $hp = "";
        if($getgb['email']) $email = '<br />'.show(_emailicon_forum, array("email" => eMailAddr($getgb['email'])));
        else $email = "";
        $onoff = "";
        $avatar = "";
        $nick = show(_link_mailto, array("nick" => re($getgb['nick']),
                                         "email" => eMailAddr($getgb['email'])));
		  } else {
        $www = data($getgb['reg'], "hp");
        $hp = empty($www) ? '' : show(_hpicon_forum, array("hp" => $www));
        $email = '<br />'.show(_emailicon_forum, array("email" => eMailAddr(data($getgb['reg'], "email"))));
        $onoff = onlinecheck($getgb['reg']);
        $nick = autor($getgb['reg']);
		  }

      $titel = show(_eintrag_titel, array("postid" => $i,
												 				     			"datum" => date("d.m.Y", $getgb['datum']),
													 		 			    	"zeit" => date("H:i", $getgb['datum'])._uhr,
                                          "edit" => $edit,
                                          "delete" => $delete));

      if($chkMe == 4) $posted_ip = $getgb['ip'];
      else            $posted_ip = _logged;

		  $membergb .= show("page/comments_show", array("titel" => $titel,
			  		  	    													      "comment" => bbcode($getgb['nachricht']),
                                                    "nick" => $nick,
                                                    "hp" => $hp,
                                                    "editby" => bbcode($getgb['editby']),
                                                    "email" => $email,
                                                    "avatar" => useravatar($getgb['reg']),
                                                    "onoff" => $onoff,
                                                    "rank" => getrank($getgb['reg']),
                                                    "ip" => $posted_ip));
		  $i--;
	  }

    if(!ipcheck("mgbid(".$_GET['id'].")", $flood_membergb))
    {
      if(isset($userid))
	    {
		    $form = show("page/editor_regged", array("nick" => autor($userid),
                                                 "von" => _autor));
	    } else {
        $form = show("page/editor_notregged", array("nickhead" => _nick,
                                                    "emailhead" => _email,
                                                    "hphead" => _hp,
                                                    "postemail" => ""));
      }
	    $add = show($dir."/usergb_add", array("titel" => _eintragen_titel,
			    																	"nickhead" => _nick,
					    															"bbcodehead" => _bbcode,
							    													"emailhead" => _email,
									    											"hphead" => _hp,
                                            "form" => $form,
                                            "security" => _register_confirm,
                                            "preview" => _preview,
                                            "ed" => "&amp;uid=".$_GET['id'],
                                            "whaturl" => "add",
                                            "reg" => "",
                                            "b1" => $u_b1,
                                            "b2" => $u_b2,
											    									"id" => $_GET['id'],
													    							"postemail" => $postemail,
		 		 											  			 	    "add_head" => _gb_add_head,
                                            "what" => _button_value_add,
                                            "lang" => $language,
                                            "ip" => _iplog_info,
																  					"posthp" => $posthp,
																	  				"postnick" => $postnick,
																		  			"posteintrag" => "",
																			  		"error" => "",
																					  "eintraghead" => _eintrag));
    } else {
      $add = "";
    }

    $seiten = nav($entrys,$maxusergb,"?action=user&amp;id=".$_GET['id']."&show=gb");

    $show = show($dir."/profil_gb",array("gbhead" => _membergb,
	 				  													   "show" => $membergb,
                                         "seiten" => $seiten,
                                         "entry" => $add));
    } else {
      $qrycustom = db("SELECT * FROM ".$db['profile']."
	   	  		           WHERE kid = '1' AND shown = '1'
                       ORDER BY id ASC");
	    while($getcustom = _fetch($qrycustom))
	    {
		    $qrycontent = db("SELECT ".$getcustom['feldname']." FROM ".$db['users']."
	            		        WHERE id = '".intval($_GET['id'])."'
					                LIMIT 1");
		    $getcontent = _fetch($qrycontent);
		    if(!empty($getcontent[$getcustom['feldname']]))
		    {
		      if($getcustom['type'] == 2)
            $custom_about .= show(_profil_custom_url, array("name" => re(pfields_name($getcustom['name'])),
		 	                                									 		"value" => re($getcontent[$getcustom['feldname']])));
			    elseif($getcustom['type'] == 3)
            $custom_about .= show(_profil_custom_mail, array("name" => re(pfields_name($getcustom['name'])),
		                                  									 		 "value" => eMailAddr(re($getcontent[$getcustom['feldname']]))));
		      else
            $custom_about .= show(_profil_custom, array("name" => re(pfields_name($getcustom['name'])),
		  	                            								 		"value" => re($getcontent[$getcustom['feldname']])));
		    }
		  }

		  $qrycustom = db("SELECT * FROM ".$db['profile']."
	            			   WHERE kid = '3' AND shown = '1'
                       ORDER BY id ASC");
	    while($getcustom = _fetch($qrycustom))
	    {
		    $qrycontent = db("SELECT ".$getcustom['feldname']." FROM ".$db['users']."
	              		      WHERE id = '".intval($_GET['id'])."'
					                LIMIT 1");
		    $getcontent = _fetch($qrycontent);
		    if(!empty($getcontent[$getcustom['feldname']]))
		    {
		      if($getcustom['type'] == 2)
            $custom_contact .= show(_profil_custom_url, array("name" => re(pfields_name($getcustom['name'])),
		    	                                   						 		  "value" => re($getcontent[$getcustom['feldname']])));
			    elseif($getcustom['type'] == 3)
            $custom_contact .= show(_profil_custom_mail, array("name" => re(pfields_name($getcustom['name'])),
	  				  							 		                               "value" => eMailAddr(re($getcontent[$getcustom['feldname']]))));
	        else
            $custom_contact .= show(_profil_custom, array("name" => re(pfields_name($getcustom['name'])),
	  				  							 		                          "value" => re($getcontent[$getcustom['feldname']])));
	      }
	    }

	    $qrycustom = db("SELECT * FROM ".$db['profile']."
  	           			   WHERE kid = '4' AND shown = '1'
                       ORDER BY id ASC");
      $cf = 0;
      while($getcustom = _fetch($qrycustom))
      {
        $qrycontent = db("SELECT ".$getcustom['feldname']." FROM ".$db['users']."
		          		        WHERE id = '".intval($_GET['id'])."'
				                  LIMIT 1");
	      $getcontent = _fetch($qrycontent);
	      if(!empty($getcontent[$getcustom['feldname']]))
	      {
	        if($getcustom['type']==2)
            $custom_favos .= show(_profil_custom_url, array("name" => re(pfields_name($getcustom['name'])),
	 				    							 		                            "value" => re($getcontent[$getcustom['feldname']])));
		      elseif($getcustom['type']==3)
            $custom_favos .= show(_profil_custom_mail, array("name" => re(pfields_name($getcustom['name'])),
	  			    							 		                             "value" => eMailAddr(re($getcontent[$getcustom['feldname']]))));
	        else
            $custom_favos .= show(_profil_custom, array("name" => re(pfields_name($getcustom['name'])),
	  			    							 		                        "value" => re($getcontent[$getcustom['feldname']])));
          $cf++;
	      }
	    }
      if($cf != 0) $favos_head = show(_profil_head_cont, array("what" => _profil_favos));

	  	$qrycustom = db("SELECT * FROM ".$db['profile']."
	    	       			   WHERE kid = '5' AND shown = '1'
                       ORDER BY id ASC");
      $ch = 0;
	    while($getcustom = _fetch($qrycustom))
	    {
        $qrycontent = db("SELECT ".$getcustom['feldname']." FROM ".$db['users']."
 		           		        WHERE id = '".intval($_GET['id'])."'
			  	                LIMIT 1");
	      $getcontent = _fetch($qrycontent);

        if(!empty($getcontent[$getcustom['feldname']]))
        {
	        if($getcustom['type']==2)
            $custom_hardware .= show(_profil_custom_url, array("name" => re(pfields_name($getcustom['name'])),
			                                  									 		 "value" => re($getcontent[$getcustom['feldname']])));
		      elseif($getcustom['type']==3)
            $custom_hardware .= show(_profil_custom_mail, array("name" => re(pfields_name($getcustom['name'])),
		 		                                    							 		  "value" => eMailAddr(re($getcontent[$getcustom['feldname']]))));
		      else
            $custom_hardware .= show(_profil_custom, array("name" => re(pfields_name($getcustom['name'])),
		 			                            								 		 "value" => re($getcontent[$getcustom['feldname']])));
          $ch++;
		    }
		  }
      if($ch != 0) $hardware_head = show(_profil_head_cont, array("what" => _profil_hardware));

      if(empty($get['rlname'])) $rlname = '-';
      else $rlname = re($get['rlname']);

      $show = show($dir."/profil_show",array("hardware_head" => $hardware_head,
                                             "about" => _profil_about,
                                             "rang" => $rang,
                                             "country" => flag($get['country']),
                                             "rangpic" => $rangpic,
                                             "pcity" => _profil_city,
                                             "city" => re($get['city']),
                                             "prank" => _profile_rank,
                                             "stats_hits" => _profil_pagehits,
                                             "stats_profilhits" => _profil_profilhits,
                                             "stats_msgs" => _profil_msgs,
                                             "stats_lastvisit" => _profil_last_visit,
                                             "stats_forenposts" => _profil_forenposts,
                                             "stats_logins" => _profil_logins,
                                             "stats_cws" => _profil_cws,
                                             "stats_reg" => _profil_registered,
                                             "stats_votes" => _profil_votes,
                                             "logins" => userstats($_GET['id'], "logins"),
                                             "hits" => userstats($_GET['id'], "hits"),
                                             "msgs" => userstats($_GET['id'], "writtenmsg"),
                                             "forenposts" => userstats($_GET['id'], "forumposts"),
                                             "votes" => userstats($_GET['id'], "votes"),
                                             "cws" => userstats($_GET['id'], "cws"),
                                             "regdatum" => date("d.m.Y H:i", $get['regdatum'])._uhr,
                                             "lastvisit" => date("d.m.Y H:i", userstats($_GET['id'], "lastvisit"))._uhr,
		     						  	                     "contact" => _profil_contact,
			      							                   "preal" => _profil_real,
                                             "pemail" => _email,
                                             "picq" => _icq,
                                             "phlsw" => _hlswstatus,
                                             "psteam" => _steamid,
                                             "php" => _hp,
                                             "hp" => $hp,
										                         "pnick" => _nick,
										                         "pbday" => _profil_bday,
										                         "page" => _profil_age,
										                         "psex" => _profil_sex,
										                         "gamestuff" => _profil_gamestuff,
                                             "xfire" => re($get['hlswid']),
  										                       "buddyadd" => $buddyadd,
	  									                       "userstats" => _profil_userstats,
		    				  				                   "pos" => _profil_os,
			    				  			                   "pcpu" => _profil_cpu,
				    				  		                   "pram" => _profil_ram,
  					    				  	                 "phdd" => _profil_hdd,
	  					    				                   "pboard" => _profil_board,
		  					    			                   "pmaus" => _profil_maus,
			  					  		                     "nick" => autor($get['id']),
				  					  	                     "rlname" => $rlname,
    			  					  	                   "bday" => $bday,
		    		  					                     "age" => getAge($get['bday']),
			    		  				                     "sex" => $sex,
							      		                     "email" => $email,
								      	                     "icq" => $icq,
									                           "icqnr" => $icqnr,
										                         "pn" => $pn,
                                             "edituser" => $edituser,
										                         "hlswid" => $hlsw,
				  	  					                     "steamid" => $steamid,
					  	  				                     "steam" => $steam,
						  	  			                     "onoff" => onlinecheck($get['id']),
  							  	  		                   "clan" => $clan,
	  							  	  	                   "picture" => userpic($get['id']),
                                             "favos_head" => $favos_head,
			  							                       "sonst" =>	_profil_sonst,
				  						                       "pich" => _profil_ich,
  					  					                     "pposition" => _profil_position,
	  					  				                     "pstatus" => _profil_status,
		  					  			                     "position" => getrank($get['id']),
			  					  		                     "status" => $status,
				  					  	                     "ich" => bbcode($get['beschreibung']),
					  					                       "custom_about" => $custom_about,
						  				                       "custom_contact" => $custom_contact,
							  			                       "custom_favos" => $custom_favos,
								  		                       "custom_hardware" => $custom_hardware));
    }

    $navi_profil = show(_profil_navi_profil, array("id" => $_GET['id']));
    $navi_gb = show(_profil_navi_gb, array("id" => $_GET['id']));
    $navi_gallery = show(_profil_navi_gallery, array("id" => $_GET['id']));

    $profil_head = show(_profil_head, array("profilhits" => userstats($_GET['id'],"profilhits")));

	  $index = show($dir."/profil", array("profilhead" => $profil_head,
						  													"show" => $show,
                                        "nick" => autor($_GET['id']),
                                        "profil" => $navi_profil,
                                        "gb" => $navi_gb,
                                        "gallery" => $navi_gallery));

    if($_GET['do'] == "delete")
    {
      if($chkMe == "4" || $_GET['id'] == $userid)
      {
        $qry = db("DELETE FROM ".$db['usergb']."
                   WHERE user = '".intval($_GET['id'])."'
                   AND id = '".intval($_GET['gbid'])."'");

        $index = info(_gb_delete_successful, "?action=user&amp;id=".$_GET['id']."&show=gb");
      } else {
        $index = error(_error_wrong_permissions, 1);
      }
    } elseif($_GET['do'] == "edit") {
    $qry = db("SELECT * FROM ".$db['usergb']."
               WHERE id = '".intval($_GET['gbid'])."'");
    $get = _fetch($qry);

    if($get['reg'] == $userid || permission('editusers'))
    {
      if($get['reg'] != 0)
  	  {
  		  $form = show("page/editor_regged", array("nick" => autor($get['reg']),
                                                 "von" => _autor));
  	  } else {
        $form = show("page/editor_notregged", array("nickhead" => _nick,
                                                    "emailhead" => _email,
                                                    "hphead" => _hp,
                                                    "postemail" => re($get['email']),
              							    									  "posthp" => re($get['hp']),
              								    								  "postnick" => re($get['nick'])));
      }

		  $index = show($dir."/usergb_add", array("nickhead" => _nick,
                                              "add_head" => _gb_edit_head,
				  																		"bbcodehead" => _bbcode,
					  																	"emailhead" => _email,
                                              "preview" => _preview,
                                              "whaturl" => "edit&gbid=".$_GET['gbid'],
                                              "ed" => "&amp;do=edit&amp;uid=".$_GET['id']."&amp;gbid=".$_GET['gbid'],
                                              "security" => _register_confirm,
                                              "b1" => $u_b1,
                                              "b2" => $u_b2,
                                              "what" => _button_value_edit,
                                              "reg" => $get['reg'],
						  																"hphead" => _hp,
							  															"id" => $_GET['id'],
                                              "form" => $form,
								  														"postemail" => $get['email'],
									  													"posthp" => $get['hp'],
										  												"postnick" => re($get['nick']),
											  											"posteintrag" => re_bbcode($get['nachricht']),
												  										"error" => $error,
                                              "ip" => _iplog_info,
																  						"eintraghead" => _eintrag));
      } else {
        $index = error(_error_edit_post,1);
      }
    }
  }
?>

