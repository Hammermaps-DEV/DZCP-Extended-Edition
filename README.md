DZCP - Extended Edition
=======================

Die DZCP - Extended Edition ist eine Abwandlung der DZCP 1.6 Version.
In dieser Version wurde ein neuer Cache integriert, dieser wirkt sich besonders auf viel besuchten Seiten aus.
Es werden Unnötig lange Ladezeiten verhindert und die Last des SQL Datenbank Servers reduziert.

Außerdem zeichnet sich die DZCP - Extended Edition auf neueren PHP Laufzeiten >= V5.3.x durch Überarbeitungen am CMS Kern aus.
Weitere Optimierungen sparen Quellcode und schonen den Web Server.

Geplant sind weitere Überarbeitungen bei Geschwindigkeit und Zuverlässigkeit, sowie Lauffähigkeit auf neuen PHP Versionen auf Windows und Linux Servern.

Änderungsübersicht

Viele der Kernfunktionen wurde in die kernel.php exportiert um später eine reine BBCode.php zu erhalten die sich wirklich nur um die Darstellung kümmert.
Viele der Kernfunktionen die in die kernel.php exportiert wurden, wurden überarbeitet und auf einer php 5.4 Laufzeit mit voll eingeschalteten Error Reporting getestet.

Es wurde eine richtige Cache Klasse eingefügt, die standardmäßig auf 'File'  eingestellt ist. 
Der Cache speichert Seiten und Server antworten zwischen, so das php nicht ständig die aktuellen Infos bei jedem Seitenaufruf erneut abrufen zu müssen.
Dabei wird auch zbs. die News Seite in den Cache geladen und alle paar Sekunden aktualisiert, dieses Verfahren ermöglicht eine Entlastung der Datenbank und des Servers bei vielen gleichzeitigen Besuchern.
Der Cache kann als Files, Memcache oder SQL Erweiterung betrieben werden. 

Der Installer / Updater wurde komplett überarbeitet und wird nachfolgende Updates selbständig erkennen und die nötigen Aktualisierungen vornehmen. *Sie müssen nur noch auf 'Weiter' drücken.
Unterstützung für FreeWebspaces wurde überarbeitet, so dass es keine PHP Warnungen oder Verzögerungen mehr geben wird, Wenn zbs. der Webspace kein fsockopen unterstützt.
Alle fsockopen abhängigen Funktionen werden dann vollständig abgeschaltet.

Viele der Seiten haben eine Code Optimierung erfahren, überflüssiger Code oder Code der deutlich sparender geschrieben werden kann wurde überarbeitet.
Teils ist der Code von über 200 Zeilen auf 50 Zeilen geschupft ohne die Quellcode Übersicht zu stark zu verändern.

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
*Sollte man einen Fehler machen, funktioniert nur das Newsarchiv nicht mehr aber der Rest der News Seite ist weiterhin betriebsbereit. 

Die Unterstützung von Multi-Indexes ist standartmassig vorhanden.

Unterstützung mehrere Bildformate zbs. für Server Pics, Adminmenü Icons, usw.
*.png / .jpg / .gif

Infos wie der XFire Status wird per Ajax nachgeladen um lange Ladezeiten der gesamten DZCP Seite zu verhindern.
Eine Auswahl des Skins der bei XFire verwendet wird ist über die config.php möglich.