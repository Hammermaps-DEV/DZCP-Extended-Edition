<?php
#########################
## OUTPUT BUFFER START ##
#########################
require_once("../../../inc/buffer.php");

## INCLUDES ##
require_once(basePath."/inc/debugger.php");
require_once(basePath."/inc/config.php");
require_once(basePath."/inc/common.php");

if(empty($_GET['sort']) || $_GET['sort'] == 'clan')
{
    $sel   = 'selected';
    $order = 'WHERE level > 1';
}
else
      $order = 'WHERE level != \'banned\' AND level != \'0\'';

$users = '';
$qry = db("SELECT id,nick,country FROM ".dba::get('users')." ".$order." ORDER BY nick");
while($get = _fetch($qry))
{
    $users .= "<tr>\n";
    $users .= "  <td>".flag($get['country'],true)." ".$get['nick']."</td>\n";
    $users .= "  <td style=\"text-align:right\"><a href=\"javascript:insertUser(".$get['id'].",'".addslashes($get['nick'])."','".rawflag($get['country'],true)."')\"><img src=\"images/insert.gif\" alt=\"insert\" title=\"{#dzcp.users_add_en}".$get['nick']."{#dzcp.users_add_de}\" border=\"0\"></a></td>\n";
    $users .= "</tr>\n";
}

echo '<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title>{#dzcp.users}</title>
    <script language="javascript" type="text/javascript" src="../../tiny_mce_popup.js"></script>
    <script language="javascript" type="text/javascript" src="jscripts/users.js"></script>
    <base target="_self" />
</head>
<body>
    <div id="users" style="padding:2px" align="center">
        <table style="width:230px" border="0" cellspacing="0" cellpadding="1">
      <tr>
        <td colspan="2" style="text-align:center">
          <select name="sort" style="width:190px"  class="mceSelect" onchange="sort(this.value)">
            <option value="all">{#dzcp.users_all}</option>
            <option value="clan" '.$sel.'>{#dzcp.users_clan}</option>
          </select>
        </td>
      </tr>
      <tr>
        <td>&nbsp;</td>
      </tr>
        '.$users.'
        </table>
    </div>
</body>
</html>';
ob_end_flush();