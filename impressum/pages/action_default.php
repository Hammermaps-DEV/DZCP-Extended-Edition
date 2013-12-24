<?php
/**
 * <DZCP-Extended Edition>
 * @package: DZCP-Extended Edition
 * @author: DZCP Developer Team || Hammermaps.de Developer Team
 * @link: http://www.dzcp.de || http://www.hammermaps.de
 */

if (!defined('IS_DZCP')) exit();
$info = settings(array('i_domain','i_autor'));
$index = show($dir."/impressum", array("show_domain" => string::decode($info['i_domain']), "show_autor" => bbcode::parse_html($info['i_autor'])));