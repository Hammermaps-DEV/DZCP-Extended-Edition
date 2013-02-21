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

            db("INSERT INTO ".$db['gallery']." SET `kat` = '".up($_POST['gallery'])."', `beschreibung` = '".up($_POST['beschreibung'], 1)."', `datum` = '".time()."'");
            $show = show($dir."/form_gallery_step2", array("what" => re($_POST['gallery']), "addfile" => $addfile, "id" => mysql_insert_id(), "do" => "add", "anzahl" => $_POST['anzahl']));
        }
    break;
    case 'add':
        $galid = $_GET['id'];
        $anzahl = $_POST['anzahl'];

        for($i=1;$i<=$anzahl;$i++)
        {
            if(isset($_FILES['file'.$i]))
            {
                $tmp = $_FILES['file'.$i]['tmp_name'];
                $type = $_FILES['file'.$i]['type'];
                $end = explode(".", $_FILES['file'.$i]['name']);
                $end = $end[count($end)-1];
                $imginfo = getimagesize($tmp);

                if($_FILES['file'.$i])
                {
                    if(($type == "image/gif" || $type == "image/pjpeg" || $type == "image/jpeg" || $type == "image/png") && $imginfo[0])
                    {
                        @copy($tmp, basePath."/inc/images/uploads/gallery/".$galid."_".str_pad($i, 3, '0', STR_PAD_LEFT).".".strtolower($end));
                        @unlink($_FILES['file'.$i]['tmp_name']);
                    }
                }
            }
        }

        $show = info(_gallery_added, "?admin=gallery");
    break;
    case 'delgal':
        db("DELETE FROM ".$db['gallery']." WHERE id = '".convert::ToInt($_GET['id'])."'");

        $files = get_files(basePath."/inc/images/uploads/gallery/",false,true);
        foreach($files as $file)
        {
            if(preg_match("#".$_GET['id']."_(.*?).(gif|jpg|jpeg|png)#",strtolower($file))!= FALSE)
            {
                preg_match("#".$_GET['id']."_(.*)#",$file,$match);
                thumbgen_delete("gallery/".$_GET['id']."_".$match[1],160); //Gallery
                thumbgen_delete("gallery/".$_GET['id']."_".$match[1],150); //Menu: random_gallery
                @unlink(basePath."/inc/images/uploads/gallery/".$_GET['id']."_".$match[1]);
            }
        }

        $show = info(_gallery_deleted, "?admin=gallery");
    break;
    case 'delete':
        $pic = $_GET['pic'];
        if(!empty($pic))
        {
            thumbgen_delete("gallery/".$pic,160); //Gallery
            thumbgen_delete("gallery/".$pic,150); //Menu: random_gallery
            @unlink(basePath."/inc/images/uploads/gallery/".$pic);

            preg_match("#(.*)_(.*?).(gif|GIF|JPG|jpg|JPEG|jpeg|png)#",$pic,$pid);
            $show = info(_gallery_pic_deleted, "../gallery/?action=show&amp;id=".$pid[1]."");
        }

        $show ='';
    break;
    case 'edit':
        $get = db("SELECT * FROM ".$db['gallery']." WHERE id = '".convert::ToInt($_GET['id'])."'",false,true);;
        $show = show($dir."/form_gallery_edit", array("id" => $get['id'], "e_gal" => re($get['kat']), "e_beschr" => re($get['beschreibung'])));
    break;
    case 'editgallery':
        db("UPDATE ".$db['gallery']." SET `kat` = '".up($_POST['gallery'])."', `beschreibung` = '".up($_POST['beschreibung'], 1)."' WHERE id = '".convert::ToInt($_GET['id'])."'");
        $show = info(_gallery_edited, "?admin=gallery");
    break;
    case 'new':
        $get = db("SELECT * FROM ".$db['gallery']." WHERE id = '".convert::ToInt($_GET['id'])."'",false,true); $option = '';
        for($i=1;$i<=100;$i++)
        { $option .= "<option value=\"".$i."\">".$i."</option>"; }

        $show = show($dir."/form_gallery_new", array("gal" => re($get['kat']), "id" => $get['id'], "option" => $option));
    break;
    case 'editstep2':
        $get = db("SELECT * FROM ".$db['gallery']." WHERE id = '".convert::ToInt($_GET['id'])."'",false,true); $addfile = '';
        for($i=1;$i<=$_POST['anzahl'];$i++)
        { $addfile .= show($dir."/form_gallery_addfile", array("file" => _gallery_image, "i" => $i)); }

        $show = show($dir."/form_gallery_step2", array("what" => re($get['kat']),
                                                       "do" => "editpics",
                                                       "addfile" => $addfile,
                                                       "id" => $get['id'],
                                                       "anzahl" => $_POST['anzahl']));
    break;
    case 'editpics':
        $galid = $_GET['id'];
        $anzahl = $_POST['anzahl'];
        $files = get_files(basePath."/inc/images/uploads/gallery/",false,true);

        $cnt = 0;
        for($c=0; $c<count($files); $c++)
        {
            if(preg_match("#".$galid."_(.*?).(gif|GIF|JPG|jpg|JPEG|jpeg|png)#",$files[$c])!==FALSE)
            { $cnt++; }
        }

        for($i=1;$i<=$anzahl;$i++)
        {
            $tmp = $_FILES['file'.$i]['tmp_name'];
            $type = $_FILES['file'.$i]['type'];
            $end = explode(".", $_FILES['file'.$i]['name']);
            $end = $end[count($end)-1];
            $imginfo = getimagesize($tmp);

            if($_FILES['file'.$i])
            {
                if(($type == "image/gif" || $type == "image/pjpeg" || $type == "image/jpeg") && $imginfo[0])
                {
                    @copy($tmp, basePath."/inc/images/uploads/gallery/".$galid."_".str_pad($i+$cnt, 3, '0', STR_PAD_LEFT).".".strtolower($end));
                    @unlink($_FILES['file'.$i]['tmp_name']);
                }
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
        $qry = db("SELECT * FROM ".$db['gallery']." ORDER BY id DESC"); $color = 1; $show = '';
        while($get = _fetch($qry))
        {
            $files = get_files(basePath."/inc/images/uploads/gallery/",false,true); $cnt = 0;
            foreach($files as $file)
            {
                if(preg_match("#^".$get['id']."_(.*?).(gif|jpg|jpeg|png)#",strtolower($file))!==FALSE)
                { $cnt++; }
            }

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