<?php
/**
 * <DZCP-Extended Edition>
 * @package: DZCP-Extended Edition
 * @author: DZCP Developer Team || Hammermaps.de Developer Team
 * @link: http://www.dzcp.de || http://www.hammermaps.de
 */

if (!defined('IS_DZCP')) exit();
if (_version < '1.0')
    $index = _version_for_page_outofdate;
else
{
    ###############
    ## Userlobby ##
    ###############
    $where = _site_user_lobby;

    if(checkme() == "unlogged")
        $index = error(_error_have_to_be_logged);
    else
    {
        $can_erase = false;

        //Get Userinfos
        $lastvisit = userstats(userid(), 'lastvisit');
        $lastvisit = empty($lastvisit) ? "0" : $lastvisit;

        ##################################
        ## Neue Foreneintraege anzeigen ##
        ##################################
        $qrykat = db("SELECT s1.id,s2.kattopic,s1.intern,s2.id FROM ".dba::get('f_kats')." AS s1 LEFT JOIN ".dba::get('f_skats')." AS s2 ON s1.id = s2.sid ".((!permission("intforum")) ? "AND s1.intern = '0'" : "")." ORDER BY s1.kid,s2.kattopic"); $forumposts = '';
        if(_rows($qrykat) >= 1)
        {
            while($getkat = _fetch($qrykat))
            {
                if(fintern($getkat['id']))
                {
                    $qrytopic = db("SELECT lp,id,topic,first,sticky FROM ".dba::get('f_threads')." WHERE kid = '".$getkat['id']."' AND lp > ".$lastvisit." ORDER BY lp DESC LIMIT 150"); $forumposts_show = '';
                    if(_rows($qrytopic) >= 1)
                    {
                        while($gettopic = _fetch($qrytopic))
                        {
                            if(check_is_new($gettopic['lp']))
                            {
                                //Posts ab 2..
                                $lp = cnt(dba::get('f_posts'), " WHERE sid = '".$gettopic['id']."'");
                                switch(($count=cnt(dba::get('f_posts'), " WHERE date > ".$lastvisit." AND sid = '".$gettopic['id']."'")))
                                {
                                    case 0:
                                        $pagenr = 1;
                                        $post = '';
                                    break;
                                    case 1:
                                        $pagenr = ceil($lp/settings('m_fposts'));
                                        $post = _new_post_1;
                                    break;
                                    default:
                                        $pagenr = ceil($lp/settings('m_fposts'));
                                        $post = _new_post_2;
                                    break;
                                }

                                $intern = ($getkat['intern'] ? '<span class="fontWichtig">'._internal.':</span>&nbsp;&nbsp;&nbsp;' : '');
                                $wichtig = ($gettopic['sticky'] ? '<span class="fontWichtig">'._sticky.':</span> ' : '');
                                $date = (date("d.m.")==date("d.m.",$gettopic['lp'])) ? '['.date("H:i",$gettopic['lp']).']' : date("d.m.",$gettopic['lp']).' ['.date("H:i",$gettopic['lp']).']';

                                $can_erase = true;
                                $forumposts_show .= '&nbsp;&nbsp;'.$date.show(_user_new_forum, array("cnt" => ($count == 0 ? $count +1 : $count),
                                                                                                     "tid" => $gettopic['id'],
                                                                                                     "thread" => string::decode($gettopic['topic']),
                                                                                                     "intern" => $intern,
                                                                                                     "wichtig" => $wichtig,
                                                                                                     "post" => $post,
                                                                                                     "page" => $pagenr,
                                                                                                     "nthread" => ($gettopic['first'] ? _no_new_thread : _new_thread),
                                                                                                     "lp" => $lp +1));
                            }
                        } //while end
                    }

                    if(!empty($forumposts_show))
                        $forumposts .= '<div style="padding:4px;padding-left:0"><span class="fontBold">'.$getkat['kattopic'].'</span></div>'.$forumposts_show; //Output
                }
            } //while end
        }

        ############################
        ## Neue Clanwars anzeigen ##
        ############################
        $qrycw = db("SELECT s1.*,s2.icon FROM ".dba::get('cw')." AS s1 LEFT JOIN ".dba::get('squads')." AS s2 ON s1.squad_id = s2.id ORDER BY s1.datum"); $cws = '';
        if(_rows($qrycw) >= 1)
        {
            while($getcw = _fetch($qrycw))
            {
                if(check_is_new($getcw['datum']))
                {
                    $can_erase = true;
                    $cws .= show(_user_new_cw, array("datum" => date("d.m. H:i", $getcw['datum'])._uhr, "id" => $getcw['id'], "icon" => $getcw['icon'], "gegner" => string::decode($getcw['clantag']))); //Output
                }
            } //while end

            if(!empty($cws))
                $cws = '<table class="hperc" cellspacing="1">'.$cws.'</table>';
        }

        #####################################
        ## Neue Registrierte User anzeigen ##
        #####################################
        $admin_pass = !permission("activateusers") ? "WHERE level >= 1" : "";
        $qryu = db("SELECT regdatum FROM ".dba::get('users')." ".$admin_pass." ORDER BY id DESC"); $user = ''; $i = 0;
        if(_rows($qryu) >= 1)
        {
            while($getu = _fetch($qryu))
            { if(check_is_new($getu['regdatum'])) { $i++; } } //while end

            if($i >= 1)
            {
                $can_erase = true;
                $eintrag = ($i == 1 ? _new_users_1 : _new_users_2);
                $user = show(_user_new_users, array("cnt" => $i, "eintrag" => $eintrag)); //Output
            }
        }

        ###########################################
        ## Neue Eintruage im Guastebuch anzeigen ##
        ###########################################
        $activ = ((!permission("gb") && settings('gb_activ')) ? "WHERE public = '1'" : '');
        $qrygb = db("SELECT datum FROM ".dba::get('gb')." ".$activ." ORDER BY id DESC"); $gb = ''; $i = 0;
        if(_rows($qrygb) >= 1)
        {
            while($getgb = _fetch($qrygb))
            { if(check_is_new($getgb['datum'])) { $i++; } } //while end

            if($i >= 1)
            {
                $can_erase = true;
                $eintrag = ($i == 1 ? _new_eintrag_1 : _new_eintrag_2);
                $gb = show(_user_new_gb, array("cnt" => $i, "eintrag" => $eintrag)); //Output
            }
        }

        ################################################
        ## Neue Eintruage im User Guastebuch anzeigen ##
        ################################################
        $qrymember = db("SELECT datum FROM ".dba::get('usergb')." WHERE user = '".userid()."' ORDER BY datum DESC"); $membergb = ''; $i = 0;
        if(_rows($qrymember) >= 1)
        {
            while($getmember = _fetch($qrymember))
            { if(check_is_new($getmember['datum'])) { $i++; } } //while end

            if($i >= 1)
            {
                $can_erase = true;
                $eintrag = ($i == 1 ? _new_eintrag_1 : _new_eintrag_2);
                $membergb = show(_user_new_membergb, array("cnt" => $i, "id" => userid(), "eintrag" => $eintrag)); //Output
            }
        }

        #######################################
        ## Neue Private Nachrichten anzeigen ##
        #######################################
        $mymsg = show(_no_lobby_mymessages); //Output
        $sqlmsg = db("SELECT id,an,datum FROM ".dba::get('msg')." WHERE an = '".userid()."' AND readed = 0 AND see_u = 0 ORDER BY datum DESC");
        if(_rows($sqlmsg) >= 1)
        {
            $getmsg = _fetch($sqlmsg);
            if(($check = cnt(dba::get('msg'), " WHERE an = '".userid()."' AND readed = 0 AND see_u = 0")) == 1)
                $mymsg = show(_lobby_mymessage, array("cnt" => '1')); //Output
            else if($check >= 2)
                $mymsg = show(_lobby_mymessages, array("cnt" => $check)); //Output
        }

        ########################
        ## Neue News anzeigen ##
        ########################
        $qrynews = db("SELECT id,datum FROM ".dba::get('news')." WHERE public = 1 ".(checkme() >= 2 ? "" : "AND intern = 0 ")."AND datum <= ".time()." ORDER BY id DESC"); $news = '';
        if(_rows($qrynews) >= 1)
        {
            while($getnews  = _fetch($qrynews))
            {
                if(check_is_new($getnews['datum']))
                {
                    $can_erase = true;
                    $news = show(_user_new_news, array("cnt" => cnt(dba::get('news'), " WHERE datum > ".$lastvisit." AND public = 1"), "eintrag" => _lobby_new_news)); //Output
                }
            } //while end
        }

        #################################
        ## Neue News comments anzeigen ##
        #################################
        $qrycheckn = db("SELECT id,titel FROM ".dba::get('news')." WHERE public = 1 AND datum <= ".time()); $newsc = '';
        if(_rows($qrycheckn) >= 1)
        {
            while($getcheckn = _fetch($qrycheckn))
            {
                $sqlnewsc = db("SELECT news,datum FROM ".dba::get('newscomments')." WHERE news = '".$getcheckn['id']."' ORDER BY datum DESC");
                if(_rows($sqlnewsc) >= 1)
                {
                    $getnewsc = _fetch($sqlnewsc);
                    if(check_is_new($getnewsc['datum']))
                    {
                        $can_erase = true;
                        $eintrag = (($check = cnt(dba::get('newscomments'), " WHERE datum > ".$lastvisit." AND news = '".$getnewsc['news']."'")) == 1 ? _lobby_new_newsc_1 : _lobby_new_newsc_2);
                        $newsc .= show(_user_new_newsc, array("cnt" => $check, "id" => $getnewsc['news'], "news" => string::decode($getcheckn['titel']), "eintrag" => $eintrag)); //Output
                    }
                }
            } //while end
        }

        #####################################
        ## Neue Download comments anzeigen ##
        #####################################
        $qrycheckn = db("SELECT id,download FROM ".dba::get('downloads')." WHERE `comments` = 1"); $downloadc = '';
        if(_rows($qrycheckn) >= 1)
        {
            while($getcheckn = _fetch($qrycheckn))
            {
                $sqldownloadc = db("SELECT download,datum FROM ".dba::get('dl_comments')." WHERE `download` = '".$getcheckn['id']."' ORDER BY datum DESC");
                if(_rows($sqldownloadc) >= 1)
                {
                    $getdownloadc = _fetch($sqldownloadc);
                    if(check_is_new($getdownloadc['datum']))
                    {
                        $can_erase = true;
                        $eintrag = (($check = cnt(dba::get('dl_comments'), " WHERE datum > ".$lastvisit." AND download = '".$getdownloadc['download']."'")) == 1 ? _lobby_dl_comments_1 : _lobby_dl_comments_2);
                        $downloadc .= show(_user_new_dlc, array("cnt" => $check, "id" => $getcheckn['id'], "download" => string::decode($getcheckn['download']), "eintrag" => $eintrag)); //Output
                    }
                }
            } //while end
        }

        #####################################
        ## Neue Clanwars comments anzeigen ##
        #####################################
        $qrycheckcw = db("SELECT id FROM ".dba::get('cw')); $cwcom = '';
        if(_rows($qrycheckcw) >= 1)
        {
            while($getcheckcw = _fetch($qrycheckcw))
            {
                $sqlcwc = db("SELECT id,cw,datum FROM ".dba::get('cw_comments')." WHERE cw = '".$getcheckcw['id']."' ORDER BY datum DESC");
                if(_rows($sqlcwc) >= 1)
                {
                    $getcwc = _fetch($sqlcwc);
                    if(check_is_new($getcwc['datum']))
                    {
                        $can_erase = true;
                        $eintrag = (($check = cnt(dba::get('cw_comments'), " WHERE datum > ".$lastvisit." AND cw = '".$getcwc['cw']."'")) == 1 ? _lobby_new_cwc_1 : _lobby_new_cwc_2);
                        $cwcom .= show(_user_new_clanwar, array("cnt" => $check, "id" => $getcwc['cw'], "eintrag" => $eintrag)); //Output
                    }
                }
            } //while end
        }

        #########################
        ## Neue Votes anzeigen ##
        #########################
        $qrynewv = db("SELECT datum FROM ".dba::get('votes')." WHERE ".(permission("votes") ? "" : "intern = 0 AND ")."forum = 0 ORDER BY datum DESC"); $newv = '';
        if(_rows($qrynewv) >= 1)
        {
            $getnewv = _fetch($qrynewv);
            if(check_is_new($getnewv['datum']))
            {
                $can_erase = true;
                $eintrag = (($check = cnt(dba::get('votes'), " WHERE datum > ".$lastvisit." AND forum = 0")) == 1 ? _new_vote_1 : _new_vote_2);
                $newv = show(_user_new_votes, array("cnt" => $check, "eintrag" => $eintrag)); //Output
            }
        }

        ##############################
        ## Kalender Events anzeigen ##
        ##############################
        $qrykal = db("SELECT datum FROM ".dba::get('events')." WHERE datum > '".time()."' ORDER BY datum"); $nextkal = '';
        if(_rows($qrykal) >= 1)
        {
            $getkal = _fetch($qrykal);
            if(check_is_new($getkal['datum']))
            {
                if(date("d.m.Y",$getkal['datum']) == date("d.m.Y", time()))
                    $nextkal = show(_userlobby_kal_today, array("time" => mktime(0,0,0,date("m",$getkal['datum']), date("d",$getkal['datum']),date("Y",$getkal['datum'])))); //Output
                else
                    $nextkal = show(_userlobby_kal_not_today, array("time" => mktime(0,0,0,date("m",$getkal['datum']), date("d",$getkal['datum']),date("Y",$getkal['datum'])), "date" => date("d.m.Y", $getkal['datum']))); //Output
            }
        }

        ##########################
        ## Neue Awards anzeigen ##
        ##########################
        $qryaw = db("SELECT id,postdate FROM ".dba::get('awards')." ORDER BY id DESC"); $awards = '';
        if(_rows($qryaw) >= 1)
        {
            $getaw = _fetch($qryaw);
            if(check_is_new($getaw['postdate']))
            {
                $can_erase = true;
                $eintrag = (($check = cnt(dba::get('awards'), " WHERE postdate > ".$lastvisit)) == 1 ? _new_awards_1 : _new_awards_2);
                $awards = show(_user_new_awards, array("cnt" => $check, "eintrag" => $eintrag)); //Output
            }
        }

        ############################
        ## Neue Rankings anzeigen ##
        ############################
        $qryra = db("SELECT id,postdate FROM ".dba::get('rankings')." ORDER BY id DESC"); $rankings = '';
        if(_rows($qryra) >= 1)
        {
            $getra = _fetch($qryra);
            if(check_is_new($getra['postdate']))
            {
                $can_erase = true;
                $eintrag = (($check = cnt(dba::get('rankings'), " WHERE postdate > ".$lastvisit."")) == 1 ? _new_rankings_1 : _new_rankings_2);
                $rankings = show(_user_new_rankings, array("cnt" => $check, "eintrag" => $eintrag)); //Output
            }
        }

        ###########################
        ## Neue Artikel anzeigen ##
        ###########################
        $qryart = db("SELECT id,datum FROM ".dba::get('artikel')." WHERE public = 1 ORDER BY id DESC"); $artikel = '';
        if(_rows($qryart) >= 1)
        {
            while($getart  = _fetch($qryart))
            {
                if(check_is_new($getart['datum']))
                {
                    $can_erase = true;
                    $eintrag =  (($check = cnt(dba::get('artikel'), " WHERE datum > ".$lastvisit." AND public = 1")) == 1 ? _lobby_new_art_1 : _lobby_new_art_2);
                    $artikel = show(_user_new_art, array("cnt" => $check, "eintrag" => $eintrag)); //Output
                }
            } //while end
        }

        ####################################
        ## Neue Artikel Comments anzeigen ##
        ####################################
        $qrychecka = db("SELECT id FROM ".dba::get('artikel')." WHERE public = 1"); $artc = '';
        if(_rows($qrychecka) >= 1)
        {
            while($getchecka = _fetch($qrychecka))
            {
                $sqlartc = db("SELECT id,artikel,datum FROM ".dba::get('acomments')." WHERE artikel = '".$getchecka['id']."' ORDER BY datum DESC");
                if(_rows($sqlartc) >= 1)
                {
                    $getartc = _fetch($sqlartc);
                    if(check_is_new($getartc['datum']))
                    {
                        $can_erase = true;
                        $eintrag = (($check = cnt(dba::get('acomments'), " WHERE datum > ".$lastvisit." AND artikel = '".$getartc['artikel']."'")) == 1 ? _lobby_new_artc_1 : _lobby_new_artc_2);
                        $artc .= show(_user_new_artc, array("cnt" => $check, "id" => $getartc['artikel'], "eintrag" => $eintrag)); //Output
                    }
                }
            } //while end
        }

        #########################################
        ## Neue Bilder in der Gallery anzeigen ##
        #########################################
        $qrygal = db("SELECT id,datum FROM ".dba::get('gallery')." ORDER BY id DESC"); $gal = ''; $i = 0;
        if(_rows($qrygal) >= 1)
        {
            while($getgal = _fetch($qrygal))
            { if(check_is_new($getgal['datum'])) { $i++; } } //while end

            if($i >= 1)
            {
                $can_erase = true;
                $eintrag = ($i == 1 ? _new_gal_1 : _new_gal_2);
                $gal = show(_user_new_gallery, array("cnt" => $i, "action" => ($i == 1 ? "action=show&amp;id=".$getgal['id'] : ''), "eintrag" => $eintrag)); //Output
            }
        }

        #########################
        ## Neue Aways anzeigen ##
        #########################
        $qryawayn = db("SELECT * FROM ".dba::get('away')." ORDER BY id"); $away_new = '';
        if(_rows($qryawayn) >= 1)
        {
            $getchklevel = db("SELECT level FROM ".dba::get('users')." WHERE id = '".userid()."'",false,true); $awayn = ''; $show_awayn = false;
            while($getawayn = _fetch($qryawayn))
            {
                if(check_is_new($getawayn['date']) && $getchklevel['level'] >= 2)
                {
                    $can_erase = true;
                    $awayn .= show(_user_away_new, array("id" => $getawayn['id'], "user" => autor($getawayn['userid']), "ab" => date("d.m.y",$getawayn['start']), "wieder" => date("d.m.y",$getawayn['end']), "what" => $getawayn['titel']));
                    $show_awayn	= true;
                }
            } //while end

            if($show_awayn)
                $away_new = show(_user_away, array("naway" => _lobby_away_new, "away" => $awayn)); //Output
        }

        #########################
        ## Alle Aways anzeigen ##
        #########################
        $qryawaya = db("SELECT * FROM ".dba::get('away')." WHERE start <= '".time()."' AND end >= '".time()."' ORDER BY start"); $away_now = '';
        if(_rows($qryawaya) >= 1)
        {
            $show_awaya	= false; $awaya = '';
            while($getawaya = _fetch($qryawaya))
            {
                if(_rows($qryawaya) && $getchklevel['level'] >= 2)
                {
                    $wieder = ($getawaya['end'] > time() ? _away_to2.' <b>'.date("d.m.y",$getawaya['end']).'</b>' : '');
                    $wieder = (date("d.m.Y",$getawaya['end']) == date("d.m.Y",time()) ? _away_today : $wieder);
                    $awaya .= show(_user_away_now, array("id" => $getawaya['id'], "user" => autor($getawaya['userid']), "wieder" => $wieder, "what" => $getawaya['titel']));
                    $show_awaya	= true;
                }
            } //while end

            if($show_awaya)
                $away_now = show(_user_away_currently, array("ncaway" => _lobby_away, "caway" => $awaya)); //Output
        }

        ################################
        ## Neue Forum Topics anzeigen ##
        ################################
        $qryft = db("SELECT s1.t_text,s1.id,s1.topic,s1.kid,s2.kattopic,s3.intern,s1.sticky FROM ".dba::get('f_threads')." s1, ".dba::get('f_skats')." s2, ".dba::get('f_kats')." s3 WHERE s1.kid = s2.id AND s2.sid = s3.id ORDER BY s1.lp DESC LIMIT 10"); $ftopics = '';
        if(_rows($qryft) >= 1)
        {
            while($getft = _fetch($qryft))
            {
                if(fintern($getft['kid']))
                {
                    $lp = cnt(dba::get('f_posts'), " WHERE sid = '".$getft['id']."'");
                    $qryp = db("SELECT text FROM ".dba::get('f_posts')." WHERE kid = '".$getft['kid']."' AND sid = '".$getft['id']."' ORDER BY date DESC LIMIT 1");
                    if(_rows($qryp)) { $getp = _fetch($qryp); $text = strip_tags(string::decode($getp['text'])); } else $text = strip_tags(string::decode($getft['t_text']));
                    $intern = ($getft['intern'] ? '<span class="fontWichtig">'._internal.':</span> ' : '');
                    $wichtig = ($getft['sticky'] ? '<span class="fontWichtig">'._sticky.':</span> ' : '');
                    $ftopics .= show($dir."/userlobby_forum", array("id" => $getft['id'],
                                                                    "pagenr" => (($pagenr = ceil($lp/settings('m_fposts'))) == 0 ? 1 : $pagenr),
                                                                    "p" => $lp +1,
                                                                    "intern" => $intern,
                                                                    "wichtig" => $wichtig,
                                                                    "lpost" => cut(string::decode($text), 100),
                                                                    "kat" => string::decode($getft['kattopic']),
                                                                    "titel" => string::decode($getft['topic']),
                                                                    "kid" => $getft['kid'])); //Output
                }
            } //while end
        }

        //Side Output
        $index = show($dir."/userlobby", array("erase" => ($can_erase ? _user_new_erase : ''),
                                               "pic" => useravatar(userid()),
                                               "mynick" => autor(),
                                               "myrank" => getrank(userid()),
                                               "myposts" => userstats(userid(), "forumposts"),
                                               "mylogins" => userstats(userid(), "logins"),
                                               "myhits" => userstats(userid(), "hits"),
                                               "mylevel" => getuserlvl(userid()),
                                               "mymsg" => $mymsg,
                                               "kal" => $nextkal,
                                               "art" => $artikel,
                                               "artc" => $artc,
                                               "rankings" => $rankings,
                                               "awards" => $awards,
                                               "ftopics" => $ftopics,
                                               "forum" => $forumposts,
                                               "cwcom" => $cwcom,
                                               "gal" => $gal,
                                               "votes" => $newv,
                                               "cws" => $cws,
                                               "newsc" => $newsc,
                                               "downloadc" => $downloadc,
                                               "gb" => $gb,
                                               "user" => $user,
                                               "mgb" => $membergb,
                                               "news" => $news,
                                               "away_new" => $away_new,
                                               "away_now" => $away_now));
    }
}