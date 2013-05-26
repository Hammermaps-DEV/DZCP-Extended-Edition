DZCP - Extended Edition
=======================


*Bei der DZCP - Extended Edition handelt es sich um eine unfertige Entwickler Version und sie ist noch nicht für den Produktiven Einsatz gedacht!


Die DZCP - Extended Edition ist eine Abwandlung der DZCP 1.6 Version.

Die Extended Edition zeichnet sich auf neueren PHP Laufzeiten >= V5.3.x durch Überarbeitungen am CMS Kern aus.
Weitere Optimierungen sparen Quellcode und schonen den Web Server.

Geplant sind weitere Überarbeitungen bei Geschwindigkeit und Zuverlässigkeit, sowie Lauffähigkeit auf neuen PHP Versionen auf Windows und Linux Servern.

=======================
Änderungsübersicht
=======================

Viele der Kernfunktionen wurde in die kernel.php exportiert um später eine reine BBCode.php zu erhalten die sich wirklich nur um die Darstellung kümmert.
Viele der Kernfunktionen die in die kernel.php exportiert wurden, haben eine Überarbeitung erfahren und wurde auf einer php 5.4 Laufzeit mit voll eingeschalteten Error Reporting getestet.

Es wurde eine richtige Cache Klasse eingefügt, die standardmäßig auf 'File'  eingestellt ist. 
Der Cache speichert Seiten und Serverantworten zwischen, so das php nicht ständig die aktuellen Infos bei jedem Seitenaufruf erneut abrufen muss.
Dabei wird auch zbs. die News Seite in den Cache geladen und alle paar Sekunden aktualisiert, dieses Verfahren ermöglicht eine Entlastung der Datenbank und des Servers bei vielen gleichzeitigen Besuchern.
Der Cache kann als Files, Memcache, ZEND - Shared Memory, ZEND - Disk oder MySQL Erweiterung betrieben werden.
Die ZEND Shared Memory und ZEND Disk Cache setzt einen Zend Technologies Server vorraus -> http://www.zend.com/de/products/server/

Der Installer / Updater wurde komplett überarbeitet und wird nachfolgende Updates selbständig erkennen und die nötigen Aktualisierungen vornehmen. *Sie müssen nur noch auf 'Weiter' drücken.

Unterstützung für FreeWebspaces wurde überarbeitet, so dass es keine PHP Warnungen oder Verzögerungen mehr geben wird, wenn zbs. der Webspace kein fsockopen unterstützt.
Alle fsockopen abhängigen Funktionen werden dann vollständig abgeschaltet.

Viele der Seiten haben eine Code Optimierung erfahren, überflüssiger Code oder Code der deutlich sparender geschrieben werden kann wurde überarbeitet.
Teils ist der Code von über 200 Zeilen auf 50 Zeilen geschupft ohne die Quellcodeübersicht zu stark zu verändern.

Die Datenbank wurde von unnötigen Ballst befreit, nicht mehr verwendete Tabellen Zeilen wurden entfernt.
Fehlende Tabellen Zeilen oder Standartwerte die Probleme auf machen Seiten verursacht haben, SQL Error - xxxx ... wurden beseitigt.

Richys: Sprachdefinitionen Verbesserung wurde intrigiert und viele der Seiten wurden dafür umgeschrieben um wieder Quellcode einzusparen.
Das einbinden neuer Statischer Texte wurde somit erleichtert. 

Beispiel:
deutsch.php / englisch.php

define('_test_text', 'Nur ein Test Text^^');

HTML File Platzhalter:

[lang_test_text]

Senden von News *News einsenden* wurde in die index.php der News Seite intrigiert.
Die meisten Seiten wurden ausgelagert und in ihre Unterfunktionen zerlegt.
So muss man nur eine Datei ändern zbs. um Änderungen an dem Newsarchiv vorzunehmen, ohne die gesamte Newsseite zu ändern.
*Sollte man einen Fehler machen, funktioniert nur das Newsarchiv nicht mehr aber der Rest der News Seite ist weiterhin unberührt. 

