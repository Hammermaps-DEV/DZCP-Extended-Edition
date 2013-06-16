<?php
/**
 * <DZCP-Extended Edition>
 * @package: DZCP-Extended Edition
 * @author: DZCP Developer Team || Hammermaps.de Developer Team
 * @link: http://www.dzcp.de || http://www.hammermaps.de
 */

#####################
## Admin Menu-File ##
#####################
if(_adminMenu != 'true')
    exit();

$where = $where.': '._clear_head;
if(isset($_GET['do']) && $_GET['do'] == "clear")
{
    if(!isset($_POST['days']) || empty($_POST['days']))
        $show = error(_clear_error_days);
    else
    {
        $time = convert::ToInt(time()-($_POST['days']*24*60*60));

        if(isset($_POST['news']))
        {
            db("DELETE FROM ".dba::get('news')." WHERE datum <= '".$time."'");
            db("DELETE FROM ".dba::get('newscomments')." WHERE datum <= '".$time."'");
        }

        if(isset($_POST['away']))
            db("DELETE FROM ".dba::get('away')." WHERE date <= '".$time."'");

        if(isset($_POST['forum']))
        {
            $qry = db("SELECT id FROM ".dba::get('f_threads')." WHERE t_date <= '".$time."' AND sticky != 1");
            while($get = _fetch($qry))
            {
                db("DELETE FROM ".dba::get('f_threads')." WHERE id = '".$get['id']."'");
                db("DELETE FROM ".dba::get('f_posts')." WHERE sid = '".$get['id']."'");
            }
        }

        $show = info(_clear_deleted, "../admin/");
    }
}
else
    $show = show($dir."/clear", array("value" => _button_value_clear, "c_days" => "", "forum_info" => _clear_forum_info));