<?php
/**
 * <DZCP-Extended Edition>
 * @package: DZCP-Extended Edition
 * @author: DZCP Developer Team || Hammermaps.de Developer Team
 * @link: http://www.dzcp.de || http://www.hammermaps.de
 */

function shout($ajax = 0)
{
    $shoutconfig = settings(array('l_shoutnick','l_shouttext','shout_max_zeichen','m_shout'));
    $qry = db("SELECT * FROM ".dba::get('shout')." ORDER BY id DESC LIMIT ".$shoutconfig['m_shout']."");

    $i = 1; $color = 1; $show = "";
    while ($get = _fetch($qry))
    {
        $class = ($color % 2) ? "navShoutContentFirst" : "navShoutContentSecond"; $color++;
        $delete = (permission("shoutbox") ? '<a href="../shout/?action=admin&amp;do=delete&amp;id='.$get['id'].'" rel="'._confirm_del_shout.'" class="confirm"><img src="../inc/images/delete_small.gif" title="'._button_title_del.'" alt="'._button_title_del.'" /></a>' : '');
        $is_num = preg_match("#\d#", $get['email']);

        if($is_num && !check_email($get['email']))
            $nick = autor($get['email'], "navShout",'','',$shoutconfig['l_shoutnick']);
        else
            $nick = '<a class="navShout" href="mailto:'.eMailAddr($get['email']).'" title="'.$get['nick'].'">'.cut($get['nick'], $shoutconfig['l_shoutnick']).'</a>';

        $show .= show("menu/shout_part", array( "nick" => $nick,
                                                "datum" => date("j.m.Y H:i", $get['datum'])._uhr,
                                                "text" => bbcode::parse_html(wrap(string::decode($get['text']), $shoutconfig['l_shouttext'])),
                                                "class" => $class,
                                                "del" => $delete));
        $i++;
    }

    $sec = ''; $only4reg = ''; $dis = ''; $dis1 = ''; $form = '';
    if(settings('reg_shout') == 1 && checkme() == 'unlogged')
    {
        $dis = ' style="text-align:center" disabled="disabled"';
        $dis1 = ' style="color:#888" disabled="disabled"';
        $only4reg = _shout_must_reg;
    }
    else
    {
        if(checkme() == "unlogged")
        {
            $form = show("menu/shout_form", array("dis" => $dis));
            $sec = show("menu/shout_antispam", array("dis" => $dis));
        }
        else
            $form = autor(userid(), "navShout",'','',$shoutconfig['l_shoutnick']);
    }

    // 0 Zeichen, disable
    if(!$shoutconfig['shout_max_zeichen'])
    {
        $dis = ' style="text-align:center;" disabled="disabled"';
        $dis1 = ' style="color:#888" disabled="disabled"';
    }

    $add = show("menu/shout_add", array("form" => $form,
                                        "dis1" => $dis1,
                                        "dis" => $dis,
                                        "only4reg" => $only4reg,
                                        "security" => $sec,
                                        "zeichen" => $shoutconfig['shout_max_zeichen']));

    $shout = show("menu/shout", array("shout" => $show, "add" => $add));
    return empty($ajax) ? '<table class="navContent" cellspacing="0">'.$shout.'</table>' : $show;
}