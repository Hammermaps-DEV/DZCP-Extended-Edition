function sort(sortby)
{
  window.location.href = 'users.php?sort='+sortby;
}

function insertUser(id,nick,country)
{
  var flagURL = 'inc/images/flaggen/';

    var html = '<img src="' + flagURL +  country + '.gif" mce_src="' + flagURL +  country + '.gif" border="0" alt="' + country + '" class="icon" />&nbsp;<a href="?index=user&amp;action=user&amp;id=' + id +'">' + nick + '</a>&nbsp;';

    tinyMCE.execCommand('mceInsertRawHTML', false, html);
    tinyMCEPopup.close();
}
