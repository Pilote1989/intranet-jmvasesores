<?php
class verCompra extends sessionCommand{
	function execute(){
		$fc=FrontController::instance();
		$usuario=$this->getUsuario();
		$this->addVar("doFalso", $this->request->do);
		$compra = Fabrica::getFromDB("Compra",$this->request->idCompra);
		if(is_null($compra)){
			$fc->redirect("?do=compras.ver");
		}else{
			$vendedor = Fabrica::getFromDB("Cliente",$compra->getIdCliente());
			$this->addVar("ruc", $vendedor->getDoc());
			$this->addVar("vendedor", $vendedor->getNombre());
			$this->addVar("tipo", $compra->getTipo());
			$this->addVar("numeroFactura", $compra->getNumeroFactura());
			if($compra->getConcepto()=="" || $compra->getConcepto()==" "){
				$this->addVar("concepto", "No hay ningun detalle de este gasto");
			}else{
				$this->addVar("concepto", $compra->getConcepto());
			}
			$this->addVar("moneda", $compra->getMoneda());
			$this->addVar("subtotal", $compra->getSubtotal());
			$this->addVar("igv", $compra->getIgv());
			$this->addVar("otros", $compra->getOtros());
			$this->addVar("total", $compra->getTotal());
			$this->addVar("fechaFactura", $compra->getFecha("DATE"));
			$this->addVar("idCompra", $compra->getId());
			if($this->request->c=="1"){
				$this->addBlock("c");
			}
			$this->addLayout("ace");
			$this->processTemplate("compras/verCompra.html");
		}
	}
}
?>