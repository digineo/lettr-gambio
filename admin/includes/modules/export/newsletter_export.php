<?php
	/* -----------------------------------------------------------------------------------------
	Newsletter Export Script fÃ¼r Ganbio
	Digineo GmbH 2011 | www.digineo.de
	Author: Dennis Meise
	Version 2.0
	Lizenz: GNU 3
	---------------------------------------------------------------------------------------*/

	defined( '_VALID_XTC' ) or die( 'Direct Access to this location is not allowed.' );

	define('MODULE_DIGILETTER_TEXT_DESCRIPTION', '<a href="https://lettr.de/signup?coupon=gambio"><img src="https://lettr.de/images/i/html-newsletter-versenden.png"></a><br /><br />Dieses Modul ist für den Datenaustausch zwischen lettr.de und Gambio zuständig.<br /> Zusätzlich können Sie den gesamten Mailverkehr Ihres Shops (Bestellbestätigungen, Aktivierungsmails ...) über lettr.de abwickeln und durch das Whitelisting bei lettr.de sicherstellen, dass Ihre Emails auch ankommen.<br /><br /> Als Neukunden bekommen Sie beit lettr.de nach Eingabe des Gutscheincodes <strong>gambio</strong> ein Versandguthaben von 5000 Mails gratis.');
	define('MODULE_DIGILETTER_TEXT_TITLE', 'Lettr.de - professioneller Mailversand');
	define('MODULE_DIGILETTER_PASSWORD_TITLE' , '<hr noshade>Passwort');
	define('MODULE_DIGILETTER_PASSWORD_DESC' , 'Geben Sie ein sicheres Passwort an. Mit diesem wird die Schnittstelle zum Schutz Ihrer Kundendaten gesichert.');
	define('MODULE_DIGILETTER_NO_SPAM_TITLE' , '<hr noshade>Nur Newsletter Kunden exportieren?');
	define('MODULE_DIGILETTER_NO_SPAM_DESC' , 'Nur Newsletter Kunden exportieren? (bei "false" wird die gesamte Datenbank exportiert.)');
	define('MODULE_DIGILETTER_STATUS_DESC', '<a href="https://lettr.de/signup?coupon=gambio"><img src="https://lettr.de/images/i/html-newsletter-versenden.png"></a><br /><br /> Mit dieser Option schalten Sie den Zugang zur Exportschnittstelle an oder aus.<br /> </br> Wenn Sie die Schnittstelle aktiviert haben können Sie auf den Export über folgende Adresse zugreifen:<br /> <br/> http://newsletter_export:<strong>IHR-PASSWORT-EINTAGEN</strong>@<strong>IHRE-DOMAIN-EINTRAGEN.de</strong>/newsletter_export/export.php<br /> Diese Adresse müssen Sie in Ihren <a hef="https://lettr.de/setting/edit">Importoptionen bei lettr.de</a> eintragen.');
	define('MODULE_DIGILETTER_STATUS_TITLE','Exportschnittstelle aktivieren');
	define('MODULE_DIGILETTER_API_TITLE' , '<hr noshade>lettr.de API-Key');
	define('MODULE_DIGILETTER_API_DESC' , '<hr noshade>Geben Sie hier Ihren lettr.de API-Key an. Diese Option ist nur erforderlich wenn Sie den kompletten Emailversand über lettr.de abwickeln möchten. <br /> Sie finden Ihren API-Key <a href="https://lettr.de/setting">hier</a>.');
	define('MODULE_DIGILETTER_SEND_MAIL_TITLE' , '<hr noshade>Kompletter Mailversand über lettr.de');
	define('MODULE_DIGILETTER_SEND_MAIL_DESC' , 'Der gesamte Emailverkehr Ihres Shops wie Bestellbestätigungen, Aktivierungsmails und so weiter wird über lettr.de abgewickelt.<br /><br /> Sie profitieren vom Whitelisting welches eine Zustellung bei <a href="http://www.certified-senders.eu/csa_html/de/271.htm">führenden Emailanbietern</a> sichert.');
	define('DATE_FORMAT_EXPORT', '%d.%m.%Y');  

  class newsletter_export {
    var $code, $title, $description, $enabled;

    function newsletter_export() {
      global $order;

      $this->code = 'newsletter_export';
      $this->title = MODULE_DIGILETTER_TEXT_TITLE;
      $this->description = MODULE_DIGILETTER_TEXT_DESCRIPTION;
      $this->enabled = ((MODULE_DIGILETTER_STATUS == 'True') ? true : false);
      $this->CAT=array();
      $this->PARENT=array();

    }
	
	function display() {
		$customers_statuses_array = xtc_get_customers_statuses();		
		return array('text' => '<br />' . xtc_button("Speichern") .
						  xtc_button_link(BUTTON_CANCEL, xtc_href_link(FILENAME_MODULE_EXPORT, 'set=' . $_GET['set'] . '&module=newsletter_export')));
    }

    function check() {
      if (!isset($this->_check)) {
        $check_query = xtc_db_query("select configuration_value from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_DIGILETTER_STATUS'");
        $this->_check = xtc_db_num_rows($check_query);
      }
      return $this->_check;
    }

	function install() {
		xtc_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_key, configuration_value,  configuration_group_id, sort_order, set_function, date_added) values ('MODULE_DIGILETTER_PASSWORD', '', '6', '1', '', now())");
		xtc_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_key, configuration_value,  configuration_group_id, sort_order, set_function, date_added) values ('MODULE_DIGILETTER_NO_SPAM', 'True', '6', '1', 'xtc_cfg_select_option(array(\'True\', \'False\'), ', now())");
		xtc_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_key, configuration_value,  configuration_group_id, sort_order, set_function, date_added) values ('MODULE_DIGILETTER_STATUS', 'False',  '6', '1', 'xtc_cfg_select_option(array(\'True\', \'False\'), ', now())");
		xtc_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_key, configuration_value,  configuration_group_id, sort_order, set_function, date_added) values ('MODULE_DIGILETTER_API', '', '6', '1', '', now())");
		xtc_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_key, configuration_value,  configuration_group_id, sort_order, set_function, date_added) values ('MODULE_DIGILETTER_SEND_MAIL', 'False',  '6', '1', 'xtc_cfg_select_option(array(\'True\', \'False\'), ', now())");
		
	}

    function remove() {
      xtc_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key in ('" . implode("', '", $this->keys()) . "')");
	  xtc_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_DIGILETTER_SHIPPING_COST'");
    }
    
    function process(){
    	
    }

    function keys() {
      return array('MODULE_DIGILETTER_STATUS','MODULE_DIGILETTER_PASSWORD', 'MODULE_DIGILETTER_NO_SPAM','MODULE_DIGILETTER_API', 'MODULE_DIGILETTER_SEND_MAIL');
    }
 
  }
?>