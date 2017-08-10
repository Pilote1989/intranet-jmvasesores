<?php
class procesarCompra extends sessionCommand{
	function execute(){
		// -> Banner
		$fc=FrontController::instance();
		$fc->import("lib.Compra");
		$fc->import("lib.Cliente");
		
		$new = false;
		$creando="";
		if($this->request->idCompra){
			$compra = Fabrica::getFromDB("Compra",$this->request->idCompra);
			//$compra->setNumeroFactura($this->request->numeroDoc);
			$compra->setConcepto($this->request->concepto);
			$compra->setTipo($this->request->tipo);
			$compra->setMoneda($this->request->moneda);
			$compra->setFecha($this->request->fecha,"DATE");
			$compra->setSubtotal($this->request->subtotal);
			$compra->setIgv($this->request->igv);
			$compra->setOtros($this->request->otros);
			$compra->setTotal($this->request->total);
		}else{
			$new = true;
			$compra = new Compra();
			$compra->setNumeroFactura($this->request->numeroDoc);
			$compra->setConcepto($this->request->concepto);
			$compra->setTipo($this->request->tipo);
			$compra->setMoneda($this->request->moneda);
			$compra->setFecha($this->request->fecha,"DATE");
			$compra->setSubtotal($this->request->subtotal);
			$compra->setOtros($this->request->otros);
			$compra->setIgv($this->request->igv);
			$compra->setTotal($this->request->total);
			$creando="&c=1";
		}
		if($new){
			if($this->request->idCliente){
				//$cliente = Fabrica::getFromDB("Cliente",$this->request->idCliente);
				$compra->setIdCliente($this->request->idCliente);
			}else{
				$cliente = new Cliente();
				$cliente->setNombre($this->request->nombre);
				$cliente->setDireccion($this->request->direccion);
				$cliente->setDistrito($this->request->distrito);
				$cliente->setTipoDoc("RUC");
				$cliente->setCorreo($this->request->correo);
				$cliente->setFechaDeCreacion(date('Y',time()) . "/" . date('m',time()). "/" . date('d',time()));
				$cliente->setDoc($this->request->doc);
				$cliente->storeIntoDB();
				$dbLink=&FrontController::instance()->getLink();
				$dbLink->next_result();
				$compra->setIdCliente($dbLink->insert_id);
			}
		}

		$compra->storeIntoDB();
		$dbLink=&FrontController::instance()->getLink();
		
		if($new){
			$dbLink->next_result();
			$id=$dbLink->insert_id;
		}else{
			$id=$compra->getId();
		}
		
		$fc->redirect("?do=compras.verCompra&idCompra=".$id.$creando);
	}
}
?>