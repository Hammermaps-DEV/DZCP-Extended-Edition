<?php
//User of the Moment
if(IS_DZCP != 'true') exit();
function uotm()
{
    global $db, $allowHover;
    $imgFiles = array(); $uotm = '';
    
    if(!$folder=get_files('../inc/images/uploads/userpics',false,true))
        return '';
    
    //Mischen
    foreach($folder AS $file) 
        array_push($imgFiles, $file);

    $userid = intval($imgFiles[rand(0, count($imgFiles) - 1)]);
    $sql = db("SELECT id,nick,country,bday FROM ".$db['users']." WHERE id = '".$userid."'");
    if(_rows($sql))
    {
        $get=_fetch($sql);
        $info = ($allowHover ? 'onmouseover="DZCP.showInfo(\''.fabo_autor($get['id']).'\', \''._age.'\', \''.getAge($get['bday']).'\', \''.hoveruserpic($get['id']).'\')" onmouseout="DZCP.hideInfo()"' : '');
        $uotm = show("menu/uotm", array("uid" => $userid, "upic" => userpic($get['id'], 130, 161), "info" => $info));
    }

    return empty($uotm) ? '' : '<table class="navContent" cellspacing="0">'.$uotm.'</table>';
}
?>