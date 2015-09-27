<?php
class enviarCorreo extends SessionCommand{
	function execute(){
	// Enviar el correo para el cambio de contrase�as
		$cuerpo='
			<table border="0" style="border:1px solid #999999;font-family:Verdana, Arial, Helvetica, sans-serif; font-size:12px;" width="950" align="center">
				<tr>
					<td>
 						<p>
							Prueba
 						</p>
 						<p>
						</p>
 	 					<p>
							Muchas gracias!!
						</p>
					</td>
				</tr>
				<tr>
					<td>
						Atentamente
						<br>
						<br>
						Sistema de Solicitudes
						<br>
						http://clientes.jmvseguros.com/
					</td>
				</tr>
			</table>
			';
		require("././phpmailer/class.phpmailer.php");
		$mail = new PHPMailer();
		//$mail->IsSMTP();
		$mail->Mailer 		= "smtp";
		$mail->Host     	= "mail.jmvseguros.com";
  		$mail->SMTPAuth		= true;
		$mail->Port			= 26;
		$mail->Username 	= "no-responder@jmvseguros.com";
		$mail->Password 	= "123abc123"; // SMTP password
		$mail->From     	= "no-responder@jmvseguros.com";
		$mail->FromName 	= "Sistema de Solicitudes";
		$mail->AddAddress("jmartinez@jmvseguros.com");               // optional name
		$mail->AddReplyTo("jmartinez@jmvseguros.com","Solicitud Transporte");
		$mail->WordWrap = 50;                              // set word wrap
		$mail->IsHTML(true);                               // send as HTML
		$mail->Subject  =  "Sistema de Solicitudes";
		$mail->Body     =  $cuerpo;
		$mail->CharSet = "UTF-8";
		if($mail->Send()){
			echo "Se ha enviado un mensaje a su cuenta de correo con las instrucciones para cambiar su clave";
		}else{
			echo "No se pudo enviar el mensaje. Intente nuevamente";
		}
		//$this->addLayout("public");
		//$this->processTemplate("personas/enviarCorreo.html");
	}
}