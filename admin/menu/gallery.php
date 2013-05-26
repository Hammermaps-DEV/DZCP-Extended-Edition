<?php
#####################
## Admin Menu-File ##
#####################
if(_adminMenu != 'true')
    exit();

switch (isset($_GET['do']) ? $_GET['do'] : 'default')
{
    case 'step2':
        if(empty($_POST['gallery']))
            $show = error(_error_gallery,1);
        else
        {
            $addfile = '';
            for($i=1;$i<=$_POST['anzahl'];$i++)
            { $addfile .= show($dir."/form_gallery_addfile", array("file" => _gallery_image, "i" => $i)); }

            db("INSERT INTO ".dba::get('gallery')." SET `kat` = '".up($_POST['gallery'])."', `beschreibung` = '".up($_POST['beschreibung'], 1)."', `datum` = '".time()."'");
            $show = show($dir."/form_gallery_step2", array("what" => re($_POST['gallery']), "addfile" => $addfile, "id" => database::get_insert_id(), "do" => "add"));
        }
    break;
    case 'add':
        $galid = convert::ToInt($_GET['id']); $cnt = 1;
        foreach($_FILES as $file)
        {
            if(file_exists($file['tmp_name']))
            {
                $end = explode(".", $file['name']);
                $imginfo = getimagesize($file['tmp_name']);
                if(($imginfo['mime'] == "image/gif" || $imginfo['mime'] == "image/pjpeg" || $imginfo['mime'] == "image/jpeg") && $imginfo[0] > 1 && $imginfo[1] > 1 && in_array(strtolower($end[1]), $picformat))
                { move_uploaded_file($file['tmp_name'],basePath."/inc/images/uploads/gallery/".$galid."_".str_pad($cnt, 3, '0', STR_PAD_LEFT).".".strtolower($end[1])); $cnt++; }
            }
        }

        $show = info(_gallery_added, "?admin=gallery");
    break;
    case 'delgal':
        $files = get_files(basePath."/inc/images/uploads/gallery/",false,true);
        foreach($files as $file)
        {
            if(preg_match("#".$_GET['id']."_(.*?).(gif|jpg|jpeg|png)#",strtolower($file)))
            {
                preg_match("#".$_GET['id']."_(.*)#",$file,$match);
                thumbgen_delete("gallery/".$_GET['id']."_".$match[1],160); //Gallery
                thumbgen_delete("gallery/".$_GET['id']."_".$match[1],150); //Menu: random_gallery

                if(file_exists(basePath."/inc/images/uploads/gallery/".$_GET['id']."_".$match[1]))
                    unlink(basePath."/inc/images/uploads/gallery/".$_GET['id']."_".$match[1]);
            }
        }

        db("DELETE FROM ".dba::get('gallery')." WHERE id = '".convert::ToInt($_GET['id'])."'");
        $show = info(_gallery_deleted, "?admin=gallery");
    break;
    case 'delete':
        $pic = $_GET['pic'];
        if(!empty($pic))
        {
            thumbgen_delete("gallery/".$pic,160); //Gallery
            thumbgen_delete("gallery/".$pic,150); //Menu: random_gallery

            if(file_exists(basePath."/inc/images/uploads/gallery/".$pic))
                unlink(basePath."/inc/images/uploads/gallery/".$pic);

            preg_match("#(.*)_(.*?).(gif|GIF|JPG|jpg|JPEG|jpeg|png)#",$pic,$pid);
            $show = info(_gallery_pic_deleted, "../gallery/?action=show&amp;id=".$pid[1]."", 2);
        }
    break;
    case 'edit':
        $get = db("SELECT * FROM ".dba::get('gallery')." WHERE id = '".convert::ToInt($_GET['id'])."'",false,true);;
        $show = show($dir."/form_gallery_edit", array("id" => $get['id'], "e_gal" => re($get['kat']), "e_beschr" => re($get['beschreibung'])));
    break;
    case 'editgallery':
        db("UPDATE ".dba::get('gallery')." SET `kat` = '".up($_POST['gallery'])."', `beschreibung` = '".up($_POST['beschreibung'], 1)."' WHERE id = '".convert::ToInt($_GET['id'])."'");
        $show = info(_gallery_edited, "?admin=gallery");
    break;
    case 'new':
        $get = db("SELECT * FROM ".dba::get('gallery')." WHERE id = '".convert::ToInt($_GET['id'])."'",false,true); $option = '';
        for($i=1;$i<=100;$i++)
        { $option .= "<option value=\"".$i."\">".$i."</option>"; }

        $show = show($dir."/form_gallery_new", array("gal" => re($get['kat']), "id" => $get['id'], "option" => $option));
    break;
    case 'editstep2':
        $get = db("SELECT * FROM ".dba::get('gallery')." WHERE id = '".convert::ToInt($_GET['id'])."'",false,true); $addfile = '';
        for($i=1;$i<=$_POST['anzahl'];$i++)
        { $addfile .= show($dir."/form_gallery_addfile", array("file" => _gallery_image, "i" => $i)); }

        $show = show($dir."/form_gallery_step2", array("what" => re($get['kat']),
                                                       "do" => "editpics",
                                                       "addfile" => $addfile,
                                                       "id" => $get['id'],
                                                       "anzahl" => $_POST['anzahl']));
    break;
    case 'editpics':
        $galid = convert::ToInt($_GET['id']);
        $files = get_files(basePath."/inc/images/uploads/gallery/",false,true,$picformat,"#".$galid."_(.*?).(gif|GIF|JPG|jpg|JPEG|jpeg|png)#");
        $cnt = convert::ToString($files ? count($files) : 0); unset($files); $cnt++;

        foreach ($_FILES as $file)
        {
            if(file_exists($file['tmp_name']))
            {
                $end = explode(".", $file['name']);
                $imginfo = getimagesize($file['tmp_name']);
                if(($imginfo['mime'] == "image/gif" || $imginfo['mime'] == "image/pjpeg" || $imginfo['mime'] == "image/jpeg") && $imginfo[0] > 1 && $imginfo[1] > 1 && in_array(strtolower($end[1]), $picformat))
                { move_uploaded_file($file['tmp_name'],basePath."/inc/images/uploads/gallery/".$galid."_".str_pad($cnt, 3, '0', STR_PAD_LEFT).".".strtolower($end[1])); $cnt++; }
            }
        }

        $show = info(_gallery_new, "?admin=gallery");
    break;
    case 'addnew':
        $option = '';
        for($i=1;$i<=100;$i++)
        { $option .= "<option value=\"".$i."\">".$i."</option>"; }
        $show = show($dir."/form_gallery", array("option" => $option));
    break;
    default:
        $qry = db("SELECT * FROM ".dba::get('gallery')." ORDER BY id DESC"); $color = 1; $show = '';
        while($get = _fetch($qry))
        {
            $files = get_files(basePath."/inc/images/uploads/gallery/",false,true,$picformat,"#".$get['id']."_(.*?).(gif|GIF|JPG|jpg|JPEG|jpeg|png)#");
            $cnt = convert::ToString($files ? count($files) : 0); unset($files);

            $edit = show("page/button_edit_single", array("id" => $get['id'], "action" => "admin=gallery&amp;do=edit", "title" => _button_title_edit));
            $del = show("page/button_delete_single", array("id" => $get['id'], "action" => "admin=gallery&amp;do=delgal", "title" => _button_title_del, "del" => convSpace(_confirm_del_gallery)));
            $new = show(_gal_newicon, array("id" => $get['id'], "titel" => _button_value_newgal));
            $cntpics = ($cnt == 1 ? _gallery_image : _gallery_images);
            $class = ($color % 2) ? "contentMainSecond" : "contentMainFirst"; $color++;
            $show .= show($dir."/gallery_show", array("link" => re($get['kat']),
                                                      "class" => $class,
                                                      "del" => $del,
                                                      "edit" => $edit,
                                                      "new" => $new,
                                                      "images" => $cntpics,
                                                      "id" => $get['id'],
                                                      "beschreibung" => bbcode($get['beschreibung']),
                                                      "cnt" => $cnt));
        }

        $show = show($dir."/gallery",array("show" => $show));
    break;
}
?>