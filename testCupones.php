<?
	require("phpmailer/class.phpmailer.php");
	$mail = new PHPMailer();
	$mail->Mailer 		= "smtp";
	$mail->Host     	= "smtpout.secureserver.net";
	$mail->SMTPAuth		= true;
	$mail->Port			= 80;
	$mail->Username 	= "no-responder@jmvasesores.com";
	$mail->Password 	= "123abc123"; // SMTP password
	$mail->From     	= "no-responder@jmvasesores.com";
	$mail->FromName 	= "JMV Seguros - Sistema de Cobranzas";
	$mail->AddBCC("no-responder@jmvasesores.com");		
	$mail->AddAddress("josecarlos89@gmail.com");               // optional name
	$mail->AddReplyTo("jmartinez@jmvasesores.com","Recordatorio");
	$mail->WordWrap = 50;                              // set word wrap
	$mail->IsHTML(true);                               // send as HTML
	$mail->Subject  =  "Primas por vencer";
	$html = "Prueba";
	$mail->Body     =  $html;
	$mail->CharSet = "UTF-8";
	if(!$mail->Send())
	{
	   echo "Error sending: " . $mail->ErrorInfo;
	}
	else
	{
	   echo "Letter is sent";
	}	

?>