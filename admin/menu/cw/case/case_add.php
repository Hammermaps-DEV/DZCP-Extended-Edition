<?php
/**
 * <DZCP-Extended Edition>
 * @package: DZCP-Extended Edition
 * @author: DZCP Developer Team || Hammermaps.de Developer Team
 * @link: http://www.dzcp.de || http://www.hammermaps.de
 */

if(_adminMenu != 'true') exit();

        if(empty($_POST['gegner']) || empty($_POST['clantag']) || empty($_POST['t']))
        {
          if(empty($_POST['gegner'])) $show = error(_cw_admin_empty_gegner);
          elseif(empty($_POST['clantag'])) $show = error(_cw_admin_empty_clantag);
          elseif(empty($_POST['t'])) $show = error(_empty_datum);
        } else {
          if(empty($_POST['xonx1']) && empty($_POST['xonx2'])) $xonx = "";
          else $xonx = "`xonx` = '".$_POST['xonx1']."on".$_POST['xonx2']."',";

          $datum = mktime($_POST['h'],$_POST['min'],0,$_POST['m'],$_POST['t'],$_POST['j']);

          if($_POST['land'] == "lazy") $kid = "";
   else $kid = "`gcountry` = '".$_POST['land']."',";

          $qry = db("INSERT INTO ".dba::get('cw')."
SET ".$kid."
".$xonx."
`datum` = '".convert::ToInt($datum)."',
`squad_id` = '".convert::ToInt($_POST['squad'])."',
`clantag` = '".string::encode($_POST['clantag'])."',
`gegner` = '".string::encode($_POST['gegner'])."',
`url` = '".links($_POST['url'])."',
`liga` = '".string::encode($_POST['liga'])."',
`gametype` = '".string::encode($_POST['gametype'])."',
`punkte` = '".convert::ToInt($_POST['punkte'])."',
`gpunkte` = '".convert::ToInt($_POST['gpunkte'])."',
`maps` = '".string::encode($_POST['maps'])."',
`serverip` = '".string::encode($_POST['serverip'])."',
`servername` = '".string::encode($_POST['servername'])."',
`serverpwd` = '".string::encode($_POST['serverpwd'])."',
`lineup` = '".string::encode($_POST['lineup'])."',
`glineup` = '".string::encode($_POST['glineup'])."',
`matchadmins` = '".string::encode($_POST['match_admins'])."',
`bericht` = '".string::encode($_POST['bericht'])."'");

          $cwid = database::get_insert_id();

          $tmp = $_FILES['logo']['tmp_name'];
          $type = $_FILES['logo']['type'];
          $end = explode(".", $_FILES['logo']['name']);
          $end = strtolower($end[count($end)-1]);

          if(!empty($tmp))
          {
            $img = @getimagesize($tmp);
if($img1[0])
            {
              @copy($tmp, basePath."/inc/images/uploads/clanwars/".database::get_insert_id()."_logo.".strtolower($end));
              @unlink($tmp);
            }
          }

          $tmp1 = $_FILES['screen1']['tmp_name'];
          $type1 = $_FILES['screen1']['type'];
          $end1 = explode(".", $_FILES['screen1']['name']);
          $end1 = strtolower($end1[count($end1)-1]);

          if(!empty($tmp1))
          {
            $img1 = @getimagesize($tmp1);
if($img1[0])
            {
              @copy($tmp1, basePath."/inc/images/uploads/clanwars/".database::get_insert_id()."_1.".strtolower($end1));
              @unlink($tmp1);
            }
          }

          $tmp2 = $_FILES['screen2']['tmp_name'];
          $type2 = $_FILES['screen2']['type'];
          $end2 = explode(".", $_FILES['screen2']['name']);
          $end2 = strtolower($end2[count($end2)-1]);

          if(!empty($tmp2))
          {
            $img2 = @getimagesize($tmp2);
if($img2[0])
            {
              @copy($tmp2, basePath."/inc/images/uploads/clanwars/".database::get_insert_id()."_2.".strtolower($end2));
              @unlink($tmp2);
            }
          }

          $tmp3 = $_FILES['screen3']['tmp_name'];
          $type3 = $_FILES['screen3']['type'];
          $end3 = explode(".", $_FILES['screen3']['name']);
          $end3 = strtolower($end3[count($end3)-1]);

          if(!empty($tmp3))
          {
            $img3 = @getimagesize($tmp3);
if($img3[0])
            {
              @copy($tmp3, basePath."/inc/images/uploads/clanwars/".database::get_insert_id()."_3.".strtolower($end3));
              @unlink($tmp3);
            }
          }

          $tmp4 = $_FILES['screen4']['tmp_name'];
          $type4 = $_FILES['screen4']['type'];
          $end4 = explode(".", $_FILES['screen4']['name']);
          $end4 = strtolower($end4[count($end4)-1]);

          if(!empty($tmp4))
          {
            $img4 = @getimagesize($tmp4);
if($img4[0])
            {
              @copy($tmp4, basePath."/inc/images/uploads/clanwars/".database::get_insert_id()."_4.".strtolower($end4));
              @unlink($tmp4);
            }
          }

          $show = info(_cw_admin_added, "?admin=cw");
        }