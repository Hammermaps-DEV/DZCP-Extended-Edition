<?php
//zuletzt registrierte User
function l_reg()
{
    global $db;
    $lregconfig = config(array('m_lreg','l_lreg')); $lreg = '';
    $qry = db("SELECT id,nick,country,regdatum FROM ".$db['users']." ORDER BY regdatum DESC LIMIT ".$lregconfig['m_lreg']."");
    while($get = _fetch($qry))
    {
        $lreg .= show("menu/last_reg", array("nick" => re(cut($get['nick'], $lregconfig['l_lreg'])),
                                             "country" => flag($get['country']),
                                             "reg" => date("d.m.", $get['regdatum']),
                                             "id" => $get['id']));
    }

    return empty($lreg) ? '' : '<table class="navContent" cellspacing="0">'.$lreg.'</table>';
}
?>