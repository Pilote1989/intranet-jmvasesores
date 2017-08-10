<?php
class procesarDatosBasicos extends sessionCommand{
	function execute(){
		// -> Banner
		$fc=FrontController::instance();
		$fc->import("lib.Cliente");
		$fc->import("lib.Poliza");
		$fc->import("lib.Cobro");
				//echo "1e";
				//print_r($this->request);
		if($this->request->idPoliza){
				//echo "1w";
			$poliza = Fabrica::getFromDB("Poliza",$this->request->idPoliza);
			/*
			$numeroPoliza = $poliza->getNumeroPoliza();
			$vigencias = Fabrica::getAllFromDB("Poliza", array("numeroPoliza = '" . $numeroPoliza . "'","estado = '1'"), "inicioVigencia DESC");		
			foreach($vigencias as $vigencia){
				$vigencia->setNumeroPoliza($this->request->numeroPoliza);
				//echo "1";
				$vigencia->storeIntoDB();
			}*/
			$cobro = Fabrica::getFromDB("Cobro",$poliza->getIdCobro());
		}else{
			$poliza = new Poliza();
			$cobro = new Cobro();
			$poliza->setNumeroPoliza($this->request->numeroPoliza);
		}
		
		$cobro->setMoneda($this->request->moneda);
		$cobro->setPrimaNeta($this->request->prima);
		$cobro->setDerechoEmision($this->request->derecho);
		$cobro->setPrimaComercial($this->request->primaComercial);
		$cobro->setIgv($this->request->igv);
		$cobro->setIntereses($this->request->intereses);
		$cobro->setTotalFactura($this->request->total);
		$cobro->setAvisoDeCobranza($this->request->cobranza);
		$cobro->setComisionP($this->request->comisionP);
		$cobro->setComision($this->request->comision);
		$cobro->setComisionCedidaP($this->request->comisionVendedorP);
		$cobro->setComisionCedida($this->request->comisionVendedor);
		$cobro->setIdPersona($this->request->idPersona);
		$cobro->storeIntoDB();
		$dbLink=&FrontController::instance()->getLink();
		
		if($this->request->idPoliza){
			$id=$cobro->getId();
		}else{
			$dbLink->next_result();
			$id=$dbLink->insert_id;
		}
		
		$poliza->setIdCobro($id);
		$poliza->setObservaciones($this->request->observaciones);
		$poliza->setMoneda($this->request->moneda);
		$poliza->setIdRamo($this->request->idRamo);
		$poliza->setIdCompania($this->request->idCompania);
		$poliza->setInicioVigencia($this->request->fechaInicio,"DATE");
		$poliza->setFinVigencia($this->request->fechaFin,"DATE");
		//$poliza->setCobranza($this->request->cobranza);
		$poliza->setDocumento($this->request->documento);
		//$poliza->setPrima($this->request->prima);
		$poliza->setRenovacion($this->request->renovacion);
		//$poliza->setDerecho($this->request->derecho);
		//$poliza->setIgv($this->request->igv);
		//$poliza->setTotal($this->request->total);
		//$poliza->setComision($this->request->comision);
		//$poliza->setIntereses($this->request->intereses);		
		//inicio - manejo de archivos
		//print_r($_FILES);
			
		$target_path = "uploads/";
		$db = time() . "-" . substr(md5(basename( $_FILES['pdf']['name'])), 0 , 4) . ".pdf";
		$target_path = $target_path . $db;
		//echo $target_path;
		print_r($_FILES);
		if ($_FILES["pdf"]["type"] == "application/pdf"){
			if ($_FILES["pdf"]["error"] > 0){
				echo "Return Code: " . $_FILES["pdf"]["error"] . "<br>";
			}elseif(!move_uploaded_file($_FILES['pdf']['tmp_name'], $target_path)){
				echo 'Error al subir el archivo';
			}else{
				$poliza->setPdf($db);	
			}
		}else{
			echo 'El archivo no es un pdf.';
		}
		//fin - manejo de archivos
		if($this->request->recordatorio){
			$poliza->setRecordatorio(1);
		}
		if($this->request->new == "0"){
			$cliente=new Cliente();
			$cliente->setNombre($this->request->nombre);
			$cliente->setFechaDeCreacion(date('Y',time()) . "/" . date('m',time()). "/" . date('d',time()));
			$cliente->setCorreo($this->request->correoCliente);
			$cliente->storeIntoDB();
			$dbLink=&FrontController::instance()->getLink();
			$poliza->setIdCliente($dbLink->insert_id);
		}else{
			$poliza->setIdCliente($this->request->idCliente);
		}		
		
		$poliza->storeIntoDB();
		
		$dbLink=&FrontController::instance()->getLink();
		
		if($this->request->idPoliza){
			$id=$this->request->idPoliza;
		}else{
			$id=$dbLink->insert_id;
		}
		//echo $poliza->matriz();
		$fc->redirect("?do=polizas.ver&idPoliza=" . $poliza->matriz() . "&vig=".$id);
	}
}
?>