Die Unterstützung von Multi-Indexes ist standartmassig vorhanden.

Unterstützung mehrere Bildformate zbs. für Server Pics, Adminmenü Icons, usw.
*.png / .jpg / .gif
* "In der "inc/config.php" einstellbar."

Infos wie der XFire Status wird per Ajax nachgeladen um lange Ladezeiten der gesamten DZCP Seite zu verhindern.
Eine Auswahl des Skins der bei XFire verwendet wird ist über die config.php möglich.

Es wird ein zufälliger MD5 Code verwendet um ein Login zu Speichern, das MD5 Verschlüsselte Passwort des Users wird nicht mehr im Cookie gespeichert.
Es wurden einzelne neue Smileys eingefügt.

Die Administrationsseiten können über eine XML Datei zusätzlich konfiguriert werden.
* "Menu" Verändern der Menüzugehörigkeit. "rootmenu,settingsmenu,contentmenu"
* "Rights" Nötigen Rechte für einen Zugriff und Anzeige im Menü.
* "Only_Admin" Ist nur für Admins mit Level 4 Rechten zugänglich.
* "Only_Root" Ist nur für den Rootadmin zugänglich.

Es wurde 11 weitere User-Rechte eingefügt alles bezogen auf die Administration.
*Der Administrator kann jeden User einen oder mehrere beliebige Bereiche zugänglich machen zbs. 'Datenbank aufräumen' oder die 'Support Informationen' einsehen.
*Der Zugang zu jedem beliebigen Bereich war vorher nur eingeschränkt möglich.

Neue HTML Tags zum ein HTML Code ausblenden basierend auf dem Loginstatus des Users.
* Der HTML Code der zwischen dem Tag steht, wird vollständig angezeigt oder komplett entfernt.
* Verwendung:

<logged_in>
	HTML Code der nur angezeigt wird wenn der aktuelle User angemeldet ist...
</logged_in>

<logged_out>
	HTML Code der nur angezeigt wird wenn der aktuelle User abgemeldet ist...
</logged_out>

Optional* Zusätzliche XML-Config für die DZCP Menufunktionen kann angelegt werden.
* Es kann eingestellt werden ob die Menufunktion über Ajax Loader nachgeladen werden sollen.
* Welches Bild für jede Menufunktion die per Ajax Loader nachgeladen wird, verwendet werden soll.
* Welches Level der Benutzer haben muss um das Menu zu sehen. Sehen ab: Gast,User,Admin,Root
* Der zusätzliche Ajax Loader kann generell in der "inc/config.php" abgeschaltet werden.

Eine Auswahl des Passwort-Hash Algorithmus ist nun möglich. *Extended Security*
* MD5,SHA1,SHA256,SHA512 stehen zur Verfügung.
* Standartmassig ist SHA256 in der Extended Edition aktiviert.
* Die User die alte MD5 Hashs verwenden, werden automatisch beim nächsten ändern ihres Passwortes in SHA1, SHA256 oder SHA512 gespeichert.

Neue Addons Schnittstelle für einfaches einfügen und löschen von Addons/Mods.
* Es müssen fast keine Original CMS Daten überschrieben werden.
* Änderungen am Template *HTML Files* oder neue HTML Daten befinden sich mit im Addon Ordner und nicht mehr umbedingt im 'inc\_templates_\*.*' Ordner.
* Anpassungen für einzelne Templates über Ordnernamen im Addon Ordner möglich.
* Include und automatisches laden von Funktionen oder Classen möglich.
* Frei zu bearbeitende Addon-XML Dateien für Statische Einstellungen aller Art.
* Eine einfache PHP-API Schnittstelle für alle Addons. *In DEV*

Die geschätzten Download Zeiten bei Downloads wurden der heutigen Zeit angepasst, ab DSL 1000 - VDSL 50.k
Die nötigen Download Zeiten werden jetzt richtig berechnet.
Download Kommentare können bei den Downloads geschrieben werden, kann beim Anlegen/Bearbeiten von Downloads aktiviert werden.

