Newsletter Export Script für Ganbio
===================================
    Digineo GmbH 2009 | www.digineo.de
    Author: Tim Kretschmer
    Version 1.0
    Lizenz: GNU 3

1. Installation
---------------
	Führen Sie die Dateien mit Ihrer Gambio Installation zusammen.
	Die Datei newsletter_export.php muss in den Ordner /admin/includes/modules/export/ kopiert werden.
	Die Datei export.php muss in den Ordner /newsletter_export/ kopiert werden.

2. Bedienung
------------
	Installieren sie das Modul über den Modulmanager im Administrationsbereich Ihres Gambio Shop.
	Legen Sie ein Passwort fest, welches Ihre Kundendaten schützt. 
	Entscheiden Sie sich, ob Sie nur die Kunden exportieren wollen, die sich für den Newsletter 
	angemeldet haben (true), oder ob Sie alle Kunden exportieren wollen (false).
	Speichern Sie die Einstellungen.
	Der Aufruf des Exportes erfolgt  über http://newsletter_export:PASSWORT@ihr-shop.de/newsletter_export/export.php 	 

	Beachten Sie bitte, dass die zu exportierenden E-Mailadressen als <approved = 1> in unser System übertragen 
	werden.
	Sie müssen daher berechtigt sein, an die Empfänger E-Mails zu versenden.