<?php
/**
 * <DZCP-Extended Edition>
 * @package: DZCP-Extended Edition
 * @author: DZCP Developer Team || Hammermaps.de Developer Team
 * @link: http://www.dzcp.de || http://www.hammermaps.de
 */

if(_adminMenu != 'true') exit();

settings::set('k_inhaber',string::encode($_POST['inhaber']));
settings::set('k_nr',string::encode($_POST['kontonr']));
settings::set('k_waehrung',string::encode($_POST['waehrung']));
settings::set('k_bank',string::encode($_POST['bank']));
settings::set('k_blz',string::encode($_POST['blz']));
settings::set('k_vwz',string::encode($_POST['vwz']));
settings::set('iban',string::encode($_POST['iban']));
settings::set('bic',string::encode($_POST['bic']));
$show = info(_config_set, "?index=admin&amp;admin=konto");