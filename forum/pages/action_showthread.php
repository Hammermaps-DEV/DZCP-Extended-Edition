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
    $checks = db("SELECT s3.name,s3.intern,s2.sid,s1.kid,s2.id FROM ".dba::get('f_kats')." s3, ".dba::get('f_skats')." s2, ".dba::get('f_threads')." s1
     WHERE s1.kid = s2.id AND s2.sid = s3.id AND s1.id = '".convert::ToInt($_GET['id'])."'",false,true);

    $f_check = db("SELECT * FROM ".dba::get('f_threads')." WHERE id = '".convert::ToInt($_GET['id'])."' AND kid = '".$checks['kid']."'");
    if(_rows($f_check))
    {
        if($checks['intern'] == 1 && !permission("intforum") && !fintern($checks['id']))
            $index = error(_error_wrong_permissions);
        else
        {
            db("UPDATE ".dba::get('f_threads')." SET `hits` = hits+1 WHERE id = '".convert::ToInt($_GET['id'])."'");

            $qryp = db("SELECT * FROM ".dba::get('f_posts')."
                  WHERE sid = '".convert::ToInt($_GET['id'])."'
                  ORDER BY id
                  LIMIT ".($page - 1)*settings('m_fposts').",".settings('m_fposts')."");

            $entrys = cnt(dba::get('f_posts'), " WHERE sid = ".convert::ToInt($_GET['id'])); $i = 2;

            if($entrys == 0) $pagenr = "1";
            else $pagenr = ceil($entrys/settings('m_fposts'));
            $hL = !empty($_GET['hl']) ? '&amp;hl='.$_GET['hl'] : '';
            $lpost = show(_forum_lastpost, array("id" => $entrys+1, "tid" => $_GET['id'], "page" => $pagenr.$hL));

            $show = '';
            while($getp = _fetch($qryp))
            {
                if(data($getp['reg'], "signatur")) $sig = _sig.bbcode::parse_html(data($getp['reg'], "signatur"));
                else                               $sig = "";

                if($getp['reg'] != 0) $userposts = show(_forum_user_posts, array("posts" => userstats($getp['reg'], "forumposts")));
                else                  $userposts = "";

                if($getp['reg'] == 0) $onoff = "";
                else                  $onoff = onlinecheck($getp['reg']);

                $zitat = show("page/button_zitat", array("id" => $_GET['id'], "action" => "index=forum&amp;action=post&amp;do=add&amp;kid=".$getp['kid']."&amp;zitat=".$getp['id'], "title" => _button_title_zitat));
                if($getp['reg'] == userid() || permission("forum"))
                {
                    $edit = show("page/button_edit_single", array("id" => $getp['id'], "action" => "index=forum&amp;action=post&amp;do=edit", "title" => _button_title_edit));
                    $delete = show("page/button_delete_single", array("id" => $getp['id'], "action" => "index=forum&amp;action=post&amp;do=delete", "title" => _button_title_del, "del" => _confirm_del_entry));
                }
                else
                {
                    $delete = "";
                    $edit = "";
                }

                $ftxt = hl($getp['text'], $_GET['hl']);
                if($_GET['hl']) $text = bbcode::parse_html($ftxt['text']);
                else $text = bbcode::parse_html($getp['text']);

                $posted_ip = checkme() == 4 ? $getp['ip'] : _logged;
                $titel = show(_eintrag_titel_forum, array("postid" => $i+($page-1)*settings('m_fposts'),
                        "datum" => date("d.m.Y", $getp['date']),
                        "zeit" => date("H:i", $getp['date'])._uhr,
                        "url" => '?index=forum&amp;action=showthread&amp;id='.convert::ToInt($_GET['id']).'&amp;page='.convert::ToInt(empty($_GET['page']) ? 1 : $_GET['page']).'#p'.($i+($page-1)*settings('m_fposts')),
                        "edit" => $edit,
                        "delete" => $delete));

                if($getp['reg'] != 0)
                {
                    $getu = db("SELECT nick,icq,hp,email FROM ".dba::get('users')." WHERE id = '".$getp['reg']."'",false,true);
                    $email = show(_emailicon_forum, array("email" => eMailAddr($getu['email'])));
                    $pn = show(_pn_write_forum, array("id" => $getp['reg'], "nick" => $getu['nick']));

                    if(empty($getu['icq']) || $getu['icq'] == 0)
                        $icq = "";
                    else
                    {
                        $uin = show(_icqstatus_forum, array("uin" => $getu['icq']));
                        $icq = '<a href="http://www.icq.com/whitepages/about_me.php?uin='.$getu['icq'].'" target="_blank">'.$uin.'</a>';
                    }

                    $hp = empty($getu['hp']) ? show(_hpicon_forum, array("hp" => $getu['hp'])) : '';
                }
                else
                {
                    $icq = "";
                    $pn = "";
                    $email = show(_emailicon_forum, array("email" => eMailAddr($getp['email'])));
                    if(empty($getp['hp'])) $hp = "";
                    else $hp = show(_hpicon_forum, array("hp" => $getp['hp']));
                }

                $nick = autor($getp['reg'], '', $getp['nick'], $getp['email']);
                if(!empty($_GET['hl']) && $_SESSION['search_type'] == 'autor')
                {
                    if(preg_match("#".$_GET['hl']."#i",$nick)) $ftxt['class'] = 'class="highlightSearchTarget"';
                }

                $show .= show($dir."/forum_posts_show", array("nick" => $nick,
                        "postnr" => "#".($i+($page-1)*settings('m_fposts')),
                        "p" => ($i+($page-1)*settings('m_fposts')),
                        "text" => $text,
                        "pn" => $pn,
                        "class" => $ftxt['class'],
                        "icq" => $icq,
                        "hp" => $hp,
                        "email" => $email,
                        "status" => getrank($getp['reg']),
                        "avatar" => useravatar($getp['reg']),
                        "ip" => $posted_ip,
                        "edited" => $getp['edited'],
                        "posts" => $userposts,
                        "titel" => $titel,
                        "signatur" => $sig,
                        "zitat" => $zitat,
                        "onoff" => $onoff,
                        "top" => _topicon,
                        "lp" => cnt(dba::get('f_posts'), " WHERE sid = '".convert::ToInt($_GET['id'])."'")+1));
                $i++;
            }

            $get = db("SELECT * FROM ".dba::get('f_threads')." WHERE id = '".convert::ToInt($_GET['id'])."'",false,true);
            $getw = db("SELECT s1.kid,s1.topic,s2.kattopic,s2.sid
                  FROM ".dba::get('f_threads')." AS s1
                  LEFT JOIN ".dba::get('f_skats')." AS s2
                  ON s1.kid = s2.id
                  WHERE s1.id = '".convert::ToInt($_GET['id'])."'",false,true);

            $kat = db("SELECT name FROM ".dba::get('f_kats')." WHERE id = '".$getw['sid']."'",false,true);
            $wheres = show(_forum_post_where, array("wherepost" => string::decode($getw['topic']),
                    "wherekat" => string::decode($getw['kattopic']),
                    "mainkat" => string::decode($kat['name']),
                    "tid" => $_GET['id'],
                    "kid" => $getw['kid']));
            if($get['t_reg'] == "0")
            {
                $userposts = "";
                $onoff = "";
            }
            else
            {
                $onoff = onlinecheck($get['t_reg']);
                $userposts = show(_forum_user_posts, array("posts" => userstats($get['t_reg'], "forumposts")));
            }

            $zitat = show("page/button_zitat", array("id" => $_GET['id'], "action" => "index=forum&amp;action=post&amp;do=add&amp;kid=".$getw['kid']."&amp;zitatt=".$get['id'], "title" => _button_title_zitat));
            $add = $get['closed'] ? show("page/button_closed") : show(_forum_addpost, array("id" => $_GET['id'], "kid" => $getw['kid']));
            $nav = nav($entrys,settings('m_fposts'),"?index=forum&amp;action=showthread&amp;id=".$_GET['id'].$hL);

            if(data($get['t_reg'], "signatur")) $sig = _sig.bbcode::parse_html(data($get['t_reg'], "signatur"));
            else $sig = "";

            if($get['t_reg'] == userid() || permission("forum"))
                $editt = show("page/button_edit_single", array("id" => $get['id'], "action" => "index=forum&amp;action=thread&amp;do=edit", "title" => _button_title_edit));

            if(permission("forum"))
            {
                $sticky = $get['sticky'] ? "checked=\"checked\"" : "";
                $global = $get['global'] ? "checked=\"checked\"" : "";

                if($get['closed'] == "1")
                {
                    $closed = "checked=\"checked\"";
                    $opened = "";
                }
                else
                {
                    $opened = "checked=\"checked\"";
                    $closed = "";
                }

                $qryok = db("SELECT * FROM ".dba::get('f_kats')." ORDER BY kid"); $move = '';
                while($getok = _fetch($qryok))
                {
                    $qryo = db("SELECT * FROM ".dba::get('f_skats')." WHERE sid = '".$getok['id']."' ORDER BY kattopic"); $skat = "";
                    while($geto = _fetch($qryo))
                    {
                        $skat .= show(_forum_select_field_skat, array("value" => $geto['id'], "what" => string::decode($geto['kattopic'])));
                    }

                    $move .= show(_forum_select_field_kat, array("value" => "lazy", "what" => string::decode($getok['name']), "skat" => $skat));
                }

                $admin = show($dir."/admin", array("admin" => _admin,
                        "id" => $get['id'],
                        "open" => _forum_admin_open,
                        "close" => _forum_admin_close,
                        "asticky" => _forum_admin_addsticky,
                        "delete" => _forum_admin_delete,
                        "moveto" => _forum_admin_moveto,
                        "aglobal" => _forum_admin_global,
                        "move" => $move,
                        "closed" => $closed,
                        "opened" => $opened,
                        "global" => $global,
                        "sticky" => $sticky));
            }

            $ftxt = hl($get['t_text'], isset($_GET['hl']) ? $_GET['hl'] : '');
            $text = isset($_GET['hl']) ? bbcode::parse_html($ftxt['text']) : bbcode::parse_html($get['t_text']);
            $posted_ip = checkme() == "4" ? $get['ip'] : _logged;
            $titel = show(_eintrag_titel_forum, array("postid" => "1",
                    "datum" => date("d.m.Y", $get['t_date']),
                    "zeit" => date("H:i", $get['t_date'])._uhr,
                    "url" => '?index=forum&amp;action=showthread&amp;id='.convert::ToInt($_GET['id']).'&amp;page=1#p1',
                    "edit" => $editt,
                    "delete" => ""));


            if($get['t_reg'] != 0)
            {
                $getu = db("SELECT nick,icq,hp,email FROM ".dba::get('users')." WHERE id = '".$get['t_reg']."'",false,true);
                $email = show(_emailicon_forum, array("email" => eMailAddr($getu['email'])));
                $pn = show(_pn_write_forum, array("id" => $get['t_reg'], "nick" => $getu['nick']));
                if(empty($getu['icq']) || $getu['icq'] == 0)
                    $icq = "";
                else
                {
                    $uin = show(_icqstatus_forum, array("uin" => $getu['icq']));
                    $icq = '<a href="http://www.icq.com/whitepages/about_me.php?uin='.$getu['icq'].'" target="_blank">'.$uin.'</a>';
                }

                $hp = !empty($getu['hp']) ? show(_hpicon_forum, array("hp" => $getu['hp'])) : '';
            }
            else
            {
                $pn = "";
                $icq = "";
                $email = show(_emailicon_forum, array("email" => eMailAddr($get['t_email'])));
                if(empty($get['t_hp'])) $hp = "";
                else $hp = show(_hpicon_forum, array("hp" => $get['t_hp']));
            }

            $nick = autor($get['t_reg'], '', $get['t_nick'], $get['t_email']);
            if(!empty($_GET['hl']) && $_SESSION['search_type'] == 'autor')
            {
                if(preg_match("#".$_GET['hl']."#i",$nick))
                    $ftxt['class'] = 'class="highlightSearchTarget"';
            }

            $abo = db("SELECT user,fid FROM ".dba::get('f_abo')." WHERE user = '".userid()."' AND fid = '".convert::ToInt($_GET['id'])."'",true) ? 'checked="checked"' : '';
            if(checkme() == "unlogged")
                $f_abo = '';
            else
            {
                $f_abo = show($dir."/forum_abo", array("id" => convert::ToInt($_GET['id']),
                        "abo" => $abo,
                        "abo_info" => _foum_fabo_checkbox,
                        "abo_title" => _forum_abo_title,
                        "submit" => _button_value_save));
            }

            if(empty($get['vote']))
                $vote = "";
            else
            {
                include_once(basePath.'/inc/menu-functions/fvote.php');
                $vote = '<tr><td>'.fvote($get['vote']).'</td></tr>';
            }

            $title = string::decode($getw['topic']).' - '.$title;
            $index = show($dir."/forum_posts", array("head" => _forum_head,
                    "where" => $wheres,
                    "admin" => $admin,
                    "nick" => $nick,
                    "threadhead" => string::decode($getw['topic']),
                    "titel" => $titel,
                    "postnr" => "1",
                    "class" => $ftxt['class'],
                    "pn" => $pn,
                    "icq" => $icq,
                    "hp" => $hp,
                    "email" => $email,
                    "posts" => $userposts,
                    "text" => $text,
                    "status" => getrank($get['t_reg']),
                    "avatar" => useravatar($get['t_reg']),
                    "edited" => $get['edited'],
                    "signatur" => $sig,
                    "date" => _posted_by.date("d.m.y H:i", $get['t_date'])._uhr,
                    "zitat" => $zitat,
                    "onoff" => $onoff,
                    "ip" => $posted_ip,
                    "top" => _topicon,
                    "lpost" => $lpost,
                    "lp" => cnt(dba::get('f_posts'), " WHERE sid = '".convert::ToInt($_GET['id'])."'")+1,
                    "add" => $add,
                    "nav" => $nav,
                    "vote" => $vote,
                    "f_abo" => $f_abo,
                    "show" => $show));
        }
    }
    else
        $index = error(_error_wrong_permissions);
}