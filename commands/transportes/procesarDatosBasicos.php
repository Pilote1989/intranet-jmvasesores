<?php
class procesarDatosBasicos extends sessionCommand{
	function execute(){
		$fc=FrontController::instance();
		$fc->import("lib.Transporte");
		if(!$usuario=$this->getUsuario()){
			$fc->redirect("?do=home");
		}
		$persona = Fabrica::getFromDB('Persona', $usuario->getId());
		// Chequea si tiene perfiles Asociados
		if(!$perfiles = $usuario->getPerfiles()){
			$fc->redirect("?do=home");
		}
		if($this->request->confirmed == 1){
			$transporte = new Transporte();
			$transporte->setIdPersona($usuario->getId());
			$transporte->setDireccion($this->request->direccion);
			$transporte->setTipoMercaderia($this->request->tipoDeMercaderia);
			$transporte->setDescripcionMercaderia($this->request->descripcionMercaderia);
			$transporte->setLugarOrigen($this->request->lugarOrigen);
			$transporte->setFechaPedido(date('d/m/Y'),'DATE');
			$transporte->setFechaSalida($this->request->fechaSalida,'DATE');
			$transporte->setPaisOrigen($this->request->paisOrigen);
			$transporte->setLugarDestino($this->request->lugarDestino);
			$transporte->setFechaLlegada($this->request->fechaLlegada,'DATE');
			$transporte->setMedioTransporte($this->request->medioTransporte);
			$transporte->setProovedor($this->request->proovedor);
			$transporte->setNombreBuque($this->request->nombreBuque);
			$transporte->setAduana($this->request->aduana);
			$transporte->setTipoContenedor($this->request->tipoContenedor);
			$transporte->setTrasbordo($this->request->trasbordo);
			$transporte->setTipoEmbalaje($this->request->tipoEmbalaje);
			$transporte->setValorFob($this->request->fob);
			$transporte->setValorFlete($this->request->flete);
			$transporte->setSobreseguro($this->request->sobreseguro);
			$transporte->setValorCif( ( $this->request->fob + $this->request->flete ) * 1.1  );
			$transporte->setObservaciones($this->request->observaciones);	
			$transporte->storeIntoDB();
			$fd = FrontController::instance();
			$response = $fd->executeCommand("transportes.verPoliza?id=" . $transporte->getId() . "&html=1");
			$plugContents = $response->getContent();
			$html = $plugContents;
//			echo $html;
			require("././phpmailer/class.phpmailer.php");
			$mail = new PHPMailer();
			//$mail->IsSMTP();
			$mail->SMTPDebug 	= 1;
			$mail->Mailer 		= "smtp";
			$mail->Host     	= "smtpout.secureserver.net";
			$mail->SMTPAuth		= true;
			$mail->Port			= 80;
			$mail->Username 	= "no-responder@jmvasesores.com";
			$mail->Password 	= "123abc123"; // SMTP password
			$mail->From     	= "no-responder@jmvasesores.com";
			$mail->FromName 	= "Sistema de Solicitudes";
			$mail->AddAddress("jmartinez@jmvasesores.com");               // optional name
			$mail->AddReplyTo("jmartinez@jmvasesores.com","Solicitud Transporte");
			$mail->WordWrap = 50;                              // set word wrap
			$mail->IsHTML(true);                               // send as HTML
			$mail->Subject  =  "Sistema de Solicitudes";
			$mail->Body     =  $html;
			$mail->CharSet = "UTF-8";
			if($mail->Send()){
				$fc->redirect("?do=transportes.verPoliza&id=" . $transporte->getId());
			}else{
				echo "Error de Correo" . $mail->ErrorInfo;
			}
			//print_r($transporte);
		}else{
			$this->addVar("contratante", $this->request->contratante);
			$this->addVar("direccion", $this->request->direccion);
			$this->addVar("ruc", $this->request->ruc);
			$this->addVar("tipoDeMercaderia", $this->request->tipoDeMercaderia);
			$this->addVar("descripcionMercaderia", $this->request->descripcionMercaderia);
			$this->addVar("lugarOrigen", $this->request->lugarOrigen);
			$this->addVar("fechaSalida", $this->request->fechaSalida);
			$this->addVar("paisOrigen", $this->request->pais);
			$this->addVar("lugarDestino", $this->request->lugarDestino);
			$this->addVar("fechaLlegada", $this->request->fechaLlegada);
			if($this->request->trasbordo){
				$this->addVar("trasbordo", "Si");
			}else{
				$this->addVar("trasbordo", "No");
			}
			$this->addVar("medioTransporte", $this->request->medioTransporte);
			$this->addVar("proovedor", $this->request->proovedor);
			$this->addVar("nombreBuque", $this->request->nombreBuque);
			$this->addVar("tipoContenedor", $this->request->tipoContenedor);
			$this->addVar("tipoEmbalaje", $this->request->tipoEmbalaje);
			$this->addVar("valorFob", $this->request->fob);
			$this->addVar("aduana", $this->request->aduana);
			$this->addVar("valorFlete", $this->request->flete);
			$this->addVar("sobreseguro", $this->request->sobreseguro);
			$this->addVar("valorCif", ( $this->request->fob + $this->request->flete ) * ( 1 + $this->request->sobreseguro) );
			$this->addVar("observaciones", $this->request->observaciones);
			$this->addVar("nombre", $usuario->getNombres()." ".$usuario->getApellidoPaterno());
			$this->addLayout("admin");
			$this->processTemplate("transportes/procesarDatosBasicos.html");	
		}
	}
}
?>