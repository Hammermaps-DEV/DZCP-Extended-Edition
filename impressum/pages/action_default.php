<?php
/**
 * <DZCP-Extended Edition>
 * @package: DZCP-Extended Edition
 * @author: DZCP Developer Team || Hammermaps.de Developer Team
 * @link: http://www.dzcp.de || http://www.hammermaps.de
 */

####################################
## Wird in einer Index ausgeführt ##
####################################
if (!defined('IS_DZCP'))
    exit();

if (_version < '1.0') //Mindest Version pruefen
    $index = _version_for_page_outofdate;
else
{
    $info = settings(array('i_domain','i_autor'));
    $index = show($dir."/impressum", array("head" => _impressum_head,
            "domain" => _impressum_domain,
            "autor" => _impressum_autor,
            "disclaimer_head" => _impressum_disclaimer,
            "disclaimer" => _impressum_txt,
            "show_domain" => $info['i_domain'],
            "show_autor" => bbcode($info['i_autor'])));
}