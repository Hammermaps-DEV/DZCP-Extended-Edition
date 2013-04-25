<?php
function ftopics()
{
    global $db,$allowHover;
    $ftopicsconfig = config(array('m_ftopics','l_ftopics','m_fposts')); $f = 0; $ftopics = '';
    $qry = db("SELECT s1.*,s2.id AS subid FROM ".$db['f_threads']." s1, ".$db['f_skats']." s2, ".$db['f_kats']." s3
               WHERE s1.kid = s2.id AND s2.sid = s3.id ORDER BY s1.lp DESC LIMIT 100");
    while($get = _fetch($qry))
    {
        if($f == $ftopicsconfig['m_ftopics'])
            break;

        if(fintern($get['kid']))
        {
            $lp = cnt($db['f_posts'], " WHERE sid = '".$get['id']."'");
            $pagenr = ceil($lp/$ftopicsconfig['m_fposts']);
            $page = (!$pagenr ? 1 : $pagenr);
            $info = ($allowHover == 1 ? 'onmouseover="DZCP.showInfo(\''.jsconvert(re($get['topic'])).'\', \''._forum_posts.';'._forum_lpost.'\', \''.$lp.';'.date("d.m.Y H:i", $get['lp'])._uhr.'\')" onmouseout="DZCP.hideInfo()"' : '');
            $ftopics .= show("menu/forum_topics", array("id" => $get['id'],
                                                        "pagenr" => $page,
                                                        "p" => $lp + 1,
                                                        "titel" => cut(re($get['topic']),$ftopicsconfig['l_ftopics']),
                                                        "info" => $info,
                                                        "kid" => $get['kid']));
            $f++;
        }
    }

    return empty($ftopics) ? '' : '<table class="navContent" cellspacing="0">'.$ftopics.'</table>';
}
?>