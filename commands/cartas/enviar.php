<?php
require $_SERVER['DOCUMENT_ROOT'].'/mailerv2/class.phpmailer.php';
require $_SERVER['DOCUMENT_ROOT'].'/mailerv2/class.smtp.php';
class enviar extends sessionCommand{
	function execute(){
		$fc=FrontController::instance();
		$response["exito"]=false;
		$response["mensaje"]="FAIL";
		$i=0;
		$carta = Fabrica::getFromDB("Carta",$this->request->idCarta);
		$poliza = Fabrica::getFromDB("Poliza",$carta->getIdPoliza());
		$mail = new PHPMailer;
		$correos = html_entity_decode($this->request->correos);
		if($this->request->correos){
			$post = explode("\",\"",rtrim(ltrim($correos,"[\""),"\"]"));
			foreach($post as $correo){
                if (filter_var($correo, FILTER_VALIDATE_EMAIL)) {
                    $mail->addAddress($correo);
                    $i++;
                }
            }
        }
		$mail->SMTPDebug = 0;
		$mail->isSMTP();  
		$mail->Host = 'smtp-relay.gmail.com';
		$mail->SMTPAuth = true; 
		$mail->Username = 'no-responder@jmvasesores.com';
		$mail->Password = '62827Peru';
		$mail->SMTPSecure = 'tls';
		$mail->Port = 587; 
		$mail->setFrom('no-responder@jmvasesores.com', 'JMV Asesores - Despacho de Documentos');
		//$mail->addBCC('jmartinez@jmvasesores.com');
		$mail->IsHTML(true);
		$mail->Subject  =  $this->request->idPoliza;
		$mail->addReplyTo("jmartinez@jmvasesores.com","Despacho de Documentos");
		$mail->IsHTML(true);
		$mail->Subject  =  $this->request->detalle;
		$temp = html_entity_decode($this->request->texto);
		$temp = str_replace("assets/unnamed.jpg", "cid:logo", $temp);
		$mail->AddEmbeddedImage($_SERVER['DOCUMENT_ROOT']."/assets/unnamed.jpg", "logo", "logo.jpg");
		$mail->Body     =  $temp;
		echo $temp;
		$mail->CharSet = "UTF-8";
		if($poliza->getPdf()!="" && $this->request->polizaPDF=="on"){
			//adjuntar el pdf
			$mail->AddAttachment($_SERVER['DOCUMENT_ROOT'].'/uploads/'.$poliza->getPdf());
		}
		$file_ary = reArrayFiles($_FILES['archivos']);
		foreach($file_ary as $archivo){
			if($archivo["tmp_name"]!="")
				$mail->AddAttachment($archivo["tmp_name"],$archivo["name"]);
		}
		if($this->request->cartaPDF=="on"){
			$ejecutapdf=$fc->executeCommand("cartas.pdfCarta?idCarta=".$this->request->idCarta."&sologenera=1");
			$mail->AddAttachment($_SERVER['DOCUMENT_ROOT'].'/cartas/carta'.$this->request->idCarta.'.pdf');
		}
		
		$tz = 'America/Lima';
		$timestamp = time();
		$dt = new DateTime("now", new DateTimeZone($tz));
		$dt->setTimestamp($timestamp);
		$carta->setDespacho($dt->format('d/m/Y'),"DATE");
		$carta->storeIntoDB();			
		if($i){
			if(!$mail->Send()){
				$response["mensaje"]="Error enviando el correo: " . $mail->ErrorInfo;
			}else{
				$response["exito"]=true;
				$response["mensaje"]="OK";
			}
		}else{
				$response["exito"]=false;
				$response["mensaje"]="No hay correos";
		}
		echo json_encode($response);
	}
}
function reArrayFiles(&$file_post) {
    $file_ary = array();
    $file_count = count($file_post['name']);
    $file_keys = array_keys($file_post);
    for ($i=0; $i<$file_count; $i++) {
        foreach ($file_keys as $key) {
            $file_ary[$i][$key] = $file_post[$key][$i];
        }
    }
    return $file_ary;
}
?>