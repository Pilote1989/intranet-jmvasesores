<?php
class procesarRenovacion extends sessionCommand{
	function execute(){
		// -> Banner
		$fc=FrontController::instance();
		$fc->import("lib.Cliente");
		$fc->import("lib.Poliza");
		$fc->import("lib.Cobro");
		if($this->request->idPoliza){
			$poliza=Fabrica::getFromDB("Poliza",$this->request->idPoliza);
			$cobroAnterior=Fabrica::getFromDB("Cobro",$poliza->getIdCobro());
			$renovacion=new Poliza();
			$cobro = new Cobro();
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
		$dbLink->next_result();
		$id=$dbLink->insert_id;

		$renovacion->setIdCobro($id);
		
		$renovacion->setObservaciones($this->request->observaciones);
		$renovacion->setMoneda($this->request->moneda);
		$renovacion->setIdRamo($poliza->getIdRamo());
		$renovacion->setIdPersona($cobroAnterior->getIdPersona());
		$renovacion->setNumeroPoliza($poliza->getNumeroPoliza());
		$renovacion->setIdCompania($poliza->getIdCompania());
		$renovacion->setInicioVigencia($this->request->fechaInicio,"DATE");
		$renovacion->setFinVigencia($this->request->fechaFin,"DATE");
		$renovacion->setCobranza($this->request->cobranza);
		$renovacion->setDocumento($this->request->documento);
		$renovacion->setPrima($this->request->prima);
		$renovacion->setRenovacion($this->request->renovacion);
		$renovacion->setDerecho($this->request->derecho);
		$renovacion->setIgv($this->request->igv);
		$renovacion->setTotal($this->request->total);
		$renovacion->setComision($this->request->comision);
		$renovacion->setIntereses($this->request->intereses);
		$renovacion->setTipo("REN");		
		
		//inicio - manejo de archivos
		//print_r($_FILES);
		$target_path = "uploads/";
		$db = time() . "-" . basename( $_FILES['pdf']['name']);
		$target_path = $target_path . $db;
		//echo $target_path;
		if ($_FILES["pdf"]["type"] == "application/pdf"){
			if ($_FILES["pdf"]["error"] > 0){
				echo "Return Code: " . $_FILES["pdf"]["error"] . "<br>";
			}elseif(!move_uploaded_file($_FILES['pdf']['tmp_name'], $target_path)){
				echo 'Error al subir el archivo';
			}else{
				$renovacion->setPdf($db);	
			}
		}else{
			echo 'El archivo no es un pdf';
		}
		//fin - manejo de archivos
		if($this->request->recordatorio){
			$renovacion->setRecordatorio(1);
		}
		$renovacion->setIdCliente($poliza->getIdCliente());
		
		$renovacion->storeIntoDB();
		
		$dbLink=&FrontController::instance()->getLink();
		$dbLink->next_result();
		$id=$dbLink->insert_id;
		$fc->redirect("?do=polizas.ver&idPoliza=" . $this->request->idPoliza . "&vig=" . $id);
	}
}
?>