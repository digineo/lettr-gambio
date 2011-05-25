Lettr.de Mailversand für Ganbio
===================================
    Digineo GmbH 2011 | www.digineo.de
    Author: Dennis Meise
    Version 2.0
    Lizenz: GNU 3

1. Registrierung
------------
	Mit diesem Modul haben Sie die Möglichkeit Ihre Kundendaten mit http://www.lettr.de zu syncronisieren und den gesamten Emailverkehr Ihres Shops über lettr.de abzuwickeln.
	Nicht zustellbare Emails und Mails die im Spam-Ordnder landen gehören dank dem Whitelisting der Certified Senders Alliance der Vergangenheit an - Sie können sicher sein, dass wichtige Emails wie Bestellbestätigungen oder Aktivierunsmails auch tatsächlich bei Ihren Kunden ankommen.
	Zur Nutzung dieses Services ist ein lettr.de Account notwendig:
	
	Registrieren Sie sich hier und Sie erhalten ein Startguthaben von 5000 Credits.
	https://lettr.de/signup?coupon=gambio


2. Installation
---------------
	Fügen Sie die beiliegenden Dateien in Ihre Shop Installation ein.
	Die Datei newsletter_export.php muss in den Ordner /admin/includes/modules/export/ kopiert werden.
	Die Datei export.php muss in den Ordner /newsletter_export/ kopiert werden.
	In der Datei lang/german/admin/configuration.php muss der Inhalt aus der entsprechenden Datei aus diesem Archiv hinzugefügt werden.
	Das Verzeichnis lettr/ müssen Sie einfach in das Installationsverzeichnis Ihres Shops kopieren.
	Als letzten Schritt müssen Sie noch die inc/xtc_php_mail.inc.php durch die Datei aus diesem Archiv ersetzen.

3. Konfiguration
------------
	Installieren sie das Modul über den Modulmanager (XT-Module) im Administrationsbereich Ihres Gambio Shop.
	Legen Sie ein Passwort fest, welches Ihre Kundendaten schützt. 
	Entscheiden Sie sich, ob Sie nur die Kunden exportieren wollen, die sich für den Newsletter 
	angemeldet haben (true), oder ob Sie alle Kunden exportieren wollen (false).
	Speichern Sie die Einstellungen.
	Der Aufruf des Exportes erfolgt  über http://newsletter_export:PASSWORT@ihr-shop.de/newsletter_export/export.php 	 

4. Synchronisation mit lettr.de
------------
	Um Ihre Kundendaten mit lettr.de zu syncronisieren	müssen Sie unter 
	https://lettr.de/setting/edit

	Unter *Import URL* und *Callback URL* ihre Export URL eingeben:

	http://newsletter_export:PASSWORT@ihr-shop.de/newsletter_export/export.php 	

	Anschließend müssen Sie unter

	https://lettr.de/imports/new

	den Reiter "Web-Schnittstelle" aktivieren und dort den Import erstellen. Die Kundendaten Ihres Shops werden dadurch innerhalb weniger Minuten in Ihren lettr Account übertragen und Sie können Ihre Kunden bequem per über das lettr Frontend anschreiben.


5. Kompletter Mail-Versand über lettr.de
------------	
	Um den kompletten Mailversand Ihres Shops über das Whitelisting von lettr abzuwickeln müssen Sie lediglich in den Emaileinstellungen Ihren Lettr-API-Key (zu finden unter https://lettr.de/setting) eintragen und die entsprechende Option aktivieren.
	Sie haben dadurch den Vorteil, dass alle Ihre wichtigen Emails über die Schnittstelle von lettr versendet werden und können dadurch sicherstellen, dass diese Emails nicht im Spam-Ordner des Empfängers landen.
