<?php   
/**
 *  Neue Languages & Neue Funktionen einbinden *Last
 */
if($l = get_files(basePath.'/inc/additional-languages/'.$language.'/',false,true,array('php')))
{
    foreach($l AS $languages)
    { include(basePath.'/inc/additional-languages/'.$language.'/'.$languages); }
}

if($f = get_files(basePath.'/inc/additional-functions/',false,true,array('php')))
{
    foreach($f AS $func)
    { include(basePath.'/inc/additional-functions/'.$func); }
}
?>