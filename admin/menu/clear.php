<?php
#####################
## Admin Menu-File ##
#####################
if(_adminMenu != 'true')
    exit();

$where = $where.': '._clear_head;
if(isset($_GET['do']) && $_GET['do'] == "clear")
{
    if(!isset($_POST['days']) || empty($_POST['days']))
        $show = error(_clear_error_days,1);
    else
    {
        $time = intval(time()-($_POST['days']*24*60*60));

        if(isset($_POST['news']))
        {
            db("DELETE FROM ".$db['news']." WHERE datum <= '".$time."'");
            db("DELETE FROM ".$db['newscomments']." WHERE datum <= '".$time."'");
        }

        if(isset($_POST['away']))
            db("DELETE FROM ".$db['away']." WHERE date <= '".$time."'");

        if(isset($_POST['forum']))
        {
            $qry = db("SELECT id FROM ".$db['f_threads']." WHERE t_date <= '".$time."' AND sticky != 1");
            while($get = _fetch($qry))
            {
                db("DELETE FROM ".$db['f_threads']." WHERE id = '".$get['id']."'");
                db("DELETE FROM ".$db['f_posts']." WHERE sid = '".$get['id']."'");
            }
        }

        $show = info(_clear_deleted, "../admin/");
    }
}
else
    $show = show($dir."/clear", array("value" => _button_value_clear, "c_days" => "", "forum_info" => _clear_forum_info));
?>
