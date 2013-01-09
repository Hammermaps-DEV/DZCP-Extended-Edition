<?php
#####################
## Admin Menu-File ##
#####################
if(_adminMenu != 'true')
    exit();

$where = $where.': '._config_impressum_head;
$wysiwyg = '_word';

$get = db("SELECT i_domain,i_autor FROM ".$db['settings'],false,true);
$show = show($dir."/form_impressum", array(
        "domain" => re($get['i_domain']),
        "bbcode" => bbcode("seitenautor"),
        "postautor" => re_bbcode($get['i_autor'])));

$show = show($dir."/imp", array("what" => "impressum", "value" => _button_value_edit, "show" => $show));
if(isset($_GET['do']) && $_GET['do'] == "update")
{
    db("UPDATE ".$db['settings']." SET `i_autor` = '".up($_POST['seitenautor'], 1)."', `i_domain` = '".up($_POST['domain'])."' WHERE id = 1");
    $show = info(_config_set, "?admin=impressum");
}
?>
