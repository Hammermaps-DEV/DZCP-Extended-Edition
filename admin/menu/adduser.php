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

$where = $where.': '._config_useradd_head;

## DO ##
$error = "";
if(isset($_GET['do']))
{
    ## Get POST ##
    $username = $_POST['user'];
    $nickname = $_POST['nick'];
    $email = $_POST['email'];
    $rlname = $_POST['rlname'];
    $sex = $_POST['sex'];
    $city = $_POST['city'];
    $land = $_POST['land'];
    $level = $_POST['level'];

    if(empty($username))
        $error = _empty_user;
    else if(empty($nickname))
        $error = _empty_nick;
    else if(empty($email))
        $error = _empty_email;
    else if(!check_email($email))
        $error = _error_invalid_email;
    else if(db("SELECT id FROM ".$db['users']." WHERE user = '".$username."'",true))
        $error = _error_user_exists;
    else if(db("SELECT id FROM ".$db['users']." WHERE nick = '".$nickname."'",true))
        $error = _error_nick_exists;
    else if(db("SELECT id FROM ".$db['users']." WHERE email = '".$email."'",true))
        $error = _error_email_exists;
    else
    {
        if(empty($_POST['pwd']))
            $mkpwd = pass_hash(mkpwd(),settings('default_pwd_encoder'));
        else
            $mkpwd = pass_hash($_POST['pwd'],settings('default_pwd_encoder'));

        if($_POST['t'] && $_POST['m'] && $_POST['j'])
            $bday = cal($_POST['t']).".".cal($_POST['m']).".".$_POST['j'];

        db("INSERT INTO ".$db['users']." SET
                `user`     = '".convert::ToString($username)."',
                `nick`     = '".convert::ToString($nickname)."',
                `email`    = '".convert::ToString($email)."',
                `pwd`      = '".convert::ToString($mkpwd)."',
                `rlname`   = '".(up($rlname))."',
                `sex`      = '".convert::ToInt($sex)."',
                `bday`     = '".convert::ToString($bday)."',
                `gmaps_koord`  = '".up($_POST['gmaps_koord'])."',
                `city`     = '".up($city)."',
                `country`  = '".convert::ToString($land)."',
                `regdatum` = '".time()."',
                `level`    = '".convert::ToInt($level)."',
                `time`     = '".time()."',
                `status`   = '1'");

        $insert_id = mysql_insert_id();
        wire_ipcheck("createuser(".$_SESSION['id']."_".$insert_id.")");

        ## Permissions ##
        $p = "";
        if(!empty($_POST['perm']))
        {
            foreach($_POST['perm'] AS $v => $k)
                $p .= "`".substr($v, 2)."` = '".((int)$k)."',";

            if(!empty($p))
                $p = ', '.substr($p, 0, strlen($p) - 1);

            db("INSERT INTO ".$db['permissions']." SET `user` = '".((int)$insert_id)."'".$p);
        }

        ## Internal boardpermissions ##
        if(!empty($_POST['board']))
        {
            foreach($_POST['board'] AS $v)
            {
                db("INSERT INTO ".$db['f_access']." SET `user` = '".((int)$insert_id)."', `forum` = '".$v."'");
            }
        }

        ## Squads ##
        $sq = db("SELECT * FROM ".$db['squads']."");
        while($getsq = _fetch($sq))
        {
            if(isset($_POST['squad'.$getsq['id']]))
                db("INSERT INTO ".$db['squaduser']." SET `user`  = '".((int)$insert_id)."', `squad` = '".((int)$_POST['squad'.$getsq['id']])."'");

            if(isset($_POST['squad'.$getsq['id']]))
                db("INSERT INTO ".$db['userpos']." SET `user` = '".((int)$insert_id)."', `posi` = '".((int)$_POST['sqpos'.$getsq['id']])."', `squad` = '".((int)$getsq['id'])."'");
        }

        ## UserPic ##
        if(isset($_FILES['file']))
        {
            $tmpname = $_FILES['file']['tmp_name'];
            $name = $_FILES['file']['name'];
            $endung = explode(".", $name);
            $endung = strtolower($endung[count($endung)-1]);

            if($tmpname)
            {
                $imageinfo = getimagesize($tmpname);
                foreach($picformat as $tmpendung)
                {
                       if(file_exists(basePath."/inc/images/uploads/userpics/".$insert_id.".".$tmpendung))
                           @unlink(basePath."/inc/images/uploads/userpics/".$insert_id.".".$tmpendung);
                }

                move_uploaded_file($tmpname, basePath."/inc/images/uploads/userpics/".$insert_id.".".strtolower($endung));
            }
        }

        ## User Stats ##
        db("INSERT INTO ".$db['userstats']." SET `user` = '".((int)$insert_id)."', `lastvisit`	= '".time()."'");

        ## E-Mail senden ##
        $message = show(settings('eml_reg'), array("user" => up($username), "pwd" => $mkpwd));
        $subject = settings('eml_reg_subj');
        sendMail($email,$subject,$message);

        $show = info(_uderadd_info, "../admin/");
    }
}

if(empty($show))
{
    ## Show ##
    $dropdown_age = show(_dropdown_date, array("day" => dropdown("day",(isset($_POST['t']) ? $_POST['t'] : null),1), "month" => dropdown("month",(isset($_POST['m']) ? $_POST['m'] : null),1), "year" => dropdown("year",(isset($_POST['j']) ? $_POST['j'] : null),1)));

    $qrysq = db("SELECT id,name FROM ".$db['squads']." ORDER BY pos");  $esquads = '';
    while($getsq = _fetch($qrysq))
    {
        $qrypos = db("SELECT id,position FROM ".$db['pos']." ORDER BY pid"); $posi = '';
        while($getpos = _fetch($qrypos))
        {
            if(db("SELECT * FROM ".$db['userpos']." WHERE posi = '".((int)$getpos['id'])."' AND squad = '".((int)$getsq['id'])."' AND user = '".(isset($_GET['edit']) ? ((int)$_GET['edit']) : '')."'",true))
                   $sel = "selected=\"selected\"";
               else
                   $sel = "";

               $posi .= show(_select_field_posis, array("value" => ((int)$getpos['id']), "sel" => $sel, "what" => re($getpos['position'])));
        }

        $esquads .= show(_checkfield_squads, array("id" => $getsq['id'], "check" => '', "eposi" => $posi, "squad" => re($getsq['name'])));
    }

    ## Sex ##
    if(isset($sex))
    {
        if($sex == 0)
            $sel_sex = show(_pedit_sex_ka,array());
        else if ($sex == 1)
            $sel_sex = show(_pedit_male,array());
        else
            $sel_sex = show(_pedit_female,array());
    }
    else
        $sel_sex = show(_pedit_sex_ka,array());

    $show = show($dir."/register", array("registerhead" => _useradd_head,
                                         "wname" => (isset($username) ? $username : ""),
                                         "wnick" => (isset($nickname) ? $nickname : ""),
                                         "wemail" => (isset($email) ? $email : ""),
                                         "wrlname" => (isset($rlname) ? $rlname : ""),
                                         "wcity" => (isset($city) ? $city : ""),
                                         "wpwd" => (isset($_POST['pwd']) ? $_POST['pwd'] : ""),
                                         "esquad" => $esquads,
                                         "dropdown_age" => $dropdown_age,
                                         "sex" => $sel_sex,
                                         "getpermissions" => getPermissions(),
                                         "getboardpermissions" => getBoardPermissions(),
                                         "country" => show_countrys(),
                                         "error" => (!empty($error) ? show("errors/errortable", array("error" => $error)) : ""),
                                         "value" => _button_value_reg));
}
?>