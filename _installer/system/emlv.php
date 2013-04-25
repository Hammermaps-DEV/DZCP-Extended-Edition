<?php
//-> E-Mail Vorlagen
function emlv($id)
{
    switch ($id)
    {
        case 'eml_reg_subj':         return 'Deine Registrierung'; break;
        case 'eml_pwd_subj':         return 'Deine Zugangsdaten'; break;
        case 'eml_nletter_subj':     return 'Newsletter'; break;
        case 'eml_reg':              return 'Du hast dich erfolgreich auf unserer Seite registriert!\r\nDeine Logindaten lauten:\r\n\r\n##########\r\nLoginname: [user]\r\nPasswort: [pwd]\r\n##########\r\n\r\n[ Diese Email wurde automatisch generiert, bitte nicht antworten! ]'; break;
        case 'eml_pwd':              return 'Ein neues Passwort wurde f&uuml;r deinen Account generiert!\r\n\r\n#########\r\nLogin-Name: [user]\r\nPasswort: [pwd]\r\n#########\r\n\r\n[ Diese Email wurde automatisch generiert, bitte nicht antworten! ]'; break;
        case 'eml_nletter':          return '[text]\r\n\r\n\r\n[ Diese Email wurde automatisch generiert, bitte nicht antworten! ]'; break;
        case 'eml_fabo_npost_subj':  return 'Neuer Beitrag auf abonniertes Thema im [titel]'; break;
        case 'eml_fabo_tedit_subj':  return 'Thread auf abonniertes Thema im [titel] wurde editiert'; break;
        case 'eml_fabo_pedit_subj':  return 'Beitrag auf abonniertes Thema im [titel] wurde editiert'; break;
        case 'eml_pn_subj':          return 'Neue PN auf [domain]'; break;
        case 'eml_fabo_npost':       return 'Hallo [nick],<br />\r\n<br />\r\n[postuser] hat auf das Thema: [topic] auf der Website: &#34;[titel]&#34; geantwortet.<br />\r\n<br />\r\nDen neuen Beitrag erreichst Du ber folgenden Link:<br />\r\n<a href=&#34;http://[domain]/forum/?action=showthread&id=[id]&page=[page]#p[entrys]&#34;>http://[domain]/forum/?action=showthread&id=[id]&page=[page]#p[entrys]</a><br />\r\n<br />\r\n[postuser] hat folgenden Text geschrieben:<br />\r\n---------------------------------<br />\r\n[text]<br />\r\n---------------------------------<br />\r\n<br />\r\nMit freundlichen Gr&uuml;&szlig;en,<br />\r\n<br />\r\nDein [clan]<br />\r\n<br />\r\n[ Diese Email wurde automatisch generiert, bitte nicht antworten! ]'; break;
        case 'eml_fabo_tedit':       return 'Hallo [nick],<br />\r\n<br />\r\nDer Thread mit dem Titel: [topic] auf der Website: &#34;[titel]&#34; wurde soeben von [postuser] editiert.<br />\r\n<br />\r\nDen editierten Beitrag erreichst Du ber folgenden Link:<br />\r\n<a href=&#34;http://[domain]/forum/?action=showthread&id=[id]&#34;>http://[domain]/forum/?action=showthread&id=[id]</a><br />\r\n	<br />\r\n[postuser] hat folgenden neuen Text geschrieben:<br />\r\n---------------------------------<br />\r\n[text]<br />\r\n---------------------------------<br />\r\n	<br />\r\nMit freundlichen Gr&uuml;&szlig;en,<br />\r\n<br />\r\nDein [clan]<br />\r\n<br />\r\n[ Diese Email wurde automatisch generiert, bitte nicht antworten! ]'; break;
        case 'eml_fabo_pedit':       return 'Hallo [nick],<br />\r\n<br />\r\nEin Beitrag im Thread mit dem Titel: [topic] auf der Website: &#34;[titel]&#34; wurde soeben von [postuser] editiert.<br />\r\n<br />\r\nDen editierten Beitrag erreichst Du ber folgenden Link:<br />\r\n<a href=&#34;http://[domain]/forum/?action=showthread&id=[id]&page=[page]#p[entrys]&#34;>http://[domain]/forum/?action=showthread&id=[id]&page=[page]#p[entrys]</a><br />\r\n<br />\r\n[postuser] hat folgenden neuen Text geschrieben:<br />\r\n---------------------------------<br />\r\n[text]<br />\r\n---------------------------------<br />\r\n<br />\r\nMit freundlichen Gr&uuml;&szlig;en,<br />\r\n<br />\r\nDein [clan]<br />\r\n<br />\r\n[ Diese Email wurde automatisch generiert, bitte nicht antworten! ]'; break;
        case 'eml_pn':               return "---------------------------------<br />\r\n<br />\r\nHallo [nick],<br />\r\n<br />\r\nDu hast eine neue Nachricht in deinem Postfach.<br />\r\n<br />\r\nTitel: [titel]<br />\r\n<br />\r\n<a href=&#34;http://[domain]/user/index.php?action=msg&#34;>Zum Nachrichten-Center</a><br />\r\n<br />\r\nVG<br />\r\n<br />\r\n[clan]<br />\r\n<br />\r\n---------------------------------"; break;

    }
}
?>