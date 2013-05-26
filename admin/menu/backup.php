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

$where = $where.': '._backup_head;

if(!empty($do))
{
    $v = str_replace(" ","_",_version);
    $file_name = 'backup_dzcp_v.'.$v.'_'.date("d.m.y").'.sql';
    file_put_contents($file_name, database::backup());
    if(file_exists($file_name))
    {
        //Ausgabe der Datei
        header('Cache-Control:  must-revalidate, post-check=0, pre-check=0');
        header("Content-type: application/txt");
        header('Content-Length: '.filesize($file_name));
        header("Content-Disposition: attachment; filename=".$file_name);
        readfile($file_name);
        @unlink($file_name);
        exit();
    }
}

$show = show($dir."/backup", array());