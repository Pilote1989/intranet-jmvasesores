<?php
class procesarBono extends sessionCommand{
	function execute(){
		// -> Banner
		$fc=FrontController::instance();	
		$usuario=$this->getUsuario();
		$fc->import("lib.Liquidacion");
		$fc->import("lib.Cobro");
		if($this->request->idLiquidacion){
			//edito
			$liquidacion = Fabrica::getFromDB("Liquidacion",$this->request->idLiquidacion);
			$id = $this->request->idLiquidacion;
			$liquidacion->setFactura($this->request->factura);
			$liquidacion->setIdCompania($this->request->compania);
			$liquidacion->setFechaFactura($this->request->fecha,"DATE");
			$liquidacion->setObservaciones($this->request->obser);
			$liquidacion->setSubTotal($this->request->subTotal);
			$liquidacion->setMoneda($this->request->moneda);
			$liquidacion->setIgv($this->request->igv);
			$liquidacion->setTotalFactura($this->request->totalFac);
			$liquidacion->setBono("1");	
			$target_path = "uploads/";
			$db = time() . "-" . substr(md5(basename( $_FILES['pdf']['name'])), 0 , 4) . ".pdf";
			$target_path = $target_path . $db;
			if ($_FILES["pdf"]["type"] == "application/pdf"){
				if ($_FILES["pdf"]["error"] > 0){
					echo "Return Code: " . $_FILES["pdf"]["error"] . "<br>";
				}elseif(!move_uploaded_file($_FILES['pdf']['tmp_name'], $target_path)){
					echo 'Error al subir el archivo';
				}else{
					$liquidacion->setPdf($db);
				}
			}else{
				echo 'El archivo no es un pdf.';
			}						
			$liquidacion->storeIntoDB();
		}else{
			//creo bono
			$liquidacion = new Liquidacion();
			$liquidacion->setFactura($this->request->factura);
			$liquidacion->setIdCompania($this->request->compania);
			$liquidacion->setFechaFactura($this->request->fecha,"DATE");
			$liquidacion->setObservaciones($this->request->obser);
			$liquidacion->setSubTotal($this->request->subTotal);
			$liquidacion->setMoneda($this->request->moneda);
			$liquidacion->setIgv($this->request->igv);
			$liquidacion->setTotalFactura($this->request->totalFac);
			$liquidacion->setBono("1");
			$target_path = "uploads/";
			$db = time() . "-" . substr(md5(basename( $_FILES['pdf']['name'])), 0 , 4) . ".pdf";
			$target_path = $target_path . $db;
			if ($_FILES["pdf"]["type"] == "application/pdf"){
				if ($_FILES["pdf"]["error"] > 0){
					echo "Return Code: " . $_FILES["pdf"]["error"] . "<br>";
				}elseif(!move_uploaded_file($_FILES['pdf']['tmp_name'], $target_path)){
					echo 'Error al subir el archivo';
				}else{
					$liquidacion->setPdf($db);
				}
			}else{
				echo 'El archivo no es un pdf.';
			}				
			$liquidacion->storeIntoDB();
			$dbLink=&FrontController::instance()->getLink();
			$dbLink->next_result();
			$id=$dbLink->insert_id;
			$i = 0;
		}
		$fc->redirect("?do=liquidaciones.ver&idLiquidacion=" . $id);
	}
}
?>