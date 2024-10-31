=== Q wie Quiz ===
Contributors: Stephan Gaertner
Donate link: http://www.q-wie-quiz.de
Tags: quiz,quizzes,games,fun,webtool,gadget
Requires at least: 2.5
Tested up to: 2.8.4
Stable tag: 0.1


== Description ==
"Q wie Quiz" ist ein Quiz-Webgadget auf Flashbasis, mit dem Quiz-Module zu
verschiedenen Themen auf einer Website schnell und einfach eingebunden werden koennen.
Die Quiz-Module beinhalten zum Teil Sound und Bilder, die die Quizfragen ergaenzen.
WP-QwieQuiz ist ein WordPress-Plugin, mit dem ganz einfach diese Quiz-Module von
"Q wie Quiz" (s. http://www.q-wie-quiz.de) in Wordpress eingebunden werden koennen.


== Copyright ==
Wordpress - Plugin "Q wie Quiz"
Ver. 0.1 (10/2009)
(c) 2009 by SteGaSoft, Stephan G‰rtner
Www: http://www.stegasoft.de
eMail: s. website

Die Nutzung dieses Plugins geschieht auf eigene Verantwortung.
Ich uebernehme keine Garantie fuer die Funktionsfaehigkeit noch fuer
irgendwelche Probleme in Zusammenhang mit wordpress oder anderen
Plugins. 

== Historie ==
Version 0.1
  Erste Version fuer Wordpress bis V2.8.4
  - einfache Quiz-Modul-Auswahl
  - Hintergrundfarbe selektierbar
  - Rahmenfarbe selektierbar
  - Rahmenbreit einstellbar
  - Personalisierung und Untertitel aktivieren



== Installation ==
Entpacken Sie die ZIP-Datei und benennen Sie den Ordner "q-wie-quiz" in "wp-qwiequiz" um.
Laden Sie den Ordner wp-qwiequiz in das Plugin-Verzeichnis von WordPress hoch: w-content/plugins/.

Setzen Sie die Schreibreichte folgender Datei auf 777:
- vars.php

Loggen Sie sich dann als Admin unter Wordpress ein.
Unter dem Menuepunkt "Plugins" koennen Sie WP-QwieQuiz 
nun aktivieren. Sie finden dort auch den Untermenuepunkt "Q wie Quiz".
Durch Klick auf diesen Link gelangen Sie zur Administration des
Plugins.

Nach Aktivierung wird unter dem Menuepunkt "Seiten" automatisch eine neue Seite
mit dem Titel "Q wie Quiz - Auswertung" angelegt. Diese wird fuer die Auswertung benoetigt. 
Nach Deaktivierung des Plugins wird die Seite, wenn in der Plugin-Administration "Deinstallieren" 
angehakt wurde, automatisch wieder entfernt.

Der Inhalt bzw. das Aussehen koennen in der Datei "template.php" und "/global/styles.css" angepasst
werden.
 
Tragen Sie auf der Seite/im Artikel, in der/dem das Quiz erscheinen
soll, folgenden Tag ein: [qwiequiz].
Dieser Tag wird spaeter durch das Flash-Quiz mit den Standard-Einstellungen aus der
Administrationsebene ersetzt.

Folgende Parameter koennen geaendert werden und ersetzen die Standard-Einstellungen:

modul=string          =>  (Ordner-) Name des jeweiligen Quiz-Moduls.
                          Der Modulname entspricht immer dem Ordnernamen des Moduls!
                          Bitte Gross-/Kleinschreibung beachten!
bgcolor=string        =>  Hintergrundfarbe in HTML-Format (z. B. #DFDFDF) des Quiz.
bordercolor=string    =>  Rahmenfarbe in HTML-Format (z. B. #000000) des Quiz.
bordersize=int        =>  Rahmendicke
is_edu=boolean        =>  true: Untertitel kann eingegeben werden, das Quiz wird personalisiert, d.h.
                          der Quiz-Teilnehmer kann vor Ausgabe der Auswertung seinen Namen eingeben.
subtext=string        =>  Untertitel des Quiz (nur wenn is_edu=true).

Beispiel:
Der Tag

[qwiequiz modul="Sport" bgcolor="#FFFFFF" bordercolor="#000000" bordersize=1 is_edu=true subtext="Fragen auch mal ohne Fussball"]

bindet das Quiz zum Thema Sport mit weiﬂer Hintergrundfarbe, schwarzem, 1 Pixel dickem Rahmen und mit dem Untertitel 
"Fragen auch mal ohne Fussball" in Ihrer Seite / Ihrem Artikel ein. Am Ende des Quiz wird noch der
Spieler nach seinem Namen gefragt und in die Auswertung integriert.

Auf diese Art ist es moeglich, verschiedene Quiz auf einer Seite oder in einem Artikel einzubauen.


-- Einbinden eines neuen Quiz-Moduls --
 - Entpacken Sie die ZIP-Datei des Moduls und laden Sie den Ordner, der in der Datei enthalten war
   in folgendes Verzeichnis auf Ihren Webserver hoch: /wp-content/plugins/wp-qwiequiz/data/ .

Moeglichkeit 1:
 - Loggen Sie sich in WordPress ein und oeffnen Sie die Admin-Seite zum Plugin.
 - In der DropDown- (Auswahl-) Liste koennen Sie nun das neue Quiz auswaehlen und aktivieren.
Moeglichkeit 2:
 - Legen Sie eine neue Seite/neuen Artikel an oder bearbeiten Sie denjenigen, unter dem das
   neue Quiz erscheinen soll.
 - Tragen Sie an der gewuenschten Stelle den Tag [qwiequiz modul="Modulname"] ein.
   Der Modulname entspricht immer dem Ordnernamen des Moduls.
   Bitte Gross-/Kleinschreibung beachten!
   Beispiel: heisst der Ordner, der in der ZIP-Datei des Moduls enthalten war, "Sport", so
   tragen Sie als Modulnamen "Sport" ein.


== Administration ==
Die Administration sollte eigentlich selbsterklaerend sein.
Fuellen Sie einfach die entsprechenden Felder aus.


Weitere Informationen finden Sie in der Anleitungs-Datei "qwq-anleitung.pdf" auf der 
Seite http://www.q-wie-quiz.de/quiz-module/ im Abschnitt "Was Sie nach den drei Minuten noch tun koennen".


Vergessen Sie zum Schluss das Speichern nicht!


Viel Spass mit dem Plugin wuenscht
SteGaSoft, Stephan Gaertner