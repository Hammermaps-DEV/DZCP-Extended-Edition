<?php
if(IS_DZCP != 'true') exit();
function motm()
{
    global $db, $allowHover;
    if(!$userpics=get_files(basePath.'/inc/images/uploads/userpics/',false,true))
        return '';

    $qry = db("SELECT id FROM ".$db['users']." WHERE level >= 2");
    while($rs = _fetch($qry))
    {
        foreach($userpics AS $userpic)
        {
            $tmpId = convert::ToInt($userpic);
            if($tmpId == $rs['id'])
            {
                $temparr[] = $rs['id'];
                break;
            }
        }
    }

    $arrayID = rand(0, count($temparr) - 1);
    $uid = $temparr[$arrayID]; $member = '';

    $sql = db("SELECT id,status,level FROM ".$db['users']." WHERE id = '".$uid."'");
    if(!_rows($sql) && !empty($temparr))
    {
        $get=_fetch($sql);
        $status = ($get['status'] == 1 || $get['level'] == 1) ? "aktiv" : "inaktiv";
        $info = ($allowHover ? 'onmouseover="DZCP.showInfo(\''.fabo_autor($get['id']).'\', \''._posi.';'._status.';'._age.'\', \''.getrank($get['id']).';'.$status.';'.getAge($get['bday']).'\', \''.hoveruserpic($get['id']).'\')" onmouseout="DZCP.hideInfo()"' : '');
        $member = show("menu/motm", array("uid" => $get['id'], "upic" => userpic($get['id'], 130, 161), "info" => $info));
    }

    return empty($member) ? '' : '<table class="navContent" cellspacing="0">'.$member.'</table>';
}
?>