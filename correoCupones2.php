<?
require("phpmailer/class.phpmailer.php");
mysql_connect ("jmvclientes.db.8222403.hostedresource.com","jmvclientes","seJMV28.") or die ("Cannot connect to the SQL server.");	// Connecting to the database server
mysql_select_db ("jmvclientes") or die ("Cannot select database."); 

$result = mysql_query("
		SELECT r.nombre as ramo, cl.nombre as cliente, c.fechaVencimiento as fechaVenc, p.numeroPoliza as numeroPoliza, c.numeroCupon as numeroCupon, cm.nombre as compania, cl.correo as correo, c.monto as monto, p.moneda as moneda
		FROM Poliza p, Cupon c, Ramo r, Cliente cl, Compania cm
		WHERE p.idCliente = cl.idCliente
		AND c.idPoliza = p.idPoliza
		AND p.idRamo = r.idRamo
		AND cl.idCliente = p.idCliente
		AND cm.idCompania = p.idCompania
		AND DATE(c.fechaVencimiento) = ADDDATE(DATE(NOW()), 4)
");
while($row = mysql_fetch_array($result)) {
	$mail = new PHPMailer();
	//$mail->IsSMTP();
	$mail->Mailer 		= "smtp";
	$mail->Host     	= "smtpout.secureserver.net";
	$mail->SMTPAuth		= true;
	$mail->Port			= 80;
	$mail->Username 	= "no-responder@jmvasesores.com";
	$mail->Password 	= "123abc123"; // SMTP password
	$mail->From     	= "no-responder@jmvasesores.com";
	$mail->FromName 	= "JMV Seguros - Sistema de Cobranzas";
	$mail->AddAddress("josecarlos89@gmail.com");
	$mail->AddBCC("jmartinez@jmvasesores.com");
	$mail->AddBCC("josecarlos89@gmail.com");
	$mail->AddCC($row["correoAlternativo"]);
	$mail->AddAddress($row["correo"]);               // optional name
	$mail->AddReplyTo("jmartinez@jmvasesores.com","Recordatorio");
	$mail->WordWrap = 50;                              // set word wrap
	$mail->IsHTML(true);                               // send as HTML
	$mail->Subject  =  "Primas por vencer";
	if($row["moneda"]=="Soles"){
		$simbolo = "S/. ";
	}else if($row["moneda"]=="Dolares"){
		$simbolo = "US$ ";
	}else if($row["moneda"]=="Euros"){
		$simbolo = "&euro; ";
	} 
	$html = "
	<p>Estimado Cliente :</p>
	<p>Dentro de cuatro dias vence una de las cuotas que comprenden el cronograma de pagos de vuestra p�liza de seguro, cuyos datos se detallan a   continuaci�n:</p>
	<ul>
		<li>Asegurado: " . $row["cliente"] . "</li>
		<li>Poliza: " . $row["numeroPoliza"] . "</li>
		<li>Ramo: " . $row["ramo"] . "</li>
		<li>Cia de Seguros: " . $row["compania"] . "</li>
		<li>Numero de Cupon: " . $row["numeroCupon"] . "</li>
		<li>Vencimiento: " . $row["fechaVenc"] . "</li>
		<li>Importe a Pagar:" . $simbolo . "" . number_format($row["monto"],2) . "</li>
	</ul>
	<p>Asimismo,   le(s) recordamos que es importante que este(n) al d�a en sus pagos,   para as� evitar la suspensi�n de la cobertura de seguro y poder ser   atendido(s) a cabalidad en caso de ocurrir alg�n siniestro y/o requerir   atenci�n de parte de vuestra aseguradora, respecto al pago del cup�n que   se indica, le informamos que el pago lo puede(n) efectuar por medio de las Web de todos los bancos afiliados o si prefiere(n) efectuando el   pago directamente en cualquiera de las oficinas de la aseguradora.</p>
	<p>Cabe mencionar que este correo tiene car�cter estrictamente informativo sobre el cup�n que esta por vencer y no es un recordatorio de  los pagos que a la fecha est�n vencidos y que pudiera tener pendientes de pago con vuestra aseguradora.</p>
	<p>Asimismo en caso de ya haber efectuado el pago del documento indicado, agradeceremos se sirvan no tomar la presente comunicaci�n.</p>
	<p>Muy Cordialmente.</p>
	<p><strong></strong><strong><em>Julio Martinez Vargas Caro</em></strong><br />
	<strong><em>SERVICIO AUTOMATICO DE COBRANZAS</em></strong><br />
	<strong><em>Asesor Corredor de Seguros</em></strong><br />
	<strong><em>REG SBS N-3340</em></strong><br />
	<strong><em>Telf� 992710108</em></strong><br />
	<strong><em>Web : www.jmvseguros.com</em></strong></p>
	";
	echo $html . "<br />";
	$mail->Body     =  $html;
	$mail->CharSet = "UTF-8";
	$mail->Send();
}

if(date('d')=='25'){
	$result = mysql_query("
	SELECT r.nombre AS ramo, cl.nombre AS cliente, p.numeroPoliza AS numeroPoliza, cm.nombre AS compania, cl.correo AS correo, p.moneda AS moneda
	FROM Poliza p, Ramo r, Cliente cl, Compania cm
	WHERE p.idCliente = cl.idCliente
	AND p.idRamo = r.idRamo
	AND cl.idCliente = p.idCliente
	AND cm.idCompania = p.idCompania
	AND p.finVigencia > DATE( NOW( ) )
	AND p.inicioVigencia < DATE( NOW( ) )
	AND p.recordatorio =1
	AND ( 
		r.nombre = 'Seguro Complementario de Pension' 
		OR
		r.nombre = 'Seguro Complementario de Salud' 
	)
	");

	while($row = mysql_fetch_array($result)) {

		$mail = new PHPMailer();
		//$mail->IsSMTP();
		$mail->Mailer 		= "smtp";
		$mail->Host     	= "smtpout.secureserver.net";
		$mail->SMTPAuth		= true;
		$mail->Port			= 80;
		$mail->Username 	= "no-responder@jmvasesores.com";
		$mail->Password 	= "123abc123"; // SMTP password
		$mail->From     	= "no-responder@jmvasesores.com";
		$mail->FromName 	= "JMV Seguros - Sistema de Recordatorios";
		$mail->AddBCC("jmartinez@jmvasesores.com");
		$mail->AddBCC("josecarlos89@gmail.com");
		$mail->AddCC($row["correoAlternativo"]);
		$mail->AddAddress($row["correo"]);               // optional name
		$mail->AddReplyTo("jmartinez@jmvseguros.com","Recordatorio");
		$mail->WordWrap = 50;                              // set word wrap
		$mail->IsHTML(true);                               // send as HTML
		$mail->Subject  =  "SCTR por declarar";
		$fecha=array('Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre','Enero');
		$html = "
	<p>Estimado Cliente :</p>
	<p>Estando pr�ximo a vencer el presente mes, le recordamos que a m�s tardar el d�a 27 de los corrientes deber� de enviarnos la declaraci�n para el mes de " . $fecha[date('n')] . " del " . date('Y') . " de sus trabajadores que deber�n de asegurarse bajo la(s) p�liza(s) correspondientes a la cobertura del Seguro Complementario de Trabajo de Riesgo, cuyos datos detallamos a continuaci�n :</p>
	<ul>
		<li>Asegurado: " . $row["cliente"] . "</li>
		<li>Poliza: " . $row["numeroPoliza"] . "</li>
		<li>Ramo: " . $row["ramo"] . "</li>
		<li>Cia de Seguros: " . $row["compania"] . "</li>
	</ul>
	<p>Asimismo, le(s) recordamos que es importante que cumpla con su declaraci�n, para as� evitar caer en falta en caso de ocurrir alg�n accidente de trabajo con lamentables consecuencias, al respecto nos permitimos enviarle adjunto el formulario electr�nico en Excel el cual deber� de enviarnos v�a correo electr�nico consignando la informaci�n que en �l se solicita, una vez recepcionado procederemos a gestionar la(s) contancia(s) de cobertura as� como la(s) liquidaci�n(es) de primas respectivas las mismas que se las enviaremos por correo electr�nico.</p>
	<p>Respecto al pago le informamos que este lo puede(n) efectuar directamente en cualquiera de las oficinas de la aseguradora o si prefieren por medio de las Web de todos los bancos afiliados efectuando el abono a cualquiera de las cuentas bancarias de la compa��a de seguros R�mac que le detallamos a continuaci�n, para lo cual agradeceremos nos env�e el voucher de dep�sito para aplicar el pago respectivo :</p>
	
	<table border='1' cellpadding='5'>
	<tr><td>BANCO</td><td>PARA LA COBERTURA DE SALUD</td><td>PARA LA COBERTURA DE PENSION</td></tr>
	<tr><td>Banco de Cr�dito del Per�</td><td>R�mac EPS  S/. 193-1087433-0-70</td><td>R�mac Internacional BCP S/. 193-0034841-0-28</td></tr>
	<tr><td>Interbank</td><td>R�mac Internacional S/. 030-0000346437 </td><td>R�mac Internacional S/. 030-0000346437 </td></tr>
	<tr><td>Scotiabank</td><td>R�mac EPS   S/. 032-1133</td><td>R�mac Internacional   S/. 001-3192032</td></tr>
	<tr><td>BBVA Bco.Continental</td><td>R�mac EPS  S/. 0011-0686-39-0100019141</td><td>R�mac Internacional S/. 0011-0686-38-0100007879</td></tr>
	</table>
	<p>Cabe mencionar que este correo tiene caracter estrictamente informativo sobre el cup�n que esta por vencer y no es un recordatorio de  los pagos que a la fecha est�n vencidos y que pudiera tener pendientes de pago con vuestra aseguradora.</p>
	<p>Asimismo en caso de ya haber efectuado el pago del documento indicado, agradeceremos se sirvan no tomar la presente comunicaci�n.</p>
	<p>Muy Cordialmente.</p>
	<p><strong></strong><strong><em>Julio Martinez Vargas Caro</em></strong><br />
	<strong><em>SERVICIO AUTOMATICO DE SEGURO COMPLEMENTARIO DE TRABAJO DE RIESGO
</em></strong><br />
	<strong><em>Asesor Corredor de Seguros</em></strong><br />
	<strong><em>REG SBS N-3340</em></strong><br />
	<strong><em>Telf� 992710108</em></strong><br />
	<strong><em>Web : www.jmvseguros.com</em></strong></p>
	";
	echo $html . "<br />";
		$mail->Body     =  $html;
		$mail->AddAttachment('sctr.xls', 'sctr.xls'); ;
		$mail->CharSet = "UTF-8";
		//$mail->Send();
			
		
		if(!$mail->Send())
		{
		   echo "Error sending: " . $mail->ErrorInfo;;
		}
		else
		{
		   echo "Letter is sent";
		}	
	}	

}
mysql_free_result($result);
?>