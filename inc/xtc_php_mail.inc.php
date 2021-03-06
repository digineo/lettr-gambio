<?php
/* --------------------------------------------------------------
   xtc_php_mail.inc.php 2008-07-21 gambio
   Gambio OHG
   http://www.gambio.de
   Copyright (c) 2008 Gambio OHG
   Released under the GNU General Public License
   --------------------------------------------------------------
*/
?><?php

/* -----------------------------------------------------------------------------------------
   $Id: xtc_php_mail.inc.php 1129 2005-08-05 11:46:11Z mz $   

   XT-Commerce - community made shopping
   http://www.xt-commerce.com

   Copyright (c) 2003 XT-Commerce
   -----------------------------------------------------------------------------------------
   based on: 
   (c) 2003	 nextcommerce (xtc_php_mail.inc.php,v 1.17 2003/08/24); www.nextcommerce.org


   Released under the GNU General Public License 
   ---------------------------------------------------------------------------------------*/
// include the mail classes


function unhtmlentities($string)
{
    // replace numeric entities
    $string = preg_replace('~&#x([0-9a-f]+);~ei', 'chr(hexdec("\\1"))', $string);
    $string = preg_replace('~&#([0-9]+);~e', 'chr("\\1")', $string);
    // replace literal entities
    $trans_tbl = get_html_translation_table(HTML_ENTITIES);
    $trans_tbl = array_flip($trans_tbl);
    return strtr($string, $trans_tbl);
}

function xtc_php_mail($from_email_address, $from_email_name, $to_email_address, $to_name, $forwarding_to, $reply_address, $reply_address_name, $path_to_attachement, $path_to_more_attachements, $email_subject, $message_body_html, $message_body_plain) {
	global $mail_error;

	$mail = new PHPMailer();
	$mail->PluginDir = DIR_FS_DOCUMENT_ROOT.'includes/classes/';

	if (isset ($_SESSION['language_charset'])) {
		$mail->CharSet = $_SESSION['language_charset'];
	} else {
		$lang_query = "SELECT * FROM ".TABLE_LANGUAGES." WHERE code = '".DEFAULT_LANGUAGE."'";
		$lang_query = xtc_db_query($lang_query);
		$lang_data = xtc_db_fetch_array($lang_query);
		$mail->CharSet = $lang_data['language_charset'];
	}
	if ($_SESSION['language'] == 'german') {
		$mail->SetLanguage("de", DIR_WS_CLASSES);
	} else {
		$mail->SetLanguage("en", DIR_WS_CLASSES);
	}
	if (EMAIL_TRANSPORT == 'smtp') {
		$mail->IsSMTP();
		$mail->SMTPKeepAlive = true; // set mailer to use SMTP
		$mail->SMTPAuth = SMTP_AUTH; // turn on SMTP authentication true/false
		$mail->Username = SMTP_USERNAME; // SMTP username
		$mail->Password = SMTP_PASSWORD; // SMTP password
		$mail->Host = SMTP_MAIN_SERVER.';'.SMTP_Backup_Server; // specify main and backup server "smtp1.example.com;smtp2.example.com"
	}

	if (EMAIL_TRANSPORT == 'sendmail') { // set mailer to use SMTP
		$mail->IsSendmail();
		$mail->Sendmail = SENDMAIL_PATH;
	}
	if (EMAIL_TRANSPORT == 'mail') {
		$mail->IsMail();
	}

	if (EMAIL_USE_HTML == 'true') // set email format to HTML
		{
		$mail->IsHTML(true);
		$mail->Body = $message_body_html;
		// remove html tags
		$message_body_plain = str_replace('<br />', " \n", $message_body_plain);
		$message_body_plain = strip_tags($message_body_plain);
		$mail->AltBody = $message_body_plain;
	} else {
		$mail->IsHTML(false);
		//remove html tags
		$message_body_plain = str_replace('<br />', " \n", $message_body_plain);
		$message_body_plain = strip_tags($message_body_plain);
		$mail->Body = $message_body_plain;
	}

	$mail->From = $from_email_address;
	$mail->Sender = $from_email_address;
	$mail->FromName = $from_email_name;
	$mail->AddAddress($to_email_address, $to_name);
	if ($forwarding_to != '')
		$mail->AddBCC($forwarding_to);
	$mail->AddReplyTo($reply_address, $reply_address_name);

	$mail->WordWrap = 50; // set word wrap to 50 characters
	$mail->AddAttachment($path_to_attachement);                     // add attachments
	//$mail->AddAttachment($path_to_more_attachements);               // optional name                                          

	$mail->Subject = $email_subject;
	$use_original_mail_function = true;
	$use_lettr = true;

  if($use_lettr and MODULE_DIGILETTER_API and MODULE_DIGILETTER_SEND_MAIL == "True")
	{
	  $use_original_mail_function = false;
	  // mail versand �ber lettr.de
	  include(DIR_FS_CATALOG."lettr/lettr_init.php");
	  	  
    Lettr::set_credentials(MODULE_DIGILETTER_API);
	  
	  $email_subject = html_entity_decode($email_subject);
	  
	  try{
	  if (EMAIL_USE_HTML == 'true')
    {
      if(count($mail->attachment)> 0){
        $attach = pathinfo($path_to_attachement);
        //Lettr::multipart_mail($to_email_address, utf8_encode($email_subject), array("delivery[reply_to]" => $from_email_address, "delivery[text]"=> utf8_encode($message_body_plain), "delivery[html]"=> utf8_encode($message_body_html), "files[" . $attach['basename'] . "]" => "@" . $path_to_attachement));
        Lettr::multipart_mail($to_email_address, utf8_encode($email_subject), array("reply_to" => $from_email_address, "text"=> utf8_encode($message_body_plain) . " ", "html"=> utf8_encode($message_body_html), "files" => array($attach['basename'] => "@" . $path_to_attachement)));
      } else {
        Lettr::multipart_mail($to_email_address, utf8_encode($email_subject), array("reply_to" => $from_email_address, "text"=> utf8_encode($message_body_plain) . " ", "html"=> utf8_encode($message_body_html))); 
      }
      return true;
	  }
	else
	  {
      Lettr::mail($to_email_address, utf8_encode($email_subject), utf8_encode($message_body_plain) . " ");
      return true;
	  }
	  
	  }catch(Exception $e)
	  {
	    error_log('Lettr-Error: ' . $e);
      // Falls gew�nscht, kann die alte Mailfunktion verwendet werden, wenn Lettr fehlschl�gt
	  	//$use_original_mail_function=true;
	  }
	}	

    if($use_original_mail_function)
	{
		if (!$mail->Send()) {
			return false;
		} else {
			return true;
		}
	}	

}
?>