Die Thumbgen Funktion wurde in die ajax.php importiert und es wird ein Cache der Bilder durchgeführt.
Die Thumbgen verwendet wenn vorhanden die Imagick PHP Erweiterung, diese ist deutlich schneller als die alte GD.
Alle Einstellungen betreffend der Thumbgen Funktion sind über die config.php einstellbar.

Der Cache kann Binary Code ohne Verluste speichern und erkennt *optional Änderungen an den Original Daten und aktualisiert den Cache automatisch.
Userprofile können nun auch nur für Registrierte User zugänglich gemacht werden. *Privatsphärenschutz*
* Es kann unter *Profil editieren* Aktiviert oder Deaktiviert werden.

Das Gästebuch wurde überarbeitet, nun können die User Kommentare gelöscht oder bearbeitet werden, ohne den Original Gästebucheintrag zu verändern.
* Gästebucheinträge die aus einer Vorgänger Version von DZCP Übernommen wurden, können Darstellungsfehler haben.
* Grund dafür ist, das die alte DZCP Version die Kommentare mit in den Gästebucheintrag Text schreibt.
* Dieser sollte per Hand aus dem  Originalen Gästebucheintrag Text entfernt werden und neu Kommentiert werden. <Gästebucheintrag bearbeiten>

In der Gallery ist nun Seitenblättern möglich, die Gallery kann in mehrere Seiten eingeteilt werden.

Die Anzeige für TS3 und die Gameserver von DZCP wurde gegen GameQ ausgetauscht und grundliegend überarbeitet.
Es können damit nun um die 60 Spiele Live angezeit werden:

Alien Swarm
Half Life 2: Deathmatch
Sven Co-op
Garry's Mod
Counter-Strike: Global Offensive
Counter-Strike 1.6
Counter-Strike: Condition Zero
Counter-Strike: Source
Day of Defeat
Day of Defeat: Source
Half Life
Half Life 2: Insurgency
Left 4 Dead
Left 4 Dead 2
Natural Selection
Natural Selection 2
Team Fortress Classic
Team Fortress 2
Zombie Master
Zombie Panic Source
Homefront
America's Army 3
Unreal Tournament 3
Age of Chivalry
Armed Assault 2
Armed Assault 3
Battlefield 1942
Battlefield 2
Battlefield 2142
Battlefield 3
Battlefield Bad Company 2
Battlefield Vietnam
Brink
Call of Duty: Modern Warfare 3
Call of Duty
Call of Duty 2
Call of Duty 4
CoD:United Offensive
Call of Duty: World at War
Unreal Tournament
Crysis
Crysis 2
Crysis Wars
DayZ
Doom 3
Wolfenstein Enemy Territory
Enemy Territory: Quake Wars
F.E.A.R.
Minecraft
Medal of Honor: Allied Assault
Medal of Honor: Spearhead
Medal of Honor Warfighter
Quake 2
Quake 3
Quake 4
Red Orchestra: Ostfront 41-45
Red Orchestra 2

Der Teamspeak 3 Viewer wurde auch gegen GameQ ersetzt und ins DZCP intigriert.
* Automatischer Download von Custom Icons für User,Channels und Server.
* Anzeige mehrere TS3 Server ist auf der Teamspeak Server Seite möglich.
* TS2 Viewer wurde entfernt ,sry
* Die Teamspeak Server Seite wird per Ajax geladen.
* Auf und zuklappen der einzelden TS3 Channel geht nun perfect.
* Auf und Zuklappen der einzelden TS3 Server ist auf der Teamspeak Server Seite möglich.
* Unterstüzung und Erkennung von TS3-DNS Servern.

Erkennung und Blockierung von Trashmail Servern, Trashmail Server können in der config.php zugelassen werden.
Verwendung von einer Globalen IP Banlist für Spamming Bots, verwendet www.stopforumspam.com
IP-Bans können vom Administrator selber eingetragen werden. *Localban*
Das MySQL Backend wurde gegen ein MySQLi Backend ausgetauscht und es wird standardmäßig 'mysqli_real_connect' verwendet.