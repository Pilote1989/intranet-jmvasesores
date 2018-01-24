<?php
class procesarDatosBasicos extends sessionCommand{
	function execute(){
		// -> Banner
		$fc=FrontController::instance();
		$fc->import("lib.Cliente");
		$fc->import("lib.Poliza");
		$fc->import("lib.Cobro");
		$matriz = null;
		if($this->request->idPoliza){
			$poliza = Fabrica::getFromDB("Poliza",$this->request->idPoliza);
			$cobro = Fabrica::getFromDB("Cobro",$poliza->getIdCobro());
			$matriz = $poliza->matriz();
		}else{
			$poliza = new Poliza();
			$cobro = new Cobro();
			$poliza->setNumeroPoliza($this->request->numeroPoliza);
			//$matriz = null;
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
			$dbLink->next_result();
		}
		$poliza->setIdCobro($id);
		$poliza->setObservaciones($this->request->observaciones);
		$poliza->setMoneda($this->request->moneda);
		$poliza->setIdRamo($this->request->idRamo);
		$poliza->setIdCompania($this->request->idCompania);
		$poliza->setInicioVigencia($this->request->fechaInicio,"DATE");
		$poliza->setFinVigencia($this->request->fechaFin,"DATE");
		$poliza->setDocumento($this->request->documento);
		$poliza->setRenovacion($this->request->renovacion);
		$target_path = "uploads/";
		$db = time() . "-" . substr(md5(basename( $_FILES['pdf']['name'])), 0 , 4) . ".pdf";
		$target_path = $target_path . $db;
		//print_r($_FILES);
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
		if($this->request->recordatorio){
			$poliza->setRecordatorio(1);
		}
		$poliza->setIdCliente($this->request->idCliente);
		$poliza->storeIntoDB();
		
		$dbLink=&FrontController::instance()->getLink();
		if($this->request->idPoliza){
			$id=$this->request->idPoliza;
		}else{
			$dbLink->next_result();
			$id=$dbLink->insert_id;
			$dbLink->next_result();
		}
		//echo "<br/>";
		//echo $matriz;
		if($matriz==null){
			$matriz=$id;
		}
		$fc->redirect("?do=polizas.ver&idPoliza=" . $matriz . "&vig=".$id);
	}
}
?>