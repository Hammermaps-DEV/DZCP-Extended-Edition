<?php
/**
 * <DZCP-Extended Edition>
 * @package: DZCP-Extended Edition
 * @author: DZCP Developer Team || Hammermaps.de Developer Team
 * @link: http://www.dzcp.de || http://www.hammermaps.de
 */

//-> Funktion um bei Clanwars Details Endergebnisse auszuwerten ohne bild
function cw_result_details($punkte, $gpunkte)
{
    if($punkte > $gpunkte)
        return '<td class="contentMainFirst" align="center"><span class="CwWon">'.$punkte.'</span></td><td class="contentMainFirst" align="center"><span class="CwLost">'.$gpunkte.'</span></td>';
    else if($punkte < $gpunkte)
        return '<td class="contentMainFirst" align="center"><span class="CwLost">'.$punkte.'</span></td><td class="contentMainFirst" align="center"><span class="CwWon">'.$gpunkte.'</span></td>';
    else
        return '<td class="contentMainFirst" align="center"><span class="CwDraw">'.$punkte.'</span></td><td class="contentMainFirst" align="center"><span class="CwDraw">'.$gpunkte.'</span></td>';
}

function img_cw($folder="", $img="")
{ return "<a href=\"../".$folder."_".$img."\" data-lightbox=\"lightbox[cw_".convert::ToInt($folder)."]\"><img src=\"inc/ajax.php?loader=thumbgen&file=uploads/".$folder."_".$img."\" alt=\"\" /></a>"; }

function cw_screenshots($cwid=0)
{
    global $picformat,$dir;
    $files = get_files(basePath."/inc/images/uploads/clanwars/",false,true,$picformat,"#^".$cwid."_(.*?)#"); $i=1; $b=1; $screens = array(); $tr = '';
    if($files != false && count($files) >= 1)
    {
        foreach($files as $file)
        { $screens[$b][] = $file; $i++; if($i % 4 == 1) $b++; }

        $scree_count = 1;
        foreach($screens as $screen)
        {
            $td1 = ''; $td2 = '';
            foreach ($screen as $file)
            {
                $td1 .= '<td class="contentMainFirst" align="center" width="25%"><span class="fontBold">'._cw_screenshot.' '.$scree_count.'</span></td>';
                $td2 .= '<td class="contentMainSecond" align="center" width="25%"><a href="inc/images/uploads/clanwars/'.$file.'" data-lightbox="cw_pic"><img src="inc/ajax.php?loader=thumbgen&width=160&height=120&file=uploads/clanwars/'.$file.'" alt="" /></a></td>';
                $scree_count++;
            }

            $tr .= '<tr>'.$td1.'</tr>';
            $tr .= '<tr>'.$td2.'</tr>';
        }
    }

    return $tr;
}
