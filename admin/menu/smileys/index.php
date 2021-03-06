<?php
/**
 * <DZCP-Extended Edition>
 * @package: DZCP-Extended Edition
 * @author: DZCP Developer Team || Hammermaps.de Developer Team
 * @link: http://www.dzcp.de || http://www.hammermaps.de
 */

if(_adminMenu != 'true')
    exit();

$where = $where.': '._smileys_head;
switch($do)
{
    case 'add':
        $show = show($dir."/form_smileys", array("what" => _button_value_add, "do" => "addsmiley"));
    break;
    case 'addsmiley':
        if(!isset($_FILES['smiley']) || empty($_FILES['smiley']))
            $show = error(_smileys_error_file);
        else
        {
            $tmpname = $_FILES['smiley']['tmp_name'];
            $name = $_FILES['smiley']['name'];
            $type = $_FILES['smiley']['type'];
            $size = $_FILES['smiley']['size'];
            $bbcode_name = explode('.', $name);
            $bbcode_name = $bbcode_name[0];
            $imageinfo = getimagesize($tmpname);
            $spfad = basePath."/inc/images/smileys/";

            if(!$tmpname || $type != "image/gif" || !$imageinfo[0] || preg_match("#[[:punct:]]|[[:space:]]#",(isset($_POST['bbcode']) && !empty($_POST['bbcode']) ? $_POST['bbcode'] : $bbcode_name)) || file_exists($spfad.(isset($_POST['bbcode']) && !empty($_POST['bbcode']) ? $_POST['bbcode'] : $bbcode_name).".gif"))
            {
                if(!$tmpname)
                    $show = error(_smileys_error_file);
                else if($type != "image/gif")
                    $show = error(_smileys_error_type);
                else if(preg_match("#[[:punct:]]|[[:space:]]#",(isset($_POST['bbcode']) && !empty($_POST['bbcode']) ? $_POST['bbcode'] : $bbcode_name)))
                    $show = error(_smileys_specialchar);
                else if(file_exists($spfad.(isset($_POST['bbcode']) && !empty($_POST['bbcode']) ? $_POST['bbcode'] : $bbcode_name).".gif"))
                    $show = error(_admin_smiley_exists);
            }
            else
            {
                if(move_uploaded_file($tmpname, basePath."/inc/images/smileys/".(isset($_POST['bbcode']) && !empty($_POST['bbcode']) ? $_POST['bbcode'] : $bbcode_name).".gif"))
                {
                    @unlink($_FILES['smiley']['tmp_name']);
                    $show = info(_smileys_added, "?index=admin&amp;admin=smileys");
                }
                else
                    $show = error(_smileys_error_file);
            }
        }
    break;
    case 'delete':
        $show = info(_smileys_delete_error, "?index=admin&amp;admin=smileys");
        if(isset($_GET['id']) && !empty($_GET['id']))
        {
            $name = preg_replace("#.gif#Uis","",$_GET['id']);
            if(file_exists(basePath."/inc/images/smileys/".$name.".gif"))
            {
                @unlink(basePath."/inc/images/smileys/".$name.".gif");
                $show = info(_smileys_deleted, "?index=admin&amp;admin=smileys");
            }
        }
    break;
    case 'edit':
        $show = info(_smileys_edited_not_exist, "?index=admin&amp;admin=smileys");
        if(isset($_GET['id']) && !empty($_GET['id']))
        {
            $name = preg_replace("#.gif#Uis","",$_GET['id']);
            if(file_exists(basePath."/inc/images/smileys/".$name.".gif"))
            {
                $akt = preg_replace("#.gif#Uis","",$_GET['id']);
                $show = show($dir."/form_smileys_edit", array("id" => $_GET['id'], "value" => _button_value_edit, "akt" => $akt));
            }
        }
    break;
    case 'editsmiley':
        if(!isset($_POST['bbcode']) || empty($_POST['bbcode']))
            $show = error(_smileys_error_bbcode);
        else
        {
            $pfad = basePath."/inc/images/smileys/";
            if(!file_exists($pfad.$_POST['bbcode'].".gif"))
            {
                if(file_exists($pfad.$_GET['id']))
                {
                    @rename($pfad.$_GET['id'], $pfad.$_POST['bbcode'].".gif");
                    $show = info(_smileys_edited, "?index=admin&amp;admin=smileys");
                }
                else
                    $show = info(_smileys_edited_not_exist, "?index=admin&amp;admin=smileys");
            }
            else
                $show = error(_admin_smiley_exists);
        }
    break;
    default:
        $files = get_files(basePath.'/inc/images/smileys',false,true,array('gif')); $color = 1; $show_default = '';
        foreach($files as $file)
        {
            if($file != '^^.gif')
            {
                $class = ($color % 2) ? "contentMainSecond" : "contentMainFirst"; $color++;
                $smileys = "inc/images/smileys/".$file;
                $bbc = ":".preg_replace("=.gif=Uis","",$file).":";
                $edit = show("page/button_edit_single", array("id" => $file, "action" => "index=admin&amp;admin=smileys&amp;do=edit", "title" => _button_title_edit));
                $delete = show("page/button_delete_single", array("id" => $file, "action" => "index=admin&amp;admin=smileys&amp;do=delete", "title" => _button_title_del, "del" => _confirm_del_smiley));
                $show_default .= show($dir."/smileys_show", array("bbcode" => $bbc, "smiley" => $smileys, "class" => $class, "del" => $delete, "edit" => $edit, "id" => $file));
            }
        }
    break;
}

if(empty($show))
    $show = show($dir."/smileys", array("show" => $show_default, "add" => _smileys_head_add));