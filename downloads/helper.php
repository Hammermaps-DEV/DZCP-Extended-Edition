<?php
/**
 * <DZCP-Extended Edition>
 * @package: DZCP-Extended Edition
 * @author: DZCP Developer Team || Hammermaps.de Developer Team
 * @link: http://www.dzcp.de || http://www.hammermaps.de
 */

/**
 * Errechnet die nötige Downloadzeit bei DSL oder VDSL
 *
 * @return string/template
 */

function download_time($size)
{
    global $dsl_formats,$dir;
    $size *= 8; $ausg = '';
    foreach($dsl_formats as $key=>$value)
    {
        $time = sec_format($size/($value*1024));
        $ausg .= show($dir."/dl_speed",array('dsl_name' => $key, 'speed' => $value, 'time' => $time));
    }

    return $ausg;
}
